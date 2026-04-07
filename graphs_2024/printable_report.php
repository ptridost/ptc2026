<?php
// File: ./graphs_2024/printable_report.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(dirname(__DIR__) . '/dbcon.php');
require_once(dirname(__DIR__) . '/dbcon_world.php');

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$sessionId = isset($_GET['sessionId']) ? $_GET['sessionId'] : 'all';

// ===================================================================
// START: COMPLETE DATA FETCHING & PROCESSING LOGIC
// ===================================================================
$response = [
    'count' => 0,
    'age' => [], 'gender' => [], 'profession' => [], 'location' => [],
    'howLearned' => [], 'motivation' => []
];
$sessionTitle = '';

// Fetch the session title if a specific session is requested
if ($sessionId != 'all' && is_numeric($sessionId)) {
    $stmt_title = $dbcon->prepare("SELECT session_head FROM tblptcsession WHERE session_number = ?");
    $stmt_title->bind_param("i", $sessionId);
    $stmt_title->execute();
    $result_title = $stmt_title->get_result();
    if ($row_title = $result_title->fetch_assoc()) {
        $sessionTitle = $row_title['session_head'];
    }
    $stmt_title->close();
}

// Determine which SQL queries to use
if ($sessionId == 'all' || !is_numeric($sessionId)) {
    $countSql = "SELECT COUNT(id) as total FROM participant WHERE YEAR(datetime_registered) = ?";
    $demographicsSql = "SELECT Age, Gender, profession, Country FROM participant WHERE YEAR(datetime_registered) = ?";
} else {
    $sessionId = intval($sessionId);
    $countSql = "SELECT COUNT(DISTINCT a.participant_id) as total FROM tblattendance a WHERE a.session_id = ?";
    $demographicsSql = "SELECT p.Age, p.Gender, p.profession, p.Country 
                        FROM participant p 
                        JOIN tblattendance a ON p.id = a.participant_id 
                        WHERE a.session_id = ?";
}

// Execute Count Query
$stmt_count = $dbcon->prepare($countSql);
if ($stmt_count) {
    if ($sessionId == 'all' || !is_numeric($sessionId)) { $stmt_count->bind_param("i", $year); }
    else { $stmt_count->bind_param("i", $sessionId); }
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    if ($row_count = $result_count->fetch_assoc()) { $response['count'] = $row_count['total']; }
    $stmt_count->close();
}

// Execute Main Demographics Query
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
                if (!empty($prof)) {
                    if (strtolower($prof) === 'none') { $prof = 'Others'; }
                    $professions[$prof] = ($professions[$prof] ?? 0) + 1;
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
    
    $location_names = [];
    if (!empty($locations) && isset($dbcon_world)) {
        $country_codes = array_keys($locations);
        $placeholders = implode(',', array_fill(0, count($country_codes), '?'));
        $stmt_countries = $dbcon_world->prepare("SELECT iso2, name FROM countries WHERE iso2 IN ($placeholders)");
        if ($stmt_countries) {
            $stmt_countries->bind_param(str_repeat('s', count($country_codes)), ...$country_codes);
            $stmt_countries->execute();
            $result_countries = $stmt_countries->get_result();
            $country_map = [];
            while($country_row = $result_countries->fetch_assoc()){
                $country_map[$country_row['iso2']] = $country_row['name'];
            }
            foreach($locations as $code => $count){
                $location_names[$country_map[$code] ?? $code] = $count;
            }
            $stmt_countries->close();
        }
    }
    $response['location'] = $location_names;
    $stmt_demo->close();
}

