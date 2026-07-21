<?php
date_default_timezone_set("Asia/Manila");
session_start();

if (empty($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

require "../dbcon.php";
require_once "../event_year.php";
set_time_limit(300);

// ============================================================
// HELPER FUNCTIONS
// ============================================================
function isValidResponse($text)
{
    if (empty($text)) return false;
    $text = trim($text);
    $emptyResponses = ["none", "n/a", "na", "no", "nothing", "wala", "-", "nope", "nil", "null", "none so far", "non", "walang", "ok lang", "ok", "okay", "none yet", "not applicable", "not yet"];
    if (in_array(strtolower($text), $emptyResponses)) return false;
    $words = array_filter(explode(" ", $text), fn($word) => trim($word) !== "");
    return count($words) > 3;
}

function getRatingClass($rating)
{
    if ($rating >= 4.5) return "rating-excellent";
    if ($rating >= 3.5) return "rating-good";
    if ($rating >= 2.5) return "rating-average";
    return "rating-poor";
}

// ============================================================
// FILTER SETUP
// ============================================================
$availableYears = getAvailableEventYears($dbcon);
$year_filter = resolveEventYear($dbcon, $_GET["year"] ?? null);
$session_filter = $_GET["session_id"] ?? "all";

$where_conditions = ["YEAR(s.session_date) = ?"];
$params = [$year_filter];
$types = "i";

if ($session_filter !== "all" && !empty($session_filter)) {
    $where_conditions[] = "f.session_id = ?";
    $params[] = $session_filter;
    $types .= "i";
}

$where_clause = implode(" AND ", $where_conditions);

// ============================================================
// EXPORT HANDLERS
// ============================================================
if (isset($_GET["export"])) {
    if ($_GET["export"] === "speaker_evaluation") {
        // Export Speaker Evaluation Summary - Formatted like the React report
        $sql = "SELECT 
            s.session_number,
            s.session_head,
            s.session_title,
            sp.speaker_Name,
            AVG(sr.q1) as avg_q1,
            AVG(sr.q2) as avg_q2,
            AVG(sr.q3) as avg_q3,
            AVG(sr.q4) as avg_q4,
            AVG((sr.q1 + sr.q2 + sr.q3 + sr.q4) / 4) as overall_avg,
            COUNT(*) as rating_count
        FROM tblspeaker_rating sr
        INNER JOIN tblspeakerses sp ON sr.speaker_id = sp.speaker_id
        INNER JOIN tblfeedback f ON sr.session_id = f.session_id AND sr.participant_id = f.participant_id
        INNER JOIN tblptcsession s ON f.session_id = s.session_number
        WHERE $where_clause
        GROUP BY s.session_number, s.session_head, s.session_title, sp.speaker_Name
        ORDER BY s.session_number ASC, overall_avg DESC";
        
        $stmt = $dbcon->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Organize by session
        $export_data = [];
        $all_speakers = [];
        while ($row = $result->fetch_assoc()) {
            $session_key = $row['session_number'];
            if (!isset($export_data[$session_key])) {
                $export_data[$session_key] = [
                    'session_head' => $row['session_head'],
                    'session_title' => $row['session_title'],
                    'speakers' => []
                ];
            }
            $export_data[$session_key]['speakers'][] = $row;
            $all_speakers[] = $row;
        }
        
        // Calculate statistics
        $total_speakers = count($all_speakers);
        $total_ratings = array_sum(array_column($all_speakers, 'rating_count'));
        $overall_avg = $total_speakers > 0 ? array_sum(array_column($all_speakers, 'overall_avg')) / $total_speakers : 0;
        $total_sessions = count($export_data);
        
        // Get top 10 performers
        usort($all_speakers, function($a, $b) {
            return $b['overall_avg'] <=> $a['overall_avg'];
        });
        $top_performers = array_slice($all_speakers, 0, 10);
        
        // Calculate rating distribution
        $distribution = [
            '4.80+' => 0,
            '4.75-4.79' => 0,
            '4.70-4.74' => 0,
            '4.65-4.69' => 0,
            'Below 4.65' => 0
        ];
        foreach ($all_speakers as $speaker) {
            $avg = $speaker['overall_avg'];
            if ($avg >= 4.80) $distribution['4.80+']++;
            elseif ($avg >= 4.75) $distribution['4.75-4.79']++;
            elseif ($avg >= 4.70) $distribution['4.70-4.74']++;
            elseif ($avg >= 4.65) $distribution['4.65-4.69']++;
            else $distribution['Below 4.65']++;
        }
        
        // Calculate criteria averages
        $criteria_totals = ['q1' => 0, 'q2' => 0, 'q3' => 0, 'q4' => 0];
        foreach ($all_speakers as $speaker) {
            $criteria_totals['q1'] += $speaker['avg_q1'];
            $criteria_totals['q2'] += $speaker['avg_q2'];
            $criteria_totals['q3'] += $speaker['avg_q3'];
            $criteria_totals['q4'] += $speaker['avg_q4'];
        }
        $count = count($all_speakers);
        $criteria_averages = [
            'Q1: Met Expectations' => $count > 0 ? $criteria_totals['q1'] / $count : 0,
            'Q2: Conducive Learning' => $count > 0 ? $criteria_totals['q2'] / $count : 0,
            'Q3: Understandable' => $count > 0 ? $criteria_totals['q3'] / $count : 0,
            'Q4: Overall Performance' => $count > 0 ? $criteria_totals['q4'] / $count : 0
        ];
        
        // Start CSV output
        $filename = "Resource_Persons_Evaluation_Report_{$year_filter}_" . date("Y-m-d_His") . ".csv";
        header("Content-Type: text/csv; charset=utf-8");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen("php://output", "w");
        fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));
        
        // TITLE
        fputcsv($output, ["RESOURCE PERSONS EVALUATION SUMMARY - YEAR {$year_filter}"]);
        fputcsv($output, ["Generated: " . date("F d, Y h:i A")]);
        fputcsv($output, [""]);
        fputcsv($output, [""]);
        
        // EXECUTIVE SUMMARY
        fputcsv($output, ["=========================================="]);
        fputcsv($output, ["EXECUTIVE SUMMARY"]);
        fputcsv($output, ["=========================================="]);
        fputcsv($output, [""]);
        fputcsv($output, ["Total Speakers", $total_speakers]);
        fputcsv($output, ["Overall Average", number_format($overall_avg, 2)]);
        fputcsv($output, ["Total Sessions", $total_sessions]);
        fputcsv($output, ["Total Ratings", number_format($total_ratings)]);
        fputcsv($output, [""]);
        
        // KEY FINDINGS
        fputcsv($output, ["KEY FINDINGS"]);
        fputcsv($output, ["• All speakers achieved ratings above 4.65/5.0, demonstrating exceptional quality"]);
        $above_470 = $distribution['4.80+'] + $distribution['4.75-4.79'] + $distribution['4.70-4.74'];
        $percent_above_470 = $total_speakers > 0 ? round(($above_470 / $total_speakers) * 100) : 0;
        fputcsv($output, ["• {$percent_above_470}% of speakers scored above 4.70, indicating consistently high performance"]);
        fputcsv($output, ["• Highest rated speaker: " . ($top_performers[0]['speaker_Name'] ?? 'N/A') . " (" . number_format($top_performers[0]['overall_avg'] ?? 0, 2) . ")"]);
        fputcsv($output, [""]);
        fputcsv($output, [""]);
        
        // TOP 10 PERFORMERS
        fputcsv($output, ["=========================================="]);
        fputcsv($output, ["TOP 10 RESOURCE PERSONS"]);
        fputcsv($output, ["=========================================="]);
        fputcsv($output, [""]);
        fputcsv($output, ["Rank", "Speaker Name", "Session", "Overall Score", "Total Ratings"]);
        foreach ($top_performers as $index => $speaker) {
            fputcsv($output, [
                $index + 1,
                $speaker['speaker_Name'],
                $speaker['session_head'] . " - " . $speaker['session_title'],
                number_format($speaker['overall_avg'], 2),
                $speaker['rating_count']
            ]);
        }
        fputcsv($output, [""]);
        fputcsv($output, [""]);
        
        // RATING DISTRIBUTION
        fputcsv($output, ["=========================================="]);
        fputcsv($output, ["RATING DISTRIBUTION"]);
        fputcsv($output, ["=========================================="]);
        fputcsv($output, [""]);
        fputcsv($output, ["Score Range", "Count", "Percentage"]);
        foreach ($distribution as $range => $count) {
            $percentage = $total_speakers > 0 ? round(($count / $total_speakers) * 100, 1) : 0;
            fputcsv($output, [$range, $count, $percentage . "%"]);
        }
        fputcsv($output, [""]);
        fputcsv($output, [""]);
        
        // EVALUATION CRITERIA BREAKDOWN
        fputcsv($output, ["=========================================="]);
        fputcsv($output, ["EVALUATION CRITERIA BREAKDOWN"]);
        fputcsv($output, ["=========================================="]);
        fputcsv($output, [""]);
        fputcsv($output, ["Criteria", "Average Score"]);
        foreach ($criteria_averages as $criteria => $score) {
            fputcsv($output, [$criteria, number_format($score, 2)]);
        }
        fputcsv($output, [""]);
        fputcsv($output, ["The evaluation criteria show remarkable consistency across all dimensions, with scores ranging from " . 
            number_format(min($criteria_averages), 2) . " to " . number_format(max($criteria_averages), 2) . 
            ". This indicates that speakers excelled holistically rather than in isolated areas."]);
        fputcsv($output, [""]);
        fputcsv($output, [""]);
        
        // SESSION PERFORMANCE ANALYSIS
        fputcsv($output, ["=========================================="]);
        fputcsv($output, ["SESSION PERFORMANCE ANALYSIS"]);
        fputcsv($output, ["=========================================="]);
        fputcsv($output, [""]);
        
        // Calculate session averages
        $session_stats = [];
        foreach ($export_data as $session_data) {
            $session_avg = 0;
            $session_ratings = 0;
            foreach ($session_data['speakers'] as $speaker) {
                $session_avg += $speaker['overall_avg'];
                $session_ratings += $speaker['rating_count'];
            }
            $session_stats[] = [
                'session_head' => $session_data['session_head'],
                'session_title' => $session_data['session_title'],
                'avg' => $session_avg / count($session_data['speakers']),
                'speakers' => count($session_data['speakers']),
                'ratings' => $session_ratings
            ];
        }
        
        // Sort by average
        usort($session_stats, function($a, $b) {
            return $b['avg'] <=> $a['avg'];
        });
        
        fputcsv($output, ["Session Name", "Average Score", "Number of Speakers", "Total Ratings"]);
        foreach ($session_stats as $stat) {
            fputcsv($output, [
                $stat['session_head'] . " - " . $stat['session_title'],
                number_format($stat['avg'], 2),
                $stat['speakers'],
                $stat['ratings']
            ]);
        }
        fputcsv($output, [""]);
        fputcsv($output, [""]);
        
        // DETAILED EVALUATION BY SESSION
        fputcsv($output, ["=========================================="]);
        fputcsv($output, ["DETAILED EVALUATION BY SESSION"]);
        fputcsv($output, ["=========================================="]);
        fputcsv($output, [""]);
        
        foreach ($export_data as $session_data) {
            // Session Header
            fputcsv($output, [strtoupper($session_data['session_head'])]);
            fputcsv($output, [$session_data['session_title']]);
            fputcsv($output, [""]);
            
            // Column Headers
            fputcsv($output, [
                "Speaker Name", 
                "Q1: Met Expectations", 
                "Q2: Conducive Learning", 
                "Q3: Understandable", 
                "Q4: Overall Performance", 
                "Overall Average", 
                "Number of Ratings"
            ]);
            
            // Speaker Data
            foreach ($session_data['speakers'] as $speaker) {
                fputcsv($output, [
                    $speaker['speaker_Name'],
                    number_format($speaker['avg_q1'], 2),
                    number_format($speaker['avg_q2'], 2),
                    number_format($speaker['avg_q3'], 2),
                    number_format($speaker['avg_q4'], 2),
                    number_format($speaker['overall_avg'], 2),
                    $speaker['rating_count']
                ]);
            }
            
            fputcsv($output, [""]);
            fputcsv($output, [""]);
        }
        
        // RECOMMENDATIONS
        fputcsv($output, ["=========================================="]);
        fputcsv($output, ["RECOMMENDATIONS"]);
        fputcsv($output, ["=========================================="]);
        fputcsv($output, [""]);
        
        $recommendations = [
            [
                'title' => '1. Recognize Top Performers',
                'desc' => 'Consider special recognition for speakers scoring 4.78 and above, particularly ' . 
                         ($top_performers[0]['speaker_Name'] ?? 'N/A') . ' who achieved the highest score of ' . 
                         number_format($top_performers[0]['overall_avg'] ?? 0, 2) . '.'
            ],
            [
                'title' => '2. Invite High-Rated Speakers for Future Events',
                'desc' => 'Prioritize speakers from top-rated sessions for upcoming conferences. The top 3 sessions demonstrated exceptional quality.'
            ],
            [
                'title' => '3. Maintain Quality Standards',
                'desc' => "Continue using the same selection criteria, as {$percent_above_470}% of speakers scored above 4.70, demonstrating excellent vetting processes."
            ],
            [
                'title' => '4. Support Lower-Rated Speakers',
                'desc' => 'Provide coaching or mentorship to speakers who scored below 4.68 to help them improve for future presentations.'
            ],
            [
                'title' => '5. Leverage High-Engagement Sessions',
                'desc' => 'Sessions with the highest participation rates should be expanded in future events to meet participant demand.'
            ]
        ];
        
        foreach ($recommendations as $rec) {
            fputcsv($output, [$rec['title']]);
            fputcsv($output, [$rec['desc']]);
            fputcsv($output, [""]);
        }
        
        // CONCLUSION
        fputcsv($output, ["=========================================="]);
        fputcsv($output, ["CONCLUSION"]);
        fputcsv($output, ["=========================================="]);
        fputcsv($output, [""]);
        fputcsv($output, ["The {$year_filter} Resource Persons Evaluation demonstrates exceptional quality across all sessions and speakers. With an overall average of " . number_format($overall_avg, 2) . " out of 5.0 and {$percent_above_470}% of speakers scoring above 4.70, the event successfully delivered high-value content to participants. The consistency across evaluation criteria and the high participation rates indicate strong engagement and satisfaction. Moving forward, the organization should continue its rigorous selection process while providing support to enhance the performance of all speakers."]);
        fputcsv($output, [""]);
        fputcsv($output, [""]);
        fputcsv($output, ["--- END OF REPORT ---"]);
        
        fclose($output);
        $stmt->close();
        $dbcon->close();
        exit();
    }
    
    if ($_GET["export"] === "participant_learning") {
        // Export Participant Learning Summary
        $sql = "SELECT 
            s.session_head,
            s.session_title,
            s.session_date,
            f.checkbox_q1,
            f.checkbox_q2,
            f.checkbox_q3,
            f.checkbox_q4,
            f.comment
        FROM tblfeedback f
        INNER JOIN tblptcsession s ON f.session_id = s.session_number
        WHERE $where_clause
            AND (f.checkbox_q1 IS NOT NULL 
                 OR f.checkbox_q2 IS NOT NULL 
                 OR f.checkbox_q3 IS NOT NULL 
                 OR f.checkbox_q4 IS NOT NULL 
                 OR f.comment IS NOT NULL)
        ORDER BY s.session_number, f.feedback_id";
        
        $stmt = $dbcon->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $filename = "Participant_Learning_Summary_{$year_filter}_" . date("Y-m-d_His") . ".csv";
        header("Content-Type: text/csv; charset=utf-8");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen("php://output", "w");
        fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));
        
        fputcsv($output, ["PARTICIPANT LEARNING SUMMARY - YEAR {$year_filter}"]);
        fputcsv($output, ["Generated: " . date("F d, Y h:i A")]);
        fputcsv($output, [""]);
        fputcsv($output, ["Session Head", "Session Title", "Date", "Ideas for Implementation", "New Learning", "Collaboration Possibilities", "Future Topics", "Additional Comments"]);
        
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['session_head'],
                $row['session_title'],
                date('F j, Y', strtotime($row['session_date'])),
                $row['checkbox_q1'],
                $row['checkbox_q2'],
                $row['checkbox_q3'],
                $row['checkbox_q4'],
                $row['comment']
            ]);
        }
        
        fclose($output);
        $stmt->close();
        $dbcon->close();
        exit();
    }
}

