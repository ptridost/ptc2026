<?php
date_default_timezone_set('Asia/Manila');
session_start();

if(empty($_SESSION['participant_id'])){
    $redirect_url = 'feedbackform.php' . (isset($_GET['id']) ? '?id=' . $_GET['id'] : '');
    $_SESSION['redirect'] = $redirect_url;
    header("Location: ../Loginn.php"); 
    exit();
}

require('../dbcon.php');

// ===================================================================
// START: BACKWARD-COMPATIBLE IMAGE PATH HELPER
// ===================================================================
function getSpeakerImagePath($filename) {
    if (empty($filename)) {
        // A placeholder image path relative to the User_dashboard folder
        return '../admin/placeholder.jpg'; 
    }

    // Path to the NEW speaker images folder, relative to feedbackform.php
    $new_path = '../admin/speaker_images/' . $filename;

    // Path to the OLD speaker images folder, relative to feedbackform.php
    $old_path = './2024/' . $filename; 

    // Use absolute server paths for a reliable check
    if (file_exists(__DIR__ . '/../admin/speaker_images/' . $filename)) {
        return $new_path;
    } elseif (file_exists(__DIR__ . '/2024/' . $filename)) {
        return $old_path;
    }

    // If found in neither location, return the placeholder
    return '../admin/placeholder.jpg';
}
// ===================================================================
// END: IMAGE PATH HELPER
// ===================================================================

$session_id = $_GET['id'] ?? $_POST['session_id'] ?? null;
$participant_id = $_SESSION['participant_id'];
$error_message = '';

