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
if ((empty($participant_id) || !is_numeric($participant_id) || !is_numeric($year)  && !$TEST_MODE)) {
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
$stmt = $dbcon->prepare("SELECT Gender, firstName, middleName, lastName FROM participant WHERE id = ?");
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
function convertImage($path) {
    // __DIR__ is the absolute path to the current folder (e.g., C:\...\admin\certificate)
    $full_path = __DIR__ . '/' . $path; 
    if (!file_exists($full_path)) {
        return ''; // Return empty string if image is not found
    }
    $type = pathinfo($full_path, PATHINFO_EXTENSION);
    $data = file_get_contents($full_path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

function makeDot($middlename) {
    return empty(trim($middlename)) ? " " : rtrim(ucfirst($middlename), '.') . ". ";
}

function makeGen($genderName) {
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
    $day = date('jS', $timestamp);     
    $month = date('F', $timestamp);    
    $cert_year = date('Y', $timestamp);

    // --- YEAR-SPECIFIC TEXT ---
    if ($year == 2023) {
        $certificate_date_text = "Given this {$day} Day of {$month} {$cert_year} at the National Museum Western Visayas, Iloilo City, Philippines";
    } else {
        $certificate_date_text = "Given this {$day} Day of {$month} {$cert_year} via Zoom Conference";
    }
}

// --- END of new code ---


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


// ===================================================================
// START: RESTORED SECOND PAGE LOGIC
// ===================================================================
$arrID = [5340, 6588]; // Array of special participant IDs

if (in_array($participant_id, $arrID)) {
    // This is the HTML for the second page, "Certificate of Participation"
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

    // Append the second page to the first one
    $html .= $secondPage;
}
// ===================================================================
// END: RESTORED SECOND PAGE LOGIC
// ===================================================================





// --- GENERATE THE PDF ---
$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'Landscape');
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream('CERTIFICATE.pdf', ["Attachment" => false]);
?>