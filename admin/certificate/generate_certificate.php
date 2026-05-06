<?php
date_default_timezone_set('Asia/Manila');
// Use the Dompdf library
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Use our central, secure database connection
require('../../dbcon.php');


// Get the participant ID and the YEAR from the URL
$participant_id = $_GET['id'] ?? null;
$year = $_GET['year'] ?? date('Y');
$dateNow = date('Y-m-d');


//$TEST_MODE = true;
$TEST_MODE = isset($_GET['TEST_MODE']) && $_GET['TEST_MODE'] === 'true';

// --- NEW: Fetch the valid date dynamically from the database ---
$dateValid = '9999-12-31'; // Default to a future date to block access if no setting is found
$variable_name = 'certificate_valid_date_' . $year;

$stmt_date = $dbcon->prepare("SELECT value FROM variables WHERE name = ?");
$stmt_date->bind_param("s", $variable_name);
$stmt_date->execute();
$result_date = $stmt_date->get_result();
if ($row_date = $result_date->fetch_assoc()) {
    $dateValid = $row_date['value'];
}
$stmt_date->close();
// --- END of new code ---

// Validate input using the dynamically fetched date
if ((empty($participant_id) || !is_numeric($participant_id) || !is_numeric($year) && !$TEST_MODE)) {
    header("Location: " . BASE_URL . "/User_dashboard/dashboard.php");
    exit;
}


