<?php
date_default_timezone_set("Asia/Manila");
session_start();

if (empty($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

require "../dbcon.php";
require "../dbcon_world.php";

// Get filter parameters
$year = $_GET['year'] ?? 2025;
$min_sessions = $_GET['min_sessions'] ?? 1;
$view_mode = $_GET['view'] ?? 'overview';
$selected_session = $_GET['session_id'] ?? null;
$filter_region = $_GET['filter_region'] ?? '';
$filter_city = $_GET['filter_city'] ?? '';

// ============================================================
// 1. GET ALL SESSIONS FOR THE YEAR
// ============================================================
$sql_sessions = "SELECT session_number, session_title, session_head, session_date, session_time 
                 FROM tblptcsession 
                 WHERE YEAR(session_date) = ? 
                 ORDER BY session_date ASC, session_time ASC";
$stmt = $dbcon->prepare($sql_sessions);
$stmt->bind_param("i", $year);
$stmt->execute();
$sessions_result = $stmt->get_result();
$all_sessions = [];
while ($row = $sessions_result->fetch_assoc()) {
    $all_sessions[$row['session_number']] = $row;
}
$stmt->close();
$total_sessions = count($all_sessions);

// ============================================================
// 2. GET PARTICIPANTS WITH LOCATION NAMES FROM LOOKUP TABLES
// ============================================================
$sql_participants = "SELECT 
    p.id,
    p.firstName,
    p.middleName,
    p.lastName,
    p.username as email,
    p.organization,
    p.Country,
    p.Region as region_code,
    p.CityProvince as city_code,
    prov.provDesc as region_name,
    muni.municipalDesc as city_name,
    p.Chem_prc,
    p.ChemEn_prc,
    p.ChemTech_prc,
    p.MechEn_prc,
    p.AgriEn_prc,
    p.Others_prc,
    p.other_profession,
    COUNT(DISTINCT a.session_id) as total_attended,
    COUNT(DISTINCT f.session_id) as total_feedback,
    COUNT(DISTINCT CASE WHEN a.session_id = f.session_id THEN a.session_id END) as qualified_sessions
FROM participant p
LEFT JOIN tbl_provinces prov ON p.Region = prov.provCode
LEFT JOIN tbl_municipalities muni ON p.CityProvince = muni.municipalCode
LEFT JOIN tblattendance a ON p.id = a.participant_id 
    AND a.session_id IN (SELECT session_number FROM tblptcsession WHERE YEAR(session_date) = ?)
LEFT JOIN tblfeedback f ON p.id = f.participant_id 
    AND f.session_id IN (SELECT session_number FROM tblptcsession WHERE YEAR(session_date) = ?)
WHERE 1=1";

$params = [$year, $year];
$param_types = "ii";

if (!empty($filter_region)) {
    $sql_participants .= " AND p.Region = ?";
    $params[] = $filter_region;
    $param_types .= "s";
}

if (!empty($filter_city)) {
    $sql_participants .= " AND p.CityProvince = ?";
    $params[] = $filter_city;
    $param_types .= "s";
}

$sql_participants .= " GROUP BY p.id
HAVING total_attended >= ?
ORDER BY qualified_sessions DESC, total_attended DESC";

$params[] = $min_sessions;
$param_types .= "i";

$stmt = $dbcon->prepare($sql_participants);
$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$participants_result = $stmt->get_result();

$participants = [];
$all_profession_counts = [];
$country_counts = [];
$location_stats = [];

while ($row = $participants_result->fetch_assoc()) {
    $professions = [];
    $has_prc = false;
    
    if (!empty($row['Chem_prc'])) {
        $professions[] = 'Chemist';
        $all_profession_counts['Chemist'] = ($all_profession_counts['Chemist'] ?? 0) + 1;
        $has_prc = true;
    }
    if (!empty($row['ChemEn_prc'])) {
        $professions[] = 'Chemical Engineer';
        $all_profession_counts['Chemical Engineer'] = ($all_profession_counts['Chemical Engineer'] ?? 0) + 1;
        $has_prc = true;
    }
    if (!empty($row['ChemTech_prc'])) {
        $professions[] = 'Chemical Technician';
        $all_profession_counts['Chemical Technician'] = ($all_profession_counts['Chemical Technician'] ?? 0) + 1;
        $has_prc = true;
    }
    if (!empty($row['MechEn_prc'])) {
        $professions[] = 'Mechanical Engineer';
        $all_profession_counts['Mechanical Engineer'] = ($all_profession_counts['Mechanical Engineer'] ?? 0) + 1;
        $has_prc = true;
    }
    if (!empty($row['AgriEn_prc'])) {
        $professions[] = 'Agricultural & Biosystems Engineer';
        $all_profession_counts['Agricultural & Biosystems Engineer'] = ($all_profession_counts['Agricultural & Biosystems Engineer'] ?? 0) + 1;
        $has_prc = true;
    }
    if (!empty($row['Others_prc'])) {
        $other_prof_name = !empty($row['other_profession']) ? $row['other_profession'] : 'Other (Unspecified)';
        $professions[] = $other_prof_name;
        $all_profession_counts[$other_prof_name] = ($all_profession_counts[$other_prof_name] ?? 0) + 1;
        $has_prc = true;
    }
    
    if (!$has_prc) {
        $professions[] = 'No PRC License';
        $all_profession_counts['No PRC License'] = ($all_profession_counts['No PRC License'] ?? 0) + 1;
    }
    
    $country_name = 'Unknown';
    if (!empty($row['Country'])) {
        $country_query = $dbcon_world->prepare("SELECT name FROM countries WHERE iso2 = ?");
        $country_query->bind_param("s", $row['Country']);
        $country_query->execute();
        $country_result = $country_query->get_result();
        if ($country_row = $country_result->fetch_assoc()) {
            $country_name = $country_row['name'];
        }
        $country_query->close();
        $country_counts[$country_name] = ($country_counts[$country_name] ?? 0) + 1;
    }
    
    $row['profession_display'] = implode(', ', $professions);
    $row['country_full_name'] = $country_name;
    
    $region_display = $row['region_name'] ?? 'Unknown Region';
    $city_display = $row['city_name'] ?? 'Unknown City';
    
    $location_key = $region_display . ' - ' . $city_display;
    $location_stats[$location_key] = ($location_stats[$location_key] ?? 0) + 1;
    $row['location_display'] = $region_display . ', ' . $city_display;
    
    $participants[] = $row;
}
$stmt->close();

arsort($location_stats);
arsort($all_profession_counts);
arsort($country_counts);
$total_profession_entries = array_sum($all_profession_counts);

// ============================================================
// 3. GET SESSION DETAILS WITH TIMESTAMP
// ============================================================
$session_details = [];
$session_all_profession_counts = [];
$session_country_counts = [];

if (($view_mode === 'session_detail' || isset($_GET['export'])) && $selected_session) {
    $sql_session_detail = "SELECT 
        p.id,
        p.firstName,
        p.middleName,
        p.lastName,
        p.username as email,
        p.organization,
        p.Country,
        p.Chem_prc,
        p.ChemEn_prc,
        p.ChemTech_prc,
        p.MechEn_prc,
        p.AgriEn_prc,
        p.Others_prc,
        p.other_profession,
        IF(a.attendance_id IS NOT NULL, 'Yes', 'No') as has_attendance,
        a.date_submitted as attendance_timestamp,
        a.Position as position,
        IF(f.feedback_id IS NOT NULL, 'Yes', 'No') as has_feedback,
        f.feedback_id as feedback_timestamp,
        IF(a.attendance_id IS NOT NULL AND f.feedback_id IS NOT NULL, 'Yes', 'No') as qualified
    FROM participant p
    LEFT JOIN tblattendance a ON p.id = a.participant_id AND a.session_id = ?
    LEFT JOIN tblfeedback f ON p.id = f.participant_id AND f.session_id = ?
    WHERE a.attendance_id IS NOT NULL OR f.feedback_id IS NOT NULL
    ORDER BY qualified DESC, has_attendance DESC, has_feedback DESC, p.lastName ASC";
    
    $stmt = $dbcon->prepare($sql_session_detail);
    $stmt->bind_param("ii", $selected_session, $selected_session);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $professions = [];
        $has_prc = false;
        
        if (!empty($row['Chem_prc'])) {
            $professions[] = 'Chemist';
            $session_all_profession_counts['Chemist'] = ($session_all_profession_counts['Chemist'] ?? 0) + 1;
            $has_prc = true;
        }
        if (!empty($row['ChemEn_prc'])) {
            $professions[] = 'Chemical Engineer';
            $session_all_profession_counts['Chemical Engineer'] = ($session_all_profession_counts['Chemical Engineer'] ?? 0) + 1;
            $has_prc = true;
        }
        if (!empty($row['ChemTech_prc'])) {
            $professions[] = 'Chemical Technician';
            $session_all_profession_counts['Chemical Technician'] = ($session_all_profession_counts['Chemical Technician'] ?? 0) + 1;
            $has_prc = true;
        }
        if (!empty($row['MechEn_prc'])) {
            $professions[] = 'Mechanical Engineer';
            $session_all_profession_counts['Mechanical Engineer'] = ($session_all_profession_counts['Mechanical Engineer'] ?? 0) + 1;
            $has_prc = true;
        }
        if (!empty($row['AgriEn_prc'])) {
            $professions[] = 'Agricultural & Biosystems Engineer';
            $session_all_profession_counts['Agricultural & Biosystems Engineer'] = ($session_all_profession_counts['Agricultural & Biosystems Engineer'] ?? 0) + 1;
            $has_prc = true;
        }
        if (!empty($row['Others_prc'])) {
            $other_prof_name = !empty($row['other_profession']) ? $row['other_profession'] : 'Other (Unspecified)';
            $professions[] = $other_prof_name;
            $session_all_profession_counts[$other_prof_name] = ($session_all_profession_counts[$other_prof_name] ?? 0) + 1;
            $has_prc = true;
        }
        
        if (!$has_prc) {
            $professions[] = 'No PRC License';
            $session_all_profession_counts['No PRC License'] = ($session_all_profession_counts['No PRC License'] ?? 0) + 1;
        }
        
        $country_name = 'Unknown';
        if (!empty($row['Country'])) {
            $country_query = $dbcon_world->prepare("SELECT name FROM countries WHERE iso2 = ?");
            $country_query->bind_param("s", $row['Country']);
            $country_query->execute();
            $country_result = $country_query->get_result();
            if ($country_row = $country_result->fetch_assoc()) {
                $country_name = $country_row['name'];
            }
            $country_query->close();
            
            $session_country_counts[$country_name] = ($session_country_counts[$country_name] ?? 0) + 1;
        }
        
        $row['profession_display'] = implode(', ', $professions);
        $row['country_full_name'] = $country_name;
        $session_details[] = $row;
    }
    $stmt->close();
    
    arsort($session_all_profession_counts);
    arsort($session_country_counts);
    $session_total_profession_entries = array_sum($session_all_profession_counts);
}

// ============================================================
// 4. NEW: BULK EXPORT ALL SESSIONS AS SEPARATE ZIP FILES OR ONE COMBINED FILE
// ============================================================
if (isset($_GET['export']) && $_GET['export'] === 'all_sessions_zip') {
    // Create a temporary directory for CSV files
    $temp_dir = sys_get_temp_dir() . '/session_exports_' . uniqid();
    mkdir($temp_dir);
    
    $files_created = [];
    
    // Loop through all sessions and create individual CSV files
    foreach ($all_sessions as $session_num => $session_info) {
        // Get ONLY qualified participants (both attendance AND feedback)
        $sql_bulk = "SELECT 
            a.date_submitted as timestamp,
            a.Position,
            p.firstName,
            p.middleName,
            p.lastName,
            p.organization,
            p.username as email,
            p.Chem_prc,
            p.ChemEn_prc,
            p.ChemTech_prc,
            p.MechEn_prc,
            p.AgriEn_prc,
            p.Others_prc,
            p.other_profession
        FROM tblattendance a
        INNER JOIN participant p ON a.participant_id = p.id
        INNER JOIN tblfeedback f ON p.id = f.participant_id AND f.session_id = a.session_id
        WHERE a.session_id = ?
        ORDER BY a.date_submitted ASC";
        
        $stmt = $dbcon->prepare($sql_bulk);
        $stmt->bind_param("i", $session_num);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Only create file if there are qualified participants
        if ($result->num_rows > 0) {
            // Create safe filename
            $safe_title = preg_replace('/[^A-Za-z0-9_\-]/', '_', $session_info['session_title']);
            $session_date = date('Y-m-d', strtotime($session_info['session_date']));
            $csv_filename = "{$session_date}_{$safe_title}.csv";
            $csv_filepath = $temp_dir . '/' . $csv_filename;
            
            $output = fopen($csv_filepath, 'w');
            fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));
            
            // Session header
            fputcsv($output, [$session_info['session_head'] ?? '']);
            fputcsv($output, [$session_info['session_title']]);
            fputcsv($output, ["Date: " . date('F j, Y', strtotime($session_info['session_date']))]);
            fputcsv($output, []);
            
            // Column headers
            fputcsv($output, ["Timestamp", "First Name", "M I", "Last Name", "Full Name", "Company/Organization", 
                              "Position", "Email Address", "Profession", "Other/s"]);
            
            while ($row = $result->fetch_assoc()) {
                // Determine profession
                $professions = [];
                $others = '';
                
                if (!empty($row['Chem_prc'])) $professions[] = 'Chemist';
                if (!empty($row['ChemEn_prc'])) $professions[] = 'Chemical Engineer';
                if (!empty($row['ChemTech_prc'])) $professions[] = 'Chemical Technician';
                if (!empty($row['MechEn_prc'])) $professions[] = 'Mechanical Engineer';
                if (!empty($row['AgriEn_prc'])) $professions[] = 'Agricultural & Biosystems Engineer';
                if (!empty($row['Others_prc'])) {
                    $others = $row['other_profession'] ?? '';
                }
                
                $profession_display = !empty($professions) ? implode(', ', $professions) : '';
                
                // Get middle initial
                $mi = !empty($row['middleName']) ? strtoupper(substr($row['middleName'], 0, 1)) : '';
                
                // Full name
                $full_name = trim($row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']);
                
                // Format timestamp
                $formatted_timestamp = !empty($row['timestamp']) 
                    ? date('m/d/Y H:i', strtotime($row['timestamp'])) 
                    : '';
                
                fputcsv($output, [
                    $formatted_timestamp,
                    $row['firstName'],
                    $mi,
                    $row['lastName'],
                    $full_name,
                    $row['organization'],
                    $row['Position'] ?? '', // Position from tblattendance
                    $row['email'],
                    $profession_display,
                    $others
                ]);
            }
            
            fclose($output);
            $files_created[] = $csv_filepath;
        }
        $stmt->close();
    }
    
    // Create ZIP file
    if (count($files_created) > 0) {
        $zip_filename = "All_Sessions_Separate_{$year}_" . date("Y-m-d_His") . ".zip";
        $zip_filepath = $temp_dir . '/' . $zip_filename;
        
        $zip = new ZipArchive();
        if ($zip->open($zip_filepath, ZipArchive::CREATE) === TRUE) {
            foreach ($files_created as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
            
            // Send ZIP file to browser
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
            header('Content-Length: ' . filesize($zip_filepath));
            readfile($zip_filepath);
            
            // Clean up
            foreach ($files_created as $file) {
                unlink($file);
            }
            unlink($zip_filepath);
            rmdir($temp_dir);
            exit();
        }
    } else {
        $_SESSION['export_error'] = "No qualified participants found in any session.";
        header("Location: ?year={$year}&min_sessions={$min_sessions}&view=overview");
        exit();
    }
    
    // Clean up if ZIP creation failed
    foreach ($files_created as $file) {
        unlink($file);
    }
    rmdir($temp_dir);
    exit();
}

// ============================================================
// 4B. NEW: BULK EXPORT ALL SESSIONS IN ONE COMBINED CSV FILE
// ============================================================
if (isset($_GET['export']) && $_GET['export'] === 'all_sessions_combined') {
    $filename = "All_Sessions_Combined_{$year}_" . date("Y-m-d_His") . ".csv";
    header("Content-Type: text/csv; charset=utf-8");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen("php://output", "w");
    fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));
    
    // Main header
    fputcsv($output, ["All Sessions Report for {$year}"]);
    fputcsv($output, ["Generated: " . date('F j, Y g:i A')]);
    fputcsv($output, []);
    
    // Loop through all sessions
    foreach ($all_sessions as $session_num => $session_info) {
        // Get ONLY qualified participants (both attendance AND feedback)
        $sql_bulk = "SELECT 
            a.date_submitted as timestamp,
            a.Position,
            p.firstName,
            p.middleName,
            p.lastName,
            p.organization,
            p.username as email,
            p.Chem_prc,
            p.ChemEn_prc,
            p.ChemTech_prc,
            p.MechEn_prc,
            p.AgriEn_prc,
            p.Others_prc,
            p.other_profession
        FROM tblattendance a
        INNER JOIN participant p ON a.participant_id = p.id
        INNER JOIN tblfeedback f ON p.id = f.participant_id AND f.session_id = a.session_id
        WHERE a.session_id = ?
        ORDER BY a.date_submitted ASC";
        
        $stmt = $dbcon->prepare($sql_bulk);
        $stmt->bind_param("i", $session_num);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Only add session if there are qualified participants
        if ($result->num_rows > 0) {
            // Session separator
            fputcsv($output, []);
            fputcsv($output, ["=== " . ($session_info['session_head'] ?? '') . " ==="]);
            fputcsv($output, [$session_info['session_title']]);
            fputcsv($output, ["Date: " . date('F j, Y', strtotime($session_info['session_date']))]);
            fputcsv($output, ["Qualified Participants: " . $result->num_rows]);
            fputcsv($output, []);
            
            // Column headers
            fputcsv($output, ["Timestamp", "First Name", "M I", "Last Name", "Full Name", "Company/Organization", 
                              "Position", "Email Address", "Profession", "Other/s"]);
            
            while ($row = $result->fetch_assoc()) {
                // Determine profession
                $professions = [];
                $others = '';
                
                if (!empty($row['Chem_prc'])) $professions[] = 'Chemist';
                if (!empty($row['ChemEn_prc'])) $professions[] = 'Chemical Engineer';
                if (!empty($row['ChemTech_prc'])) $professions[] = 'Chemical Technician';
                if (!empty($row['MechEn_prc'])) $professions[] = 'Mechanical Engineer';
                if (!empty($row['AgriEn_prc'])) $professions[] = 'Agricultural & Biosystems Engineer';
                if (!empty($row['Others_prc'])) {
                    $others = $row['other_profession'] ?? '';
                }
                
                $profession_display = !empty($professions) ? implode(', ', $professions) : '';
                
                // Get middle initial
                $mi = !empty($row['middleName']) ? strtoupper(substr($row['middleName'], 0, 1)) : '';
                
                // Full name
                $full_name = trim($row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']);
                
                // Format timestamp
                $formatted_timestamp = !empty($row['timestamp']) 
                    ? date('m/d/Y H:i', strtotime($row['timestamp'])) 
                    : '';
                
                fputcsv($output, [
                    $formatted_timestamp,
                    $row['firstName'],
                    $mi,
                    $row['lastName'],
                    $full_name,
                    $row['organization'],
                    $row['Position'] ?? '',
                    $row['email'],
                    $profession_display,
                    $others
                ]);
            }
        }
        $stmt->close();
    }
    
    fclose($output);
    exit();
}

