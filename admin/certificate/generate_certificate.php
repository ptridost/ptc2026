<?php
date_default_timezone_set('Asia/Manila');
// Use the Dompdf library
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Use our central, secure database connection
require('../../dbcon.php');
require_once('../../event_year.php');


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


// Final security check: dashboard and direct URLs must use the same eligibility rules.
$eligibility = getParticipantCertificateEligibility($dbcon, (int) $participant_id, (int) $year);
$attendance_count = $eligibility['attended_count'];
$minimum_sessions = $eligibility['rules']['minimum_attendance'];
$feedback_count = $eligibility['feedback_count'];
$minimum_feedback = $eligibility['rules']['minimum_feedback'];

if (!$eligibility['eligible'] && !$TEST_MODE) {
    $not_eligible_url = "certificate_not_eligible.php?year=" . urlencode($year)
        . "&attended=" . urlencode($attendance_count)
        . "&required=" . urlencode($minimum_sessions)
        . "&feedback=" . urlencode($feedback_count)
        . "&feedback_required=" . urlencode($minimum_feedback)
        . "&id=" . urlencode($participant_id);
    if (($_GET['source'] ?? '') === 'admin') {
        $not_eligible_url .= "&source=admin";
    }
    header("Location: " . $not_eligible_url);
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

function certificateAssetSetIsComplete(int $assetYear): bool
{
    $requiredFiles = [
        "assets/{$assetYear}/ptcbanner.png",
        "assets/{$assetYear}/ptclogowithrc.png",
        "assets/{$assetYear}/esigdirector.png",
        "assets/{$assetYear}/certificate{$assetYear}bg.jpg",
    ];

    foreach ($requiredFiles as $requiredFile) {
        if (!file_exists(__DIR__ . '/' . $requiredFile)) {
            return false;
        }
    }

    return true;
}

function resolveCertificateAssetYear(mysqli $dbcon, int $requestedYear): int
{
    if (certificateAssetSetIsComplete($requestedYear)) {
        return $requestedYear;
    }

    $latestYear = (int) getEventSetting($dbcon, 'latest_certificate_asset_year', '2025');
    if ($latestYear > 0 && certificateAssetSetIsComplete($latestYear)) {
        return $latestYear;
    }

    foreach ([2025, 2024, 2023] as $fallbackYear) {
        if (certificateAssetSetIsComplete($fallbackYear)) {
            return $fallbackYear;
        }
    }

    return $requestedYear;
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

// DYNAMIC Image paths using the requested year, with fallback to latest available assets.
$asset_year = resolveCertificateAssetYear($dbcon, (int) $year);
$ptcbanner_path = convertImage("./assets/{$asset_year}/ptcbanner.png");
$ptclogowithrc_path = convertImage("./assets/{$asset_year}/ptclogowithrc.png");
$esigdirector_path = convertImage("./assets/{$asset_year}/esigdirector.png");
$background_path = convertImage("./assets/{$asset_year}/certificate{$asset_year}bg.jpg");

// Static Image Paths (assuming these don't change year to year)
$logo2_path = convertImage('logo2.png');
$logo1_path = convertImage('logo1.png');

// The 2025-style certificate is a full-background template with text overlays.
// Use it for copied dry-run assets until a dedicated yearly layout is added.
$use_single_template = ($asset_year >= 2025);

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
$paper_size = "A4"; // Default paper size
$certificate_date_text = "Given this via Zoom Conference"; // Fallback text
if (!empty($distribution_date_from_db)) {
    $timestamp = strtotime($distribution_date_from_db);
    $day = date('j', $timestamp);
    $suffix = date('S', $timestamp);
    $month = date('F', $timestamp);
    $cert_year = date('Y', $timestamp);

    // --- YEAR-SPECIFIC TEXT ---
    if ($year == 2023) {
        $certificate_date_text = "Given this {$day} Day of {$month} {$cert_year} at the National Museum Western Visayas, Iloilo City, Philippines";
    } else if ($year >= 2025) {
        $paper_size = "Legal";
        $certificate_date_text = "Given this {$day}<sup>{$suffix}</sup> Day of {$month}, {$cert_year}.";
    } else {
        $certificate_date_text = "Given this {$day} Day of {$month} {$cert_year} via Zoom Conference";

    }
}

// --- SESSION TEXT ---
// Get session names from attended sessions
$stmt_sessions = $dbcon->prepare("
    SELECT DISTINCT s.session_head, s.session_title
    FROM tblptcsession s 
    JOIN tblattendance a ON s.session_number = a.session_id 
    WHERE a.participant_id = ?
        AND YEAR(a.date_submitted) = ?
        AND lower(s.session_head) like 'session%'
    ORDER BY s.session_number ASC
");
$stmt_sessions->bind_param("is", $participant_id, $year);
$stmt_sessions->execute();
$sessions_result = $stmt_sessions->get_result();
$sessions = [];
while ($session_row = $sessions_result->fetch_assoc()) {
    $sessions[] = [
        'head' => ucwords(strtolower(htmlspecialchars($session_row['session_head']))),
        'title' => htmlspecialchars($session_row['session_title'])
    ];
}
$stmt_sessions->close();

// Session Names Formatting Handler
$sessions_count = count($sessions);
if ($sessions_count > 1) {
    $formatted = array_map(fn($s) => "<span style = 'white-space:nowrap'>{$s['head']}: {$s['title']}</span>", $sessions);
    $last = array_pop($formatted);
    $sessions_attended = implode(', ', $formatted) . ' and ' . $last;
} else if ($sessions_count === 1) {
    $sessions_attended = "{$sessions[0]['head']}: {$sessions[0]['title']}";
} else {
    $sessions_attended = "the Philippine Textile Congress {$year}";
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

$cpd_map = [
    'Chemical Engineer' => 25,
    'Chemical Technician' => 22,
    'Chemist' => 22,
];


$certificates = [];
if ($feedback_submitted) {
    foreach ($profession_list as $prof) {
        $prof_normalized = ucwords(strtolower($prof));
        if (isset($cpd_map[$prof_normalized])) {
            $cpd_points = $sessions_count >= 9 ? $cpd_map[$prof_normalized] : $sessions_count;
            $cpd_text = "and has earned a total of <b>[ {$cpd_points} ] CPD units</b> applicable for {$prof_normalized}s";
            $certificates[] = ['cpd_text' => $cpd_text];
        }
    }
}

if (empty($certificates))
    {
        $certificates[] = ['cpd_text' => ''];
    }

// --- END of new code ---


// The main certificate HTML. Notice how clean and easy to read this is.
if ($use_single_template) {
    $first = true;
    $html = '
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
                font-size: 12px;
                margin: 0;
                position: absolute;
                top: 38%;
                left: 50%;
                transform: translateX(-50%);
                color: #000;
                width: 78%;
                text-align: center;
                line-height: 1.25;
            }
            .certificate-text {
                font-family: "Poppins-Regular", sans-serif;
                font-size: 18px;
                margin: 0;
                position: absolute;
                top: 58%;
                left: 50%;
                transform: translateX(-50%);
                color: #000;
            }
            .certificate-banner {
                font-size: 18px;
                margin: 0;
                position: absolute;
                top: 58%;
                left: 50%;
                transform: translateX(-50%);
                color: #000;
                width: 100%;
                length: 100%;
                text-align: center;
            }
            .certificate-cpd {
                font-family: "Poppins-Regular", sans-serif;
                font-size: 18px;
                margin: 0;
                position: absolute;
                top: 73%;
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
        <body>';

    foreach ($certificates as $cert) {
        $cpd_text = $cert['cpd_text'];
        $page_break = $first ? '' : '<div style="page-break-before: always;"></div>';
        $first = false;
    // 2025: Single template with overlaid text
        $html .= <<<HTML
        {$page_break}
            <img class="background-image" src="{$background_path}" alt="Certificate background">
            <div class="certificate-content">
                <h1 class="certificate-name">{$firstName} {$middleInitial} {$lastName}</h1>
                <h3 class="certificate-subtitle">{$sessions_attended}</h3>
                <p class="certificate-text">during the</p>
                <div class = "certificate-banner"><img src="{$ptcbanner_path}" style="height:130px; margin:0;"></div>
                <p class="certificate-cpd">{$cpd_text}</p>
                <p class="certificate-date">{$certificate_date_text}</p>
            </div>
        HTML;
    }
    $html .= '</body></html>';
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