// --- FORM PROCESSING LOGIC ---
if (isset($_POST['submit']) && $session_id) {
    
    
    // NEW VALIDATION: Check for a minimum word count
    $minWords = 3;
    $all_text = trim($_POST['aincheck']) . ' ' . trim($_POST['bincheck']) . ' ' . trim($_POST['cincheck']) . ' ' . trim($_POST['dincheck']) . ' ' . trim($_POST['comment']);

    if (str_word_count($all_text) < $minWords) {
        $_SESSION['flash_message'] = ['type' => 'info', 'text' => "To help us improve, please provide at least $minWords words in the final section. Thank you!"];
        header("Location: feedbackform.php?id=" . $session_id);
        exit();
    }

    // Check for duplicate submission first
    $check_stmt = $dbcon->prepare("SELECT feedback_id FROM tblfeedback WHERE session_id = ? AND participant_id = ?");
    $check_stmt->bind_param("is", $session_id, $participant_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    if ($check_result->num_rows > 0) {
        $_SESSION['flash_message'] = ['type' => 'warning', 'text' => 'You have already submitted feedback for this session.'];
        header("Location: dashboard.php");
        exit();
    }
    $check_stmt->close();

    // Begin transaction
    $dbcon->begin_transaction();

   try {
        // First, get the logged-in participant's data
        $stmt = $dbcon->prepare("SELECT * FROM participant WHERE id = ?");
        $stmt->bind_param("s", $participant_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $participant_data = ($result->num_rows > 0) ? $result->fetch_assoc() : [];
        $stmt->close();

        // Safely get the participant's details from the data we just fetched
        $email = $participant_data['username'] ?? '';
        $fname = $participant_data['firstName'] ?? '';
        $mname = $participant_data['middleName'] ?? '';
        $lname = $participant_data['lastName'] ?? '';
        $organization = $participant_data['organization'] ?? '';
        $position = $participant_data['position'] ?? '';

        // Prepare the full INSERT statement for tblfeedback
        $feedback_stmt = $dbcon->prepare(
            "INSERT INTO tblfeedback (
                participant_id, information, purpose_attend, 
                email, Fname, Mname, Lname, organization, position, 
                comment, session_id, 
                checkbox_q1, checkbox_q2, checkbox_q3, checkbox_q4, 
                category
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        // This is the crucial line: it binds all 16 variables to the 16 columns
        $feedback_stmt->bind_param("ssssssssssisssss", 
            $participant_id, $_POST['info'], $_POST['infoatt'],
            $email, $fname, $mname, $lname, $organization, $position,
            $_POST['comment'], $session_id,
            $_POST['aincheck'], $_POST['bincheck'], $_POST['cincheck'], $_POST['dincheck'],
            $_POST['category']
        );
        $feedback_stmt->execute();
        $feedback_stmt->close();

        // The rest of your code for tblconrating and tblspeaker_rating follows...
        $conrating_stmt = $dbcon->prepare("INSERT INTO tblconrating (session_id, participant_id, q1, q2, q3, q4, q5) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $conrating_stmt->bind_param("isiiiii", $session_id, $participant_id, $_POST['q1'], $_POST['q2'], $_POST['q3'], $_POST['q4'], $_POST['q5']);
        $conrating_stmt->execute();

        $conrating_stmt->close();
       
        // Insert into tblspeaker_rating
   
        if (isset($_POST['speaker_ratings'])) {
            // The 'comment' column has been removed from the query
            $speaker_rating_stmt = $dbcon->prepare("INSERT INTO tblspeaker_rating (session_id, participant_id, speaker_id, q1, q2, q3, q4) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($_POST['speaker_ratings'] as $speaker_id => $ratings) {
                $q1 = $ratings['q1'] ?? 0;
                $q2 = $ratings['q2'] ?? 0;
                $q3 = $ratings['q3'] ?? 0;
                $q4 = $ratings['q4'] ?? 0;
                // The $comment variable is no longer needed

                // The type string and variable list are now shorter
                $speaker_rating_stmt->bind_param("isiiiii", $session_id, $participant_id, $speaker_id, $q1, $q2, $q3, $q4);
                $speaker_rating_stmt->execute();
            }
            $speaker_rating_stmt->close();
        }

                $dbcon->commit();
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Your feedback has been sent successfully!'];
                header("Location: dashboard.php");
                exit();

            } catch (mysqli_sql_exception $exception) {
                $dbcon->rollback();
                error_log("Feedback Form SQL Error: " . $exception->getMessage());
                $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'An error occurred. Please try again.'];
                header("Location: feedbackform.php?id=" . $session_id);
                exit();
            }
        
        }

        // --- FETCH DATA FOR DISPLAY ---
        $session_data = null; $participant_data = null; $speakers = []; $already_submitted = false;
        if ($session_id) {
            // This logic is complete and correct
            $stmt = $dbcon->prepare("SELECT * FROM tblptcsession WHERE session_number = ?");
            $stmt->bind_param("i", $session_id); $stmt->execute();
            $result = $stmt->get_result(); if($result->num_rows > 0) $session_data = $result->fetch_assoc();
            $stmt->close();

            $stmt = $dbcon->prepare("SELECT * FROM participant WHERE id = ?");
            $stmt->bind_param("s", $participant_id); $stmt->execute();
            $result = $stmt->get_result(); if($result->num_rows > 0) $participant_data = $result->fetch_assoc();
            $stmt->close();

            $speaker_sql = "SELECT speaker_id, speaker_Name, speaker_Info, speaker_Img FROM tblspeakerses WHERE session_id = ?";
            $stmt = $dbcon->prepare($speaker_sql);
            $stmt->bind_param("i", $session_id); $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                // Add the correct, checked image path to each speaker's data
                $row['correct_image_path'] = getSpeakerImagePath($row['speaker_Img']);
                $speakers[] = $row;
            }
            $stmt->close();

            $stmt = $dbcon->prepare("SELECT feedback_id FROM tblfeedback WHERE session_id = ? AND participant_id = ?");
            $stmt->bind_param("is", $session_id, $participant_id); $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) $already_submitted = true;
            $stmt->close();
        } else {
            $error_message = "No session was selected. Please go back to the dashboard.";
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />
    <title>Feedback Form | Philippine Textile Congress</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="feedbackform.css">
    <link rel="stylesheet" type="text/css" href="user_head&footer/user_nav.css">
    <link rel="stylesheet" type="text/css" href="user_head&footer/footer.css">
</head>

<body>
    <?php include("user_head&footer/user_nav.php"); ?>

    <div class="container feedback-container">
        <?php if ($session_data): ?>
        <div class="card feedback-card">
            <div class="feedback-header">
                <img src="../landingpageimg/<?php echo htmlspecialchars($session_data['logo_image']); ?>"
                    alt="Session Logo" class="session-logo">
                <h1 class="h3"><?php echo htmlspecialchars($session_data['session_title']); ?></h1>
                <p class="mb-2">Feedback Form</p>
                <div class="speaker-avatar-list">
                    <?php foreach ($speakers as $speaker): ?>
                    <img src="<?php echo htmlspecialchars($speaker['correct_image_path']); ?>" alt="Speaker"
                        title="<?php echo htmlspecialchars($speaker['speaker_Name']); ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="feedback-body">
                <?php if ($already_submitted): ?>
                <div class="text-center p-5">
                    <h3 class="text-success">Thank You!</h3>
                    <p>You have already submitted feedback for this session.</p>
                    <a href="dashboard.php" class="btn btn-primary mt-3">Return to Dashboard</a>
                </div>
                <?php else: ?>
                <form action="feedbackform.php" method="POST" id="feedbackForm" novalidate>
                    <input type="hidden" name="session_id" value="<?php echo htmlspecialchars($session_id); ?>">

                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h4 class="question-title">How did you learn about this event?</h4>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info" id="info1"
                                        value="By invitations" required>
                                    <label class="form-check-label" for="info1">By invitations</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info" id="info2"
                                        value="Social media">
                                    <label class="form-check-label" for="info2">Social media</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info" id="info3"
                                        value="Colleagues/Friends/Acquaintances">
                                    <label class="form-check-label" for="info3">Colleagues/Friends/Acquaintances</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info" id="info4" value="Others">
                                    <label class="form-check-label" for="info4">Others</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h4 class="question-title">What made you attend this event?</h4>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoatt" id="infoatt1"
                                        value="Interest" required>
                                    <label class="form-check-label" for="infoatt1">Interest</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoatt" id="infoatt2"
                                        value="Required by school/office">
                                    <label class="form-check-label" for="infoatt2">Required by school/office</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoatt" id="infoatt3"
                                        value="Eagerness to learn">
                                    <label class="form-check-label" for="infoatt3">Eagerness to learn</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoatt" id="infoatt4"
                                        value="Other">
                                    <label class="form-check-label" for="infoatt4">Other</label>
                                </div>
                            </div>
                        </div>
                        <h4 class="question-title">Which category best describes you?</h4>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-4">
                                <input class="form-check-input" type="radio" id="students" name="category"
                                    value="Students/Researchers/Academe" required>
                                <label for="students" class="form-check-label">Students/Researchers/Academe</label>
                            </div>
                            <div class="form-check me-4">
                                <input class="form-check-input" type="radio" id="government" name="category"
                                    value="Government Employees">
                                <label for="government" class="form-check-label">Government Employees</label>
                            </div>
                            <div class="form-check me-4">
                                <input class="form-check-input" type="radio" id="private" name="category"
                                    value="Private Employees">
                                <label for="private" class="form-check-label">Private Employees</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="other" name="category" value="Other">
                                <label for="other" class="form-check-label">Other</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="section-title">Client Satisfaction Measurement</h2>
                        <p class="text-muted">On a scale of 1 (Strongly Disagree) to 5 (Strongly Agree), please rate the
                            following.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-start">Evaluation Criteria</th>
                                        <th class="text-center" style="width: 40%;">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>The webinar met the expectations of the participants.</td>
                                        <td>
                                            <div class="rating-wrapper">
                                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input name="q1" type="radio" id="q1_<?php echo $i; ?>"
                                                    value="<?php echo $i; ?>" required />
                                                <label for="q1_<?php echo $i; ?>"><?php echo $i; ?></label>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I found the online webinar conducive to learning.</td>
                                        <td>
                                            <div class="rating-wrapper">
                                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input name="q2" type="radio" id="q2_<?php echo $i; ?>"
                                                    value="<?php echo $i; ?>" required />
                                                <label for="q2_<?php echo $i; ?>"><?php echo $i; ?></label>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I was provided with information in a language I can easily understand.</td>
                                        <td>
                                            <div class="rating-wrapper">
                                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input name="q3" type="radio" id="q3_<?php echo $i; ?>"
                                                    value="<?php echo $i; ?>" required />
                                                <label for="q3_<?php echo $i; ?>"><?php echo $i; ?></label>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>The content of the webinar met the intended goals of the participants.</td>
                                        <td>
                                            <div class="rating-wrapper">
                                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input name="q4" type="radio" id="q4_<?php echo $i; ?>"
                                                    value="<?php echo $i; ?>" required />
                                                <label for="q4_<?php echo $i; ?>"><?php echo $i; ?></label>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I am satisfied with the overall conduct of the webinar.</td>
                                        <td>
                                            <div class="rating-wrapper">
                                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input name="q5" type="radio" id="q5_<?php echo $i; ?>"
                                                    value="<?php echo $i; ?>" required />
                                                <label for="q5_<?php echo $i; ?>"><?php echo $i; ?></label>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php if (!empty($speakers)): ?>
                    <div class="form-section">
                        <h2 class="section-title">Resource Person Assessment</h2>
                        <p class="text-muted">Please rate each speaker on a scale of 1 to 5.</p>
                        <?php
                                        $criteria = [
                                            "q1" => "The speaker met the expectations of the participants.",
                                            "q2" => "The presentation was conducive to learning.",
                                            "q3" => "Information was presented in an understandable manner.",
                                            "q4" => "I am satisfied with the speaker's overall performance."
                                        ];
                                    ?>
                        <?php foreach ($speakers as $speaker): ?>
                        <div class="mb-5">
                            <div class="speaker-header">
                                <img class="speaker-header-img"
                                    src="<?php echo htmlspecialchars($speaker['correct_image_path']); ?>"
                                    alt="Speaker Photo">
                                <div>
                                    <h4 class="mb-0" style="color: var(--accent-color);">
                                        <?php echo htmlspecialchars($speaker['speaker_Name']); ?></h4>
                                    <p class="mb-0"><em><?php echo htmlspecialchars($speaker['speaker_Info']); ?></em>
                                    </p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <tbody>
                                        <?php foreach ($criteria as $key => $text): ?>
                                        <tr>
                                            <td><?php echo $text; ?></td>
                                            <td style="width: 40%;">
                                                <div class="rating-wrapper">
                                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                                    <input
                                                        name="speaker_ratings[<?php echo $speaker['speaker_id']; ?>][<?php echo $key; ?>]"
                                                        type="radio"
                                                        id="<?php echo $key; ?>_s<?php echo $speaker['speaker_id']; ?>_<?php echo $i; ?>"
                                                        value="<?php echo $i; ?>" required />

                                                    <label
                                                        for="<?php echo $key; ?>_s<?php echo $speaker['speaker_id']; ?>_<?php echo $i; ?>"><?php echo $i; ?></label>

                                                    <?php endfor; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="form-section">
                        <h2 class="section-title">Final Thoughts</h2>
                        <p class="text-muted">Please provide your insights on the following.</p>
                        <div class="mb-3">
                            <label for="aincheck" class="form-label">1. I gained ideas that could be implemented in my
                                area of practice (what?)</label>
                            <input name="aincheck" id="aincheck" type="text" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="bincheck" class="form-label">2. I learned something new (what?)</label>
                            <input name="bincheck" id="bincheck" type="text" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="cincheck" class="form-label">3. I can see possibilities for
                                collaboration</label>
                            <input name="cincheck" id="cincheck" type="text" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="dincheck" class="form-label">4. What topics would you like to hear in the
                                future?</label>
                            <input name="dincheck" id="dincheck" type="text" class="form-control" />
                        </div>
                        <div class="mt-4">
                            <label for="comment" class="form-label fw-bold">Is there anything else you think we should
                                know?</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4"
                                placeholder="e.g., The congress was well-organized and the topics were very relevant."
                                required></textarea>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-submit-feedback">Submit Feedback</button>
                    </div>

                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-danger text-center">
            <h4>Error</h4>
            <p><?php echo $error_message ?: 'The selected session could not be found.'; ?></p>
            <a href="dashboard.php" class="btn btn-primary">Return to Dashboard</a>
        </div>
        <?php endif; ?>
    </div>

    <?php include("user_head&footer/footer.php" );?>
    <?php $dbcon->close(); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackForm = document.getElementById('feedbackForm');

    feedbackForm.addEventListener('submit', function(event) {

        // --- NEW VALIDATION CHECK #1: Top Section Radio Buttons ---
        const info = document.querySelector('input[name="info"]:checked');
        const infoatt = document.querySelector('input[name="infoatt"]:checked');
        const category = document.querySelector('input[name="category"]:checked');

        if (!info) {
            event.preventDefault();
            document.querySelector('input[name="info"]').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            Swal.fire({
                icon: 'warning',
                title: 'Required Field',
                text: 'Please select how you learned about this event.',
                confirmButtonColor: '#0d1e53'
            });
            return;
        }

        if (!infoatt) {
            event.preventDefault();
            document.querySelector('input[name="infoatt"]').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            Swal.fire({
                icon: 'warning',
                title: 'Required Field',
                text: 'Please select what made you attend this event.',
                confirmButtonColor: '#0d1e53'
            });
            return;
        }

        if (!category) {
            event.preventDefault();
            document.querySelector('input[name="category"]').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            Swal.fire({
                icon: 'warning',
                title: 'Required Field',
                text: 'Please select the category that best describes you.',
                confirmButtonColor: '#0d1e53'
            });
            return;
        }

        // --- VALIDATION CHECK #2: Client Satisfaction Measurement (q1-q5) ---
        const ratingQuestions = ['q1', 'q2', 'q3', 'q4', 'q5'];
        for (let i = 0; i < ratingQuestions.length; i++) {
            const qName = ratingQuestions[i];
            if (!document.querySelector(`input[name="${qName}"]:checked`)) {
                event.preventDefault();
                document.querySelector(`input[name="${qName}"]`).scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                Swal.fire({
                    icon: 'warning',
                    title: 'Rating Required',
                    text: 'Please provide a rating for all Client Satisfaction Measurement questions.',
                    confirmButtonColor: '#0d1e53'
                });
                return;
            }
        }

        // --- VALIDATION CHECK #3: Speaker Ratings ---
        const speakerRatingSections = document.querySelectorAll('[name^="speaker_ratings"]');
        if (speakerRatingSections.length > 0) {
            const questionGroups = new Set();
            speakerRatingSections.forEach(input => questionGroups.add(input.name));

            let firstUnansweredGroup = null;
            questionGroups.forEach(groupName => {
                if (!document.querySelector(`input[name="${groupName}"]:checked`)) {
                    if (!firstUnansweredGroup) {
                        firstUnansweredGroup = groupName;
                    }
                }
            });

            if (firstUnansweredGroup) {
                event.preventDefault();
                const firstInvalidInput = document.querySelector(
                    `input[name="${firstUnansweredGroup}"]`);
                const speakerSection = firstInvalidInput.closest('.mb-5');
                if (speakerSection) {
                    speakerSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                Swal.fire({
                    icon: 'info',
                    title: 'Ratings Needed',
                    text: 'Please make sure to provide a rating for every question for each speaker. Thank you!',
                    confirmButtonColor: '#0d1e53'
                });
                return;
            }
        }

        // --- VALIDATION CHECK #4: Final Comment Box ---
        const comment = document.getElementById('comment').value.trim();
        if (comment === '') {
            event.preventDefault();
            document.getElementById('comment').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            Swal.fire({
                icon: 'info',
                title: 'Feedback Required',
                text: 'Please share your overall thoughts in the final comment box to help us improve. Thank you!',
                confirmButtonColor: '#0d1e53'
            });
            return;
        }

        // If all checks pass, the form will submit successfully.
    });
});
</script>


    <?php
        // Check if a flash message exists in the session
        if (isset($_SESSION['flash_message'])) {
            // Get the message details
            $message = $_SESSION['flash_message'];
            
            // Output the SweetAlert2 script
            echo "<script>
                // Ensure the DOM is loaded before showing the alert
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: '" . htmlspecialchars($message['type']) . "',
                        title: '" . ucfirst(htmlspecialchars($message['type'])) . "!',
                        text: '" . addslashes(htmlspecialchars($message['text'])) . "'
                    });
                });
            </script>";

            // Unset the message so it doesn't show again on the next refresh
            unset($_SESSION['flash_message']);
        }
        ?>
</body>

</html>