// ============================================================
// 5. EXPORT INDIVIDUAL SESSION WITH TIMESTAMP
// ============================================================
if (isset($_GET['export'])) {
    if ($_GET['export'] === 'overview') {
        $filename = "Attendance_Overview_{$year}_" . date("Y-m-d_His") . ".csv";
        header("Content-Type: text/csv; charset=utf-8");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen("php://output", "w");
        fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));
        
        fputcsv($output, ["Participant Name", "Email", "Organization", "Country", "Profession/PRC License", 
                         "Total Sessions Attended", "Total Feedback Submitted", 
                         "Qualified Sessions (Both Attendance & Feedback)"]);
        
        foreach ($participants as $p) {
            fputcsv($output, [
                trim($p['firstName'] . ' ' . $p['middleName'] . ' ' . $p['lastName']),
                $p['email'],
                $p['organization'],
                $p['country_full_name'],
                $p['profession_display'],
                $p['total_attended'],
                $p['total_feedback'],
                $p['qualified_sessions']
            ]);
        }
        
        fclose($output);
        exit();
    } elseif ($_GET['export'] === 'session_detail' && $selected_session) {
        if (empty($session_details)) {
            $_SESSION['export_error'] = "No participant data found for this session.";
            header("Location: ?year={$year}&min_sessions={$min_sessions}&view=session_detail&session_id={$selected_session}");
            exit();
        }
        
        $session_info = $all_sessions[$selected_session];
        $safe_title = preg_replace('/[^A-Za-z0-9_\-]/', '_', $session_info['session_title']);
        $session_date = date('Y-m-d', strtotime($session_info['session_date']));
        $filename = "{$session_date}_{$safe_title}.csv";
        
        header("Content-Type: text/csv; charset=utf-8");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen("php://output", "w");
        fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));
        
        fputcsv($output, [$session_info['session_head'] ?? '']);
        fputcsv($output, [$session_info['session_title']]);
        fputcsv($output, ["Date: " . date('F j, Y', strtotime($session_info['session_date']))]);
        fputcsv($output, []);
        fputcsv($output, ["Timestamp", "First Name", "M I", "Last Name", "Full Name", "Company/Organization", 
                         "Position", "Email Address", "Profession", "Other/s"]);
        
        foreach ($session_details as $p) {
            // Only export qualified participants (both attendance AND feedback)
            if ($p['qualified'] !== 'Yes') continue;
            
            $professions = [];
            $others = '';
            
            if (!empty($p['Chem_prc'])) $professions[] = 'Chemist';
            if (!empty($p['ChemEn_prc'])) $professions[] = 'Chemical Engineer';
            if (!empty($p['ChemTech_prc'])) $professions[] = 'Chemical Technician';
            if (!empty($p['MechEn_prc'])) $professions[] = 'Mechanical Engineer';
            if (!empty($p['AgriEn_prc'])) $professions[] = 'Agricultural & Biosystems Engineer';
            if (!empty($p['Others_prc'])) {
                $others = $p['other_profession'] ?? '';
            }
            
            $profession_display = !empty($professions) ? implode(', ', $professions) : '';
            $mi = !empty($p['middleName']) ? strtoupper(substr($p['middleName'], 0, 1)) : '';
            $full_name = trim($p['firstName'] . ' ' . $p['middleName'] . ' ' . $p['lastName']);
            $formatted_timestamp = !empty($p['attendance_timestamp']) 
                ? date('m/d/Y H:i', strtotime($p['attendance_timestamp'])) 
                : '';
            
            fputcsv($output, [
                $formatted_timestamp,
                $p['firstName'],
                $mi,
                $p['lastName'],
                $full_name,
                $p['organization'],
                $p['position'] ?? '', // Position from tblattendance
                $p['email'],
                $profession_display,
                $others
            ]);
        }
        
        fclose($output);
        exit();
    } elseif ($_GET['export'] === 'email_list') {
        $filename = "Participant_Email_List_{$year}";
        if (!empty($filter_region)) $filename .= "_{$filter_region}";
        if (!empty($filter_city)) $filename .= "_" . preg_replace('/[^A-Za-z0-9_]/', '_', $filter_city);
        $filename .= "_" . date("Y-m-d_His") . ".csv";
        
        header("Content-Type: text/csv; charset=utf-8");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen("php://output", "w");
        fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));
        
        fputcsv($output, ["Full Name", "Email", "Organization", "Region", "City/Province", 
                         "Profession", "Sessions Attended", "Qualified Sessions"]);
        
        foreach ($participants as $p) {
            fputcsv($output, [
                trim($p['firstName'] . ' ' . $p['middleName'] . ' ' . $p['lastName']),
                $p['email'],
                $p['organization'],
                $p['region_name'] ?? 'Unknown',
                $p['city_name'] ?? 'Unknown',
                $p['profession_display'],
                $p['total_attended'],
                $p['qualified_sessions']
            ]);
        }
        
        fclose($output);
        exit();
    }
}

