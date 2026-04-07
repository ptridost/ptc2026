<?php
date_default_timezone_set('Asia/Manila');
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if(empty($_SESSION['admin_id'])){
    header("Location: ../Loginn.php"); 
    exit();
}

require('../dbcon.php');

// ===================================================================
// START: BACKWARD-COMPATIBLE IMAGE PATH HELPER
// ===================================================================
function getImagePath($filename, $type = 'logo') {
    if (empty($filename)) {
        return './placeholder.png'; // Path to a default placeholder image
    }

    $new_logo_path = './session_logos/' . $filename;
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
// ===================================================================
// END: IMAGE PATH HELPER
// ===================================================================

// --- 2. HANDLE ALL FORM SUBMISSIONS AT THE TOP ---
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

    // --- Handle ADD SESSION form ---
    if (isset($_POST['add_session_submit'])) {
            $sdate = $_POST['sesdate'];
            $stime = $_POST['sestime'];
            $etime = $_POST['endsestime'];
            $stitle = $_POST['stitle'];
            $shead = $_POST['sesshead'];
            $seslink = $_POST['sesl'];
            $sescpd = $_POST['cpd'];
            
            $logoNameToSave = $_POST['logo_image_text']; // Default to manually typed name

            // Priority: If a file is uploaded, it overrides the typed name
            if (isset($_FILES['logo_image_file']) && $_FILES['logo_image_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['logo_image_file']['tmp_name'];
                $fileName = $_FILES['logo_image_file']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedfileExtensions)) {
                    $uploadFileDir = '../landingpageimg/'; 
                    if (!is_dir($uploadFileDir)) { mkdir($uploadFileDir, 0755, true); }
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $logoNameToSave = $newFileName; 
                    }
                }
            }

            $stmt = $dbcon->prepare("INSERT INTO tblptcsession (session_date, session_time, sessionend_time, session_title, session_head, sessionlink, cpd_units, logo_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssds", $sdate, $stime, $etime, $stitle, $shead, $seslink, $sescpd, $logoNameToSave);
            
            if ($stmt->execute()) {
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Session added successfully!'];
            } else {
                $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Error adding session.'];
            }
            $stmt->close();
            header("Location: admindashboard.php");
            exit();
        }
        // --- Handle EDIT SESSION form ---
        if (isset($_POST['editInfo'])) {
            $sdate = $_POST['sesdate1'];
            $stime = $_POST['sestime1'];
            $etime = $_POST['sessionendtime1'];
            $shead = $_POST['seshead1'];
            $stitle = $_POST['sestitle1'];
            $seslink = $_POST['sessionlink1'];
            $sescpd = $_POST['cpd1'];
            $session_id = $_POST['session_id'];
            $current_logo = $_POST['current_logo_image'];
            $typed_logo = $_POST['logoimage1'];

            $logoToSave = $current_logo;

            if (isset($_FILES['new_logo_image_file']) && $_FILES['new_logo_image_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['new_logo_image_file']['tmp_name'];
                $fileName = $_FILES['new_logo_image_file']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    $uploadFileDir = '../landingpageimg/';
                    $dest_path = $uploadFileDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $logoToSave = $newFileName;
                        if ($current_logo && file_exists($uploadFileDir . $current_logo)) {
                            unlink($uploadFileDir . $current_logo);
                        }
                    }
                }
            } else if ($typed_logo !== $current_logo) {
                $logoToSave = $typed_logo;
            }

            $stmt = $dbcon->prepare("UPDATE tblptcsession SET session_date = ?, session_time = ?, sessionend_time = ?, session_head = ?, session_title = ?, sessionlink = ?, logo_image = ?, cpd_units = ? WHERE session_number = ?");
            $stmt->bind_param("sssssssdi", $sdate, $stime, $etime, $shead, $stitle, $seslink, $logoToSave, $sescpd, $session_id);
            if ($stmt->execute()) {
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Session updated successfully!'];
            } else {
                $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Error updating session.'];
            }
            $stmt->close();
            header("Location: admindashboard.php");
            exit();
        }

        // --- Handle ADD SPEAKER form ---
        if (isset($_POST['addspeak'])) {
            $message = null;

            if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
                $sHead = $_POST['speakerhead'];
                $sName = $_POST['speakername'];
                $sDes = $_POST['speakerdes'];
                $sGen = $_POST['speakergen'];
                
                $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
                $fileName = $_FILES['uploadedFile']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                // Create unique filename
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                
                $allowedfileExtensions = ['jpg', 'jpeg', 'png'];

                if (in_array($fileExtension, $allowedfileExtensions)) {
                    $uploadFileDir = './speaker_images/';
                    if (!is_dir($uploadFileDir)) { mkdir($uploadFileDir, 0755, true); }
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        // IMPORTANT: Save ONLY the filename, not the full path
                        $filenameToSave = $newFileName;  // Just the filename!
                        $currentYear = date('Y');
                        
                        $stmt = $dbcon->prepare("INSERT INTO tblspeakerses (session_id, speaker_Name, speaker_Info, spkr_gender, speaker_Img, year) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("isssss", $sHead, $sName, $sDes, $sGen, $filenameToSave, $currentYear);
                        
                        if ($stmt->execute()) {
                            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Speaker added successfully!'];
                        } else {
                            $message = 'Error saving speaker to the database.';
                        }
                        $stmt->close();
                    } else {
                        $message = 'Error moving the uploaded file. Please check directory permissions.';
                    }
                } else {
                    $message = 'Upload failed. Allowed file types are: jpg, jpeg, png.';
                }
            } else {
                $message = 'File upload error. Please choose a file to upload.';
            }

            if ($message) {
                $_SESSION['flash_message'] = ['type' => 'error', 'text' => $message];
            }
            
            header("Location: admindashboard.php");
            exit();
        }

        // --- Handle DELETE SESSION form ---
        if (isset($_POST['confirm_delete_session'])) {
            $session_id_to_delete = $_POST['session_id_to_delete'];

            $dbcon->begin_transaction();
            try {
                $tables_to_delete = ['tblspeaker_rating', 'tblconrating', 'tblfeedback', 'tblattendance', 'tblspeakerses'];
                foreach ($tables_to_delete as $table) {
                    $stmt = $dbcon->prepare("DELETE FROM $table WHERE session_id = ?");
                    $stmt->bind_param("i", $session_id_to_delete);
                    $stmt->execute();
                    $stmt->close();
                }

                $stmt = $dbcon->prepare("DELETE FROM tblptcsession WHERE session_number = ?");
                $stmt->bind_param("i", $session_id_to_delete);
                $stmt->execute();
                $stmt->close();

                $dbcon->commit();
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Session and all related data have been permanently deleted.'];

            } catch (mysqli_sql_exception $exception) {
                $dbcon->rollback();
                error_log($exception->getMessage());
                $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'An error occurred during deletion. No data was changed.'];
            }
            
            // Redirect after the operation is complete
            header("Location: admindashboard.php");
            exit();
        }

    // --- Handle any other form submissions here ---
    // You can add more form handling logic as needed.
}

