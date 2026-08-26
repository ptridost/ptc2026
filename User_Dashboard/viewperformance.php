<?php
date_default_timezone_set('Asia/Manila');
session_start();

if(empty($_SESSION['participant_id'])){
    header("Location: ../Loginn.php"); 
    exit();
}

require('../dbcon.php');
require_once('../event_year.php');

function getPerformanceLogoPath($filename) {
    if (empty($filename) || str_contains($filename, '..')) {
        return './user_dash_img/PTClogo.jpg';
    }

    $relativePath = 'landingpageimg/' . ltrim(str_replace('\\', '/', $filename), '/');
    $absolutePath = dirname(__DIR__) . '/' . $relativePath;

    return file_exists($absolutePath)
        ? '../' . $relativePath
        : './user_dash_img/PTClogo.jpg';
}

// --- START: YEAR FILTERING LOGIC ---
$available_years = getAvailableEventYears($dbcon);
$target_year = resolveEventYear($dbcon, $_GET['year'] ?? null, $_SESSION['congress_type'] ?? 'PTC');
// --- END: YEAR FILTERING LOGIC ---

// Get ALL sessions for the TARGET YEAR.
$sql_sessions = "SELECT session_number, session_head, logo_image, session_date FROM tblptcsession WHERE YEAR(session_date) = ? ORDER BY session_date DESC";
$stmt_sessions = $dbcon->prepare($sql_sessions);
$stmt_sessions->bind_param("s", $target_year);
$stmt_sessions->execute();
$sessions_result = $stmt_sessions->get_result();
$all_sessions = [];
while ($row = $sessions_result->fetch_assoc()) {
    $all_sessions[] = $row;
}
$stmt_sessions->close();

// Get all of THIS USER'S attendance and feedback records.
$participant_id = $_SESSION['participant_id'];
// Get attendance
$stmt_att = $dbcon->prepare("SELECT session_id, date_submitted FROM tblattendance WHERE participant_id = ?");
$stmt_att->bind_param("s", $participant_id);
$stmt_att->execute();
$attendance_result = $stmt_att->get_result();
$user_attendance = [];
while ($row = $attendance_result->fetch_assoc()) {
    $user_attendance[$row['session_id']] = $row['date_submitted'];
}
$stmt_att->close();
// Get feedback
$stmt_feed = $dbcon->prepare("SELECT session_id, fDate FROM tblfeedback WHERE participant_id = ?");
$stmt_feed->bind_param("s", $participant_id);
$stmt_feed->execute();
$feedback_result = $stmt_feed->get_result();
$user_feedback = [];
while ($row = $feedback_result->fetch_assoc()) {
    $user_feedback[$row['session_id']] = $row['fDate'];
}
$stmt_feed->close();

