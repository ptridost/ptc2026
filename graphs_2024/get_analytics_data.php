<?php
// File: ./graphs_2024/get_analytics_data.php
header('Content-Type: application/json');

require_once(dirname(__DIR__) . '/dbcon.php');
require_once(dirname(__DIR__) . '/dbcon_world.php');

$year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
$sessionId = isset($_POST['sessionId']) ? $_POST['sessionId'] : 'all';

$response = [
    'count' => 0,
    'age' => [], 'gender' => [], 'profession' => [], 'location' => [],
    'howLearned' => [], 'motivation' => []
];

// --- QUERY 1: GET MAIN DEMOGRAPHICS FROM 'participant' TABLE ---
if ($sessionId == 'all' || !is_numeric($sessionId)) {
    $countSql = "SELECT COUNT(id) as total FROM participant WHERE YEAR(datetime_registered) = ?";
    // CORRECTED: 'information' column has been removed.
    $demographicsSql = "SELECT Age, Gender, profession, Country FROM participant WHERE YEAR(datetime_registered) = ?";
} else {
    $sessionId = intval($sessionId);
    $countSql = "SELECT COUNT(DISTINCT a.participant_id) as total FROM tblattendance a WHERE a.session_id = ?";
    // CORRECTED: 'p.information' column has been removed.
    $demographicsSql = "SELECT p.Age, p.Gender, p.profession, p.Country 
                        FROM participant p 
                        JOIN tblattendance a ON p.id = a.participant_id 
                        WHERE a.session_id = ?";
}

// --- Execute Count Query ---
$stmt_count = $dbcon->prepare($countSql);
if ($stmt_count) {
    if ($sessionId == 'all' || !is_numeric($sessionId)) { $stmt_count->bind_param("i", $year); }
    else { $stmt_count->bind_param("i", $sessionId); }
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    if ($row_count = $result_count->fetch_assoc()) { $response['count'] = $row_count['total']; }
    $stmt_count->close();
}

// --- Execute Main Demographics Query ---
if ($sessionId == 'all' || !is_numeric($sessionId)) {
    $demographicsSql = "SELECT Age, Gender, profession, other_profession, Country FROM participant WHERE YEAR(datetime_registered) = ?";
} else {
    $demographicsSql = "SELECT p.Age, p.Gender, p.profession, p.other_profession, p.Country FROM participant p JOIN tblattendance a ON p.id = a.participant_id WHERE a.session_id = ?";
}

$stmt_demo = $dbcon->prepare($demographicsSql);
if ($stmt_demo) {
    if ($sessionId == 'all' || !is_numeric($sessionId)) { $stmt_demo->bind_param("i", $year); }
    else { $stmt_demo->bind_param("i", $sessionId); }
    $stmt_demo->execute();
    $result_demo = $stmt_demo->get_result();
    
    $ageGroups = ['61 and above'=>0, '51-60'=>0, '41-50'=>0, '31-40'=>0, '21-30'=>0, 'Less than 21'=>0];
    $genders = ['Male' => 0, 'Female' => 0];
    $professions = [];
    $locations = [];

    while ($row = $result_demo->fetch_assoc()) {
        if (!empty($row['Age']) && isset($ageGroups[$row['Age']])) { $ageGroups[$row['Age']]++; }
        if (!empty($row['Gender']) && isset($genders[$row['Gender']])) { $genders[$row['Gender']]++; }
        if (!empty($row['profession'])) {
            $prof_list = explode(',', $row['profession']);
            foreach ($prof_list as $single_prof) {
                $prof = trim($single_prof);
                
                // FINAL, CORRECTED LOGIC
                if (!empty($prof)) {
                    // 1. If the profession is 'Others', only count the typed-in text
                    if ($prof === 'Others' && !empty($row['other_profession'])) {
                        $actual_profession = trim($row['other_profession']);
                        // 2. Data Quality Check: Only count if it's longer than 3 characters
                        // This will filter out junk like "asdasd" but keep "Doctor".
                        if (strlen($actual_profession) > 3) {
                           $professions[$actual_profession] = ($professions[$actual_profession] ?? 0) + 1;
                        }
                    } 
                    // 3. If it's a normal profession (and not 'None' or 'Others'), count it
                    elseif ($prof !== 'Others' && strtolower($prof) !== 'none') {
                        $professions[$prof] = ($professions[$prof] ?? 0) + 1;
                    }
                }
            }
        }
        if (!empty($row['Country'])) {
            $loc_code = $row['Country'];
            $locations[$loc_code] = ($locations[$loc_code] ?? 0) + 1;
        }
    }
    
    $response['age'] = $ageGroups;
    $response['gender'] = $genders;
    $response['profession'] = $professions;
    
    // Convert location codes to full names
    $location_names = [];
    if (!empty($locations) && isset($dbcon_world)) {
        $country_codes = array_keys($locations);
        $placeholders = implode(',', array_fill(0, count($country_codes), '?'));
        
        $stmt_countries = $dbcon_world->prepare("SELECT iso2, name FROM countries WHERE iso2 IN ($placeholders)");
        if ($stmt_countries) {
            // CORRECTED: Replaced modern '...' operator with a more compatible version
            $types = str_repeat('s', count($country_codes));
            $stmt_countries->bind_param($types, ...$country_codes);
            
            $stmt_countries->execute();
            $result_countries = $stmt_countries->get_result();
            $country_map = [];
            while($country_row = $result_countries->fetch_assoc()){
                $country_map[$country_row['iso2']] = $country_row['name'];
            }
            $stmt_countries->close();

            foreach($locations as $code => $count){
                $location_names[$country_map[$code] ?? $code] = $count;
            }
        }
    }
    $response['location'] = $location_names;
    $stmt_demo->close();
}

