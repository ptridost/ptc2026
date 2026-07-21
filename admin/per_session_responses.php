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
    if (empty($text)) {
        return false;
    }
    $text = trim($text);
    $emptyResponses = [
        "none",
        "n/a",
        "na",
        "no",
        "nothing",
        "wala",
        "-",
        "nope",
        "nil",
        "null",
        "none so far",
        "non",
        "walang",
        "ok lang",
        "ok",
        "okay",
        "none yet",
        "not applicable",
        "not yet"
    ];
    if (in_array(strtolower($text), $emptyResponses)) {
        return false;
    }
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
$available_years = getAvailableEventYears($dbcon);
$year_filter = resolveEventYear($dbcon, $_GET["year"] ?? null);
$session_filter = $_GET["session_id"] ?? "all";
$date_from = $_GET["date_from"] ?? "";
$date_to = $_GET["date_to"] ?? "";

$where_conditions = ["YEAR(s.session_date) = ?"];
$params = [$year_filter];
$types = "i";

if ($session_filter !== "all" && !empty($session_filter)) {
    $where_conditions[] = "s.session_number = ?";
    $params[] = $session_filter;
    $types .= "i";
}

if (!empty($date_from)) {
    $where_conditions[] = "f.feedback_id >= ?";
    $params[] = $date_from;
    $types .= "s";
}

if (!empty($date_to)) {
    $where_conditions[] = "f.feedback_id <= ?";
    $params[] = $date_to;
    $types .= "s";
}

$where_clause = implode(" AND ", $where_conditions);