// Execute Feedback Query
$motivations = [];
$howLearned = [];
if ($sessionId == 'all' || !is_numeric($sessionId)) {
    $feedbackSql = "SELECT purpose_attend, information FROM tblfeedback WHERE YEAR(fDate) = ?";
    $stmt_feedback = $dbcon->prepare($feedbackSql);
    if($stmt_feedback){ $stmt_feedback->bind_param("i", $year); }
} else {
    $feedbackSql = "SELECT purpose_attend, information FROM tblfeedback WHERE session_id = ?";
    $stmt_feedback = $dbcon->prepare($feedbackSql);
    if($stmt_feedback){ $stmt_feedback->bind_param("i", $sessionId); }
}
if (isset($stmt_feedback) && $stmt_feedback) {
    $stmt_feedback->execute();
    $result_feedback = $stmt_feedback->get_result();
    while ($row_feedback = $result_feedback->fetch_assoc()) {
        if (!empty($row_feedback['purpose_attend'])) { $motivations[$row_feedback['purpose_attend']] = ($motivations[$row_feedback['purpose_attend']] ?? 0) + 1; }
        if (!empty($row_feedback['information'])) { $howLearned[$row_feedback['information']] = ($howLearned[$row_feedback['information']] ?? 0) + 1; }
    }
    $stmt_feedback->close();
}
$response['motivation'] = $motivations;
$response['howLearned'] = $howLearned;
// ===================================================================
// END OF DATA-FETCHING LOGIC
// ===================================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Summary Report - <?php echo htmlspecialchars($year); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: sans-serif; }
        .report-container { max-width: 800px; margin: 2rem auto; }
        .report-header h1 { color: #0E2369; font-weight: bold; }
        .summary-text { font-size: 1.1rem; line-height: 1.8; margin: 2rem 0; padding: 1.5rem; background-color: #f8f9fa; border-radius: 0.5rem; }
        .summary-text strong { color: #000; }
        .data-table { margin-top: 2rem; }
        .table-dark th { background-color: #0E2369 !important; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; background-color: #fff; }
            .report-container { max-width: 100%; margin: 0; box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="text-center report-header">
            <h1>Analytics Summary Report</h1>
            <p class="lead">
                <?php 
                    if ($sessionId == 'all') {
                        echo "All Registrants for " . htmlspecialchars($year);
                    } else {
                        echo htmlspecialchars($sessionTitle ?: "Session {$sessionId}") . " (" . htmlspecialchars($year) . ")";
                    }
                ?>
            </p>
        </div>

        <div class="summary-text">
            <?php
                if ($response['count'] > 0) {
                    $subject = ($sessionId == 'all') ? "the <strong>{$year} Registrants</strong>" : "attendees of the selected session";
                    
                    // Helper to find the top category, returns 'N/A' if data is empty
                    $findTop = function($data) {
                        if(empty($data)) return 'N/A';
                        arsort($data);
                        return key($data);
                    };
                    
                    $topGender = $findTop($response['gender']);
                    $topAge = $findTop($response['age']);
                    $topProfession = $findTop($response['profession']);
                    $topLocation = $findTop($response['location']);
                    
                    echo "For {$subject}, there are a total of <strong>{$response['count']}</strong> participants. ";
                    if ($topLocation !== 'N/A') {
                        echo "The largest group of participants is from <strong>{$topLocation}</strong>. ";
                    }
                    echo "The most represented gender is <strong>{$topGender}</strong>, and the largest age group is <strong>{$topAge}</strong>. ";
                    echo "The most common profession is <strong>{$topProfession}</strong>.";
                } else {
                    echo "No data available to generate a summary for this selection.";
                }
            ?>
        </div>

        <table class="table table-bordered table-striped data-table">
            <thead class="table-dark">
                <tr><th>Category</th><th>Sub-Category</th><th>Count</th></tr>
            </thead>
            <tbody>
                <?php
                    function print_table_rows($title, $data) {
                        if (empty($data)) return;
                        arsort($data); // Sort data by count, descending
                        foreach($data as $key => $value) {
                            if ($value > 0) { // Only show rows with data
                                echo "<tr><td>".htmlspecialchars($title)."</td><td>".htmlspecialchars($key)."</td><td>".htmlspecialchars($value)."</td></tr>";
                            }
                        }
                    }
                    echo "<tr><td colspan='3' class='table-secondary'><strong>Demographics</strong></td></tr>";
                    print_table_rows('Gender', $response['gender']);
                    print_table_rows('Age', $response['age']);
                    print_table_rows('Profession', $response['profession']);
                    print_table_rows('Location', $response['location']);
                    echo "<tr><td colspan='3' class='table-secondary'><strong>Feedback</strong></td></tr>";
                    print_table_rows('How Learned', $response['howLearned']);
                    print_table_rows('Motivation', $response['motivation']);
                ?>
            </tbody>
        </table>

        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg">Print or Save as PDF</button>
        </div>
    </div>
</body>
</html>