// --- NEW: Final security check for attendance count ---
$stmt_attendance_count = $dbcon->prepare("
    SELECT COUNT(DISTINCT session_id) as count 
    FROM tblattendance 
    WHERE participant_id = ? AND YEAR(date_submitted) = ?
");
$stmt_attendance_count->bind_param("is", $participant_id, $year);
$stmt_attendance_count->execute();
$attendance_count = $stmt_attendance_count->get_result()->fetch_assoc()['count'] ?? 0;
$stmt_attendance_count->close();

// If they haven't attended at least 6 sessions for that year, redirect them.
if ($attendance_count < 6 && !$TEST_MODE) {
    header("Location: " . BASE_URL . "/User_dashboard/dashboard.php");
    exit;
}

// --- END of new check ---

// Securely fetch participant data
$stmt = $dbcon->prepare("SELECT Gender, firstName, middleName, lastName, profession FROM participant WHERE id = ?");
$stmt->bind_param("i", $participant_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Redirect if participant not found
    header("Location: " . BASE_URL . "/User_dashboard/dashboard.php");
    exit;
}
$stmt->close();


// --- HELPER FUNCTIONS ---

// This function now uses absolute paths for reliability
function convertImage($path)
{
    // __DIR__ is the absolute path to the current folder (e.g., C:\...\admin\certificate)
    $full_path = __DIR__ . '/' . $path;
    if (!file_exists($full_path)) {
        return ''; // Return empty string if image is not found
    }
    $type = pathinfo($full_path, PATHINFO_EXTENSION);
    $data = file_get_contents($full_path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

function makeDot($middlename)
{
    return empty(trim($middlename)) ? " " : rtrim(ucfirst($middlename), '.') . ". ";
}

function makeGen($genderName)
{
    if ($genderName == "Male") {
        return "his attendance";
    } elseif ($genderName == "Female") {
        return "her attendance";
    } else {
        return "attending";
    }
}


// --- PREPARE CERTIFICATE HTML using HEREDOC syntax for readability ---

// Escape data for HTML display
$firstName = htmlspecialchars(ucwords($row['firstName']));
$middleInitial = htmlspecialchars(makeDot($row['middleName']));
$lastName = htmlspecialchars(ucwords($row['lastName']));
$genderText = makeGen($row['Gender']);

// DYNAMIC Image paths using the $year variable
$ptcbanner_path = convertImage("./assets/{$year}/ptcbanner.png");
$ptclogowithrc_path = convertImage("./assets/{$year}/ptclogowithrc.png");
$esigdirector_path = convertImage("./assets/{$year}/esigdirector.png");
$background_path = convertImage("./assets/{$year}/certificate{$year}bg.jpg");

// Static Image Paths (assuming these don't change year to year)
$logo2_path = convertImage('logo2.png');
$logo1_path = convertImage('logo1.png');

// Check if using single template for 2025
$use_single_template = ($year == 2025);

// DYNAMIC Text for the certificate date
$distribution_date_from_db = ''; // Default to empty
$variable_name_dist = 'certificate_distribution_date_' . $year;

$stmt_dist_date = $dbcon->prepare("SELECT value FROM variables WHERE name = ?");
$stmt_dist_date->bind_param("s", $variable_name_dist);
$stmt_dist_date->execute();
$result_dist_date = $stmt_dist_date->get_result();
if ($row_dist_date = $result_dist_date->fetch_assoc()) {
    $distribution_date_from_db = $row_dist_date['value'];
}
$stmt_dist_date->close();

// Format the date from the database into the text string for the certificate
$certificate_date_text = "Given this via Zoom Conference"; // Fallback text
if (!empty($distribution_date_from_db)) {
    $timestamp = strtotime($distribution_date_from_db);
    $day = date('j', $timestamp);
    $suffix = date('S', $timestamp);
    $month = date('F', $timestamp);
    $cert_year = date('Y', $timestamp);

    // --- YEAR-SPECIFIC TEXT ---
    $paper_size = "A4"; // Default paper size
    if ($year == 2023) {
        $certificate_date_text = "Given this {$day} Day of {$month} {$cert_year} at the National Museum Western Visayas, Iloilo City, Philippines";
    } else if ($year == 2025) {
        $paper_size = "Legal";
        $certificate_date_text = "Given this {$day}<sup>{$suffix}</sup> Day of {$month}, {$cert_year}.";
    } else {
        $certificate_date_text = "Given this {$day} Day of {$month} {$cert_year} via Zoom Conference";

    }
}

// --- CPD CALCULATION ---
// Check if feedback submitted for the year
$stmt_feedback = $dbcon->prepare("SELECT COUNT(*) as feedback_count FROM tblfeedback WHERE participant_id = ? AND YEAR(fDate) = ?");
$stmt_feedback->bind_param("is", $participant_id, $year);
$stmt_feedback->execute();
$feedback_count = $stmt_feedback->get_result()->fetch_assoc()['feedback_count'];
$feedback_submitted = $feedback_count > 0;
$stmt_feedback->close();

// Calculate CPD points based on profession, session count, and feedback
$profession_raw = $row['profession'] ?? '';
$profession_list = array_filter(array_map('trim', explode(',', $profession_raw)));
$cpd_text = "";
$cpd_points = 0;
$profession = '';

$cpd_map = [
    'Chemical Engineer' => 25,
    'Chemical Technician' => 22,
    'Chemist' => 22,
];

if ($feedback_submitted) {
    foreach ($profession_list as $prof) {
        $prof_normalized = ucwords(strtolower($prof));
        if (isset($cpd_map[$prof_normalized])) {
            $profession = $prof_normalized;
            $cpd_points = $attendance_count >= 9 ? $cpd_map[$profession] : $attendance_count;
            $cpd_text = "and has earned a total of <b>[ {$cpd_points} ] CPD units</b> applicable for {$profession}s";
            break;
        }
    }
}

// --- SESSION TEXT ---
// Get session names from attended sessions
$stmt_sessions = $dbcon->prepare("
    SELECT DISTINCT s.session_head
    FROM tblptcsession s 
    JOIN tblattendance a ON s.session_number = a.session_id 
    WHERE a.participant_id = ? AND YEAR(a.date_submitted) = ?
    ORDER BY s.session_number ASC
");
$stmt_sessions->bind_param("is", $participant_id, $year);
$stmt_sessions->execute();
$sessions_result = $stmt_sessions->get_result();
$session_names = [];
while ($session_row = $sessions_result->fetch_assoc()) {
    $session_names[] = htmlspecialchars($session_row['session_head']);
}
$stmt_sessions->close();
if (count($session_names) > 1) {
    $last = array_pop($session_names);
    $sessions_attended = implode(', ', $session_names) . ' and ' . $last;
} else {
    $sessions_attended = implode(', ', $session_names);     // Session text combiner
}

// --- END of new code ---


// The main certificate HTML. Notice how clean and easy to read this is.
if ($use_single_template) {
    // 2025: Single template with overlaid text
    $html = <<<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>
    @page { margin: 0; }
    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
    }
    @font-face {
        font-family: "Poppins-Regular";
        src: url("../../fonts/Poppins-Regular.ttf") format("truetype");
    }
    @font-face {
        font-family: "Poppins-Bold";
        src: url("../../fonts/Poppins-Bold.ttf") format("truetype");
    }
    body {
        font-family: "Poppins-Regular", sans-serif;
        position: relative;
        width: 100%;
        height: 100%;
    }
    .background-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }
    .certificate-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }
    .certificate-name {
        font-family: "Poppins-Bold", sans-serif;
        font-size: 39px;
        margin: 0;
        line-height: 1.0;
        position: absolute;
        top: 25%;
        left: 50%;
        transform: translateX(-50%);
        color: #000;
        width: 80%;
        text-align: center;
    }
    .certificate-subtitle {
        font-family: "Poppins-Bold", sans-serif;
        font-size: 18px;
        margin: 0;
        position: absolute;
        top: 41%;
        left: 50%;
        transform: translateX(-50%);
        color: #000;
        width: 80%;
        text-align: center;
    }
    .certificate-cpd {
        font-family: "Poppins-Regular", sans-serif;
        font-size: 18px;
        margin: 0;
        position: absolute;
        top: 70%;
        left: 50%;
        transform: translateX(-50%);
        color: #000;
    }
    .certificate-date {
        font-size: 18px;
        margin: 0;
        position: absolute;
        top: 78%;
        left: 50%;
        transform: translateX(-50%);
        color: #000;
        width: 80%;
        text-align: center;
    }
</style>
</head>
<body>
    <img class="background-image" src="{$background_path}" alt="Certificate background">
    <div class="certificate-content">
        <h1 class="certificate-name">{$firstName} {$middleInitial} {$lastName}</h1>
        <h3 class="certificate-subtitle">{$sessions_attended}</h3>
        <p class="certificate-cpd">{$cpd_text}</p>
        <p class="certificate-date">{$certificate_date_text}</p>
    </div>
</body>
</html>
HTML;
} else {
    // Original multi-image layout for other years
    // The main certificate HTML. Notice how clean and easy to read this is.
    $html = <<<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>
    html { margin: 0; }
    @font-face {
        font-family: "BLMelody-Bold";
        src: url("../../fonts/BLMelody-Bold.ttf") format("truetype");
    }
    @font-face {
        font-family: "BLMelody-Light";
        src: url("../../fonts/BLMelody-Bold.ttf") format("truetype");
    }
    h1, h2, h3, h4 {
        font-family: "BLMelody-Bold", sans-serif;
    }
    p {
        font-family: "BLMelody-Light", sans-serif;
    }
    body { 
        background-image: url("{$background_path}"); 
        background-size: cover; 
        background-repeat: no-repeat; 
    }
</style>
</head>
<body>
    <div style="margin-top: 80px; text-align: center;">
        <img src="{$logo2_path}" style="height:75px; margin-top:-20px; float: left; margin-left: 230px;">
        <img src="{$logo1_path}" style="height:75px; margin-top:-20px; margin-right: 230px; float: right;">
        
        <p style="font-size: 15px; margin-top:-20px; margin-bottom:0;">Republic of the Philippines</p>
        <p style="font-size: 15px; margin-top: -15px;">Department of Science and Technology</p>
        <h3 style="font-size: 19px; margin-top: -20px;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</h3>
        <p style="font-size: 12px; margin-bottom:-15px;">awards this</p>
        <h1 style="font-size: 30px;">CERTIFICATE OF ATTENDANCE</h1>
        <p style="font-size: 12px; margin-top: -10px; margin-bottom: -10px;">to</p>
        <h1 style="font-size:52px; padding: 0 50px; margin-top: 25px; line-height:30px;">{$firstName} {$middleInitial} {$lastName}</h1>
        <p style="font-size: 13px; margin-top: -35px;">in acknowledgement of {$genderText} in the</p>
        
        <div><img src="{$ptcbanner_path}" style="height:110px; margin:0;"></div>
        <p style="font-size: 13px; margin-top:-10px; margin-bottom:-5px">during the</p>
        <div><img src="{$ptclogowithrc_path}" style="height:100px; margin:0;"></div>
        
        <p style="font-size: 12px; margin-top:20px; margin-bottom:-10px">{$certificate_date_text}</p>
        <div>
            <img src="{$esigdirector_path}" style="height:90px; margin-bottom: -50px;">
            <h4 style="font-size: 14px; margin-top: 30px;">JULIUS L. LEAÑO JR., Ph.D.</h4>
        </div>
        <p style="font-size: 12px; margin-top: -15px; margin-bottom:0;">Director</p>
    </div> 
</body>
</html>
HTML;
}
;

// ===================================================================
// START: RESTORED SECOND PAGE LOGIC
// ===================================================================
$arrID = [5340, 6588]; // Array of special participant IDs

if (in_array($participant_id, $arrID)) {
    // This is the HTML for the second page, "Certificate of Participation"
    if ($use_single_template) {
        $secondPage = <<<HTML
        <div style="page-break-before: always;">
            <img class="background-image" src="{$background_path}" alt="Certificate background">
            <div class="certificate-content">
                <h1 class="certificate-name">{$firstName} {$middleInitial} {$lastName}</h1>
                <p class="certificate-subtitle">in acknowledgement of {$genderText} in the</p>
                <p class="certificate-date">{$certificate_date_text}</p>
            </div>
        </div>
HTML;
    } else {
        $secondPage = <<<HTML
        <div style="page-break-before: always;">
            <div style="margin-top: 80px; text-align: center;">
                <img src="{$logo2_path}" style="height:75px; margin-top:-20px; float: left; margin-left: 230px;">
                <img src="{$logo1_path}" style="height:75px; margin-top:-20px; margin-right: 230px; float: right;">
                <p style="font-size: 15px; margin-top:-20px; margin-bottom:0;">Republic of the Philippines</p>
                <p style="font-size: 15px; margin-top: -15px;">Department of Science and Technology</p>
                <h3 style="font-size: 19px; margin-top: -20px;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</h3>
                <p style="font-size: 12px; margin-bottom:-15px;">awards this</p>
                <h1 style="font-size: 30px;">CERTIFICATE OF PARTICIPATION</h1>
                <p style="font-size: 12px; margin-top: -10px; margin-bottom: -10px;">to</p>
                <h1 style="font-size:52px; padding: 0 50px; margin-top: 25px; line-height:30px;">{$firstName} {$middleInitial} {$lastName}</h1>
                <p style="font-size: 13px; margin-top: -35px;">in acknowledgement of {$genderText} in the</p>
                
                <div><img src="{$ptcbanner_path}" style="height:110px; margin:0;"></div>
                <p style="font-size: 13px; margin-top:-10px; margin-bottom:-5px">during the</p>
                <div><img src="{$ptclogowithrc_path}" style="height:100px; margin:0;"></div>
                
                <p style="font-size: 12px; margin-top:20px; margin-bottom:-10px">{$certificate_date_text}</p>
                <div>
                    <img src="{$esigdirector_path}" style="height:90px; margin-bottom: -50px;">
                    <h4 style="font-size: 14px; margin-top: 30px;">JULIUS L. LEAÑO JR., Ph.D.</h4>
                </div>
                <p style="font-size: 12px; margin-top: -15px; margin-bottom:0;">Director</p>
            </div> 
        </div>
HTML;
    }

    // Append the second page to the first one
    $html .= $secondPage;
}
// ===================================================================
// END: RESTORED SECOND PAGE LOGIC
// ===================================================================




/*
// --- PREVIEW MODE ---
if (isset($_GET['preview']) && $_GET['preview'] === '1') {
    header('Content-Type: text/html; charset=UTF-8');
    echo $html;
    exit;
}
*/

// --- GENERATE THE PDF ---
$dompdf = new Dompdf();
$dompdf->setPaper($paper_size, 'Landscape');
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream('CERTIFICATE.pdf', ["Attachment" => false]);
?>