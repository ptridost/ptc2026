<?php
date_default_timezone_set('Asia/Manila');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(empty($_SESSION['participant_id'])){
    header("Location: ../Loginn.php"); 
    exit();
}

require('../dbcon.php');


function getImagePath($filename, $type = 'logo') {
    if (empty($filename)) {
        return './placeholder.png'; // Path to a default placeholder image
    }

    $new_logo_path = '../admin/session_logos/' . $filename;
    $old_logo_path = '../landingpageimg/' . $filename;

    $new_speaker_path = './speaker_images/' . $filename;
    $old_speaker_path = './2024/' . $filename; // Your original speaker folder

    if ($type === 'logo') {
        // Check for the logo in the new location first, then the old one
        if (file_exists($new_logo_path)) {
            return $new_logo_path;
        } elseif (file_exists($old_logo_path)) {
            return $old_logo_path;
        }
    } 
    elseif ($type === 'speaker') {
        // Check for the speaker image in the new location first, then the old one
        if (file_exists($new_speaker_path)) {
            return $new_speaker_path;
        } elseif (file_exists($old_speaker_path)) {
            return $old_speaker_path;
        }
    }

    // If found in neither location, return the placeholder
    return './placeholder.jpg';
}

// --- START: EDIT PROFILE FORM PROCESSING (NOW INCLUDES NEW PROFESSIONS) ---
if (isset($_POST['submit_profile_update'])) {
    $pfname = $_POST['pfname'];
    $pmname = $_POST['pmname'];
    $plname = $_POST['plname'];
    $partage = $_POST['partage'];
    $institution = $_POST['organization'];
    $emailuser = $_POST['username'];
    $other_profession = $_POST['other_profession'];
    
    // Get all PRC license data from the form
    $chemEnPrc = $_POST['ChemEn_prc'];
    $chemPrc = $_POST['Chem_prc'];
    $chemTechPrc = $_POST['ChemTech_prc'];
    $mechEnPrc = $_POST['MechEn_prc'];
    $agriEnPrc = $_POST['AgriEn_prc'];
    $othersPrc = $_POST['Others_prc'];
    
    // UPDATED: Added new columns to the secure prepared statement
    $stmt = $dbcon->prepare("UPDATE participant SET 
        firstName = ?, middleName = ?, lastName = ?, Age = ?, organization = ?, username = ?,
        ChemEn_prc = ?, Chem_prc = ?, ChemTech_prc = ?, Others_prc = ?, other_profession = ?,
        MechEn_prc = ?, AgriEn_prc = ? 
        WHERE id = ?");
        
    $stmt->bind_param("sssssssssssssi", 
        $pfname, $pmname, $plname, $partage, $institution, $emailuser,
        $chemEnPrc, $chemPrc, $chemTechPrc, $othersPrc, $other_profession,
        $mechEnPrc, $agriEnPrc,
        $_SESSION['participant_id']
    );
    
    if ($stmt->execute()) {
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Your profile has been updated!'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Error updating profile.'];
    }
    $stmt->close();
    
    header("Location: dashboard.php");
    exit();
}
// --- END: EDIT PROFILE FORM PROCESSING ---

