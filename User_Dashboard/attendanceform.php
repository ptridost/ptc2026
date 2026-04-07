<?php
date_default_timezone_set('Asia/Manila');
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(empty($_SESSION['participant_id'])){
    header("Location: ../Loginn.php"); 
    exit();
}

require('../dbcon.php');

// ===================================================================
// IMAGE PATH HELPERS
// ===================================================================
function getSpeakerImagePath($filename) {
    if (empty($filename)) {
        return '../admin/placeholder.jpg'; 
    }

    if (file_exists(__DIR__ . '/../admin/speaker_images/' . $filename)) {
        return '../admin/speaker_images/' . $filename;
    } 
    elseif (file_exists(__DIR__ . '/2024/' . $filename)) {
        return './2024/' . $filename;
    }

    return '../admin/placeholder.jpg';
}

function getLogoImagePath($filename) {
    if (empty($filename)) {
        return '../admin/placeholder.jpg';
    }

    if (file_exists(__DIR__ . '/../admin/session_logos/' . $filename)) {
        return '../admin/session_logos/' . $filename;
    }
    elseif (file_exists(__DIR__ . '/../landingpageimg/' . $filename)) {
        return '../landingpageimg/' . $filename;
    }

    return '../admin/placeholder.jpg';
}

$session_id = null;
$session_head = '';
$error_message = '';

// Get session ID
if (isset($_GET['id'])) {
    $session_id = intval($_GET['id']);
} elseif (isset($_POST['hidden'])) {
    $session_id = intval($_POST['hidden']);
}

// Get session head from GET/POST, or fetch from database if not provided
if (isset($_GET['sessionHeader'])) {
    $session_head = $_GET['sessionHeader'];
} elseif (isset($_POST['hidden1'])) {
    $session_head = $_POST['hidden1'];
} elseif ($session_id) {
    // Fetch session_head from database if not in URL
    $head_stmt = $dbcon->prepare("SELECT session_head FROM tblptcsession WHERE session_number = ?");
    $head_stmt->bind_param("i", $session_id);
    $head_stmt->execute();
    $head_result = $head_stmt->get_result();
    if ($head_result->num_rows > 0) {
        $head_row = $head_result->fetch_assoc();
        $session_head = $head_row['session_head'];
    }
    $head_stmt->close();
}