$dbcon->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance & Feedback Tracker <?= $year ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f5f7fa; }
        .stat-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px; }
        .stat-number { font-size: 2rem; font-weight: 700; color: #0d1e53; }
        .stat-label { color: #6c757d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-qualified { background: #28a745; }
        .badge-partial { background: #ffc107; }
        .badge-none { background: #dc3545; }
        .filter-section { background: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .session-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; margin-top: 20px; }
        .session-card { background: white; border: 2px solid #e0e0e0; border-radius: 8px; padding: 15px; cursor: pointer; transition: all 0.3s; }
        .session-card:hover { border-color: #0d1e53; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .session-card.active { border-color: #0d1e53; background: #f0f4ff; }
        .qualified-yes { background-color: #d4edda !important; }
        .qualified-no { background-color: #f8d7da !important; }
        .table-sm td, .table-sm th { padding: 0.5rem; }
        .profession-badge { font-size: 0.75rem; margin-right: 3px; }
        .export-mega-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px 40px;
            font-size: 1.2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
        }
        .export-mega-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.6);
            color: white;
        }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <!-- HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0">📊 Attendance & Feedback Tracker</h1>
                    <p class="text-muted">Track participant attendance and feedback for <?= $year ?></p>
                </div>
                <div>
                    <?php if ($view_mode === 'session_detail'): ?>
                        <a href="?year=<?= $year ?>&min_sessions=<?= $min_sessions ?>&view=overview" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Back to Overview
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- MEGA EXPORT BUTTONS WITH CHOICE -->
    <?php if ($view_mode === 'overview'): ?>
    <div class="stat-card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h4 class="text-white mb-3">
            <i class="fas fa-download"></i> Bulk Export All Sessions <?= $year ?>
        </h4>
        <p class="text-white mb-4">Choose your preferred export format:</p>
        
        <div class="row g-3 justify-content-center">
            <div class="col-md-5">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-file-archive fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Separate Files (ZIP)</h5>
                        <p class="card-text text-muted">
                            Each session gets its own CSV file, all packaged in a ZIP. 
                            Perfect for organized filing and individual session analysis.
                        </p>
                        <a href="?export=all_sessions_zip&year=<?= $year ?>" class="btn btn-primary w-100">
                            <i class="fas fa-file-archive"></i> Download as ZIP
                        </a>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> One file per session
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-file-csv fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Combined File (CSV)</h5>
                        <p class="card-text text-muted">
                            All sessions in one CSV file with clear separators. 
                            Great for comprehensive reporting and easy searching.
                        </p>
                        <a href="?export=all_sessions_combined&year=<?= $year ?>" class="btn btn-success w-100">
                            <i class="fas fa-file-csv"></i> Download Combined CSV
                        </a>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> All sessions in one file
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert alert-light mt-4 mx-5">
            <strong><i class="fas fa-star"></i> Note:</strong> Both options export only <strong>qualified participants</strong> 
            (those with both attendance and feedback submitted).
        </div>
    </div>
    <?php endif; ?>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <form method="GET" action="" class="row g-3">
            <input type="hidden" name="view" value="<?= $view_mode ?>">
            <?php if ($selected_session): ?>
                <input type="hidden" name="session_id" value="<?= $selected_session ?>">
            <?php endif; ?>
            
            <div class="col-md-3">
                <label class="form-label fw-bold">Year</label>
                <select name="year" class="form-select">
                    <option value="2025" <?= $year == 2025 ? 'selected' : '' ?>>2025</option>
                    <option value="2024" <?= $year == 2024 ? 'selected' : '' ?>>2024</option>
                    <option value="2023" <?= $year == 2023 ? 'selected' : '' ?>>2023</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label fw-bold">Minimum Sessions Attended</label>
                <input type="number" name="min_sessions" class="form-control" 
                       value="<?= $min_sessions ?>" min="1" max="<?= $total_sessions ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">Filter by Region</label>
                <select name="filter_region" class="form-select">
                    <option value="">All Regions</option>
                    <option value="NCR" <?= ($filter_region ?? '') == 'NCR' ? 'selected' : '' ?>>NCR</option>
                    <option value="Region I" <?= ($filter_region ?? '') == 'Region I' ? 'selected' : '' ?>>Region I</option>
                    <option value="Region II" <?= ($filter_region ?? '') == 'Region II' ? 'selected' : '' ?>>Region II</option>
                    <option value="Region III" <?= ($filter_region ?? '') == 'Region III' ? 'selected' : '' ?>>Region III</option>
                    <option value="Region IV-A" <?= ($filter_region ?? '') == 'Region IV-A' ? 'selected' : '' ?>>Region IV-A</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">Filter by City</label>
                <input type="text" name="filter_city" class="form-control" 
                       placeholder="e.g., Manila, Quezon City" 
                       value="<?= htmlspecialchars($filter_city ?? '') ?>">
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
            </div>
            
            <?php if ($view_mode === 'overview'): ?>
            <div class="col-md-4 d-flex align-items-end justify-content-end">
                <a href="?export=overview&year=<?= $year ?>&min_sessions=<?= $min_sessions ?>" 
                   class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export to CSV
                </a>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($view_mode === 'overview'): ?>
    <!-- STATISTICS -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number"><?= $total_sessions ?></div>
                <div class="stat-label">Total Sessions in <?= $year ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number"><?= count($participants) ?></div>
                <div class="stat-label">Participants (≥<?= $min_sessions ?> sessions)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">
                    <?= count(array_filter($participants, fn($p) => $p['qualified_sessions'] >= $min_sessions)) ?>
                </div>
                <div class="stat-label">Fully Qualified (≥<?= $min_sessions ?> complete)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $avg = count($participants) > 0 
                        ? round(array_sum(array_column($participants, 'qualified_sessions')) / count($participants), 1) 
                        : 0;
                    echo $avg;
                    ?>
                </div>
                <div class="stat-label">Avg Qualified Sessions</div>
            </div>
        </div>
    </div>

    <!-- CONSOLIDATED PROFESSION BREAKDOWN -->
    <div class="stat-card">
        <h4 class="mb-3"><i class="fas fa-user-tie"></i> Profession Breakdown (All Professions Ranked)</h4>
        <p class="text-muted small"><strong>Note:</strong> One participant can have multiple professions, so total counts may exceed participant count.</p>
        <p class="text-muted small"><strong>Total profession entries:</strong> <?= $total_profession_entries ?></p>
        
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Rank</th>
                        <th>Profession</th>
                        <th class="text-center">Count</th>
                        <th class="text-center">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    foreach ($all_profession_counts as $prof_name => $count): 
                        $percentage = $total_profession_entries > 0 ? ($count / $total_profession_entries * 100) : 0;
                        
                        // Assign badge colors based on profession type
                        $badge_class = 'bg-secondary';
                        if ($prof_name === 'Chemist') $badge_class = 'bg-primary';
                        elseif ($prof_name === 'Chemical Engineer') $badge_class = 'bg-info';
                        elseif ($prof_name === 'Chemical Technician') $badge_class = 'bg-success';
                        elseif ($prof_name === 'Mechanical Engineer') $badge_class = 'bg-warning text-dark';
                        elseif ($prof_name === 'Agricultural & Biosystems Engineer') $badge_class = 'bg-secondary';
                        elseif ($prof_name === 'No PRC License') $badge_class = 'bg-danger';
                        else $badge_class = 'bg-dark';
                    ?>
                    <tr>
                        <td><strong><?= $rank++ ?></strong></td>
                        <td><?= htmlspecialchars($prof_name) ?></td>
                        <td class="text-center"><span class="badge <?= $badge_class ?>"><?= $count ?></span></td>
                        <td class="text-center"><?= number_format($percentage, 1) ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- COUNTRY BREAKDOWN -->
    <div class="stat-card">
        <h4 class="mb-3"><i class="fas fa-globe"></i> Country Distribution</h4>
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Country</th>
                        <th class="text-center">Participant Count</th>
                        <th class="text-center">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    $total_with_country = array_sum($country_counts);
                    foreach ($country_counts as $country => $count): 
                        $percentage = $total_with_country > 0 ? ($count / $total_with_country * 100) : 0;
                    ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><strong><?= htmlspecialchars($country) ?></strong></td>
                        <td class="text-center"><span class="badge bg-primary"><?= $count ?></span></td>
                        <td class="text-center"><?= number_format($percentage, 1) ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Extract emails for easy copying
    $participant_emails = array_column($participants, 'email');
    $email_list_comma = implode(', ', $participant_emails);
    $email_list_semicolon = implode('; ', $participant_emails);
    $email_list_newline = implode("\n", $participant_emails);
    ?>

    <!-- EMAIL EXTRACTION SECTION -->
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 text-white">
                <i class="fas fa-envelope"></i> Email List for Invitations
            </h4>
            <span class="badge bg-light text-dark"><?= count($participant_emails) ?> emails</span>
        </div>
        
        <?php if (!empty($filter_region) || !empty($filter_city)): ?>
            <div class="alert alert-light mb-3">
                <strong>Filtered Results:</strong>
                <?php if (!empty($filter_region)): ?>
                    Region: <span class="badge bg-primary"><?= htmlspecialchars($filter_region) ?></span>
                <?php endif; ?>
                <?php if (!empty($filter_city)): ?>
                    City: <span class="badge bg-primary"><?= htmlspecialchars($filter_city) ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (count($participant_emails) > 0): ?>
            <!-- Quick Copy Buttons -->
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <button class="btn btn-light w-100" onclick="copyEmails('comma')">
                        <i class="fas fa-copy"></i> Copy (Comma Separated)
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-light w-100" onclick="copyEmails('semicolon')">
                        <i class="fas fa-copy"></i> Copy (Semicolon)
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-light w-100" onclick="copyEmails('newline')">
                        <i class="fas fa-copy"></i> Copy (One Per Line)
                    </button>
                </div>
            </div>

            <!-- Email Preview Boxes -->
            <div class="accordion" id="emailAccordion">
                <!-- Comma Separated -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComma">
                            <i class="fas fa-at me-2"></i> Comma Separated (For Gmail/Outlook BCC)
                        </button>
                    </h2>
                    <div id="collapseComma" class="accordion-collapse collapse" data-bs-parent="#emailAccordion">
                        <div class="accordion-body">
                            <textarea id="email-comma" class="form-control" rows="4" readonly><?= htmlspecialchars($email_list_comma) ?></textarea>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Best for: Gmail, Outlook, Yahoo Mail (BCC field)
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Semicolon Separated -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSemicolon">
                            <i class="fas fa-at me-2"></i> Semicolon Separated (For Outlook Desktop)
                        </button>
                    </h2>
                    <div id="collapseSemicolon" class="accordion-collapse collapse" data-bs-parent="#emailAccordion">
                        <div class="accordion-body">
                            <textarea id="email-semicolon" class="form-control" rows="4" readonly><?= htmlspecialchars($email_list_semicolon) ?></textarea>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Best for: Microsoft Outlook Desktop
                            </small>
                        </div>
                    </div>
                </div>

                <!-- One Per Line -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNewline">
                            <i class="fas fa-list me-2"></i> One Email Per Line (For MailChimp/Bulk Import)
                        </button>
                    </h2>
                    <div id="collapseNewline" class="accordion-collapse collapse" data-bs-parent="#emailAccordion">
                        <div class="accordion-body">
                            <textarea id="email-newline" class="form-control" rows="8" readonly><?= htmlspecialchars($email_list_newline) ?></textarea>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Best for: MailChimp, Constant Contact, CSV imports
                            </small>
                        </div>
                    </div>
                </div>

                <!-- CSV Export Option -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCSV">
                            <i class="fas fa-file-csv me-2"></i> Full Details with Email (CSV Export)
                        </button>
                    </h2>
                    <div id="collapseCSV" class="accordion-collapse collapse" data-bs-parent="#emailAccordion">
                        <div class="accordion-body">
                            <p>Export complete participant details including emails, names, and organizations.</p>
                            <a href="?export=email_list&year=<?= $year ?>&min_sessions=<?= $min_sessions ?>&filter_region=<?= urlencode($filter_region) ?>&filter_city=<?= urlencode($filter_city) ?>" 
                               class="btn btn-success">
                                <i class="fas fa-download"></i> Download Email List CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sample Email Template -->
            <div class="mt-4 p-3" style="background: rgba(255,255,255,0.1); border-radius: 8px;">
                <h6 class="text-white mb-2"><i class="fas fa-lightbulb"></i> Quick Tip for Sending:</h6>
                <ol class="mb-0 text-white" style="font-size: 0.9rem;">
                    <li>Open your email client (Gmail, Outlook, etc.)</li>
                    <li>Click one of the "Copy" buttons above</li>
                    <li>Paste into the <strong>BCC</strong> field (to protect privacy)</li>
                    <li>Add your invitation message and subject</li>
                    <li>Send! 📧</li>
                </ol>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> No participants found with current filters. 
                Try adjusting your filter criteria.
            </div>
        <?php endif; ?>
    </div>

    <!-- SESSIONS GRID -->
    <div class="stat-card">
        <h4 class="mb-3">📅 Sessions in <?= $year ?> (Click to view details)</h4>
        <div class="session-grid">
            <?php foreach ($all_sessions as $session): ?>
                <a href="?year=<?= $year ?>&min_sessions=<?= $min_sessions ?>&view=session_detail&session_id=<?= $session['session_number'] ?>" 
                class="session-card text-decoration-none text-dark">
                    <?php if (!empty($session['session_head'])): ?>
                        <div class="mb-1"><strong><?= htmlspecialchars($session['session_head']) ?></strong></div>
                    <?php endif; ?>
                    <h6 class="mb-2"><?= htmlspecialchars($session['session_title']) ?></h6>
                    <small class="text-muted">
                        <i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($session['session_date'])) ?>
                        <br>
                        <i class="fas fa-clock"></i> <?= date('g:i A', strtotime($session['session_time'])) ?>
                    </small>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- PARTICIPANTS TABLE -->
    <div class="stat-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">👥 Participants Overview</h4>
            <span class="badge bg-info">Showing <?= count($participants) ?> participants</span>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Participant Name</th>
                        <th>Email</th>
                        <th>Organization</th>
                        <th>Country</th>
                        <th>Profession/PRC</th>
                        <th class="text-center">Attended</th>
                        <th class="text-center">Feedback</th>
                        <th class="text-center">Qualified<br><small>(Both)</small></th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($participants)): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No participants found with minimum <?= $min_sessions ?> session(s) attended in <?= $year ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($participants as $index => $p): ?>
                        <tr class="<?= $p['qualified_sessions'] >= $min_sessions ? 'qualified-yes' : '' ?>">
                            <td><?= $index + 1 ?></td>
                            <td>
                                <strong><?= htmlspecialchars(trim($p['firstName'] . ' ' . $p['middleName'] . ' ' . $p['lastName'])) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                            <td><?= htmlspecialchars($p['organization']) ?></td>
                            <td><?= htmlspecialchars($p['country_full_name']) ?></td>
                            <td><small><?= htmlspecialchars($p['profession_display']) ?></small></td>
                            <td class="text-center">
                                <span class="badge bg-primary"><?= $p['total_attended'] ?> / <?= $total_sessions ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info"><?= $p['total_feedback'] ?> / <?= $total_sessions ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-qualified"><?= $p['qualified_sessions'] ?> / <?= $total_sessions ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($p['qualified_sessions'] >= $min_sessions): ?>
                                    <span class="badge bg-success">✓ Qualified</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Partial</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php elseif ($view_mode === 'session_detail' && $selected_session): ?>
    <!-- SESSION DETAIL VIEW -->
    <?php $session_info = $all_sessions[$selected_session]; ?>
    
    <div class="stat-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-2"><?= htmlspecialchars($session_info['session_title']) ?></h3>
                <p class="text-muted mb-0">
                    <i class="fas fa-calendar"></i> <?= date('F j, Y', strtotime($session_info['session_date'])) ?> 
                    | <i class="fas fa-clock"></i> <?= date('g:i A', strtotime($session_info['session_time'])) ?>
                </p>
            </div>
            <a href="?export=session_detail&session_id=<?= $selected_session ?>&year=<?= $year ?>" 
               class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Session Data
            </a>
        </div>

        <!-- Session Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card bg-light">
                    <div class="stat-number"><?= count($session_details) ?></div>
                    <div class="stat-label">Total Participants</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-light">
                    <div class="stat-number">
                        <?= count(array_filter($session_details, fn($p) => $p['has_attendance'] === 'Yes')) ?>
                    </div>
                    <div class="stat-label">With Attendance</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-light">
                    <div class="stat-number text-success">
                        <?= count(array_filter($session_details, fn($p) => $p['qualified'] === 'Yes')) ?>
                    </div>
                    <div class="stat-label">Qualified (Both)</div>
                </div>
            </div>
        </div>

        <!-- SESSION PROFESSION BREAKDOWN -->
        <div class="stat-card bg-light">
            <h5 class="mb-3"><i class="fas fa-user-tie"></i> Profession Breakdown for This Session (Ranked)</h5>
            <p class="text-muted small"><strong>Note:</strong> One participant can have multiple professions.</p>
            <p class="text-muted small"><strong>Total profession entries:</strong> <?= $session_total_profession_entries ?></p>
            
            <div class="table-responsive">
                <table class="table table-sm table-bordered bg-white">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Profession</th>
                            <th class="text-center">Count</th>
                            <th class="text-center">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rank = 1;
                        foreach ($session_all_profession_counts as $prof_name => $count): 
                            $percentage = $session_total_profession_entries > 0 ? ($count / $session_total_profession_entries * 100) : 0;
                            
                            $badge_class = 'bg-secondary';
                            if ($prof_name === 'Chemist') $badge_class = 'bg-primary';
                            elseif ($prof_name === 'Chemical Engineer') $badge_class = 'bg-info';
                            elseif ($prof_name === 'Chemical Technician') $badge_class = 'bg-success';
                            elseif ($prof_name === 'Mechanical Engineer') $badge_class = 'bg-warning text-dark';
                            elseif ($prof_name === 'Agricultural & Biosystems Engineer') $badge_class = 'bg-secondary';
                            elseif ($prof_name === 'No PRC License') $badge_class = 'bg-danger';
                            else $badge_class = 'bg-dark';
                        ?>
                        <tr>
                            <td><?= $rank++ ?></td>
                            <td><?= htmlspecialchars($prof_name) ?></td>
                            <td class="text-center"><span class="badge <?= $badge_class ?>"><?= $count ?></span></td>
                            <td class="text-center"><?= number_format($percentage, 1) ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SESSION COUNTRY BREAKDOWN -->
        <div class="stat-card bg-light">
            <h5 class="mb-3"><i class="fas fa-globe"></i> Country Distribution for This Session</h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover bg-white">
                    <thead class="table-secondary">
                        <tr>
                            <th>Rank</th>
                            <th>Country</th>
                            <th class="text-center">Count</th>
                            <th class="text-center">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rank = 1;
                        $session_total_countries = array_sum($session_country_counts);
                        foreach ($session_country_counts as $country => $count): 
                            $percentage = $session_total_countries > 0 ? ($count / $session_total_countries * 100) : 0;
                        ?>
                        <tr>
                            <td><?= $rank++ ?></td>
                            <td><strong><?= htmlspecialchars($country) ?></strong></td>
                            <td class="text-center"><span class="badge bg-primary"><?= $count ?></span></td>
                            <td class="text-center"><?= number_format($percentage, 1) ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Participants Table with Timestamp -->
        <div class="table-responsive mt-4">
            <table class="table table-hover table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Timestamp</th>
                        <th>Participant Name</th>
                        <th>Email</th>
                        <th>Organization</th>
                        <th>Position</th>
                        <th>Country</th>
                        <th>Profession/PRC</th>
                        <th class="text-center">Attendance</th>
                        <th class="text-center">Feedback</th>
                        <th class="text-center">Qualified</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($session_details)): ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                No participants found for this session
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($session_details as $index => $p): ?>
                        <tr class="<?= $p['qualified'] === 'Yes' ? 'qualified-yes' : 'qualified-no' ?>">
                            <td><?= $index + 1 ?></td>
                            <td>
                                <small class="text-muted">
                                    <?php 
                                    if (!empty($p['attendance_timestamp'])) {
                                        echo date('m/d/Y H:i', strtotime($p['attendance_timestamp']));
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </small>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars(trim($p['firstName'] . ' ' . $p['middleName'] . ' ' . $p['lastName'])) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                            <td><?= htmlspecialchars($p['organization']) ?></td>
                            <td><?= htmlspecialchars($p['position'] ?? '') ?></td>
                            <td><?= htmlspecialchars($p['country_full_name']) ?></td>
                            <td><small><?= htmlspecialchars($p['profession_display']) ?></small></td>
                            <td class="text-center">
                                <?php if ($p['has_attendance'] === 'Yes'): ?>
                                    <span class="badge bg-success">✓ Yes</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">✗ No</span>
                                <?php endif; ?> 
                            </td>
                            <td class="text-center">
                                <?php if ($p['has_feedback'] === 'Yes'): ?>
                                    <span class="badge bg-success">✓ Yes</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">✗ No</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($p['qualified'] === 'Yes'): ?>
                                    <span class="badge bg-success"><i class="fas fa-star"></i> QUALIFIED</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Incomplete</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function copyEmails(format) {
    let textArea;
    switch(format) {
        case 'comma':
            textArea = document.getElementById('email-comma');
            break;
        case 'semicolon':
            textArea = document.getElementById('email-semicolon');
            break;
        case 'newline':
            textArea = document.getElementById('email-newline');
            break;
    }
    
    if (!textArea) {
        alert('Error: Email list not found!');
        return;
    }
    
    textArea.select();
    textArea.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        button.classList.remove('btn-light');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-light');
        }, 2000);
    } catch (err) {
        alert('Failed to copy. Please manually select and copy the text.');
    }
}
</script>
</body>
</html>