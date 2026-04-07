<?php
date_default_timezone_set("Asia/Manila");
session_start();

if (empty($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

require "../dbcon.php";

// Increase execution time for large datasets
set_time_limit(300); // 5 minutes

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
    ];
    if (in_array(strtolower($text), $emptyResponses)) {
        return false;
    }
    $words = array_filter(explode(" ", $text), fn($word) => trim($word) !== "");
    return count($words) > 3;
}

function getRatingClass($rating)
{
    if ($rating >= 4.5) {
        return "rating-excellent";
    }
    if ($rating >= 3.5) {
        return "rating-good";
    }
    if ($rating >= 2.5) {
        return "rating-average";
    }
    return "rating-poor";
}

// ============================================================
// FILTER SETUP
// ============================================================
$session_filter = $_GET["session_id"] ?? "all";
$year_filter = $_GET["year"] ?? "all";
$date_from = $_GET["date_from"] ?? "";
$date_to = $_GET["date_to"] ?? "";

$where_conditions = ["1=1"];
$params = [];
$types = "";

if ($session_filter !== "all" && !empty($session_filter)) {
    $where_conditions[] = "f.session_id = ?";
    $params[] = $session_filter;
    $types .= "i";
}

if ($year_filter !== "all" && !empty($year_filter)) {
    $where_conditions[] = "YEAR(s.session_date) = ?";
    $params[] = $year_filter;
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
// CACHE SETUP
// ============================================================
$cache_key =
    "dashboard_" .
    md5(serialize([$session_filter, $year_filter, $date_from, $date_to]));
$cache_file = sys_get_temp_dir() . "/" . $cache_key . ".cache";
$cache_duration = 300; // 5 minutes
$use_cache = !isset($_GET["refresh"]);

if (
    $use_cache &&
    file_exists($cache_file) &&
    time() - filemtime($cache_file) < $cache_duration
) {
    $cached_data = unserialize(file_get_contents($cache_file));
    extract($cached_data);
} else {
    // ============================================================
    // EXPORT HANDLER
    // ============================================================
    if (isset($_GET["export"]) && $_GET["export"] === "final_thoughts_full") {
        $sql = "SELECT f.feedback_id, f.participant_id, f.Fname, f.Lname, f.organization,
                    f.position, f.email, s.session_title,
                    f.checkbox_q1, f.checkbox_q2, f.checkbox_q3, f.checkbox_q4, f.comment
                FROM tblfeedback f
                LEFT JOIN tblptcsession s ON f.session_id = s.session_number
                WHERE $where_clause ORDER BY f.feedback_id DESC";

        $stmt = $dbcon->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $filename = "Final_Thoughts_Report_" . date("Y-m-d_His") . ".csv";
        header("Content-Type: text/csv; charset=utf-8");
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen("php://output", "w");
        fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));

        fputcsv($output, [
            "Feedback ID",
            "Participant Name",
            "Email",
            "Organization",
            "Position",
            "Session Title",
            "Q1: Ideas for Implementation",
            "Q2: Something New Learned",
            "Q3: Collaboration Possibilities",
            "Q4: Future Topics Suggestions",
            "Additional Comments",
        ]);

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row["feedback_id"],
                trim($row["Fname"] . " " . $row["Lname"]),
                $row["email"],
                $row["organization"],
                $row["position"],
                $row["session_title"],
                $row["checkbox_q1"],
                $row["checkbox_q2"],
                $row["checkbox_q3"],
                $row["checkbox_q4"],
                $row["comment"],
            ]);
        }

        fclose($output);
        $stmt->close();
        $dbcon->close();
        exit();
    }

    // Query 1: Combined Overall Statistics
    $sql = "SELECT COUNT(*) as total_submissions,
                COUNT(DISTINCT f.participant_id) as unique_participants,
                COUNT(DISTINCT f.session_id) as sessions_with_feedback
            FROM tblfeedback f
            LEFT JOIN tblptcsession s ON f.session_id = s.session_number
            WHERE $where_clause";
    $stmt = $dbcon->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $stats = $result->fetch_assoc();
    $stmt->close();

    // Query 2: Demographics (Combined - removed unnecessary join)
    $sql = "SELECT f.information, f.purpose_attend, f.category
            FROM tblfeedback f";

    // Only add session filter if needed
    $demo_where = ["1=1"];
    $demo_params = [];
    $demo_types = "";

    if ($session_filter !== "all" && !empty($session_filter)) {
        $demo_where[] = "f.session_id = ?";
        $demo_params[] = $session_filter;
        $demo_types .= "i";
    }

    if (!empty($date_from)) {
        $demo_where[] = "f.feedback_id >= ?";
        $demo_params[] = $date_from;
        $demo_types .= "s";
    }

    if (!empty($date_to)) {
        $demo_where[] = "f.feedback_id <= ?";
        $demo_params[] = $date_to;
        $demo_types .= "s";
    }

    // Only join if year filter is used
    if ($year_filter !== "all" && !empty($year_filter)) {
        $sql .= " LEFT JOIN tblptcsession s ON f.session_id = s.session_number";
        $demo_where[] = "YEAR(s.session_date) = ?";
        $demo_params[] = $year_filter;
        $demo_types .= "i";
    }

    $sql .= " WHERE " . implode(" AND ", $demo_where);

    $stmt = $dbcon->prepare($sql);
    if (!empty($demo_params)) {
        $stmt->bind_param($demo_types, ...$demo_params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $info_source = [];
    $attendance_reason = [];
    $categories = [];

    while ($row = $result->fetch_assoc()) {
        if (!empty($row["information"])) {
            $info_source[$row["information"]] =
                ($info_source[$row["information"]] ?? 0) + 1;
        }
        if (!empty($row["purpose_attend"])) {
            $attendance_reason[$row["purpose_attend"]] =
                ($attendance_reason[$row["purpose_attend"]] ?? 0) + 1;
        }
        if (!empty($row["category"])) {
            $categories[$row["category"]] =
                ($categories[$row["category"]] ?? 0) + 1;
        }
    }
    $stmt->close();

    $info_source = array_map(
        fn($k, $v) => ["information" => $k, "count" => $v],
        array_keys($info_source),
        $info_source,
    );
    $attendance_reason = array_map(
        fn($k, $v) => ["purpose_attend" => $k, "count" => $v],
        array_keys($attendance_reason),
        $attendance_reason,
    );
    $categories = array_map(
        fn($k, $v) => ["category" => $k, "count" => $v],
        array_keys($categories),
        $categories,
    );

    usort($info_source, fn($a, $b) => $b["count"] - $a["count"]);
    usort($attendance_reason, fn($a, $b) => $b["count"] - $a["count"]);
    usort($categories, fn($a, $b) => $b["count"] - $a["count"]);

    // Query 3: Satisfaction Ratings
    $sql = "SELECT cr.q1, cr.q2, cr.q3, cr.q4, cr.q5
            FROM tblconrating cr
            INNER JOIN tblfeedback f ON cr.session_id = f.session_id AND cr.participant_id = f.participant_id
            LEFT JOIN tblptcsession s ON f.session_id = s.session_number
            WHERE $where_clause";
    $stmt = $dbcon->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $satisfaction_avg = [
        "avg_q1" => 0,
        "avg_q2" => 0,
        "avg_q3" => 0,
        "avg_q4" => 0,
        "avg_q5" => 0,
    ];
    $satisfaction_distribution = [
        "q1" => [],
        "q2" => [],
        "q3" => [],
        "q4" => [],
        "q5" => [],
    ];
    $total_ratings = 0;

    while ($row = $result->fetch_assoc()) {
        $total_ratings++;
        for ($i = 1; $i <= 5; $i++) {
            $q = "q$i";
            $satisfaction_avg["avg_$q"] += $row[$q];
            $satisfaction_distribution[$q][$row[$q]] =
                ($satisfaction_distribution[$q][$row[$q]] ?? 0) + 1;
        }
    }
    $stmt->close();

    if ($total_ratings > 0) {
        foreach ($satisfaction_avg as $key => $sum) {
            $satisfaction_avg[$key] = $sum / $total_ratings;
        }
    }

    foreach ($satisfaction_distribution as $q => $dist) {
        $satisfaction_distribution[$q] = array_map(
            fn($rating, $count) => ["rating" => $rating, "count" => $count],
            array_keys($dist),
            $dist,
        );
        usort(
            $satisfaction_distribution[$q],
            fn($a, $b) => $b["rating"] - $a["rating"],
        );
    }
    $satisfaction_avg["total_ratings"] = $total_ratings;

    // Query 4: Speaker Ratings
    $sql = "SELECT sp.speaker_Name, sr.q1, sr.q2, sr.q3, sr.q4
            FROM tblspeaker_rating sr
            INNER JOIN tblspeakerses sp ON sr.speaker_id = sp.speaker_id
            INNER JOIN tblfeedback f ON sr.session_id = f.session_id AND sr.participant_id = f.participant_id
            LEFT JOIN tblptcsession s ON f.session_id = s.session_number
            WHERE $where_clause";
    $stmt = $dbcon->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $speaker_data = [];
    while ($row = $result->fetch_assoc()) {
        $name = $row["speaker_Name"];
        if (!isset($speaker_data[$name])) {
            $speaker_data[$name] = [
                "speaker_Name" => $name,
                "q1" => 0,
                "q2" => 0,
                "q3" => 0,
                "q4" => 0,
                "count" => 0,
            ];
        }
        $speaker_data[$name]["q1"] += $row["q1"];
        $speaker_data[$name]["q2"] += $row["q2"];
        $speaker_data[$name]["q3"] += $row["q3"];
        $speaker_data[$name]["q4"] += $row["q4"];
        $speaker_data[$name]["count"]++;
    }
    $stmt->close();

    $speaker_ratings = [];
    foreach ($speaker_data as $data) {
        $count = $data["count"];
        $speaker_ratings[] = [
            "speaker_Name" => $data["speaker_Name"],
            "avg_q1" => $data["q1"] / $count,
            "avg_q2" => $data["q2"] / $count,
            "avg_q3" => $data["q3"] / $count,
            "avg_q4" => $data["q4"] / $count,
            "overall_avg" =>
                ($data["q1"] + $data["q2"] + $data["q3"] + $data["q4"]) /
                (4 * $count),
            "rating_count" => $count,
        ];
    }
    usort(
        $speaker_ratings,
        fn($a, $b) => $b["overall_avg"] <=> $a["overall_avg"],
    );

    // Query 5: Checkbox Responses (Optimized - removed unnecessary join when possible)
    $sql = "SELECT f.checkbox_q1, f.checkbox_q2, f.checkbox_q3, f.checkbox_q4, f.comment
            FROM tblfeedback f";

    // Build where clause for checkbox query
    $cb_where = ["1=1"];
    $cb_params = [];
    $cb_types = "";

    if ($session_filter !== "all" && !empty($session_filter)) {
        $cb_where[] = "f.session_id = ?";
        $cb_params[] = $session_filter;
        $cb_types .= "i";
    }

    if (!empty($date_from)) {
        $cb_where[] = "f.feedback_id >= ?";
        $cb_params[] = $date_from;
        $cb_types .= "s";
    }

    if (!empty($date_to)) {
        $cb_where[] = "f.feedback_id <= ?";
        $cb_params[] = $date_to;
        $cb_types .= "s";
    }

    // Only join if year filter is used
    if ($year_filter !== "all" && !empty($year_filter)) {
        $sql .= " LEFT JOIN tblptcsession s ON f.session_id = s.session_number";
        $cb_where[] = "YEAR(s.session_date) = ?";
        $cb_params[] = $year_filter;
        $cb_types .= "i";
    }

    // Add condition to filter only rows with at least one non-empty field
    $cb_where[] = "(f.checkbox_q1 IS NOT NULL OR f.checkbox_q2 IS NOT NULL OR
                    f.checkbox_q3 IS NOT NULL OR f.checkbox_q4 IS NOT NULL OR
                    f.comment IS NOT NULL)";

    $sql .= " WHERE " . implode(" AND ", $cb_where);

    $stmt = $dbcon->prepare($sql);
    if (!empty($cb_params)) {
        $stmt->bind_param($cb_types, ...$cb_params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $checkbox_responses = ["q1" => [], "q2" => [], "q3" => [], "q4" => []];
    $all_comments = [];
    $seen_q1 = $seen_q2 = $seen_q3 = $seen_q4 = $seen_comments = [];

    // Process results in batches
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $count++;

        // Process Q1
        if (
            !empty($row["checkbox_q1"]) &&
            !isset($seen_q1[$row["checkbox_q1"]])
        ) {
            if (isValidResponse($row["checkbox_q1"])) {
                $checkbox_responses["q1"][] = $row["checkbox_q1"];
                $seen_q1[$row["checkbox_q1"]] = true;
            }
        }
        // Process Q2
        if (
            !empty($row["checkbox_q2"]) &&
            !isset($seen_q2[$row["checkbox_q2"]])
        ) {
            if (isValidResponse($row["checkbox_q2"])) {
                $checkbox_responses["q2"][] = $row["checkbox_q2"];
                $seen_q2[$row["checkbox_q2"]] = true;
            }
        }
        // Process Q3
        if (
            !empty($row["checkbox_q3"]) &&
            !isset($seen_q3[$row["checkbox_q3"]])
        ) {
            if (isValidResponse($row["checkbox_q3"])) {
                $checkbox_responses["q3"][] = $row["checkbox_q3"];
                $seen_q3[$row["checkbox_q3"]] = true;
            }
        }
        // Process Q4
        if (
            !empty($row["checkbox_q4"]) &&
            !isset($seen_q4[$row["checkbox_q4"]])
        ) {
            if (isValidResponse($row["checkbox_q4"])) {
                $checkbox_responses["q4"][] = $row["checkbox_q4"];
                $seen_q4[$row["checkbox_q4"]] = true;
            }
        }
        // Process comments
        if (
            !empty($row["comment"]) &&
            !isset($seen_comments[$row["comment"]])
        ) {
            if (isValidResponse($row["comment"])) {
                $all_comments[] = $row["comment"];
                $seen_comments[$row["comment"]] = true;
            }
        }

        // Free memory every 1000 rows
        if ($count % 1000 == 0) {
            gc_collect_cycles();
        }
    }
    $stmt->close();

    $checkbox_counts = [
        "q1" => count($checkbox_responses["q1"]),
        "q2" => count($checkbox_responses["q2"]),
        "q3" => count($checkbox_responses["q3"]),
        "q4" => count($checkbox_responses["q4"]),
    ];
    $total_unique_comments = count($all_comments);
    $recent_comments = array_slice($all_comments, 0, 10);

    // Query 6: Session Breakdown
    $sql = "SELECT s.session_title, f.session_id, COUNT(*) as feedback_count,
                AVG((cr.q1 + cr.q2 + cr.q3 + cr.q4 + cr.q5) / 5) as avg_satisfaction
            FROM tblfeedback f
            INNER JOIN tblptcsession s ON f.session_id = s.session_number
            LEFT JOIN tblconrating cr ON f.session_id = cr.session_id AND f.participant_id = cr.participant_id
            WHERE $where_clause
            GROUP BY f.session_id, s.session_title
            ORDER BY feedback_count DESC";
    $stmt = $dbcon->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $session_breakdown = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Cache Results
    $cache_data = compact(
        "stats",
        "info_source",
        "attendance_reason",
        "categories",
        "satisfaction_avg",
        "satisfaction_distribution",
        "speaker_ratings",
        "checkbox_responses",
        "checkbox_counts",
        "all_comments",
        "recent_comments",
        "total_unique_comments",
        "session_breakdown",
    );
    file_put_contents($cache_file, serialize($cache_data));
}

// Get Filter Data
$sql = "SELECT session_number, session_title, YEAR(session_date) as year
        FROM tblptcsession ORDER BY session_date DESC, session_number DESC";
$result = $dbcon->query($sql);
$all_sessions = $result->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT DISTINCT YEAR(session_date) as year FROM tblptcsession
        WHERE session_date IS NOT NULL ORDER BY year DESC";
$result = $dbcon->query($sql);
$available_years = array_column($result->fetch_all(MYSQLI_ASSOC), "year");

$dbcon->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f5f7fa; }
        .stat-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px; }
        .stat-number { font-size: 2.5rem; font-weight: 700; color: #0d1e53; }
        .stat-label { color: #6c757d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .section-title { color: #0d1e53; font-weight: 600; margin-bottom: 20px; font-size: 1.5rem; }
        .rating-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; }
        .rating-excellent { background: #d4edda; color: #155724; }
        .rating-good { background: #d1ecf1; color: #0c5460; }
        .rating-average { background: #fff3cd; color: #856404; }
        .rating-poor { background: #f8d7da; color: #721c24; }
        .filter-section { background: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .cache-badge { position: fixed; bottom: 20px; right: 20px; background: #28a745; color: white; padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    </style>
</head>
<body>

<?php if ($use_cache && file_exists($cache_file)): ?>
<div class="cache-badge">⚡ Cached (Refresh in <?= max(
    0,
    $cache_duration - (time() - filemtime($cache_file)),
) ?>s)</div>
<?php endif; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0">📊 Feedback Analytics Dashboard</h1>
                    <p class="text-muted">Comprehensive insights from participant feedback</p>
                </div>
                <div>
                    <a href="?refresh=1&session_id=<?= $session_filter ?>&year=<?= $year_filter ?>&date_from=<?= $date_from ?>&date_to=<?= $date_to ?>"
                       class="btn btn-outline-primary btn-sm me-2">🔄 Refresh Data</a>
                    <a href="summary_report.php?session_id=<?= $session_filter ?>&year=<?= $year_filter ?>&date_from=<?= $date_from ?>&date_to=<?= $date_to ?>"
                       class="btn btn-primary" target="_blank">📄 Generate Executive Summary</a>
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
                    <option value="all" <?= $year_filter === "all"
                        ? "selected"
                        : "" ?>>All Years</option>
                    <?php foreach ($available_years as $year): ?>
                        <option value="<?= $year ?>" <?= $year_filter == $year
    ? "selected"
    : "" ?>><?= $year ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Session</label>
                <select name="session_id" class="form-select">
                    <option value="all" <?= $session_filter === "all"
                        ? "selected"
                        : "" ?>>All Sessions</option>
                    <?php foreach ($all_sessions as $session): ?>
                        <option value="<?= $session[
                            "session_number"
                        ] ?>" <?= $session_filter == $session["session_number"]
    ? "selected"
    : "" ?>>
                            <?= htmlspecialchars(
                                $session["session_title"],
                            ) ?> <?= isset($session["year"])
     ? "(" . $session["year"] . ")"
     : "" ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Feedback ID From</label>
                <input type="text" name="date_from" class="form-control" value="<?= htmlspecialchars(
                    $date_from,
                ) ?>" placeholder="e.g., 1">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Feedback ID To</label>
                <input type="text" name="date_to" class="form-control" value="<?= htmlspecialchars(
                    $date_to,
                ) ?>" placeholder="e.g., 100">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    <!-- OVERVIEW STATISTICS -->
    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number"><?= number_format(
                    $stats["total_submissions"],
                ) ?></div>
                <div class="stat-label">Total Feedback Submissions</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number"><?= number_format(
                    $stats["unique_participants"],
                ) ?></div>
                <div class="stat-label">Unique Participants</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number"><?= number_format(
                    $stats["sessions_with_feedback"],
                ) ?></div>
                <div class="stat-label">Sessions With Feedback</div>
            </div>
        </div>
    </div>

    <!-- DEMOGRAPHICS -->
    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <h3 class="section-title">Information Source</h3>
                <canvas id="infoSourceChart"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h3 class="section-title">Attendance Reason</h3>
                <canvas id="attendanceReasonChart"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h3 class="section-title">Participant Categories</h3>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- SATISFACTION -->
    <div class="row">
        <div class="col-12">
            <div class="stat-card">
                <h3 class="section-title">Client Satisfaction Measurement</h3>
                <p class="text-muted">Average ratings across all criteria (Scale: 1-5)</p>
                <div class="row mb-4">
                    <?php
                    $questions = [
                        "q1" => "Met Expectations",
                        "q2" => "Conducive to Learning",
                        "q3" => "Easy to Understand",
                        "q4" => "Met Intended Goals",
                        "q5" => "Overall Satisfaction",
                    ];
                    $q_num = 1;
                    foreach ($questions as $key => $label):
                        $avg = round($satisfaction_avg["avg_$key"] ?? 0, 2); ?>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><strong>Q<?= $q_num ?>:</strong> <?= $label ?></span>
                            <span class="rating-badge <?= getRatingClass(
                                $avg,
                            ) ?>"><?= number_format($avg, 2) ?>/5</span>
                        </div>
                    </div>
                    <?php $q_num++;
                    endforeach;
                    ?>
                </div>
                <canvas id="satisfactionChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>

    <!-- SPEAKER RATINGS -->
    <?php if (!empty($speaker_ratings)): ?>
    <div class="row">
        <div class="col-12">
            <div class="stat-card">
                <h3 class="section-title">Speaker Performance Analysis</h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Speaker Name</th>
                                <th class="text-center">Q1: Met Expectations</th>
                                <th class="text-center">Q2: Conducive Learning</th>
                                <th class="text-center">Q3: Understandable</th>
                                <th class="text-center">Q4: Overall Performance</th>
                                <th class="text-center">Overall Average</th>
                                <th class="text-center">Rating Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($speaker_ratings as $speaker): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars(
                                    $speaker["speaker_Name"],
                                ) ?></strong></td>
                                <td class="text-center"><?= number_format(
                                    $speaker["avg_q1"],
                                    2,
                                ) ?></td>
                                <td class="text-center"><?= number_format(
                                    $speaker["avg_q2"],
                                    2,
                                ) ?></td>
                                <td class="text-center"><?= number_format(
                                    $speaker["avg_q3"],
                                    2,
                                ) ?></td>
                                <td class="text-center"><?= number_format(
                                    $speaker["avg_q4"],
                                    2,
                                ) ?></td>
                                <td class="text-center">
                                    <span class="rating-badge <?= getRatingClass(
                                        $speaker["overall_avg"],
                                    ) ?>">
                                        <?= number_format(
                                            $speaker["overall_avg"],
                                            2,
                                        ) ?>
                                    </span>
                                </td>
                                <td class="text-center"><?= $speaker[
                                    "rating_count"
                                ] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- SESSION BREAKDOWN -->
    <?php if (!empty($session_breakdown)): ?>
    <div class="row">
        <div class="col-12">
            <div class="stat-card">
                <h3 class="section-title">Session-wise Performance</h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Session Title</th>
                                <th class="text-center">Feedback Count</th>
                                <th class="text-center">Average Satisfaction</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($session_breakdown as $session): ?>
                            <tr>
                                <td><?= htmlspecialchars(
                                    $session["session_title"],
                                ) ?></td>
                                <td class="text-center"><?= $session[
                                    "feedback_count"
                                ] ?></td>
                                <td class="text-center">
                                    <?php if ($session["avg_satisfaction"]): ?>
                                    <span class="rating-badge <?= getRatingClass(
                                        $session["avg_satisfaction"],
                                    ) ?>">
                                        <?= number_format(
                                            $session["avg_satisfaction"],
                                            2,
                                        ) ?>/5
                                    </span>
                                    <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- FINAL THOUGHTS -->
    <div class="row">
        <div class="col-md-6">
            <div class="stat-card">
                <h3 class="section-title">Final Thoughts Response Rate</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Ideas for Implementation</span>
                        <strong><?= $checkbox_counts["q1"] ?> responses</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Something New Learned</span>
                        <strong><?= $checkbox_counts["q2"] ?> responses</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Collaboration Possibilities</span>
                        <strong><?= $checkbox_counts["q3"] ?> responses</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Future Topics Suggestions</span>
                        <strong><?= $checkbox_counts["q4"] ?> responses</strong>
                    </li>
                </ul>
                <div class="mt-3 text-center">
                    <a href="?export=final_thoughts_full&session_id=<?= $session_filter ?>&year=<?= $year_filter ?>&date_from=<?= $date_from ?>&date_to=<?= $date_to ?>"
                       class="btn btn-success btn-sm">📥 Export Final Thoughts Data</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <h3 class="section-title">Recent Unique Comments (<?= $total_unique_comments ?> total)</h3>
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($recent_comments)): ?>
                        <?php foreach ($recent_comments as $comment): ?>
                        <div class="border-start border-3 border-primary ps-3 mb-3">
                            <p class="mb-1"><?= htmlspecialchars(
                                $comment,
                            ) ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">No comments available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAILED FINAL THOUGHTS RESPONSES -->
    <div class="row">
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="section-title mb-0">Final Thoughts - All Unique Responses</h3>
                    <a href="?export=final_thoughts_full&session_id=<?= $session_filter ?>&year=<?= $year_filter ?>&date_from=<?= $date_from ?>&date_to=<?= $date_to ?>"
                       class="btn btn-primary btn-sm">📄 Export All Responses to CSV</a>
                </div>

                <div class="alert alert-success">
                    <strong>✓ Showing only MEANINGFUL responses</strong> - Automatically filtered out:
                    <ul class="mb-0 mt-2">
                        <li>Responses with less than 4 words (e.g., "none", "nothing", "ok lang")</li>
                        <li>Common empty responses (none, n/a, na, wala, etc.)</li>
                        <li>Duplicate responses</li>
                    </ul>
                    Perfect for presenting quality feedback!
                </div>

                <!-- Q1: Ideas for Implementation -->
                <div class="mb-4">
                    <h5 class="text-primary">Q1: I gained ideas that could be implemented in my area of practice (what?)</h5>
                    <p class="text-muted mb-2"><strong><?= number_format(
                        $checkbox_counts["q1"],
                    ) ?></strong> unique responses</p>
                    <button class="btn btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQ1">
                        👁️ View All Unique Responses
                    </button>
                    <div class="collapse mt-3" id="collapseQ1">
                        <div style="max-height: 500px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                            <?php if (empty($checkbox_responses["q1"])): ?>
                                <p class="text-muted text-center">No responses found.</p>
                            <?php else: ?>
                                <?php foreach (
                                    $checkbox_responses["q1"]
                                    as $index => $response
                                ): ?>
                                <div class="bg-white p-3 mb-2 rounded border">
                                    <div class="d-flex align-items-start">
                                        <span class="badge bg-primary me-2"><?= $index +
                                            1 ?></span>
                                        <p class="mb-0"><?= nl2br(
                                            htmlspecialchars($response),
                                        ) ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q2: Something New Learned -->
                <div class="mb-4">
                    <h5 class="text-primary">Q2: I learned something new (what?)</h5>
                    <p class="text-muted mb-2"><strong><?= number_format(
                        $checkbox_counts["q2"],
                    ) ?></strong> unique responses</p>
                    <button class="btn btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQ2">
                        👁️ View All Unique Responses
                    </button>
                    <div class="collapse mt-3" id="collapseQ2">
                        <div style="max-height: 500px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                            <?php if (empty($checkbox_responses["q2"])): ?>
                                <p class="text-muted text-center">No responses found.</p>
                            <?php else: ?>
                                <?php foreach (
                                    $checkbox_responses["q2"]
                                    as $index => $response
                                ): ?>
                                <div class="bg-white p-3 mb-2 rounded border">
                                    <div class="d-flex align-items-start">
                                        <span class="badge bg-primary me-2"><?= $index +
                                            1 ?></span>
                                        <p class="mb-0"><?= nl2br(
                                            htmlspecialchars($response),
                                        ) ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q3: Collaboration Possibilities -->
                <div class="mb-4">
                    <h5 class="text-primary">Q3: I can see possibilities for collaboration</h5>
                    <p class="text-muted mb-2"><strong><?= number_format(
                        $checkbox_counts["q3"],
                    ) ?></strong> unique responses</p>
                    <button class="btn btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQ3">
                        👁️ View All Unique Responses
                    </button>
                    <div class="collapse mt-3" id="collapseQ3">
                        <div style="max-height: 500px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                            <?php if (empty($checkbox_responses["q3"])): ?>
                                <p class="text-muted text-center">No responses found.</p>
                            <?php else: ?>
                                <?php foreach (
                                    $checkbox_responses["q3"]
                                    as $index => $response
                                ): ?>
                                <div class="bg-white p-3 mb-2 rounded border">
                                    <div class="d-flex align-items-start">
                                        <span class="badge bg-primary me-2"><?= $index +
                                            1 ?></span>
                                        <p class="mb-0"><?= nl2br(
                                            htmlspecialchars($response),
                                        ) ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q4: Future Topics -->
                <div class="mb-4">
                    <h5 class="text-primary">Q4: What topics would you like to hear in the future?</h5>
                    <p class="text-muted mb-2"><strong><?= number_format(
                        $checkbox_counts["q4"],
                    ) ?></strong> unique responses</p>
                    <button class="btn btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQ4">
                        👁️ View All Unique Responses
                    </button>
                    <div class="collapse mt-3" id="collapseQ4">
                        <div style="max-height: 500px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                            <?php if (empty($checkbox_responses["q4"])): ?>
                                <p class="text-muted text-center">No responses found.</p>
                            <?php else: ?>
                                <?php foreach (
                                    $checkbox_responses["q4"]
                                    as $index => $response
                                ): ?>
                                <div class="bg-white p-3 mb-2 rounded border">
                                    <div class="d-flex align-items-start">
                                        <span class="badge bg-primary me-2"><?= $index +
                                            1 ?></span>
                                        <p class="mb-0"><?= nl2br(
                                            htmlspecialchars($response),
                                        ) ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Additional Comments Section -->
                <div class="mb-4">
                    <h5 class="text-primary">Additional Comments: Is there anything else you think we should know?</h5>
                    <p class="text-muted mb-2"><strong><?= number_format(
                        $total_unique_comments,
                    ) ?></strong> unique comments</p>
                    <button class="btn btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComments">
                        👁️ View All Unique Comments
                    </button>
                    <div class="collapse mt-3" id="collapseComments">
                        <div style="max-height: 500px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                            <?php if (empty($all_comments)): ?>
                                <p class="text-muted text-center">No comments found.</p>
                            <?php else: ?>
                                <?php foreach (
                                    $all_comments
                                    as $index => $comment
                                ): ?>
                                <div class="bg-white p-3 mb-2 rounded border">
                                    <div class="d-flex align-items-start">
                                        <span class="badge bg-primary me-2"><?= $index +
                                            1 ?></span>
                                        <p class="mb-0"><?= nl2br(
                                            htmlspecialchars($comment),
                                        ) ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Information Source Chart