$attended_count_for_year = 0;
foreach ($all_sessions as $session) {
    if (isset($user_attendance[$session['session_number']])) {
        $attended_count_for_year++;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Performance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="stylesheet" type="text/css" href="./user_head&footer/footer.css">
    <link rel="stylesheet" type="text/css" href="./user_head&footer/user_nav.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .performance-card {
            display: flex; background-color: #fff; border: 2px solid #0E2369;
            border-radius: 20px; margin-bottom: 1.5rem; box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            overflow: hidden; transition: transform 0.2s ease-in-out;
        }
        .performance-card:hover { transform: translateY(-5px); }
        .performance-card-logo {
            flex-shrink: 0; width: 150px; background-color: #f8f9fa; display: flex;
            align-items: center; justify-content: center; padding: 1rem;
        }
        .performance-card-logo img { max-width: 100%; height: auto; border-radius: 8px; }
        .performance-card-content { flex-grow: 1; display: flex; flex-direction: column; }
        .performance-card-header {
            background-color: #0E2369; color: #fff; padding: 1rem 1.5rem;
            font-family: MelodyBold, sans-serif; border-bottom: 1px solid #0d1e53;
        }
        .performance-card-header h5 { margin: 0; font-size: 1.25rem; }
        .performance-card-header p { margin: 0; opacity: 0.8; font-size: 0.9rem; font-family: MelodyRegular, sans-serif; }
        .performance-card-body { padding: 1.5rem; font-family: MelodyRegular, sans-serif; flex-grow: 1; }
        .status-section h6 {
            font-family: MelodySemibold, sans-serif; color: #6c757d; text-transform: uppercase;
            font-size: 0.8rem; letter-spacing: 1px; margin-bottom: 0.75rem;
        }
        .status-section .badge { font-size: 0.9rem; padding: 0.5em 1em; font-family: MelodyBold, sans-serif; letter-spacing: 0.5px; }
        .status-section .submission-time { font-size: 0.85rem; color: #6c757d; margin-top: 0.5rem; }
        
        .year-filter-group .btn {
            font-family: MelodySemibold, sans-serif; border-radius: 50px !important; padding: 0.5rem 1.5rem;
            margin: 0 5px; font-weight: bold; transition: all 0.3s ease; border-width: 2px; border-style: solid;
            color: #0E2369; border-color: #0E2369; background-color: #fff;
        }
        .year-filter-group .btn:hover, .year-filter-group .btn.active {
            background-color: #0E2369; color: #fff; box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }
        .year-color-2025 { color: #C7000D; } .btn-year-2025 { color: #C7000D; border-color: #C7000D; background-color: #fff; }
        .btn-year-2025:hover, .btn-year-2025.active { background-color: #C7000D; color: #fff !important; }
        .year-color-2024 { color: #DAA520; } .btn-year-2024 { color: #b59400; border-color: #FED300; background-color: #fff; }
        .btn-year-2024:hover, .btn-year-2024.active { background-color: #FED300; color: #000 !important; border-color: #b59400;}
        .year-color-2023 { color: #0E2369; } .btn-year-2023 { color: #0E2369; border-color: #0E2369; background-color: #fff; }
        .btn-year-2023:hover, .btn-year-2023.active { background-color: #0E2369; color: #fff !important; }
        .performance-actions {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            margin-bottom: 2rem; flex-wrap: wrap;
        }
        .performance-summary {
            margin: 0; color: #6c757d; font-family: MelodyRegular, sans-serif; font-size: 1rem;
        }
        .btn-download-attendance {
            display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;
        }
        .btn-download-attendance.disabled {
            pointer-events: none; opacity: 0.65;
        }
        
        @media (max-width: 767px) {
            .performance-card { flex-direction: column; }
            .performance-card-logo { width: 100%; height: 120px; border-bottom: 1px solid #dee2e6; }
            .performance-card-logo img { object-fit: contain; max-height: 100%; }
            .performance-actions { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
    <?php include("user_head&footer/user_nav.php" ); ?>
    <div class="container mt-5 py-5">
        <div class="text-center mb-4">
            <h1 style="font-family: MelodyBold, sans-serif;">
                My Attendance for <span class="year-color-<?php echo $target_year; ?>"><?php echo htmlspecialchars($target_year); ?></span>
            </h1>
        </div>

        <div class="text-center mb-4">
            <div class="btn-group year-filter-group" role="group" aria-label="Filter by Year">
                <?php foreach ($available_years as $year):
                    $isActive = ($year == $target_year);
                ?>
                    <a href="viewperformance.php?year=<?php echo $year; ?>" 
                       class="btn btn-year-<?php echo $year; ?> <?php echo $isActive ? 'active' : ''; ?>">
                        <?php echo $year; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-center mb-5">
            <a href="dashboard.php" class="btn-main-action">
                <ion-icon name="arrow-back-outline"></ion-icon>Back to Dashboard
            </a>
        </div>
        <div class="performance-actions">
            <p class="performance-summary">
                <?php echo number_format($attended_count_for_year); ?> attended session<?php echo $attended_count_for_year === 1 ? '' : 's'; ?> in <?php echo htmlspecialchars($target_year); ?>
            </p>
            <a
                href="download_attendance.php?year=<?php echo urlencode((string) $target_year); ?>"
                class="btn-ptc btn-ptc--profile btn-ptc--font-regular btn-ptc--primary btn-download-attendance <?php echo $attended_count_for_year === 0 ? 'disabled' : ''; ?>"
                <?php echo $attended_count_for_year === 0 ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
            >
                <i class="fa-solid fa-download"></i>Download <?php echo htmlspecialchars($target_year); ?> Attendance
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <?php
                if (!empty($all_sessions)) {
                    $today_date = date("Y-m-d");
                    foreach ($all_sessions as $session) {
                        $session_id = $session['session_number'];
                        $attendance_date = $user_attendance[$session_id] ?? null;
                        $feedback_date = $user_feedback[$session_id] ?? null;
                ?>
                <div class="performance-card">
                    <div class="performance-card-logo">
                        <img src="<?= htmlspecialchars(getPerformanceLogoPath($session['logo_image'])) ?>" alt="Session Logo">
                    </div>
                    <div class="performance-card-content">
                        <div class="performance-card-header">
                            <h5><?php echo htmlspecialchars($session["session_head"]); ?></h5>
                            <p><?php echo date("F j, Y, l", strtotime($session['session_date'])); ?></p>
                        </div>
                        <div class="performance-card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6 status-section mb-3 mb-md-0">
                                    <h6>Attendance</h6>
                                    <?php if ($attendance_date): ?>
                                        <span class="badge bg-success">PRESENT</span>
                                        <p class="submission-time">Submitted: <?php echo date("h:i A", strtotime($attendance_date)); ?></p>
                                    <?php elseif (date('Y-m-d', strtotime($session['session_date'])) > $today_date): ?>
                                        <span class="badge bg-secondary">UPCOMING</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">ABSENT</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 status-section">
                                    <h6>Feedback</h6>
                                    <?php if ($feedback_date): ?>
                                        <span class="badge" style="background-color: #FED300; color: #000;">SUBMITTED</span>
                                        <p class="submission-time">Submitted: <?php echo date("h:i A", strtotime($feedback_date)); ?></p>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark">NOT SUBMITTED</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo '<div class="alert alert-info text-center"><h4 class="alert-heading">No Sessions Found</h4><p>There are no sessions available for the year ' . htmlspecialchars($target_year) . '.</p></div>';
                }
                $dbcon->close();
                ?>
            </div>
        </div>
    </div> 
    
    <?php include("user_head&footer/footer.php" );?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