// --- START: REPLACE WITH THIS NEW CODE ---
// --- CORRECTED FEEDBACK QUERIES ---
// NOTE: These queries assume your `tblfeedback` table has a `participant_id` column.

$response['howLearned'] = [];
$howLearnedSql = "";
if ($sessionId == 'all' || !is_numeric($sessionId)) {
    $howLearnedSql = "SELECT information, COUNT(DISTINCT participant_id) as count FROM tblfeedback WHERE YEAR(fDate) = ? AND information IS NOT NULL AND information != '' GROUP BY information";
    $stmt_hl = $dbcon->prepare($howLearnedSql);
    $stmt_hl->bind_param("i", $year);
} else {
    $howLearnedSql = "SELECT f.information, COUNT(DISTINCT f.participant_id) as count FROM tblfeedback f JOIN tblattendance a ON f.participant_id = a.participant_id AND f.session_id = a.session_id WHERE a.session_id = ? AND f.information IS NOT NULL AND f.information != '' GROUP BY f.information";
    $stmt_hl = $dbcon->prepare($howLearnedSql);
    $stmt_hl->bind_param("i", $sessionId);
}
if ($stmt_hl) {
    $stmt_hl->execute();
    $result_hl = $stmt_hl->get_result();
    while ($row_hl = $result_hl->fetch_assoc()) {
        $response['howLearned'][$row_hl['information']] = $row_hl['count'];
    }
    $stmt_hl->close();
}


$response['motivation'] = [];
$motivationSql = "";
if ($sessionId == 'all' || !is_numeric($sessionId)) {
    $motivationSql = "SELECT purpose_attend, COUNT(DISTINCT participant_id) as count FROM tblfeedback WHERE YEAR(fDate) = ? AND purpose_attend IS NOT NULL AND purpose_attend != '' GROUP BY purpose_attend";
    $stmt_m = $dbcon->prepare($motivationSql);
    $stmt_m->bind_param("i", $year);
} else {
    $motivationSql = "SELECT f.purpose_attend, COUNT(DISTINCT f.participant_id) as count FROM tblfeedback f JOIN tblattendance a ON f.participant_id = a.participant_id AND f.session_id = a.session_id WHERE a.session_id = ? AND f.purpose_attend IS NOT NULL AND f.purpose_attend != '' GROUP BY f.purpose_attend";
    $stmt_m = $dbcon->prepare($motivationSql);
    $stmt_m->bind_param("i", $sessionId);
}
if ($stmt_m) {
    $stmt_m->execute();
    $result_m = $stmt_m->get_result();
    while ($row_m = $result_m->fetch_assoc()) {
        $response['motivation'][$row_m['purpose_attend']] = $row_m['count'];
    }
    $stmt_m->close();
}
// --- END: REPLACE WITH THIS NEW CODE ---
// --- Send final combined response ---
echo json_encode($response);