// ============================================================
// EXPORT HANDLER - PER SESSION CSV
// ============================================================
if (isset($_GET["export"]) && $_GET["export"] === "per_session_responses") {
    // Build export-specific where clause
    $export_where = ["YEAR(s.session_date) = ?"];
    $export_params = [$year_filter];
    $export_types = "i";

    if ($session_filter !== "all" && !empty($session_filter)) {
        $export_where[] = "f.session_id = ?";
        $export_params[] = $session_filter;
        $export_types .= "i";
    }

    if (!empty($date_from)) {
        $export_where[] = "f.feedback_id >= ?";
        $export_params[] = $date_from;
        $export_types .= "s";
    }

    if (!empty($date_to)) {
        $export_where[] = "f.feedback_id <= ?";
        $export_params[] = $date_to;
        $export_types .= "s";
    }

    $export_where_clause = implode(" AND ", $export_where);

    $sql = "SELECT f.feedback_id, f.session_id, s.session_head, s.session_title, s.session_number,
                   s.session_date
            FROM tblfeedback f
            INNER JOIN tblptcsession s ON f.session_id = s.session_number
            WHERE YEAR(s.session_date) = " . (int) $year_filter;
    
    // Add additional filters if provided
    if ($session_filter !== "all" && !empty($session_filter)) {
        $sql .= " AND s.session_number = " . intval($session_filter);
    }
    if (!empty($date_from)) {
        $sql .= " AND f.feedback_id >= '" . $dbcon->real_escape_string($date_from) . "'";
    }
    if (!empty($date_to)) {
        $sql .= " AND f.feedback_id <= '" . $dbcon->real_escape_string($date_to) . "'";
    }
    
    $sql .= " ORDER BY s.session_number ASC, f.feedback_id ASC";
    
    $result = $dbcon->query($sql);

    // First pass: get all feedback with responses
    $sessions_data = [];
    while ($row = $result->fetch_assoc()) {
        $session_num = $row["session_number"];
        if (!isset($sessions_data[$session_num])) {
            $sessions_data[$session_num] = [
                'session_head' => $row['session_head'],
                'session_title' => $row['session_title'],
                'session_date' => $row['session_date'],
                'feedback_ids' => []
            ];
        }
        $sessions_data[$session_num]['feedback_ids'][] = $row['feedback_id'];
    }

    // Second pass: get responses for each session
    $filename = "Unique_Responses_Per_Session_" . $year_filter . "_" . date("Y-m-d_His") . ".csv";
    header("Content-Type: text/csv; charset=utf-8");
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen("php://output", "w");
    fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));

    fputcsv($output, ["UNIQUE RESPONSES PER SESSION - YEAR " . $year_filter]);
    fputcsv($output, ["Generated: " . date("F d, Y h:i A")]);
    fputcsv($output, ["Filter: " . ($session_filter !== "all" ? "Session " . $session_filter : "All Sessions")]);
    fputcsv($output, [""]);

    foreach ($sessions_data as $session_num => $session_info) {
        if (empty($session_info['feedback_ids'])) {
            continue;
        }
        
        $feedback_ids = implode(',', $session_info['feedback_ids']);
        
        $sql = "SELECT checkbox_q1, checkbox_q2, checkbox_q3, checkbox_q4, comment
                FROM tblfeedback 
                WHERE feedback_id IN ($feedback_ids)
                ORDER BY feedback_id ASC";
        
        $result = $dbcon->query($sql);
        
        $session_unique_q1 = [];
        $session_unique_q2 = [];
        $session_unique_q3 = [];
        $session_unique_q4 = [];
        $session_unique_comments = [];
        
        while ($row = $result->fetch_assoc()) {
            // Q1
            if (!empty($row["checkbox_q1"]) && isValidResponse($row["checkbox_q1"])) {
                $lower = strtolower(trim($row["checkbox_q1"]));
                $found = false;
                foreach ($session_unique_q1 as $existing) {
                    if (strtolower(trim($existing)) === $lower) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $session_unique_q1[] = $row["checkbox_q1"];
                }
            }
            
            // Q2
            if (!empty($row["checkbox_q2"]) && isValidResponse($row["checkbox_q2"])) {
                $lower = strtolower(trim($row["checkbox_q2"]));
                $found = false;
                foreach ($session_unique_q2 as $existing) {
                    if (strtolower(trim($existing)) === $lower) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $session_unique_q2[] = $row["checkbox_q2"];
                }
            }
            
            // Q3
            if (!empty($row["checkbox_q3"]) && isValidResponse($row["checkbox_q3"])) {
                $lower = strtolower(trim($row["checkbox_q3"]));
                $found = false;
                foreach ($session_unique_q3 as $existing) {
                    if (strtolower(trim($existing)) === $lower) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $session_unique_q3[] = $row["checkbox_q3"];
                }
            }
            
            // Q4
            if (!empty($row["checkbox_q4"]) && isValidResponse($row["checkbox_q4"])) {
                $lower = strtolower(trim($row["checkbox_q4"]));
                $found = false;
                foreach ($session_unique_q4 as $existing) {
                    if (strtolower(trim($existing)) === $lower) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $session_unique_q4[] = $row["checkbox_q4"];
                }
            }
            
            // Comments
            if (!empty($row["comment"]) && isValidResponse($row["comment"])) {
                $lower = strtolower(trim($row["comment"]));
                $found = false;
                foreach ($session_unique_comments as $existing) {
                    if (strtolower(trim($existing)) === $lower) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $session_unique_comments[] = $row["comment"];
                }
            }
        }
        
        // Write session data to CSV
        fputcsv($output, [""]);
        fputcsv($output, [strtoupper($session_info['session_head'])]);
        fputcsv($output, [$session_info['session_title']]);
        if (!empty($session_info['session_date']) && $session_info['session_date'] !== '0000-00-00') {
            fputcsv($output, ["Date: " . date("F d, Y", strtotime($session_info['session_date']))]);
        }
        fputcsv($output, ["Total Responses: " . count($session_info['feedback_ids'])]);
        fputcsv($output, [str_repeat("=", 100)]);
        fputcsv($output, [""]);
        
        // Q1
        fputcsv($output, ["Q1: Ideas for Implementation - " . count($session_unique_q1) . " unique responses"]);
        fputcsv($output, [""]);
        $counter = 1;
        foreach ($session_unique_q1 as $response) {
            fputcsv($output, [$counter . ".", $response]);
            $counter++;
        }
        fputcsv($output, [""]);
        
        // Q2
        fputcsv($output, ["Q2: Something New Learned - " . count($session_unique_q2) . " unique responses"]);
        fputcsv($output, [""]);
        $counter = 1;
        foreach ($session_unique_q2 as $response) {
            fputcsv($output, [$counter . ".", $response]);
            $counter++;
        }
        fputcsv($output, [""]);
        
        // Q3
        fputcsv($output, ["Q3: Collaboration Possibilities - " . count($session_unique_q3) . " unique responses"]);
        fputcsv($output, [""]);
        $counter = 1;
        foreach ($session_unique_q3 as $response) {
            fputcsv($output, [$counter . ".", $response]);
            $counter++;
        }
        fputcsv($output, [""]);
        
        // Q4
        fputcsv($output, ["Q4: Future Topics - " . count($session_unique_q4) . " unique responses"]);
        fputcsv($output, [""]);
        $counter = 1;
        foreach ($session_unique_q4 as $response) {
            fputcsv($output, [$counter . ".", $response]);
            $counter++;
        }
        fputcsv($output, [""]);
        
        // Comments
        fputcsv($output, ["Additional Comments - " . count($session_unique_comments) . " unique responses"]);
        fputcsv($output, [""]);
        $counter = 1;
        foreach ($session_unique_comments as $response) {
            fputcsv($output, [$counter . ".", $response]);
            $counter++;
        }
        fputcsv($output, [""]);
        fputcsv($output, [""]);
    }

    fclose($output);
    $dbcon->close();
    exit();
}

