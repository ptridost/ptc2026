<?php
date_default_timezone_set('Asia/Manila');
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if(empty($_SESSION['admin_id'])){
    header("Location: ../Loginn.php"); 
    exit();
}

require('../dbcon.php');
require_once('../event_year.php');

$availableYears = getAvailableEventYears($dbcon);
$currentPtcYear = getCurrentEventYear($dbcon);
$isSessionsPage = !empty($sessionsManagementPage);

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
            header("Location: sessions.php");
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
            header("Location: sessions.php");
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
            
            header("Location: sessions.php");
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
            header("Location: sessions.php");
            exit();
        }

    // --- Handle any other form submissions here ---
    // You can add more form handling logic as needed.
}

// --- 3. FETCH DATA FOR PAGE DISPLAY ---
$stats = $dbcon->query("SELECT (SELECT COUNT(*) FROM tblptcsession) as total_sessions, (SELECT COUNT(DISTINCT id) FROM participant) as total_participants, (SELECT COUNT(*) FROM tblspeakerses) as total_speakers")->fetch_assoc();
$sessionOverview = $dbcon->query("SELECT COUNT(*) AS total_sessions, SUM(session_date > CURDATE() OR (session_date = CURDATE() AND session_time > CURTIME())) AS upcoming_sessions, SUM(session_date = CURDATE() AND CURTIME() BETWEEN session_time AND sessionend_time) AS live_sessions FROM tblptcsession")->fetch_assoc();
$add_speaker_sessions_result = $dbcon->query("SELECT session_number, session_head FROM tblptcsession ORDER BY session_date DESC");
?>









<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PTC | <?php echo $isSessionsPage ? 'Session Management' : 'Admin Dashboard'; ?></title>
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