const infoCtx = document.getElementById('infoSourceChart').getContext('2d');
new Chart(infoCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($info_source, "information")) ?>,
        datasets: [{
            data: <?= json_encode(array_column($info_source, "count")) ?>,
            backgroundColor: ['#0d1e53', '#1e3a8a', '#3b82f6', '#60a5fa', '#93c5fd']
        }]
    },
    options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } }
});

// Attendance Reason Chart
const attendCtx = document.getElementById('attendanceReasonChart').getContext('2d');
new Chart(attendCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(
            array_column($attendance_reason, "purpose_attend"),
        ) ?>,
        datasets: [{
            data: <?= json_encode(array_column($attendance_reason, "count")) ?>,
            backgroundColor: ['#10b981', '#059669', '#047857', '#065f46']
        }]
    },
    options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } }
});

// Category Chart
const catCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(catCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($categories, "category")) ?>,
        datasets: [{
            data: <?= json_encode(array_column($categories, "count")) ?>,
            backgroundColor: ['#f59e0b', '#d97706', '#b45309', '#92400e']
        }]
    },
    options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } }
});

// Satisfaction Chart
const satCtx = document.getElementById('satisfactionChart').getContext('2d');
new Chart(satCtx, {
    type: 'bar',
    data: {
        labels: ['Q1: Met Expectations', 'Q2: Conducive Learning', 'Q3: Easy to Understand', 'Q4: Met Goals', 'Q5: Overall Satisfaction'],
        datasets: [{
            label: 'Average Rating',
            data: [
                <?= $satisfaction_avg["avg_q1"] ?? 0 ?>,
                <?= $satisfaction_avg["avg_q2"] ?? 0 ?>,
                <?= $satisfaction_avg["avg_q3"] ?? 0 ?>,
                <?= $satisfaction_avg["avg_q4"] ?? 0 ?>,
                <?= $satisfaction_avg["avg_q5"] ?? 0 ?>
            ],
            backgroundColor: '#0d1e53',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: { y: { beginAtZero: true, max: 5, ticks: { stepSize: 1 } } },
        plugins: { legend: { display: false } }
    }
});
</script>

</body>
</html>