// --- 3. FETCH DATA FOR PAGE DISPLAY ---
$stats = $dbcon->query("SELECT (SELECT COUNT(*) FROM tblptcsession) as total_sessions, (SELECT COUNT(DISTINCT id) FROM participant) as total_participants, (SELECT COUNT(*) FROM tblspeakerses) as total_speakers")->fetch_assoc();
$add_speaker_sessions_result = $dbcon->query("SELECT session_number, session_head FROM tblptcsession ORDER BY session_date DESC");
?>









<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PTC | Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css" 
      rel="stylesheet" 
      
      crossorigin="anonymous" 
      referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="./admindashboard.css">
    <link rel="stylesheet" type="text/css" href="./nav&footer/usernav.css">
    <link rel="stylesheet" type="text/css" href="./nav&footer/footer.css">
    <link rel="stylesheet" type="text/css" href="../analytics2024.css">
</head>

<body class="pb-5">
    <?php include("./nav&footer/usernav.php"); ?>

    <div class="admindash_con container mt-5 pt-5">
        <div class="row mb-4">
            <div class="admin_dashboard">
                <img src="Picture1.png">
                <h1>Admin Dashboard</h1>
                <button id="toggle-charts-btn" class="btn-admin-action btn-admin-darkgray">
                    <i class="fas fa-plus"></i><span class="ms-2">Show Charts</span>
                </button>
            </div>
        </div>

        <div class="dashboard-control-bar">
            <div class="control-bar__title">
                <h1>Dashboard Filters</h1>
                <p class="lead">Filter all charts and stats below by year and session.</p>
            </div>
            <div class="control-bar__actions">
                <div class="btn-group year-selector" role="group">
                    <button type="button" class="btn year-btn btn-year-2023" data-year="2023">2023</button>
                    <button type="button" class="btn year-btn btn-year-2024 active" data-year="2024">2024</button>
                    <button type="button" class="btn year-btn btn-year-2025" data-year="2025">2025</button>
                </div>
                <div class="session-selector">
                    <button class="selector-arrow" id="btnLeft"><i class="fas fa-chevron-left"></i></button>
                    <div class="dropdown">
                        <button class="selector-button dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">Loading...</button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="session_list"></ul>
                    </div>
                    <button class="selector-arrow" id="btnRight"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>


        <div id="charts-wrapper" style="display: none;">
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="dashboard-card kpi-card h-100">
                        <h2 class="card-title" id="count_graph">Total Registrants</h2>
                        <p class="card-subtitle" id="count_graph_subtext">Test</p>
                        <div class="count-display" id="count_graph_value">...</div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 graph mb-4">
                    <div class="graph_inner h-100">
                        <h1>Gender</h1>
                        <div class="chart-wrapper"><canvas id="graphCanvasGender"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 graph mb-4">
                    <div class="graph_inner h-100">
                        <h1>Age Demographics</h1>
                        <div class="chart-wrapper"><canvas id="graphCanvasAge"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6 graph mb-4">
                    <div class="graph_inner h-100">
                        <h1>How Participants Learned</h1>
                        <div class="chart-wrapper"><canvas id="graphCanvasHowLearned"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6 graph mb-4">
                    <div class="graph_inner h-100">
                        <h1>Motivation for Attending</h1>
                        <div class="chart-container"><canvas id="graphCanvasMotivation"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6 graph mb-4">
                    <div class="graph_inner h-100">
                        <h1>Profession</h1>
                        <div class="chart-container"><canvas id="graphCanvasProfession"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6 graph mb-4">
                    <div class="graph_inner h-100">
                        <h1>Location</h1>
                        <div class="chart-container"><canvas id="graphCanvasLocation"></canvas></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-12">
                <div class="session_inner_item h-100">
                    <div class="session_header">
                        <h1 class="m-0">PTC CONTROLS</h1>
                    </div>
                    <div class="p-3 d-flex flex-wrap justify-content-center gap-2">
                        <button class="btn-admin-action btn-admin-blue" data-bs-toggle="modal"
                            data-bs-target="#addSessionModal">
                            <ion-icon name="clipboard-outline"></ion-icon> Add Session
                        </button>
                        <button class="btn-admin-action btn-admin-blue" data-bs-toggle="modal"
                            data-bs-target="#addSpeakerModal">
                            <ion-icon name="people-outline"></ion-icon> Add Speaker
                        </button>
                        <a href="editParticipants.php" class="btn-admin-action btn-admin-darkblue">
                            <ion-icon name="search-outline"></ion-icon> Search Participants
                        </a>
                        <a href="monitoring.php" class="btn-admin-action btn-admin-darkblue">
                            <ion-icon name="eye-outline"></ion-icon> Participant Monitoring
                        </a>
                        <a href="speaker_rating.php" class="btn-admin-action btn-admin-yellow">
                            <ion-icon name="podium-outline"></ion-icon> Speaker Ratings
                        </a>
                        <a href="editVariables.php" class="btn-admin-action btn-admin-darkgray">
                            <ion-icon name="settings-outline"></ion-icon> Session Settings
                        </a>
                        <a href="viewpartcert.php" class="btn-admin-action btn-admin-darkblue">
                            <ion-icon name="ribbon-outline"></ion-icon> View Participant with Certificate
                        </a>
                        <a href="overall.php" class="btn-admin-action btn-admin-darkgray">
                            <ion-icon name="document-text-outline"></ion-icon> Overall Summary Report
                        </a>
                        <button class="btn-admin-action btn-admin-blue" data-bs-toggle="modal"
                            data-bs-target="#addCpdModal">
                            <ion-icon name="add-circle-outline"></ion-icon> Add CPD Units
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="sessions-header live-session-header">
                <div class="d-flex align-items-center">
                    <img src="Picture1.png" alt="PTC Logo">
                    <h1> Live Now</h1>
                </div>
            </div>
            <div class="row g-4 mt-2">
                <?php
            // This query finds sessions happening on today's date and where the current time is between the start and end times
            $stmt_live = $dbcon->prepare("SELECT * FROM tblptcsession WHERE session_date = CURDATE() AND CURTIME() BETWEEN session_time AND sessionend_time ORDER BY session_time");
            $stmt_live->execute();
            $live_sessions_result = $stmt_live->get_result();

            if ($live_sessions_result->num_rows > 0) {
                while ($row = $live_sessions_result->fetch_assoc()) {
                    $row['correct_logo_path'] = getImagePath($row['logo_image'], 'logo');
                    echo '<div class="col-sm-12 col-md-6 col-lg-4 session-card">';
                    // We reuse the same admin card template
                    include('adminSessionCards.php');
                    echo '</div>';
                }
            } else {
                echo '<div class="col-12"><p class="h4 text-center text-muted my-4">There are no sessions currently live.</p></div>';
            }
            $stmt_live->close();
        ?>
            </div>
        </div>

        <div class="row mt-5">
            <div class="sessions-header">
                <div class="d-flex align-items-center"><img src="Picture1.png" alt="PTC Logo">
                    <h1>Upcoming Sessions</h1>
                </div>
            </div>
            <div class="row g-4 mt-2">
                <?php
            $today_date = date('Y-m-d');
            $stmt_upcoming = $dbcon->prepare("SELECT * FROM tblptcsession WHERE session_date >= ? ORDER BY session_date ASC, session_time ASC");
            $stmt_upcoming->bind_param("s", $today_date);
            $stmt_upcoming->execute();
            $upcoming_sessions_result = $stmt_upcoming->get_result();
            if ($upcoming_sessions_result->num_rows > 0) {
                while ($row = $upcoming_sessions_result->fetch_assoc()) {
                    $row['correct_logo_path'] = getImagePath($row['logo_image'], 'logo');
                    echo '<div class="col-sm-12 col-md-6 col-lg-4 session-card">';
                    include('adminSessionCards.php');
                    echo '</div>';
                }
            } else {
                echo '<div class="col-12"><p class="h4 text-center text-muted my-4">No upcoming sessions scheduled.</p></div>';
            }
            $stmt_upcoming->close();
        ?>
            </div>
        </div>

        <div class="row mt-5">
            <div class="sessions-header">
                <div class="d-flex align-items-center"><img src="Picture1.png" alt="PTC Logo">
                    <h1>All Sessions</h1>
                </div>
                <div class="btn-group btn-group-lg year-filter-group" role="group">
                    <button type="button" class="btn btn-dashboard selected" data-year="all">All</button>
                    <button type="button" class="btn btn-dashboard" data-year="2025">2025</button>
                    <button type="button" class="btn btn-dashboard" data-year="2024">2024</button>
                    <button type="button" class="btn btn-dashboard" data-year="2023">2023</button>
                </div>
            </div>
            <div class="row g-4 mt-2" id="all-sessions-list">
                <?php
            $stmt_all = $dbcon->prepare("SELECT * FROM `tblptcsession` ORDER BY session_date DESC, session_time DESC");
            $stmt_all->execute();
            $all_session_list = $stmt_all->get_result();
            while($row = $all_session_list->fetch_assoc()){
                $session_year = date('Y', strtotime($row['session_date']));
                $row['correct_logo_path'] = getImagePath($row['logo_image'], 'logo');
                echo "<div class='col-sm-12 col-md-6 col-lg-4 session-card' data-session-year='{$session_year}'>";
                include('adminSessionCards.php');
                echo '</div>';
            }
            $stmt_all->close();
        ?>
            </div>
        </div>

        <div class="modal fade" id="addSessionModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="admindashboard.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">ADD SESSION</h5><button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2"><label class="form-label">Date</label><input class="form-control"
                                    type="date" required name="sesdate"></div>
                            <div class="mb-2"><label class="form-label">Time</label><input class="form-control"
                                    type="time" required name="sestime"></div>
                            <div class="mb-2"><label class="form-label">End Time</label><input class="form-control"
                                    type="time" required name="endsestime"></div>
                            <div class="mb-2"><label class="form-label">Header</label><input class="form-control"
                                    type="text" required name="sesshead"></div>
                            <div class="mb-2"><label class="form-label">Title</label><input class="form-control"
                                    type="text" required name="stitle"></div>
                            <div class="mb-2"><label class="form-label">Zoom Link</label><input class="form-control"
                                    type="url" name="sesl"></div>
                            <div class="mb-2"><label class="form-label">CPD units</label><input class="form-control"
                                    type="number" step="0.1" name="cpd"></div>
                            <div class="mb-2">
                                <label for="logo_image_text" class="form-label">Logo Image Filename (Optional)</label>
                                <input class="form-control" type="text" name="logo_image_text" id="logo_image_text"
                                    placeholder="e.g., logo.png">
                            </div>
                            <div class="mb-2">
                                <label for="logo_image_file" class="form-label">Or Upload New Logo</label>
                                <input class="form-control" type="file" name="logo_image_file" id="logo_image_file">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                            <button type="submit" class="btn btn-primary" name="add_session_submit"
                                value="POST">ADD</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addSpeakerModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="admindashboard.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">ADD SPEAKER</h5><button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">SESSION</label>
                                <select class="form-select" name="speakerhead" required>
                                    <option value="" disabled selected>-- Choose a Session --</option>
                                    <?php
                                if ($add_speaker_sessions_result && $add_speaker_sessions_result->num_rows > 0) {
                                    $add_speaker_sessions_result->data_seek(0);
                                    while($row_speaker_ses = $add_speaker_sessions_result->fetch_assoc()) {
                                        echo "<option value='" . $row_speaker_ses["session_number"] . "'>" . htmlspecialchars($row_speaker_ses["session_head"]) . "</option>";
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <div class="mb-2"><label class="form-label">Speaker Name</label><input class="form-control"
                                    type="text" required name="speakername"></div>
                            <div class="mb-2"><label class="form-label">Speaker Description</label><input
                                    class="form-control" type="text" required name="speakerdes"></div>
                            <div class="mb-2"><label class="form-label">Sex</label><select class="form-select"
                                    name="speakergen">
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Not Specify</option>
                                </select></div>
                            <div class="mb-2"><label class="form-label">Speaker Image</label><input class="form-control"
                                    type="file" name="uploadedFile" /></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                            <button type="submit" class="btn btn-primary" name="addspeak" value="ADD">ADD
                                SPEAKER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addCpdModal" tabindex="-1" aria-labelledby="addCpdModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="admindashboard.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCpdModalLabel">Add CPD Units Manually</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cpd_units_input" class="form-label">CPD Units to Add</label>
                                <input type="number" step="0.1" class="form-control" id="cpd_units_input" name="cpdp"
                                    placeholder="e.g., 1.5" required>
                            </div>
                            <div class="form-text">
                                This action will add the specified CPD units to all participants. This cannot be undone.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="cpdadd">Add CPD Units</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="admindashboard.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Confirm Deletion</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="session_id_to_delete" id="session_id_to_delete">
                            <p>Are you sure you want to permanently delete the session:</p>
                            <h4 class="text-center" id="sessionNameToDelete"></h4>
                            <p class="text-danger mt-3"><strong>Warning:</strong> This action cannot be undone. All
                                related
                                attendance, feedback, and speaker data for this session will be permanently erased.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" name="confirm_delete_session">Yes, Permanently
                                Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editDeadlineModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Feedback Deadline</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="admin_update_deadline.php" method="POST">
                        <div class="modal-body">
                            <p>Session: <strong id="modal_session_title"></strong></p>
                            
                            <input type="hidden" name="session_number" id="modal_session_id">
                            
                            <div class="mb-3">
                                <label for="modal_override_deadline" class="form-label">New Override Deadline</label>
                                <input type="datetime-local" class="form-control" name="override_deadline" id="modal_override_deadline">
                                <small class="form-text text-muted">Leave blank and check "Clear Override" to use the default closing rule.</small>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="clear_deadline" id="clear_deadline_checkbox">
                                <label class="form-check-label" for="clear_deadline_checkbox">
                                    Clear Override (Revert to Default Rule)
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editSessionModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="editForm" method="POST" action="admindashboard.php" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Session Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="session_id_edit" name="session_id">
                            <input type="hidden" id="current_logo_image" name="current_logo_image">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-2"><label class="form-label">Date</label><input type="date"
                                            class="form-control" id="sesdate1" name="sesdate1" required></div>
                                    <div class="mb-2"><label class="form-label">Time</label><input type="time"
                                            class="form-control" id="sestime1" name="sestime1" required></div>
                                    <div class="mb-2"><label class="form-label">End Time</label><input type="time"
                                            class="form-control" id="sessionendtime1" name="sessionendtime1" required>
                                    </div>
                                    <div class="mb-2"><label class="form-label">Header</label><input type="text"
                                            class="form-control" id="seshead1" name="seshead1" required></div>
                                    <div class="mb-2"><label class="form-label">Title</label><input type="text"
                                            class="form-control" id="sestitle1" name="sestitle1" required></div>
                                    <div class="mb-2"><label class="form-label">Link</label><input type="url"
                                            class="form-control" id="sessionlink1" name="sessionlink1" required></div>
                                    <div class="mb-2"><label class="form-label">CPD Units</label><input type="number"
                                            step="0.1" class="form-control" id="cpd1" name="cpd1" required></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center mb-2">
                                        <label class="form-label fw-bold">Logo Preview</label>
                                        <img id="logo_preview" src="" alt="Logo Preview"
                                            class="img-fluid rounded border bg-light"
                                            style="width: 150px; height: 150px; object-fit: contain;">
                                    </div>
                                    <div class="mb-2"><label class="form-label">Logo Filename</label><input type="text"
                                            class="form-control" id="logoimage1" name="logoimage1"></div>
                                    <div class="mb-2"><label class="form-label">Or Upload New Logo</label><input
                                            type="file" class="form-control" id="new_logo_image_file"
                                            name="new_logo_image_file"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="editInfo">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include("./nav&footer/footer.php"); ?>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.bundle.min.js" 
         
        crossorigin="anonymous" 
        referrerpolicy="no-referrer"></script>

        <script src="../js/Chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://unpkg.com/countup.js@2.0.7/dist/countUp.umd.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>



        <script>


    function showDeadlineModal(sessionData) {
            // Populate the modal fields with data from the session row
            document.getElementById('modal_session_id').value = sessionData.session_number;
            document.getElementById('modal_session_title').innerText = sessionData.session_title;

            // The datetime-local input needs a 'T' separator.
            // This code checks if a deadline exists and formats it correctly.
            let currentDeadline = sessionData.feedback_override_deadline;
            if (currentDeadline) {
                // Replace the space with 'T' for the input field
                document.getElementById('modal_override_deadline').value = currentDeadline.replace(' ', 'T');
            } else {
                document.getElementById('modal_override_deadline').value = '';
            }
            
            // Ensure the "clear" checkbox is unticked when opening
            document.getElementById('clear_deadline_checkbox').checked = false;

            // Create a Bootstrap Modal instance and show it
            const deadlineModal = new bootstrap.Modal(document.getElementById('editDeadlineModal'));
            deadlineModal.show();
        }
        // --- Helper functions for modals (must be in the global scope) ---
        function showmodal(sessionData) {
            var myModal = new bootstrap.Modal(document.getElementById('editSessionModal'));
            document.getElementById('session_id_edit').value = sessionData.session_number;
            document.getElementById('sesdate1').value = sessionData.session_date;
            document.getElementById('sestime1').value = sessionData.session_time;
            document.getElementById('sessionendtime1').value = sessionData.sessionend_time;
            document.getElementById('seshead1').value = sessionData.session_head;
            document.getElementById('sestitle1').value = sessionData.session_title;
            document.getElementById('sessionlink1').value = sessionData.sessionlink;
            document.getElementById('logoimage1').value = sessionData.logo_image;
            document.getElementById('cpd1').value = sessionData.cpd_units;
            document.getElementById('current_logo_image').value = sessionData.logo_image;
            const logoPreview = document.getElementById('logo_preview');
            if (sessionData.correct_logo_path) {
                logoPreview.src = sessionData.correct_logo_path;
            } else {
                logoPreview.src = 'placeholder.jpg';
            }
            myModal.show();
        }

        function prepareDelete(sessionId, sessionName) {
            document.getElementById('session_id_to_delete').value = sessionId;
            document.getElementById('sessionNameToDelete').innerText = sessionName;
            var myModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            myModal.show();
        }

        // --- New Dynamic Dashboard Engine ---
        $(document).ready(function() {
            let chartInstances = {};
            let currentYear = 2024;
            let currentIndex = 0;





            // 1. Create the modal objects once when the page loads
            const addSessionModal = new bootstrap.Modal(document.getElementById('addSessionModal'));
            const addSpeakerModal = new bootstrap.Modal(document.getElementById('addSpeakerModal'));
            const addCpdModal = new bootstrap.Modal(document.getElementById('addCpdModal'));

            // 2. The click handlers now just show the existing modal
            $('button[data-bs-target="#addSessionModal"]').on('click', function() {
                addSessionModal.show();
            });
            $('button[data-bs-target="#addSpeakerModal"]').on('click', function() {
                addSpeakerModal.show();
            });
            $('button[data-bs-target="#addCpdModal"]').on('click', function() {
                addCpdModal.show();
            });


            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(this);
                dropdownInstance.toggle();
            });



            // --- Event Listeners for new controls ---
            $('.year-btn').click(function() {
                $('.year-btn').removeClass('active');
                $(this).addClass('active');
                currentYear = $(this).data('year');
                updateSessionsDropdown();
            });
            $('#btnLeft').click(function() {
                const $items = $('#session_list .dropdown-item');
                if ($items.length === 0) return;
                currentIndex = (currentIndex === 0) ? $items.length - 1 : currentIndex - 1;
                updateSelection();
                fetchData();
            });
            $('#btnRight').click(function() {
                const $items = $('#session_list .dropdown-item');
                if ($items.length === 0) return;
                currentIndex = (currentIndex === $items.length - 1) ? 0 : currentIndex + 1;
                updateSelection();
                fetchData();
            });

            // --- Client-side filter for the "All Sessions" list ---
            $('.year-filter-group .btn-dashboard').on('click', function() {
                $('.year-filter-group .btn-dashboard').removeClass('selected');
                $(this).addClass('selected');
                const yearToShow = $(this).data('year');

                // This now specifically targets the list we want to filter
                const allSessionsList = $('#all-sessions-list');

                if (yearToShow === 'all') {
                    // Show all cards INSIDE the target list
                    allSessionsList.find('.session-card').show();
                } else {
                    // Hide all cards INSIDE the target list
                    allSessionsList.find('.session-card').hide();
                    // Show only the cards that match the year INSIDE the target list
                    allSessionsList.find(`.session-card[data-session-year="${yearToShow}"]`).show();
                }
            });


            // --- Logic for Minimize/Maximize Charts Button ---
            $('#toggle-charts-btn').on('click', function() {
                const wrapper = $('#charts-wrapper');
                const icon = $(this).find('i');
                const textSpan = $(this).find('span'); // Target the new span

                wrapper.slideToggle('slow');

                // Update the button icon and text
                if (icon.hasClass('fa-minus')) {
                    icon.removeClass('fa-minus').addClass('fa-plus');
                    textSpan.text(' Show Charts'); // Update the span's text
                } else {
                    icon.removeClass('fa-plus').addClass('fa-minus');
                    textSpan.text(' Hide Charts'); // Update the span's text
                }
            });


            $('#addSessionModal').on('show.bs.modal', function() {
                // Get the input fields for time
                const startTimeInput = document.querySelector('input[name="sestime"]');
                const endTimeInput = document.querySelector('input[name="endsestime"]');

                // Get the current hour (0-23)
                const currentHour = new Date().getHours();

                // Set default times based on whether it's morning or afternoon
                if (currentHour < 12) {
                    // If it's before noon, default to a morning session
                    startTimeInput.value = '09:00'; // 9:00 AM
                    endTimeInput.value = '12:00'; // 12:00 PM
                } else {
                    // If it's noon or later, default to an afternoon session
                    startTimeInput.value = '13:00'; // 1:00 PM
                    endTimeInput.value = '17:00'; // 5:00 PM
                }
            });

            // --- Core Functions ---
            function updateSessionsDropdown() {
                $.ajax({
                    url: '../graphs_2024/get_sessions.php',
                    method: 'POST',
                    data: {
                        year: currentYear
                    },
                    dataType: 'json',
                    success: function(sessions) {
                        const $sessionList = $('#session_list');
                        $sessionList.empty();
                        $sessionList.append(
                            `<li class="dropdown-item" data-session-number="all">${currentYear} Registrants</li>`
                        );
                        sessions.forEach(session => {
                            $sessionList.append(
                                `<li class="dropdown-item" data-session-number="${session.session_number}">${session.session_head}</li>`
                            );
                        });
                        $('#session_list .dropdown-item').click(function() {
                            currentIndex = $('#session_list .dropdown-item').index(this);
                            updateSelection();
                            fetchData();
                        });
                        currentIndex = 0;
                        updateSelection();
                        fetchData();
                    }
                });
            }

            function updateSelection() {
                const $items = $('#session_list .dropdown-item');
                if ($items.length === 0) {
                    $('#dropdownMenuButton').text(`No Sessions for ${currentYear}`);
                    return;
                }
                if (currentIndex >= $items.length) {
                    currentIndex = 0;
                }
                const selectedItem = $items.eq(currentIndex);
                $('#dropdownMenuButton').text(selectedItem.text());
            }

            function fetchData() {
                const $items = $('#session_list .dropdown-item');
                if ($items.length === 0) return;
                const selectedItem = $items.eq(currentIndex);
                const selectedValue = selectedItem.data('session-number');

                $.ajax({
                    url: '../graphs_2024/get_analytics_data.php',
                    method: 'POST',
                    data: {
                        sessionId: selectedValue,
                        year: currentYear
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            updateAdminDashboard(data, selectedValue);
                        } else {
                            Swal.fire('Error', 'Invalid data from server.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Could not fetch analytics data.', 'error');
                    }
                });
            }


            
        

            function updateAdminDashboard(data, selectedValue) {
                const isAllRegistrants = (selectedValue === 'all');
                const mainTitle = document.getElementById('count_graph_title');
                if (mainTitle) {
                    mainTitle.innerText = isAllRegistrants ? 'TOTAL REGISTRANTS' : 'SESSION ATTENDEES';
                }
                new countUp.CountUp('count_graph_value', data.count).start();
                renderChart('gender', data.gender);
                renderChart('age', data.age);
                renderChart('profession', data.profession);
                renderChart('location', data.location);
                renderChart('howLearned', data.howLearned);
                renderChart('motivation', data.motivation);
            }

            function renderChart(type, data) {
                if (!data || Object.keys(data).filter(k => k !== 'total').length === 0) {
                    if (chartInstances[type]) {
                        chartInstances[type].destroy();
                    }
                    return;
                }
                let labels = Object.keys(data).filter(k => k !== 'total');
                let values = labels.map(label => data[label]);
                if (['profession', 'location', 'howLearned', 'motivation'].includes(type)) {
                    let combined = labels.map((label, i) => ({
                        label: label,
                        value: values[i]
                    }));
                    combined.sort((a, b) => b.value - a.value);
                    if (['profession', 'location'].includes(type) && combined.length > 10) {
                        const top10 = combined.slice(0, 10);
                        const othersValue = combined.slice(10).reduce((sum, item) => sum + item.value, 0);
                        labels = top10.map(item => item.label);
                        values = top10.map(item => item.value);
                        if (othersValue > 0) {
                            labels.push('Others');
                            values.push(othersValue);
                        }
                    } else {
                        labels = combined.map(item => item.label);
                        values = combined.map(item => item.value);
                    }
                }
                const canvasId = `graphCanvas${type.charAt(0).toUpperCase() + type.slice(1)}`;
                const context = document.getElementById(canvasId);
                if (!context) return;
                const ctx = context.getContext('2d');
                const defaultOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        labels: {
                            fontColor: '#495057',
                            fontFamily: "'MelodyRegular', sans-serif"
                        }
                    },
                    tooltips: {
                        backgroundColor: '#0E2369',
                        titleFontFamily: "'MelodyBold', sans-serif",
                        bodyFontFamily: "'MelodyRegular', sans-serif",
                        cornerRadius: 3,
                        displayColors: false
                    }
                };
                let chartConfig = {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            hoverOffset: 4
                        }]
                    },
                    options: defaultOptions
                };
                if (type === 'gender') {
                    chartConfig.type = 'pie';
                    chartConfig.data.datasets[0].backgroundColor = ['#0E2369', '#C7000D', '#6c757d'];
                    chartConfig.options.legend.display = true;
                } else if (type === 'age') {
                    chartConfig.type = 'doughnut';
                    chartConfig.data.datasets[0].backgroundColor = ['#ffc107', '#fd7e14', '#20c997', '#6f42c1',
                        '#0dcaf0', '#d63384'
                    ];
                    chartConfig.options.legend.display = true;
                } else {
                    chartConfig.type = 'horizontalBar';
                    chartConfig.options.legend = {
                        display: false
                    };
                    chartConfig.options.scales = {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontColor: '#6c757d'
                            },
                            gridLines: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                fontColor: '#6c757d'
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    };
                    const themeColors = ['#0E2369', '#C7000D', '#507AFF', '#DAA520', '#1A3A99', '#E53935',
                        '#7E9FFF', '#FDD835', '#2F54CD', '#B71C1C'
                    ];
                    chartConfig.data.datasets[0].backgroundColor = themeColors;
                }
                if (chartInstances[type]) {
                    chartInstances[type].destroy();
                }
                chartInstances[type] = new Chart(ctx, chartConfig);
            }

            // --- Initial Load ---
            updateSessionsDropdown();

            // --- SweetAlert for Flash Messages ---
            <?php
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            echo "Swal.fire({ icon: '".$message['type']."', title: '".ucfirst($message['type'])."!', text: '".addslashes($message['text'])."', timer: 3000, showConfirmButton: false });";
            unset($_SESSION['flash_message']);
        }
        ?>
        });
        </script>



        <?php
// Check if a flash message exists in the session
if (isset($_SESSION['flash_message'])) {
    // Get the message details
    $message = $_SESSION['flash_message'];
    
    // Output the SweetAlert2 script
    echo "<script>
        Swal.fire({
            icon: '" . $message['type'] . "',
            title: '" . ucfirst($message['type']) . "!',
            text: '" . addslashes($message['text']) . "',
            timer: 3000,
            showConfirmButton: false
        });
    </script>";

    // Unset the message so it doesn't show again on the next refresh
    unset($_SESSION['flash_message']);
}
?>

</body>

</html>