<body>
    <?php include("./nav&footer/usernav.php"); ?>

    <main class="admindash_con container-fluid py-4">
        <div class="row mb-4">
            <div class="admin_dashboard">
                <img src="Picture1.png">
                <h1><?php echo $isSessionsPage ? 'Session Management' : 'Analytics Dashboard'; ?></h1>
                <?php if (!$isSessionsPage): ?>
                <button id="toggle-charts-btn" class="btn-admin-action btn-admin-darkgray">
                    <i class="fas fa-minus"></i><span class="ms-2">Hide Charts</span>
                </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!$isSessionsPage): ?>
        <div class="dashboard-kpi-grid">
            <div class="overview-kpi overview-kpi--live"><span class="overview-kpi__icon"><i class="fa-solid fa-signal"></i></span><div><small>Live now</small><strong><?php echo (int) ($sessionOverview['live_sessions'] ?? 0); ?></strong><span>Sessions currently in progress</span></div></div>
            <div class="overview-kpi"><span class="overview-kpi__icon"><i class="fa-regular fa-calendar"></i></span><div><small>Upcoming</small><strong><?php echo (int) ($sessionOverview['upcoming_sessions'] ?? 0); ?></strong><span>Scheduled sessions</span></div></div>
            <div class="overview-kpi"><span class="overview-kpi__icon"><i class="fa-solid fa-layer-group"></i></span><div><small>All sessions</small><strong><?php echo (int) ($sessionOverview['total_sessions'] ?? 0); ?></strong><span>Complete session records</span></div></div>
        </div>

        <div class="dashboard-control-bar">
            <div class="control-bar__title"><h1>Analytics Filters</h1><p class="lead">Refine participant data by event year and session.</p></div>
            <div class="control-bar__actions">
                <div class="filter-field"><label for="analytics-year-filter">Event year</label><select id="analytics-year-filter" class="form-select year-selector">
                    <?php foreach ($availableYears as $availableYear): ?><option value="<?php echo $availableYear; ?>" <?php echo $currentPtcYear === $availableYear ? 'selected' : ''; ?>><?php echo $availableYear; ?></option><?php endforeach; ?>
                </select></div>
                <div class="filter-field filter-field--session"><label for="session_list">Session</label><select id="session_list" class="form-select"><option value="all">Loading sessions...</option></select></div>
                <button type="button" id="reset-analytics-filters" class="btn btn-outline-secondary filter-reset"><i class="fa-solid fa-arrow-rotate-left"></i> Reset</button>
            </div>
            <p class="filter-summary mb-0">Displaying: <strong id="analytics-filter-summary"><?php echo $currentPtcYear; ?> · All registrants</strong></p>
        </div>

        <div id="charts-wrapper">
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
        <?php else: ?>
        <select id="session_list" hidden aria-hidden="true"><option value="all">All registrants</option></select>

        <div class="row mt-4">

            <div class="col-12">
                <div class="session_inner_item h-100">
                    <div class="session-controls-bar">
                        <div class="session-controls-bar__title">
                            <h2>Session Actions</h2>
                            <p>Create sessions, assign speakers, and manage session settings.</p>
                        </div>
                        <div class="session-controls-bar__actions">
                            <button class="btn-admin-action btn-admin-blue" data-bs-toggle="modal"
                                data-bs-target="#addSessionModal">
                                <ion-icon name="clipboard-outline"></ion-icon> Add Session
                            </button>
                            <button class="btn-admin-action btn-admin-blue" data-bs-toggle="modal"
                                data-bs-target="#addSpeakerModal">
                                <ion-icon name="people-outline"></ion-icon> Add Speaker
                            </button>
                            <button class="btn-admin-action btn-admin-blue" data-bs-toggle="modal"
                                data-bs-target="#addCpdModal">
                                <ion-icon name="add-circle-outline"></ion-icon> Add CPD Units
                            </button>
                            <button type="button" class="btn-admin-action btn-admin-darkgray" data-bs-toggle="modal"
                                data-bs-target="#sessionSettingsModal">
                                <ion-icon name="settings-outline"></ion-icon> Session Settings
                            </button>
                        </div>
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
            <div class="session-table-wrap mt-3"><table class="session-table" data-session-table="live"><thead><tr><th>Session</th><th>Date</th><th>Time</th><th class="text-end">Actions</th></tr></thead><tbody>
                <?php
            // This query finds sessions happening on today's date and where the current time is between the start and end times
            $stmt_live = $dbcon->prepare("SELECT * FROM tblptcsession WHERE session_date = CURDATE() AND CURTIME() BETWEEN session_time AND sessionend_time ORDER BY session_time");
            $stmt_live->execute();
            $live_sessions_result = $stmt_live->get_result();

            if ($live_sessions_result->num_rows > 0) {
                while ($row = $live_sessions_result->fetch_assoc()) {
                    $row['correct_logo_path'] = getImagePath($row['logo_image'], 'logo');
                    include('adminSessionRow.php');
                }
            } else {
                echo '<tr><td colspan="4" class="session-table__empty">There are no sessions currently live.</td></tr>';
            }
            $stmt_live->close();
        ?>
            </tbody></table></div><div class="session-pagination" data-pagination-for="live"></div>
        </div>

        <div class="row mt-5">
            <div class="sessions-header">
                <div class="d-flex align-items-center"><img src="Picture1.png" alt="PTC Logo">
                    <h1>Upcoming Sessions</h1>
                </div>
            </div>
            <div class="session-table-wrap mt-3"><table class="session-table" data-session-table="upcoming"><thead><tr><th>Session</th><th>Date</th><th>Time</th><th class="text-end">Actions</th></tr></thead><tbody>
                <?php
            $today_date = date('Y-m-d');
            $stmt_upcoming = $dbcon->prepare("SELECT * FROM tblptcsession WHERE session_date >= ? ORDER BY session_date ASC, session_time ASC");
            $stmt_upcoming->bind_param("s", $today_date);
            $stmt_upcoming->execute();
            $upcoming_sessions_result = $stmt_upcoming->get_result();
            if ($upcoming_sessions_result->num_rows > 0) {
                while ($row = $upcoming_sessions_result->fetch_assoc()) {
                    $row['correct_logo_path'] = getImagePath($row['logo_image'], 'logo');
                    include('adminSessionRow.php');
                }
            } else {
                echo '<tr><td colspan="4" class="session-table__empty">No upcoming sessions scheduled.</td></tr>';
            }
            $stmt_upcoming->close();
        ?>
            </tbody></table></div><div class="session-pagination" data-pagination-for="upcoming"></div>
        </div>

        <div class="row mt-5">
            <div class="sessions-header">
                <div class="d-flex align-items-center"><img src="Picture1.png" alt="PTC Logo">
                    <h1>All Sessions</h1>
                </div>
                <div class="session-year-filter">
                    <label for="session-year-filter">Filter by year</label>
                    <select id="session-year-filter" class="form-select">
                    <option value="all">All years</option>
                    <?php foreach ($availableYears as $availableYear): ?>
                        <option value="<?php echo $availableYear; ?>"><?php echo $availableYear; ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="session-table-wrap mt-3"><table class="session-table" data-session-table="all"><thead><tr><th>Session</th><th>Date</th><th>Time</th><th class="text-end">Actions</th></tr></thead><tbody id="all-sessions-list">
                <?php
            $stmt_all = $dbcon->prepare("SELECT * FROM `tblptcsession` ORDER BY session_date DESC, session_time DESC");
            $stmt_all->execute();
            $all_session_list = $stmt_all->get_result();
            while($row = $all_session_list->fetch_assoc()){
                $session_year = date('Y', strtotime($row['session_date']));
                $row['correct_logo_path'] = getImagePath($row['logo_image'], 'logo');
                include('adminSessionRow.php');
            }
            $stmt_all->close();
        ?>
            </tbody></table></div><div class="session-pagination" data-pagination-for="all"></div>
        </div>

        <div class="modal fade" id="sessionSettingsModal" tabindex="-1" aria-labelledby="sessionSettingsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content session-settings-modal">
                    <div class="modal-header">
                        <div><h5 class="modal-title" id="sessionSettingsModalLabel">Session Settings</h5><small>Configure event, certificate, feedback, and attendance rules.</small></div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <iframe src="editVariables.php?embedded=1" title="Session Settings" class="session-settings-frame"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addSessionModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="sessions.php" method="POST" enctype="multipart/form-data">
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
                    <form action="sessions.php" method="POST" enctype="multipart/form-data">
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
                    <form action="sessions.php" method="POST">
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
                    <form action="sessions.php" method="POST" enctype="multipart/form-data">
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
                    <form id="editForm" method="POST" action="sessions.php" enctype="multipart/form-data">
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
        <?php endif; ?>
    </main>
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
            let currentYear = <?php echo (int) $currentPtcYear; ?>;
            let currentIndex = 0;





            // 1. Create the modal objects once when the page loads
            const addSessionModalElement = document.getElementById('addSessionModal');
            const addSpeakerModalElement = document.getElementById('addSpeakerModal');
            const addCpdModalElement = document.getElementById('addCpdModal');
            const addSessionModal = addSessionModalElement ? new bootstrap.Modal(addSessionModalElement) : null;
            const addSpeakerModal = addSpeakerModalElement ? new bootstrap.Modal(addSpeakerModalElement) : null;
            const addCpdModal = addCpdModalElement ? new bootstrap.Modal(addCpdModalElement) : null;

            // 2. The click handlers now just show the existing modal
            $('button[data-bs-target="#addSessionModal"]').on('click', function() {
                if (addSessionModal) addSessionModal.show();
            });
            $('button[data-bs-target="#addSpeakerModal"]').on('click', function() {
                if (addSpeakerModal) addSpeakerModal.show();
            });
            $('button[data-bs-target="#addCpdModal"]').on('click', function() {
                if (addCpdModal) addCpdModal.show();
            });


            // --- Reusable client-side pagination for session tables ---
            const sessionPaginators = {};

            function createSessionPaginator(tableName) {
                const $table = $(`[data-session-table="${tableName}"]`);
                const $controls = $(`[data-pagination-for="${tableName}"]`);
                let currentPage = 1;
                let pageSize = 10;

                function render() {
                    const $allRows = $table.find('tbody .session-row');
                    const $rows = $allRows.not('.session-filtered-out');
                    const totalRows = $rows.length;
                    const totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
                    currentPage = Math.min(currentPage, totalPages);

                    $allRows.hide();
                    $rows.slice((currentPage - 1) * pageSize, currentPage * pageSize).show();

                    if (totalRows === 0) {
                        $controls.empty();
                        return;
                    }

                    const firstRow = (currentPage - 1) * pageSize + 1;
                    const lastRow = Math.min(currentPage * pageSize, totalRows);
                    const pageButtons = Array.from({ length: totalPages }, (_, index) => index + 1)
                        .filter(page => page === 1 || page === totalPages || Math.abs(page - currentPage) <= 1)
                        .map((page, index, pages) => {
                            const gap = index > 0 && page - pages[index - 1] > 1 ? '<span class="pagination-gap">…</span>' : '';
                            return `${gap}<button type="button" class="pagination-page${page === currentPage ? ' active' : ''}" data-page="${page}" aria-label="Page ${page}">${page}</button>`;
                        }).join('');

                    $controls.html(`
                        <div class="pagination-summary">Showing <strong>${firstRow}–${lastRow}</strong> of <strong>${totalRows}</strong></div>
                        <div class="pagination-size"><label>Rows per page
                            <select class="form-select form-select-sm" aria-label="Rows per page">
                                <option value="5" ${pageSize === 5 ? 'selected' : ''}>5</option>
                                <option value="10" ${pageSize === 10 ? 'selected' : ''}>10</option>
                                <option value="25" ${pageSize === 25 ? 'selected' : ''}>25</option>
                            </select></label>
                        </div>
                        <div class="pagination-pages">
                            <button type="button" class="pagination-nav" data-direction="previous" ${currentPage === 1 ? 'disabled' : ''}><i class="fa-solid fa-chevron-left"></i><span>Previous</span></button>
                            ${pageButtons}
                            <button type="button" class="pagination-nav" data-direction="next" ${currentPage === totalPages ? 'disabled' : ''}><span>Next</span><i class="fa-solid fa-chevron-right"></i></button>
                        </div>`);

                    $controls.find('.pagination-page').on('click', function() {
                        currentPage = Number($(this).data('page'));
                        render();
                    });
                    $controls.find('.pagination-nav').on('click', function() {
                        currentPage += $(this).data('direction') === 'next' ? 1 : -1;
                        render();
                    });
                    $controls.find('select').on('change', function() {
                        pageSize = Number($(this).val());
                        currentPage = 1;
                        render();
                    });
                }

                sessionPaginators[tableName] = {
                    refresh: render,
                    firstPage: function() { currentPage = 1; render(); }
                };
                render();
            }

            ['live', 'upcoming', 'all'].forEach(createSessionPaginator);

            // --- Event Listeners for dashboard controls ---
            $('#analytics-year-filter').on('change', function() {
                currentYear = $(this).val();
                updateSessionsDropdown();
            });
            $('#session_list').on('change', function() { fetchData(); });
            $('#reset-analytics-filters').on('click', function() {
                currentYear = '<?php echo $currentPtcYear; ?>';
                $('#analytics-year-filter').val(currentYear);
                updateSessionsDropdown();
            });

            // --- Client-side filter for the "All Sessions" list ---
            $('#session-year-filter').on('change', function() {
                const yearToShow = $(this).val();

                // This now specifically targets the list we want to filter
                const allSessionsList = $('#all-sessions-list');

                if (yearToShow === 'all') {
                    allSessionsList.find('.session-row').removeClass('session-filtered-out');
                } else {
                    allSessionsList.find('.session-row').addClass('session-filtered-out');
                    allSessionsList.find(`.session-row[data-session-year="${yearToShow}"]`).removeClass('session-filtered-out');
                }
                sessionPaginators.all.firstPage();
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
                            `<option value="all">All registrants</option>`
                        );
                        sessions.forEach(session => {
                            $sessionList.append(
                                `<option value="${session.session_number}">${session.session_head}</option>`
                            );
                        });
                        currentIndex = 0;
                        updateSelection();
                        fetchData();
                    }
                });
            }

            function updateSelection() {
                const $items = $('#session_list option');
                if ($items.length === 0) {
                    $('#session_list').html(`<option value="all">No sessions for ${currentYear}</option>`);
                    return;
                }
                $('#session_list').prop('selectedIndex', 0);
                $('#analytics-filter-summary').text(`${currentYear} · ${$items.eq(0).text()}`);
            }

            function fetchData() {
                const $selector = $('#session_list');
                const selectedValue = $selector.val();
                if (selectedValue === null) return;
                $('#analytics-filter-summary').text(`${currentYear} · ${$selector.find('option:selected').text()}`);

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
            if (document.getElementById('analytics-year-filter')) updateSessionsDropdown();

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