// --- SECURELY Fetch all participant data ---
$participant_data = [];
$stmt = $dbcon->prepare("SELECT * FROM participant WHERE id = ?");
$stmt->bind_param("i", $_SESSION['participant_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $participant_data = $result->fetch_assoc();
} else {
    echo "Error: Participant not found."; exit();
}
$stmt->close();

// --- DYNAMIC PRC MAP ---
$prc_map = [
    "Chem_prc"     => "Chemist",
    "ChemEn_prc"   => "Chemical Engineer",
    "ChemTech_prc" => "Chemical Technician",
    "MechEn_prc"   => "Mechanical Engineer",
    "AgriEn_prc"   => "Agricultural & Biosystems Engineer",
    "Others_prc"   => !empty($participant_data['other_profession']) ? $participant_data['other_profession'] : 'Other Profession'
];
$hasPRC = false;
foreach ($prc_map as $column_name => $label) {
    if (!empty($participant_data[$column_name])) { $hasPRC = true; break; }
}

// --- Fetch Settings & Certificate Data (Preserved) ---
$settings = [];
$stmt_settings = $dbcon->prepare("SELECT name, value FROM variables");
if ($stmt_settings) {
    $stmt_settings->execute();
    $result_settings = $stmt_settings->get_result();
    while ($row_setting = $result_settings->fetch_assoc()) {
        $settings[$row_setting['name']] = $row_setting['value'];
    }
    $stmt_settings->close();
}
$cert_check_stmt = $dbcon->prepare("SELECT YEAR(date_submitted) as year, COUNT(DISTINCT session_id) as session_count FROM tblattendance WHERE participant_id = ? GROUP BY YEAR(date_submitted)");
$cert_check_stmt->bind_param("i", $_SESSION['participant_id']);
$cert_check_stmt->execute();
$cert_result = $cert_check_stmt->get_result();
$attended_sessions_by_year = []; 
while ($year_row = $cert_result->fetch_assoc()) {
    $attended_sessions_by_year[$year_row['year']] = $year_row['session_count'];
}
$cert_check_stmt->close();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />
    <title>Participant Dashboard | Philippine Textile Congress</title>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css" 
      rel="stylesheet" 
      
      crossorigin="anonymous" 
      referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet" type="text/css" href="dashboard.css" />
    <link rel="stylesheet" type="text/css" href="./user_head&footer/footer.css">
    <link rel="stylesheet" type="text/css" href="./user_head&footer/user_nav.css">
</head>

<body>

    <?php include("./user_head&footer/user_nav.php"); ?>

    <main class="container user-dashboard">
        <header class="dashboard-header">
            <img src="./user_dash_img/PTClogo.jpg" alt="PTC Logo">
            <h1>Participant Dashboard</h1>
        </header>

        <?php if(!empty($participant_data)): ?>
        <div class="user-card-wrapper">
            <div class="user-card">
                <div class="user-card__image-wrapper d-none d-md-block">
                    <img src="../img_outside/ptc.png" class="user-card__image" alt="Profile background image">
                </div>
                <div class="user-card__content">
                    <h2 class="user-card__name">
                        <?php 
                            $middle = trim($participant_data["middleName"] ?? '');
                            $middleFormatted = $middle ? ucfirst(rtrim($middle, '.')) . '. ' : '';
                            echo htmlspecialchars(ucfirst($participant_data["firstName"]) . ' ' . $middleFormatted . ucfirst($participant_data["lastName"]));
                        ?>
                    </h2>
                    <p class="user-card__professions">
                        <?php
                            $profession_arr = explode(',', $participant_data["profession"]);
                            $display_professions = [];
                            foreach ($profession_arr as $profession) {
                                $prof_trimmed = trim($profession);
                                if ($prof_trimmed == "Others" && !empty($participant_data["other_profession"])) {
                                    $display_professions[] = htmlspecialchars($participant_data["other_profession"]);
                                } elseif (!empty($prof_trimmed)) {
                                    $display_professions[] = htmlspecialchars($prof_trimmed);
                                }
                            }
                            echo implode(' / ', $display_professions);
                        ?>
                    </p>
                    <div class="user-card__actions">
                        <button type="button" class="btn-ptc btn-ptc--profile btn-ptc--font-regular"
                            data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <ion-icon name="person-circle-outline"></ion-icon>Edit Profile
                        </button>
                        <a href="viewperformance.php"
                            class="btn-ptc btn-ptc--profile btn-ptc--font-regular btn-ptc--warning">
                            <ion-icon name="document-text-outline"></ion-icon>View my Attendance
                        </a>
                    </div>
                    <hr>
                    <div class="prc-section">
                        <h5 class="prc-section__title"><i class="fa fa-address-card"></i> PRC License Number</h5>
                        <div class="prc-list">
                            <?php
                                $_has_prc_displayed = false;
                                foreach ($prc_map as $column_name => $label) {
                                    if (!empty($participant_data[$column_name])) {
                                        echo '<div class="prc-item"><span>' . htmlspecialchars($label) . ':</span><strong>' . htmlspecialchars($participant_data[$column_name]) . '</strong></div>';
                                        $_has_prc_displayed = true;
                                    }
                                }
                                if (!$_has_prc_displayed) {
                                    echo "<p class='fst-italic text-white-50 ms-2'>No PRC License Provided</p>";
                                }
                            ?>
                        </div>
                    </div>
                    <hr>
                    <?php
                    // --- START: DYNAMIC CPD DISPLAY LOGIC (RESTORED & CORRECTED) ---
                    $professions_to_check = [
                        'chemeng'  => 'Chemical Engineer',
                        'chem'     => 'Chemist',
                        'chemtech' => 'Chemical Technician',
                        'mecheng'  => 'Mechanical Engineer',
                        'agrien'   => 'Agricultural & Biosystems Engineer'
                    ];
                    $years_to_check = ['2023','2024', '2025', '2026']; // You can add more years here

                    foreach ($years_to_check as $year) {
                        foreach ($professions_to_check as $key => $label) {
                            $column_name = 'cpd_' . $key . '_' . $year;
                            // Check if data exists for that column in the participant's data
                            if (!empty($participant_data[$column_name])) {
                    ?>
                    <div class="cpd-item">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <small class="cpd-item__text">
                            <strong><?php echo htmlspecialchars($participant_data[$column_name]); ?></strong>
                            CPD units earned (<?php echo $label; ?> - <?php echo $year; ?>)
                        </small>
                    </div>
                    <?php
                            }
                        }
                    }
                    // --- END: DYNAMIC CPD DISPLAY LOGIC ---
                    ?>
                    <div class="certificate-section mt-3">
                        <?php
                        // --- ALWAYS SHOW BUTTONS FOR ALL YEARS ---
                        // If you want fixed years (e.g., 2023–2025), define the range explicitly:
                        //$years = [2023, 2024, 2025];
                        $years = [2023, 2024];

                        // Or if you want dynamic based on attended sessions, uncomment this line:
                        // $years = array_keys($attended_sessions_by_year);

                        foreach ($years as $year) {
                            $count = $attended_sessions_by_year[$year] ?? 0;
                            $dist_date_name = 'certificate_distribution_date_' . $year;
                            $dist_date = $settings[$dist_date_name] ?? '2030-12-31';

                            // Check eligibility
                            $eligible = ($count >= 6 && $hasPRC && $dist_date <= date('Y-m-d'));
                            //$eligible = true; // TEMPORARY: force eligibility for testing
                            //$TEST_MODE = true; // For testing only
                            //if ($eligible|| $TEST_MODE) {
                            if ($eligible) {
                                //  Eligible → direct generate button
                                $cert_url = (defined('BASE_URL') ? BASE_URL : '') .
                                    '/admin/certificate/generate_certificate.php?id=' . $_SESSION['participant_id'] . '&year=' . $year;

                                echo '<button type="button" 
                                        class="btn-ptc btn-ptc--profile btn-ptc--font-regular btn-ptc--primary mt-2"
                                        onclick="window.open(\'' . $cert_url . '\')">
                                        Generate Certificate for ' . $year . '
                                    </button>';
                            } else {
                                //  Not eligible → show modal with reasons
                                $reasons = [];
                                if ($count < 6) {
                                    $reasons[] = "You attended $count of the required 6 sessions for $year.";
                                }
                                if (!$hasPRC) {
                                    $reasons[] = "A PRC License number is required. Please use the 'Edit Profile' button to add it.";
                                }
                                if ($dist_date > date('Y-m-d')) {
                                    $reasons[] = "This certificate for the year $year can be downloaded starting " .
                                                date('F j, Y', strtotime($dist_date)) . ".";
                                }

                                $reason_html = "<ul><li>" . implode("</li><li>", $reasons) . "</li></ul>";

                                echo '<button type="button" 
                                        class="btn-ptc btn-ptc--profile btn-ptc--font-regular btn-ptc--secondary mt-2 mx-2"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#exampleModal2" 
                                        data-reason="' . htmlspecialchars($reason_html) . '">
                                        Generate Certificate for ' . $year . '
                                    </button>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="sessions-section">
            <div class="sessions-header">
                <img src="./user_dash_img/PTClogo.jpg" alt="PTC Logo">
                <h1>Today's Session/s</h1>
            </div>
            <div class="row g-4 mt-2">
                <?php
                    $today_date = date('Y-m-d');
                    $sql_today = "SELECT tblptcsession.*, IF(video.session_id = tblptcsession.session_number, 'true', 'false') AS hasVideo FROM tblptcsession LEFT JOIN video ON video.session_id = tblptcsession.session_number WHERE DATE(tblptcsession.session_date) = ? ORDER BY tblptcsession.session_time;";
                    $stmt_today = $dbcon->prepare($sql_today);
                    $stmt_today->bind_param("s", $today_date);
                    $stmt_today->execute();
                    $today_sessions_result = $stmt_today->get_result();

                    if ($today_sessions_result->num_rows > 0) {
                        while ($row = $today_sessions_result->fetch_assoc()) {
                            echo "<div class='col-sm-12 col-md-6 col-lg-4'>";
                            require('sessionCards.php');
                            echo "</div>";
                        }
                    } else {
                        echo '<div class="col-12"><p class="no-session-title h3 text-center">No session/s for today</p></div>';
                    }
                    $stmt_today->close();
                ?>
            </div>
        </div>

        <div class="sessions-section">
            <div class="sessions-header">
                <div class="d-flex align-items-center">
                    <img src="./user_dash_img/PTClogo.jpg" alt="PTC Logo">
                    <h1>Upcoming Sessions</h1>
                </div>
            </div>
            <div class="row g-4 mt-2">
                <?php
                // This query finds sessions scheduled for today or any future date
                $today_date = date('Y-m-d');
                $stmt_upcoming = $dbcon->prepare("SELECT * FROM tblptcsession WHERE session_date >= ? ORDER BY session_date ASC, session_time ASC");
                $stmt_upcoming->bind_param("s", $today_date);
                $stmt_upcoming->execute();
                $upcoming_sessions_result = $stmt_upcoming->get_result();

                if ($upcoming_sessions_result->num_rows > 0) {
                    while ($row = $upcoming_sessions_result->fetch_assoc()) {
                        // To prevent errors, we set default statuses for the session card
                        $row['attendance_status'] = 'Not Attended'; // Default for upcoming
                        $row['feedback_status'] = 'Not Submitted'; // Default for upcoming
                        
                        echo "<div class='col-sm-12 col-md-6 col-lg-4 session-card-wrapper' data-session-year='" . date('Y', strtotime($row['session_date'])) . "'>";
                        // We reuse the same user-facing card template
                        include('sessionCards.php');
                        echo "</div>";
                    }
                } else {
                    echo '<div class="col-12"><p class="h4 text-center text-muted my-4">There are no upcoming sessions currently scheduled. Please check back later!</p></div>';
                }
                $stmt_upcoming->close();
            ?>
            </div>
        </div>

        

        <div class="sessions-section">
            <div class="sessions-header all-sessions-header">
                <div class="d-flex align-items-center">
                    <img src="./user_dash_img/PTClogo.jpg" alt="PTC Logo">
                    <h1>All Sessions</h1>
                </div>
                <div class="btn-group btn-group-lg year-filter-group" role="group">
                    <button id="showAll" class="btn btn-dashboard selected">All</button>
                    <button id="show2025" class="btn btn-dashboard">2025</button>
                    <button id="show2024" class="btn btn-dashboard">2024</button>
                    <button id="show2023" class="btn btn-dashboard">2023</button>
                </div>
            </div>
            <div class="row g-4 mt-2">
                <?php
                    $sql_all = "SELECT tblptcsession.*, IF(video.session_id = tblptcsession.session_number, 'true', 'false') AS hasVideo FROM tblptcsession LEFT JOIN video ON video.session_id = tblptcsession.session_number ORDER BY tblptcsession.session_date DESC, tblptcsession.session_time DESC";
                    $all_sessions_result = $dbcon->query($sql_all);
                    while ($row = $all_sessions_result->fetch_assoc()) {
                        $session_year = date('Y', strtotime($row['session_date']));
                        echo "<div class='col-sm-12 col-md-6 col-lg-4 session-card' data-session-year='" . $session_year . "'>";
                        require('sessionCards.php');
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <form action="dashboard.php" method="POST">
        <div class="modal fade" id="editProfileModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Profile</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">First Name</label><input type="text"
                                class="form-control" name="pfname"
                                value="<?php echo htmlspecialchars($participant_data['firstName']); ?>"></div>
                        <div class="mb-3"><label class="form-label">Middle Name</label><input type="text"
                                class="form-control" name="pmname"
                                value="<?php echo htmlspecialchars($participant_data['middleName']); ?>"></div>
                        <div class="mb-3"><label class="form-label">Last Name</label><input type="text"
                                class="form-control" name="plname"
                                value="<?php echo htmlspecialchars($participant_data['lastName']); ?>"></div>
                        <div class="mb-3"><label class="form-label">Age Group</label><input type="text"
                                class="form-control" name="partage"
                                value="<?php echo htmlspecialchars($participant_data['Age']); ?>"></div>
                        <div class="mb-3"><label class="form-label">Institution</label><input type="text"
                                class="form-control" name="organization"
                                value="<?php echo htmlspecialchars($participant_data['organization']); ?>"></div>
                        <div class="mb-3"><label class="form-label">Email / Username</label><input type="email"
                                class="form-control" name="username"
                                value="<?php echo htmlspecialchars($participant_data['username']); ?>"></div>
                        <hr>
                        <p class="text-center fw-bold">PRC License Numbers</p>
                        <?php foreach ($prc_map as $column_name => $label): ?>
                        <div class="mb-3">
                            <label class="form-label"><?php echo htmlspecialchars($label); ?></label>
                            <input type="text" class="form-control" name="<?php echo $column_name; ?>"
                                value="<?php echo htmlspecialchars($participant_data[$column_name] ?? ''); ?>">
                        </div>
                        <?php endforeach; ?>
                        <div class="mb-3">
                            <label class="form-label">If 'Other', please specify</label>
                            <input type="text" class="form-control" name="other_profession"
                                value="<?php echo htmlspecialchars($participant_data['other_profession'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit_profile_update" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModalatt" tabindex="-1" aria-labelledby="attendanceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="attendanceModalLabel">Attendance Not Yet Open</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-0">Attendance for this session will open at</p>
                        <p class="h4 text-danger mt-1" id="attendanceOpenTime">...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="exampleModalfeed" tabindex="-1" aria-labelledby="feedbackModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedbackModalLabel">Feedback Not Yet Open</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-0">The feedback form for this session will open at</p>
                        <p class="h4 text-danger mt-1" id="feedbackOpenTime">...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <div class="modal fade" id="exampleModal2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preparing Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="preparingCertReason"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal3" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Certificate Not Available</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>We're sorry, we can't provide a certificate because our records show you haven't attended any
                        sessions yet.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php include("./user_head&footer/footer.php"); ?>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.bundle.min.js" 
         
        crossorigin="anonymous" 
        referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script>
    $(document).ready(function() {
        function filterSessions(yearToShow) {
            $('.btn-dashboard').removeClass('selected');
            const buttonId = (yearToShow === 'all') ? '#showAll' : '#show' + yearToShow;
            $(buttonId).addClass('selected');
            if (yearToShow === 'all') {
                $(".session-card").fadeIn(400);
            } else {
                $(".session-card:visible").fadeOut(300).promise().done(function() {
                    $(`.session-card[data-session-year=${yearToShow}]`).fadeIn(300);
                });
            }
        }
        $("#showAll").click(function() {
            filterSessions('all');
        });
        $("#show2025").click(function() {
            filterSessions('2025');
        });
        $("#show2024").click(function() {
            filterSessions('2024');
        });
        $("#show2023").click(function() {
            filterSessions('2023');
        });
    });

    // --- Logic for the dynamic "Attendance Not Open" modal ---
    const attendanceModal = document.getElementById('exampleModalatt');
    if (attendanceModal) {
        attendanceModal.addEventListener('show.bs.modal', function(event) {
            // Get the button that triggered the modal
            const button = event.relatedTarget;
            // Get the specific time from the button's data-accept-time attribute
            const openTime = button.getAttribute('data-accept-time');

            // Find the element inside the modal where we'll show the time
            const timeElement = attendanceModal.querySelector('#attendanceOpenTime');

            // Update the modal's content with the correct time
            if (timeElement) {
                timeElement.textContent = openTime;
            }
        });
    }

    // --- Logic to make the "Preparing Certificate" modal dynamic ---
    const preparingCertModal = document.getElementById('exampleModal2');
    if (preparingCertModal) {
        preparingCertModal.addEventListener('show.bs.modal', function(event) {
            // Get the button that triggered the modal
            const button = event.relatedTarget;
            // Get the specific reason from the button's data-reason attribute
            const reason = button.getAttribute('data-reason');

            // Find the paragraph inside the modal where we'll show the message
            const reasonParagraph = preparingCertModal.querySelector('#preparingCertReason');

            // The default message, plus the specific reason
            if (reasonParagraph) {
                reasonParagraph.innerHTML =
                    "Please wait, we are still preparing your certificate. Availability is subject to the following: <br><br><strong class='text-danger'>" +
                    (reason || 'An unknown issue occurred.') + "</strong>";
            }
        });
    }


    // --- Logic for the dynamic "Feedback Not Open" modal ---
    const feedbackModal = document.getElementById('exampleModalfeed');
    if (feedbackModal) {
        feedbackModal.addEventListener('show.bs.modal', function(event) {
            // Get the button that triggered the modal
            const button = event.relatedTarget;
            // Get the specific time from the button's data-accept-time attribute
            const openTime = button.getAttribute('data-accept-time');

            // Find the element inside the modal where we'll show the time
            const timeElement = feedbackModal.querySelector('#feedbackOpenTime');

            // Update the modal's content with the correct time
            if (timeElement) {
                timeElement.textContent = openTime;
            }
        });
    }



    // SweetAlert flash message script is preserved
    <?php
            if (isset($_SESSION['flash_message'])) {
                $message = $_SESSION['flash_message'];
                echo "Swal.fire({ icon: '".$message['type']."', title: '".ucfirst($message['type'])."!', text: '".addslashes($message['text'])."', timer: 2000, showConfirmButton: false });";
                unset($_SESSION['flash_message']);
            }
            ?>
    </script>

</body>

</html>