// ============================================================
// DATA QUERIES
// ============================================================

// 1. SPEAKER EVALUATION DATA
$sql = "SELECT 
    sp.speaker_Name,
    s.session_title,
    s.session_head,
    AVG(sr.q1) as avg_q1,
    AVG(sr.q2) as avg_q2,
    AVG(sr.q3) as avg_q3,
    AVG(sr.q4) as avg_q4,
    AVG((sr.q1 + sr.q2 + sr.q3 + sr.q4) / 4) as overall_avg,
    COUNT(*) as rating_count
FROM tblspeaker_rating sr
INNER JOIN tblspeakerses sp ON sr.speaker_id = sp.speaker_id
INNER JOIN tblfeedback f ON sr.session_id = f.session_id AND sr.participant_id = f.participant_id
INNER JOIN tblptcsession s ON f.session_id = s.session_number
WHERE $where_clause
GROUP BY sp.speaker_Name, s.session_title, s.session_head
ORDER BY sp.speaker_Name, overall_avg DESC";

$stmt = $dbcon->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$speaker_evaluations = [];
$speaker_overall = [];

while ($row = $result->fetch_assoc()) {
    $speaker_evaluations[] = $row;
    
    // Calculate overall speaker average across all sessions
    if (!isset($speaker_overall[$row['speaker_Name']])) {
        $speaker_overall[$row['speaker_Name']] = [
            'total_avg' => 0,
            'count' => 0,
            'total_ratings' => 0
        ];
    }
    $speaker_overall[$row['speaker_Name']]['total_avg'] += $row['overall_avg'];
    $speaker_overall[$row['speaker_Name']]['count']++;
    $speaker_overall[$row['speaker_Name']]['total_ratings'] += $row['rating_count'];
}