// --- FORM PROCESSING ---
if (isset($_POST['submit_attendance'])) {
    if ($session_id && $session_head) {
        $participant_id = intval($_SESSION['participant_id']);

        // Check if attendance already exists
        $check_stmt = $dbcon->prepare("SELECT attendance_id FROM tblattendance WHERE session_id = ? AND participant_id = ?");
        $check_stmt->bind_param("ii", $session_id, $participant_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $check_stmt->close();
            echo "<script>
                alert('You have already submitted attendance for this session.');
                window.location = 'dashboard.php';
            </script>";
            exit();
        }
        $check_stmt->close();

        // Fetch participant data from database
        $participant_stmt = $dbcon->prepare("SELECT firstName, middleName, lastName, Gender, Country, organization, Position FROM participant WHERE id = ?");
        $participant_stmt->bind_param("i", $participant_id);
        $participant_stmt->execute();
        $participant_result = $participant_stmt->get_result();
        
        if ($participant_result->num_rows > 0) {
            $participant = $participant_result->fetch_assoc();
            $participant_stmt->close();
            
            // Prepare values
            $fname = $participant['firstName'];
            $mname = $participant['middleName'];
            $lname = $participant['lastName'];
            $gender = $participant['Gender'];
            $country = $participant['Country'];
            $institution = $participant['organization'];
            $position = $participant['Position'];
            
            // Insert attendance
            $insert_stmt = $dbcon->prepare("INSERT INTO tblattendance (session_id, session_head, participant_id, fname, mName, lName, Gender, Country, Institution, Position, date_submitted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $insert_stmt->bind_param("isisssssss", $session_id, $session_head, $participant_id, $fname, $mname, $lname, $gender, $country, $institution, $position);
            
            if ($insert_stmt->execute()) {
                $insert_stmt->close();
                
                // Update CPD units
                $cpd_stmt = $dbcon->prepare("SELECT SUM(ses.cpd_units) AS TOTAL_CPD 
                                              FROM tblattendance att
                                              INNER JOIN tblptcsession ses ON att.session_id = ses.session_number
                                              WHERE att.participant_id = ?");
                $cpd_stmt->bind_param("i", $participant_id);
                $cpd_stmt->execute();
                $cpd_result = $cpd_stmt->get_result();
                $cpd_row = $cpd_result->fetch_assoc();
                $total_cpd = $cpd_row['TOTAL_CPD'] ?? 0;
                $cpd_stmt->close();
                
                // Update participant CPD
                $update_cpd_stmt = $dbcon->prepare("UPDATE participant SET cpd = ? WHERE id = ?");
                $update_cpd_stmt->bind_param("di", $total_cpd, $participant_id);
                $update_cpd_stmt->execute();
                $update_cpd_stmt->close();
                
                echo "<script>
                    alert('Attendance Submitted Successfully!');
                    window.location = 'dashboard.php';
                </script>";
                exit();
            } else {
                echo "<script>
                    alert('Error submitting attendance: " . $insert_stmt->error . "');
                    window.location = 'attendanceform.php?id=" . $session_id . "';
                </script>";
                $insert_stmt->close();
                exit();
            }
        } else {
            $participant_stmt->close();
            echo "<script>
                alert('Participant information not found.');
                window.location = 'dashboard.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Missing session information. Please try again.');
            window.location = 'dashboard.php';
        </script>";
        exit();
    }
}

// --- FETCH DATA FOR DISPLAY ---
$session_data = null;
$speaker_images = [];
$already_attended = false;

if ($session_id) {
    // Get session details
    $session_stmt = $dbcon->prepare("SELECT * FROM tblptcsession WHERE session_number = ?");
    $session_stmt->bind_param("i", $session_id);
    $session_stmt->execute();
    $session_result = $session_stmt->get_result();
    if($session_result->num_rows > 0) {
        $session_data = $session_result->fetch_assoc();
    }
    $session_stmt->close();

    // Get speaker images
    if ($session_data) {
        $speaker_stmt = $dbcon->prepare("SELECT speaker_Img FROM tblspeakerses WHERE session_id = ? AND speaker_Img != 'placeholder.jpg'");
        $speaker_stmt->bind_param("i", $session_id);
        $speaker_stmt->execute();
        $speaker_result = $speaker_stmt->get_result();
        while($speaker = $speaker_result->fetch_assoc()) {
            if (!empty($speaker['speaker_Img'])) {
                $speaker_images[] = getSpeakerImagePath($speaker['speaker_Img']);
            }
        }
        $speaker_stmt->close();
    }

    // Check if already attended
    $attend_check_stmt = $dbcon->prepare("SELECT attendance_id FROM tblattendance WHERE session_id = ? AND participant_id = ?");
    $attend_check_stmt->bind_param("ii", $session_id, $_SESSION['participant_id']);
    $attend_check_stmt->execute();
    if($attend_check_stmt->get_result()->num_rows > 0) {
        $already_attended = true;
    }
    $attend_check_stmt->close();
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
    <title>Attendance Form | Philippine Textile Congress</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="attendanceform.css">
    <link rel="stylesheet" type="text/css" href="user_head&footer/user_nav.css">
    <link rel="stylesheet" type="text/css" href="user_head&footer/footer.css">
</head>

<body>
    <?php include("user_head&footer/user_nav.php"); ?>

    <div class="container atten_con">
        <div class="row">
            <div class="col-md-12 atten_title">
                <img src="./user_dash_img/Picture1.png">
                <h1>PTC Attendance Form</h1>
            </div>
        </div>
        <br><br>
        
        <?php if ($session_data): ?>
        <div class="container atten_con1">
            <div class="row">
                <div class="col-md-12 atten_inner_title">
                    <img src="<?php echo htmlspecialchars(getLogoImagePath($session_data['logo_image'])); ?>" 
                         alt="Session Logo">
                    <div class="h1_img">
                        <h1><?php echo htmlspecialchars($session_data['session_title']); ?></h1>
                        <?php foreach($speaker_images as $img): ?>
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="Speaker">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 inner_p">
                    <p><span>Data Privacy Notice:</span> In line with the Data Privacy Act of 2012, DOST-PTRI is
                        committed to protect and secure your personal information obtained in the evaluation of the
                        conduct of <span>2024 Philippine Textile Congress</span>. The personal information you provided
                        herein will be processed and utilized solely for this event. Collected personal information will
                        be kept/stored and accessed only by authorized DOST-PTRI personnel and shall not be shared with
                        any outside parties unless written consent is secured. Information shall be stored within one
                        (1) year after which physical records shall be disposed of or destroyed following the
                        disposition process provided by the National Archives of the Philippines.</p>
                </div>
            </div>
            <br>

            <form action="attendanceform.php" method="POST" id="attendanceForm">
                <input type="hidden" name="hidden" value="<?php echo $session_id; ?>">
                <input type="hidden" name="hidden1" value="<?php echo htmlspecialchars($session_head ?? ''); ?>">

                <center>
                    <?php if ($already_attended): ?>
                    <button id="btn_submit_atten" class="button" type="button" disabled>
                        <i class="fas fa-check-circle me-2"></i>ALREADY ATTENDED
                    </button>
                    <?php else: ?>
                    <button id="btn_submit_atten" class="button" type="submit" name="submit_attendance">
                        <i class="fas fa-user-check me-2"></i>Submit My Attendance
                    </button>
                    <?php endif; ?>
                </center>
            </form>
        </div>
        <?php else: ?>
        <div class="alert alert-danger">
            <h4>Error</h4>
            <p><?php echo $error_message ?: 'The selected session could not be found. It may have been deleted.'; ?></p>
            <a href="dashboard.php" class="btn btn-primary">Return to Dashboard</a>
        </div>
        <?php endif; ?>
    </div>

    <?php include("user_head&footer/footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // Simple form submission - no confirmation needed for testing
    const attendanceForm = document.getElementById('attendanceForm');
    if (attendanceForm) {
        attendanceForm.addEventListener('submit', function(e) {
            // Let it submit normally for now
            console.log('Form submitting...');
        });
    }
    </script>

    <?php $dbcon->close(); ?>
</body>
</html>