// ============================================================
// QUERY: PER-SESSION UNIQUE RESPONSES
// ============================================================
$sql = "SELECT f.session_id, s.session_head, s.session_title, s.session_number, s.session_date,
            f.checkbox_q1, f.checkbox_q2, f.checkbox_q3, f.checkbox_q4, f.comment
        FROM tblfeedback f
        INNER JOIN tblptcsession s ON f.session_id = s.session_number
        WHERE $where_clause
        ORDER BY s.session_number ASC";

$stmt = $dbcon->prepare($sql);
if (!empty($params) && !empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$per_session_responses = [];
$total_processed = 0;

while ($row = $result->fetch_assoc()) {
    $session_id = $row["session_number"];
    $total_processed++;
    
    if (!isset($per_session_responses[$session_id])) {
        $per_session_responses[$session_id] = [
            "session_head" => $row["session_head"],
            "session_title" => $row["session_title"],
            "session_date" => $row["session_date"],
            "q1" => [],
            "q2" => [],
            "q3" => [],
            "q4" => [],
            "comments" => [],
            "total_feedback" => 0
        ];
    }
    
    $session = &$per_session_responses[$session_id];
    $session["total_feedback"]++;
    
    // Process each question with case-insensitive deduplication
    if (!empty($row["checkbox_q1"]) && isValidResponse($row["checkbox_q1"])) {
        $lower = strtolower(trim($row["checkbox_q1"]));
        $found = false;
        foreach ($session["q1"] as $existing) {
            if (strtolower(trim($existing)) === $lower) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $session["q1"][] = $row["checkbox_q1"];
        }
    }
    
    if (!empty($row["checkbox_q2"]) && isValidResponse($row["checkbox_q2"])) {
        $lower = strtolower(trim($row["checkbox_q2"]));
        $found = false;
        foreach ($session["q2"] as $existing) {
            if (strtolower(trim($existing)) === $lower) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $session["q2"][] = $row["checkbox_q2"];
        }
    }
    
    if (!empty($row["checkbox_q3"]) && isValidResponse($row["checkbox_q3"])) {
        $lower = strtolower(trim($row["checkbox_q3"]));
        $found = false;
        foreach ($session["q3"] as $existing) {
            if (strtolower(trim($existing)) === $lower) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $session["q3"][] = $row["checkbox_q3"];
        }
    }
    
    if (!empty($row["checkbox_q4"]) && isValidResponse($row["checkbox_q4"])) {
        $lower = strtolower(trim($row["checkbox_q4"]));
        $found = false;
        foreach ($session["q4"] as $existing) {
            if (strtolower(trim($existing)) === $lower) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $session["q4"][] = $row["checkbox_q4"];
        }
    }
    
    if (!empty($row["comment"]) && isValidResponse($row["comment"])) {
        $lower = strtolower(trim($row["comment"]));
        $found = false;
        foreach ($session["comments"] as $existing) {
            if (strtolower(trim($existing)) === $lower) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $session["comments"][] = $row["comment"];
        }
    }
}
$stmt->close();

// Get Filter Data - Get all sessions for the selected year
$sql = "SELECT session_number, session_head, session_title, YEAR(session_date) as year
        FROM tblptcsession 
        WHERE YEAR(session_date) = ?
        ORDER BY session_number ASC";
$stmt = $dbcon->prepare($sql);
$stmt->bind_param("i", $year_filter);
$stmt->execute();
$result = $stmt->get_result();
$all_sessions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Debug information - should match the filtered query
$debug_sql = "SELECT COUNT(*) as total, 
              COUNT(DISTINCT s.session_number) as unique_sessions
              FROM tblfeedback f
              INNER JOIN tblptcsession s ON f.session_id = s.session_number
              WHERE " . $where_clause;
              
$debug_stmt = $dbcon->prepare($debug_sql);
if (!empty($params) && !empty($types)) {
    $debug_stmt->bind_param($types, ...$params);
}
$debug_stmt->execute();
$debug_result = $debug_stmt->get_result();
$debug_info = $debug_result->fetch_assoc();
$debug_stmt->close();

$dbcon->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Per-Session Unique Responses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f5f7fa; }
        .stat-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px; }
        .section-title { color: #0d1e53; font-weight: 600; margin-bottom: 20px; font-size: 1.5rem; }
        .session-header { background: linear-gradient(135deg, #0d1e53 0%, #1e3a8a 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .response-card { background: #f8f9fa; border-left: 4px solid #0d1e53; padding: 15px; margin-bottom: 10px; border-radius: 4px; }
        .question-section { margin-bottom: 30px; }
        .question-title { color: #0d1e53; font-weight: 600; font-size: 1.1rem; margin-bottom: 15px; }
        .count-badge { background: #0d1e53; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; margin-left: 10px; }
        .filter-section { background: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .empty-state { text-align: center; padding: 40px; color: #6c757d; }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0">📋 Unique Responses Per Session</h1>
                    <p class="text-muted">View unique, meaningful responses organized by session</p>
                </div>
                <div>
                    <a href="dashboard.php" class="btn btn-outline-secondary me-2">← Back to Dashboard</a>
                    <a href="?export=per_session_responses&session_id=<?= $session_filter ?>&year=<?= $year_filter ?>&date_from=<?= $date_from ?>&date_to=<?= $date_to ?>"
                       class="btn btn-success">📥 Export All Sessions to CSV</a>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Year</label>
                <select name="year" class="form-select">
                    <?php foreach ($available_years as $available_year): ?>
                        <option value="<?= $available_year ?>" <?= $year_filter == $available_year ? "selected" : "" ?>>
                            <?= $available_year ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Session</label>
                <select name="session_id" class="form-select">
                    <option value="all" <?= $session_filter === "all" ? "selected" : "" ?>>All Sessions</option>
                    <?php foreach ($all_sessions as $session): ?>
                        <option value="<?= $session["session_number"] ?>" <?= $session_filter == $session["session_number"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($session["session_head"]) ?> - <?= htmlspecialchars($session["session_title"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Feedback ID From</label>
                <input type="text" name="date_from" class="form-control" value="<?= htmlspecialchars($date_from) ?>" placeholder="e.g., 1">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Feedback ID To</label>
                <input type="text" name="date_to" class="form-control" value="<?= htmlspecialchars($date_to) ?>" placeholder="e.g., 100">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="alert alert-info">
        <strong>✨ Smart Filtering Enabled:</strong> Responses with less than 4 words and common empty responses (none, n/a, wala, etc.) are automatically filtered out. Only unique, meaningful responses are shown.
        <br><strong>📊 Data Stats:</strong> Processing <?= number_format($debug_info['total']) ?> total feedback entries from <?= $debug_info['unique_sessions'] ?> sessions in <?= $year_filter ?>.
    </div>

    <!-- PER-SESSION RESPONSES -->
    <?php if (empty($per_session_responses)): ?>
        <div class="stat-card">
            <div class="empty-state">
                <h3>No responses found</h3>
                <p>Try adjusting your filters to see responses.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($per_session_responses as $session_id => $session_data): ?>
            <?php 
            // Only display sessions that have at least one response
            $has_data = (
                (isset($session_data["q1"]) && is_array($session_data["q1"]) && count($session_data["q1"]) > 0) ||
                (isset($session_data["q2"]) && is_array($session_data["q2"]) && count($session_data["q2"]) > 0) ||
                (isset($session_data["q3"]) && is_array($session_data["q3"]) && count($session_data["q3"]) > 0) ||
                (isset($session_data["q4"]) && is_array($session_data["q4"]) && count($session_data["q4"]) > 0) ||
                (isset($session_data["comments"]) && is_array($session_data["comments"]) && count($session_data["comments"]) > 0)
            );
            
            if (!$has_data) continue;
            ?>
        <div class="stat-card">
            <div class="session-header">
                <h2 class="mb-1"><?= htmlspecialchars($session_data["session_head"]) ?></h2>
                <h3 class="mb-2 opacity-90"><?= htmlspecialchars($session_data["session_title"]) ?></h3>
                <p class="mb-0 opacity-75">
                    <small>
                        <?php if (!empty($session_data["session_date"]) && $session_data["session_date"] !== '0000-00-00'): ?>
                            Date: <?= date("F d, Y", strtotime($session_data["session_date"])) ?> | 
                        <?php endif; ?>
                        Total Feedback: <?= isset($session_data["total_feedback"]) ? $session_data["total_feedback"] : 0 ?>
                    </small><br>
                    Q1: <?= is_array($session_data["q1"]) ? count($session_data["q1"]) : 0 ?> | 
                    Q2: <?= is_array($session_data["q2"]) ? count($session_data["q2"]) : 0 ?> | 
                    Q3: <?= is_array($session_data["q3"]) ? count($session_data["q3"]) : 0 ?> | 
                    Q4: <?= is_array($session_data["q4"]) ? count($session_data["q4"]) : 0 ?> | 
                    Comments: <?= is_array($session_data["comments"]) ? count($session_data["comments"]) : 0 ?>
                </p>
            </div>

            <!-- Q1 -->
            <div class="question-section">
                <div class="question-title">
                    Q1: Ideas for Implementation
                    <span class="count-badge"><?= isset($session_data["q1"]) && is_array($session_data["q1"]) ? count($session_data["q1"]) : 0 ?> unique responses</span>
                </div>
                <?php if (empty($session_data["q1"]) || !is_array($session_data["q1"])): ?>
                    <p class="text-muted">No valid responses for this question.</p>
                <?php else: ?>
                    <?php foreach ($session_data["q1"] as $index => $response): ?>
                    <div class="response-card">
                        <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($response)) ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Q2 -->
            <div class="question-section">
                <div class="question-title">
                    Q2: Something New Learned
                    <span class="count-badge"><?= isset($session_data["q2"]) && is_array($session_data["q2"]) ? count($session_data["q2"]) : 0 ?> unique responses</span>
                </div>
                <?php if (empty($session_data["q2"]) || !is_array($session_data["q2"])): ?>
                    <p class="text-muted">No valid responses for this question.</p>
                <?php else: ?>
                    <?php foreach ($session_data["q2"] as $index => $response): ?>
                    <div class="response-card">
                        <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($response)) ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Q3 -->
            <div class="question-section">
                <div class="question-title">
                    Q3: Collaboration Possibilities
                    <span class="count-badge"><?= isset($session_data["q3"]) && is_array($session_data["q3"]) ? count($session_data["q3"]) : 0 ?> unique responses</span>
                </div>
                <?php if (empty($session_data["q3"]) || !is_array($session_data["q3"])): ?>
                    <p class="text-muted">No valid responses for this question.</p>
                <?php else: ?>
                    <?php foreach ($session_data["q3"] as $index => $response): ?>
                    <div class="response-card">
                        <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($response)) ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Q4 -->
            <div class="question-section">
                <div class="question-title">
                    Q4: Future Topics Suggestions
                    <span class="count-badge"><?= isset($session_data["q4"]) && is_array($session_data["q4"]) ? count($session_data["q4"]) : 0 ?> unique responses</span>
                </div>
                <?php if (empty($session_data["q4"]) || !is_array($session_data["q4"])): ?>
                    <p class="text-muted">No valid responses for this question.</p>
                <?php else: ?>
                    <?php foreach ($session_data["q4"] as $index => $response): ?>
                    <div class="response-card">
                        <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($response)) ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Comments -->
            <div class="question-section">
                <div class="question-title">
                    Additional Comments
                    <span class="count-badge"><?= isset($session_data["comments"]) && is_array($session_data["comments"]) ? count($session_data["comments"]) : 0 ?> unique responses</span>
                </div>
                <?php if (empty($session_data["comments"]) || !is_array($session_data["comments"])): ?>
                    <p class="text-muted">No valid comments for this session.</p>
                <?php else: ?>
                    <?php foreach ($session_data["comments"] as $index => $comment): ?>
                    <div class="response-card">
                        <strong>#<?= $index + 1 ?>:</strong> <?= nl2br(htmlspecialchars($comment)) ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