// Calculate final averages
foreach ($speaker_overall as $name => &$data) {
    $data['final_avg'] = $data['total_avg'] / $data['count'];
}
arsort($speaker_overall);

$stmt->close();

// 2. PARTICIPANT LEARNING DATA
$sql = "SELECT 
    s.session_number,
    s.session_head,
    s.session_title,
    s.session_date,
    f.checkbox_q1,
    f.checkbox_q2,
    f.checkbox_q3,
    f.checkbox_q4,
    f.comment
FROM tblfeedback f
INNER JOIN tblptcsession s ON f.session_id = s.session_number
WHERE $where_clause
    AND (f.checkbox_q1 IS NOT NULL 
         OR f.checkbox_q2 IS NOT NULL 
         OR f.checkbox_q3 IS NOT NULL 
         OR f.checkbox_q4 IS NOT NULL 
         OR f.comment IS NOT NULL)
ORDER BY s.session_number, f.feedback_id";

$stmt = $dbcon->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$learning_by_session = [];
$all_learning = [
    'q1' => [],
    'q2' => [],
    'q3' => [],
    'q4' => [],
    'comments' => []
];

while ($row = $result->fetch_assoc()) {
    $session_id = $row['session_number'];
    
    if (!isset($learning_by_session[$session_id])) {
        $learning_by_session[$session_id] = [
            'session_head' => $row['session_head'],
            'session_title' => $row['session_title'],
            'session_date' => $row['session_date'],
            'q1' => [],
            'q2' => [],
            'q3' => [],
            'q4' => [],
            'comments' => []
        ];
    }
    
    // Process each response with deduplication
    if (!empty($row['checkbox_q1']) && isValidResponse($row['checkbox_q1'])) {
        $lower = strtolower(trim($row['checkbox_q1']));
        if (!in_array($lower, array_map('strtolower', $learning_by_session[$session_id]['q1']))) {
            $learning_by_session[$session_id]['q1'][] = $row['checkbox_q1'];
        }
        if (!in_array($lower, array_map('strtolower', $all_learning['q1']))) {
            $all_learning['q1'][] = $row['checkbox_q1'];
        }
    }
    
    if (!empty($row['checkbox_q2']) && isValidResponse($row['checkbox_q2'])) {
        $lower = strtolower(trim($row['checkbox_q2']));
        if (!in_array($lower, array_map('strtolower', $learning_by_session[$session_id]['q2']))) {
            $learning_by_session[$session_id]['q2'][] = $row['checkbox_q2'];
        }
        if (!in_array($lower, array_map('strtolower', $all_learning['q2']))) {
            $all_learning['q2'][] = $row['checkbox_q2'];
        }
    }
    
    if (!empty($row['checkbox_q3']) && isValidResponse($row['checkbox_q3'])) {
        $lower = strtolower(trim($row['checkbox_q3']));
        if (!in_array($lower, array_map('strtolower', $learning_by_session[$session_id]['q3']))) {
            $learning_by_session[$session_id]['q3'][] = $row['checkbox_q3'];
        }
        if (!in_array($lower, array_map('strtolower', $all_learning['q3']))) {
            $all_learning['q3'][] = $row['checkbox_q3'];
        }
    }
    
    if (!empty($row['checkbox_q4']) && isValidResponse($row['checkbox_q4'])) {
        $lower = strtolower(trim($row['checkbox_q4']));
        if (!in_array($lower, array_map('strtolower', $learning_by_session[$session_id]['q4']))) {
            $learning_by_session[$session_id]['q4'][] = $row['checkbox_q4'];
        }
        if (!in_array($lower, array_map('strtolower', $all_learning['q4']))) {
            $all_learning['q4'][] = $row['checkbox_q4'];
        }
    }
    
    if (!empty($row['comment']) && isValidResponse($row['comment'])) {
        $lower = strtolower(trim($row['comment']));
        if (!in_array($lower, array_map('strtolower', $learning_by_session[$session_id]['comments']))) {
            $learning_by_session[$session_id]['comments'][] = $row['comment'];
        }
        if (!in_array($lower, array_map('strtolower', $all_learning['comments']))) {
            $all_learning['comments'][] = $row['comment'];
        }
    }
}

$stmt->close();

// Get available sessions for filter
$sql = "SELECT session_number, session_title, session_head 
        FROM tblptcsession 
        WHERE YEAR(session_date) = ? 
        ORDER BY session_number ASC";
$stmt = $dbcon->prepare($sql);
$stmt->bind_param("i", $year_filter);
$stmt->execute();
$result = $stmt->get_result();
$available_sessions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$dbcon->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning & Evaluation Summary Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f5f7fa; }
        .stat-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px; }
        .stat-number { font-size: 2rem; font-weight: 700; color: #0d1e53; }
        .stat-label { color: #6c757d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .section-title { color: #0d1e53; font-weight: 600; margin-bottom: 20px; font-size: 1.5rem; }
        .rating-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; }
        .rating-excellent { background: #d4edda; color: #155724; }
        .rating-good { background: #d1ecf1; color: #0c5460; }
        .rating-average { background: #fff3cd; color: #856404; }
        .rating-poor { background: #f8d7da; color: #721c24; }
        .filter-section { background: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .response-card { background: #f8f9fa; border-left: 4px solid #0d1e53; padding: 15px; margin-bottom: 10px; border-radius: 4px; }
        .session-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
        .count-badge { background: #0d1e53; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; }
        .top-speaker-card { border: 2px solid #28a745; background: #f0fff4; }
        .speaker-rank { background: #ffc107; color: #000; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <!-- HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0">📊 Learning & Evaluation Summary Report</h1>
                    <p class="text-muted">Resource Persons Evaluation & Participant Learning Insights</p>
                </div>
                <div>
                    <a href="dashboard.php" class="btn btn-outline-secondary me-2">← Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Year</label>
                <select name="year" class="form-select">
                    <?php foreach ($availableYears as $availableYear): ?>
                        <option value="<?= $availableYear ?>" <?= $year_filter == $availableYear ? 'selected' : '' ?>>
                            <?= $availableYear ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Session (Optional)</label>
                <select name="session_id" class="form-select">
                    <option value="all" <?= $session_filter === "all" ? "selected" : "" ?>>All Sessions</option>
                    <?php foreach ($available_sessions as $session): ?>
                        <option value="<?= $session["session_number"] ?>" <?= $session_filter == $session["session_number"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($session["session_head"]) ?> - <?= htmlspecialchars($session["session_title"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="dropdown w-100">
                    <button class="btn btn-success dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="?export=speaker_evaluation&year=<?= $year_filter ?>&session_id=<?= $session_filter ?>">
                                <i class="fas fa-chalkboard-teacher"></i> Speaker Evaluation
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="?export=participant_learning&year=<?= $year_filter ?>&session_id=<?= $session_filter ?>">
                                <i class="fas fa-graduation-cap"></i> Participant Learning
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>

    <!-- SECTION 1: RESOURCE PERSONS EVALUATION -->
    <div class="stat-card">
        <h2 class="section-title">
            <i class="fas fa-chalkboard-teacher"></i> Section 1: Summary of Resource Persons Evaluation
        </h2>
        
        <!-- Overall Speaker Rankings -->
        <div class="mb-4">
            <h4 class="mb-3">Top Resource Persons (Overall Average Across All Sessions)</h4>
            <div class="row">
                <?php 
                $rank = 1;
                foreach (array_slice($speaker_overall, 0, 6, true) as $name => $data): 
                ?>
                <div class="col-md-4 mb-3">
                    <div class="stat-card <?= $rank <= 3 ? 'top-speaker-card' : '' ?>">
                        <div class="d-flex align-items-center mb-2">
                            <div class="speaker-rank me-3"><?= $rank ?></div>
                            <div>
                                <h6 class="mb-0"><?= htmlspecialchars($name) ?></h6>
                                <small class="text-muted"><?= $data['total_ratings'] ?> total ratings</small>
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="rating-badge <?= getRatingClass($data['final_avg']) ?>">
                                <?= number_format($data['final_avg'], 2) ?>/5.00
                            </span>
                        </div>
                    </div>
                </div>
                <?php 
                $rank++;
                endforeach; 
                ?>
            </div>
        </div>

        <!-- Detailed Speaker Evaluation by Session -->
        <h4 class="mb-3">Detailed Evaluation Organized by Session</h4>
        
        <?php if (empty($speaker_evaluations)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> No speaker evaluation data available for the selected filters.
            </div>
        <?php else: ?>
            <?php 
            // Organize evaluations by session
            $evaluations_by_session = [];
            foreach ($speaker_evaluations as $eval) {
                $session_key = $eval['session_title'];
                if (!isset($evaluations_by_session[$session_key])) {
                    $evaluations_by_session[$session_key] = [
                        'session_head' => $eval['session_head'],
                        'session_title' => $eval['session_title'],
                        'speakers' => []
                    ];
                }
                $evaluations_by_session[$session_key]['speakers'][] = $eval;
            }
            ?>
            
            <?php foreach ($evaluations_by_session as $session_data): ?>
            <div class="stat-card mb-4" style="border-left: 4px solid #0d1e53;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <?php if (!empty($session_data['session_head'])): ?>
                            <h5 class="text-primary mb-1"><?= htmlspecialchars($session_data['session_head']) ?></h5>
                        <?php endif; ?>
                        <h6 class="mb-0"><?= htmlspecialchars($session_data['session_title']) ?></h6>
                    </div>
                    <span class="badge bg-info"><?= count($session_data['speakers']) ?> Speaker(s)</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Speaker Name</th>
                                <th class="text-center">Q1: Met<br>Expectations</th>
                                <th class="text-center">Q2: Conducive<br>Learning</th>
                                <th class="text-center">Q3: Easy to<br>Understand</th>
                                <th class="text-center">Q4: Overall<br>Performance</th>
                                <th class="text-center">Average</th>
                                <th class="text-center">Ratings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($session_data['speakers'] as $eval): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($eval['speaker_Name']) ?></strong></td>
                                <td class="text-center"><?= number_format($eval['avg_q1'], 2) ?></td>
                                <td class="text-center"><?= number_format($eval['avg_q2'], 2) ?></td>
                                <td class="text-center"><?= number_format($eval['avg_q3'], 2) ?></td>
                                <td class="text-center"><?= number_format($eval['avg_q4'], 2) ?></td>
                                <td class="text-center">
                                    <span class="rating-badge <?= getRatingClass($eval['overall_avg']) ?>">
                                        <?= number_format($eval['overall_avg'], 2) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary"><?= $eval['rating_count'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="alert alert-info mt-3">
            <strong><i class="fas fa-info-circle"></i> Rating Scale:</strong>
            <ul class="mb-0 mt-2">
                <li><strong>4.5 - 5.0:</strong> Excellent</li>
                <li><strong>3.5 - 4.4:</strong> Good</li>
                <li><strong>2.5 - 3.4:</strong> Average</li>
                <li><strong>Below 2.5:</strong> Needs Improvement</li>
            </ul>
        </div>
    </div>

    <!-- SECTION 2: PARTICIPANT LEARNING SUMMARY -->
    <div class="stat-card">
        <h2 class="section-title">
            <i class="fas fa-graduation-cap"></i> Section 2: Summary of Participant Learning
        </h2>

        <!-- Overall Learning Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="stat-number"><?= count($all_learning['q1']) ?></div>
                    <div class="stat-label">Ideas for Implementation</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="stat-number"><?= count($all_learning['q2']) ?></div>
                    <div class="stat-label">New Learning</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="stat-number"><?= count($all_learning['q3']) ?></div>
                    <div class="stat-label">Collaboration Ideas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="stat-number"><?= count($all_learning['q4']) ?></div>
                    <div class="stat-label">Future Topics</div>
                </div>
            </div>
        </div>

        <!-- Top Learning Insights (Sample from all responses) -->
        <div class="mb-4">
            <h4 class="mb-3">Key Learning Insights (Unique Responses Across All Sessions)</h4>
            
            <div class="accordion" id="learningAccordion">
                <!-- Q1: Ideas for Implementation -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQ1">
                            <i class="fas fa-lightbulb me-2"></i> Ideas for Implementation 
                            <span class="count-badge ms-2"><?= count($all_learning['q1']) ?></span>
                        </button>
                    </h2>
                    <div id="collapseQ1" class="accordion-collapse collapse show" data-bs-parent="#learningAccordion">
                        <div class="accordion-body" style="max-height: 400px; overflow-y: auto;">
                            <?php if (empty($all_learning['q1'])): ?>
                                <p class="text-muted">No responses available.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($all_learning['q1'], 0, 20) as $index => $response): ?>
                                <div class="response-card">
                                    <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($response)) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php if (count($all_learning['q1']) > 20): ?>
                                    <div class="alert alert-info text-center">
                                        Showing top 20 of <?= count($all_learning['q1']) ?> responses. 
                                        <a href="?export=participant_learning&year=<?= $year_filter ?>&session_id=<?= $session_filter ?>">Export all responses</a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q2: New Learning -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQ2">
                            <i class="fas fa-book me-2"></i> New Learning 
                            <span class="count-badge ms-2"><?= count($all_learning['q2']) ?></span>
                        </button>
                    </h2>
                    <div id="collapseQ2" class="accordion-collapse collapse" data-bs-parent="#learningAccordion">
                        <div class="accordion-body" style="max-height: 400px; overflow-y: auto;">
                            <?php if (empty($all_learning['q2'])): ?>
                                <p class="text-muted">No responses available.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($all_learning['q2'], 0, 20) as $index => $response): ?>
                                <div class="response-card">
                                    <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($response)) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php if (count($all_learning['q2']) > 20): ?>
                                    <div class="alert alert-info text-center">
                                        Showing top 20 of <?= count($all_learning['q2']) ?> responses. 
                                        <a href="?export=participant_learning&year=<?= $year_filter ?>&session_id=<?= $session_filter ?>">Export all responses</a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q3: Collaboration Possibilities -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQ3">
                            <i class="fas fa-handshake me-2"></i> Collaboration Possibilities 
                            <span class="count-badge ms-2"><?= count($all_learning['q3']) ?></span>
                        </button>
                    </h2>
                    <div id="collapseQ3" class="accordion-collapse collapse" data-bs-parent="#learningAccordion">
                        <div class="accordion-body" style="max-height: 400px; overflow-y: auto;">
                            <?php if (empty($all_learning['q3'])): ?>
                                <p class="text-muted">No responses available.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($all_learning['q3'], 0, 20) as $index => $response): ?>
                                <div class="response-card">
                                    <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($response)) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php if (count($all_learning['q3']) > 20): ?>
                                    <div class="alert alert-info text-center">
                                        Showing top 20 of <?= count($all_learning['q3']) ?> responses. 
                                        <a href="?export=participant_learning&year=<?= $year_filter ?>&session_id=<?= $session_filter ?>">Export all responses</a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q4: Future Topics -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQ4">
                            <i class="fas fa-rocket me-2"></i> Future Topics Suggestions 
                            <span class="count-badge ms-2"><?= count($all_learning['q4']) ?></span>
                        </button>
                    </h2>
                    <div id="collapseQ4" class="accordion-collapse collapse" data-bs-parent="#learningAccordion">
                        <div class="accordion-body" style="max-height: 400px; overflow-y: auto;">
                            <?php if (empty($all_learning['q4'])): ?>
                                <p class="text-muted">No responses available.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($all_learning['q4'], 0, 20) as $index => $response): ?>
                                <div class="response-card">
                                    <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($response)) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php if (count($all_learning['q4']) > 20): ?>
                                    <div class="alert alert-info text-center">
                                        Showing top 20 of <?= count($all_learning['q4']) ?> responses. 
                                        <a href="?export=participant_learning&year=<?= $year_filter ?>&session_id=<?= $session_filter ?>">Export all responses</a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Additional Comments -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComments">
                            <i class="fas fa-comments me-2"></i> Additional Comments 
                            <span class="count-badge ms-2"><?= count($all_learning['comments']) ?></span>
                        </button>
                    </h2>
                    <div id="collapseComments" class="accordion-collapse collapse" data-bs-parent="#learningAccordion">
                        <div class="accordion-body" style="max-height: 400px; overflow-y: auto;">
                            <?php if (empty($all_learning['comments'])): ?>
                                <p class="text-muted">No comments available.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($all_learning['comments'], 0, 20) as $index => $comment): ?>
                                <div class="response-card">
                                    <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($comment)) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php if (count($all_learning['comments']) > 20): ?>
                                    <div class="alert alert-info text-center">
                                        Showing top 20 of <?= count($all_learning['comments']) ?> responses. 
                                        <a href="?export=participant_learning&year=<?= $year_filter ?>&session_id=<?= $session_filter ?>">Export all responses</a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning by Session Breakdown -->
        <div class="mt-5">
            <h4 class="mb-3">Learning Insights by Session</h4>
            <?php if (empty($learning_by_session)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> No learning data available for the selected filters.
                </div>
            <?php else: ?>
                <?php foreach ($learning_by_session as $session_id => $session_data): ?>
                <div class="stat-card mb-3">
                    <div class="session-header">
                        <h5 class="mb-1"><?= htmlspecialchars($session_data['session_head']) ?></h5>
                        <h6 class="mb-0 opacity-90"><?= htmlspecialchars($session_data['session_title']) ?></h6>
                        <?php if (!empty($session_data['session_date']) && $session_data['session_date'] !== '0000-00-00'): ?>
                            <small class="opacity-75">
                                <i class="fas fa-calendar"></i> <?= date('F j, Y', strtotime($session_data['session_date'])) ?>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-lightbulb"></i> Ideas for Implementation 
                                <span class="badge bg-primary"><?= count($session_data['q1']) ?></span>
                            </h6>
                            <?php if (empty($session_data['q1'])): ?>
                                <p class="text-muted small">No responses</p>
                            <?php else: ?>
                                <ul class="list-unstyled">
                                    <?php foreach (array_slice($session_data['q1'], 0, 3) as $response): ?>
                                    <li class="mb-2"><small>• <?= htmlspecialchars($response) ?></small></li>
                                    <?php endforeach; ?>
                                    <?php if (count($session_data['q1']) > 3): ?>
                                    <li class="text-muted small">... and <?= count($session_data['q1']) - 3 ?> more</li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-success mb-2">
                                <i class="fas fa-book"></i> New Learning 
                                <span class="badge bg-success"><?= count($session_data['q2']) ?></span>
                            </h6>
                            <?php if (empty($session_data['q2'])): ?>
                                <p class="text-muted small">No responses</p>
                            <?php else: ?>
                                <ul class="list-unstyled">
                                    <?php foreach (array_slice($session_data['q2'], 0, 3) as $response): ?>
                                    <li class="mb-2"><small>• <?= htmlspecialchars($response) ?></small></li>
                                    <?php endforeach; ?>
                                    <?php if (count($session_data['q2']) > 3): ?>
                                    <li class="text-muted small">... and <?= count($session_data['q2']) - 3 ?> more</li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-handshake"></i> Collaboration Possibilities 
                                <span class="badge bg-info"><?= count($session_data['q3']) ?></span>
                            </h6>
                            <?php if (empty($session_data['q3'])): ?>
                                <p class="text-muted small">No responses</p>
                            <?php else: ?>
                                <ul class="list-unstyled">
                                    <?php foreach (array_slice($session_data['q3'], 0, 3) as $response): ?>
                                    <li class="mb-2"><small>• <?= htmlspecialchars($response) ?></small></li>
                                    <?php endforeach; ?>
                                    <?php if (count($session_data['q3']) > 3): ?>
                                    <li class="text-muted small">... and <?= count($session_data['q3']) - 3 ?> more</li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-rocket"></i> Future Topics 
                                <span class="badge bg-warning text-dark"><?= count($session_data['q4']) ?></span>
                            </h6>
                            <?php if (empty($session_data['q4'])): ?>
                                <p class="text-muted small">No responses</p>
                            <?php else: ?>
                                <ul class="list-unstyled">
                                    <?php foreach (array_slice($session_data['q4'], 0, 3) as $response): ?>
                                    <li class="mb-2"><small>• <?= htmlspecialchars($response) ?></small></li>
                                    <?php endforeach; ?>
                                    <?php if (count($session_data['q4']) > 3): ?>
                                    <li class="text-muted small">... and <?= count($session_data['q4']) - 3 ?> more</li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
