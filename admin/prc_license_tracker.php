<?php
date_default_timezone_set("Asia/Manila");
session_start();

if (empty($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

require "../dbcon.php";

// Get filter parameters
$year = $_GET['year'] ?? 2025;
$min_qualified_sessions = $_GET['min_qualified'] ?? 1;

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

// ============================================================
// 2. GET ALL QUALIFIED ATTENDANCE & FEEDBACK DATA (BOTH REQUIRED)
// ============================================================
$sql_attendance = "SELECT 
    a.participant_id,
    a.session_id,
    1 as qualified
FROM tblattendance a
INNER JOIN tblfeedback f ON a.participant_id = f.participant_id AND a.session_id = f.session_id
WHERE a.session_id IN (SELECT session_number FROM tblptcsession WHERE YEAR(session_date) = ?)";

$stmt = $dbcon->prepare($sql_attendance);
$stmt->bind_param("i", $year);
$stmt->execute();
$attendance_result = $stmt->get_result();

// Build attendance lookup array - only contains qualified sessions
$attendance_lookup = [];
$participant_qualified_count = []; // Track which participants have at least 1 qualified session
while ($row = $attendance_result->fetch_assoc()) {
    $key = $row['participant_id'] . '_' . $row['session_id'];
    $attendance_lookup[$key] = $row;
    $participant_qualified_count[$row['participant_id']] = ($participant_qualified_count[$row['participant_id']] ?? 0) + 1;
}
$stmt->close();

// ============================================================
// 3. GET PARTICIPANTS WITH LICENSES
// ============================================================
$sql_participants = "SELECT 
    p.id,
    p.firstName,
    p.middleName,
    p.lastName,
    p.Chem_prc,
    p.ChemEn_prc,
    p.ChemTech_prc,
    p.MechEn_prc,
    p.AgriEn_prc,
    p.Others_prc,
    p.other_profession
FROM participant p
WHERE (p.Chem_prc IS NOT NULL AND p.Chem_prc != '') 
   OR (p.ChemEn_prc IS NOT NULL AND p.ChemEn_prc != '')
   OR (p.ChemTech_prc IS NOT NULL AND p.ChemTech_prc != '')
   OR (p.MechEn_prc IS NOT NULL AND p.MechEn_prc != '')
   OR (p.AgriEn_prc IS NOT NULL AND p.AgriEn_prc != '')
   OR (p.Others_prc IS NOT NULL AND p.Others_prc != '')
ORDER BY p.lastName ASC, p.firstName ASC";

$stmt = $dbcon->prepare($sql_participants);
$stmt->execute();
$participants_result = $stmt->get_result();

// Explode participants by license
$exploded_participants = [];

while ($row = $participants_result->fetch_assoc()) {
    // Skip participants who don't have at least the minimum qualified sessions
    if (!isset($participant_qualified_count[$row['id']]) || $participant_qualified_count[$row['id']] < $min_qualified_sessions) {
        continue;
    }
    
    $full_name = trim($row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']);
    
    // Define profession mappings
    $licenses = [
        'Chem_prc' => 'Chemist',
        'ChemEn_prc' => 'Chemical Engineer',
        'ChemTech_prc' => 'Chemical Technician',
        'MechEn_prc' => 'Mechanical Engineer',
        'AgriEn_prc' => 'Agricultural & Biosystems Engineer',
        'Others_prc' => !empty($row['other_profession']) ? $row['other_profession'] : 'Other'
    ];
    
    // Create separate entry for each license
    foreach ($licenses as $license_field => $profession_name) {
        if (!empty($row[$license_field])) {
            $entry = [
                'participant_id' => $row['id'],
                'participant_name' => $full_name,
                'cpd_license_no' => $row[$license_field],
                'profession' => $profession_name,
                'sessions' => [],
                'total_qualified' => 0
            ];
            
            // Use lookup array - only check for qualified sessions
            foreach ($all_sessions as $session_num => $session_info) {
                $lookup_key = $row['id'] . '_' . $session_num;
                
                if (isset($attendance_lookup[$lookup_key])) {
                    $entry['sessions'][$session_num] = [
                        'qualified' => 1
                    ];
                    $entry['total_qualified']++;
                } else {
                    $entry['sessions'][$session_num] = [
                        'qualified' => 0
                    ];
                }
            }
            
            $exploded_participants[] = $entry;
        }
    }
}
$stmt->close();

// Sort participants by total_qualified sessions (highest to lowest)
usort($exploded_participants, function($a, $b) {
    // Sort by total_qualified DESC, then by name ASC
    if ($b['total_qualified'] !== $a['total_qualified']) {
        return $b['total_qualified'] - $a['total_qualified'];
    }
    return strcmp($a['participant_name'], $b['participant_name']);
});

// ============================================================
// 4. EXPORT TO CSV
// ============================================================
if (isset($_GET['export'])) {
    $filename = "PRC_License_Attendance_{$year}_" . date("Y-m-d_His") . ".csv";
    header("Content-Type: text/csv; charset=utf-8");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen("php://output", "w");
    fprintf($output, chr(0xef) . chr(0xbb) . chr(0xbf));
    
    // Create header row
    $headers = ['Participant Name', 'CPD License No.', 'Profession', 'Qualified Sessions (X/Total)', 'Qualified for Points'];
    foreach ($all_sessions as $session) {
        $header_text = '';
        if (!empty($session['session_head'])) {
            $header_text .= $session['session_head'] . ' - ';
        }
        $header_text .= $session['session_title'];
        $headers[] = $header_text;
    }
    fputcsv($output, $headers);
    
    // Data rows
    foreach ($exploded_participants as $p) {
        // Make sure we're calculating the count correctly
        $total_sessions = count($all_sessions);
        $qualified_count = isset($p['total_qualified']) ? $p['total_qualified'] : 0;
        // Add '= to prevent Excel from treating it as a date
        $count_display = '="' . $qualified_count . '/' . $total_sessions . '"';
        
        $row_data = [
            $p['participant_name'],
            $p['cpd_license_no'],
            $p['profession'],
            $count_display,  // e.g., ="9/19" to force text format in Excel
            $qualified_count > 0 ? 'YES' : 'NO'
        ];
        
        foreach ($all_sessions as $session_num => $session) {
            $row_data[] = isset($p['sessions'][$session_num]['qualified']) && $p['sessions'][$session_num]['qualified'] ? 'YES' : '';
        }
        
        fputcsv($output, $row_data);
    }
    
    fclose($output);
    exit();
}

$dbcon->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRC License Attendance Tracker <?= $year ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #f5f7fa; 
            font-size: 0.85rem;
        }
        .stat-card { 
            background: white; 
            border-radius: 12px; 
            padding: 24px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); 
            margin-bottom: 24px; 
        }
        .stat-number { 
            font-size: 2rem; 
            font-weight: 700; 
            color: #0d1e53; 
        }
        .stat-label { 
            color: #6c757d; 
            font-size: 0.9rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        .filter-section { 
            background: white; 
            padding: 20px; 
            border-radius: 12px; 
            margin-bottom: 24px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); 
        }
        .table-wrapper {
            overflow-x: auto;
            max-width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .table-wrapper::-webkit-scrollbar {
            height: 12px;
        }
        .table-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .table-wrapper::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .attendance-table {
            font-size: 0.75rem;
            white-space: nowrap;
            margin-bottom: 0;
            width: max-content;
            min-width: 100%;
        }
        .attendance-table th {
            background: #0d1e53;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 8px 4px;
            vertical-align: middle;
            text-align: center;
        }
        .attendance-table td {
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        .attendance-table .name-column {
            position: sticky;
            left: 0;
            background: white;
            z-index: 5;
            min-width: 180px;
            text-align: left;
            font-weight: 500;
        }
        .attendance-table .license-column {
            position: sticky;
            left: 180px;
            background: white;
            z-index: 5;
            min-width: 120px;
        }
        .attendance-table .profession-column {
            position: sticky;
            left: 300px;
            background: white;
            z-index: 5;
            min-width: 150px;
            text-align: left;
        }
        .check-mark {
            color: #28a745;
            font-size: 1.1rem;
        }
        .qualified-badge {
            background: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
        }
        .session-header {
            writing-mode: horizontal-tb;
            max-width: 80px;
            font-size: 0.7rem;
        }
        .profession-badge {
            font-size: 0.7rem;
            padding: 2px 6px;
        }
        tr:hover .name-column,
        tr:hover .license-column,
        tr:hover .profession-column {
            background: #f8f9fa;
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
                    <h1 class="mb-0">📋 PRC License Attendance Tracker</h1>
                    <p class="text-muted">Individual license tracking for <?= $year ?></p>
                </div>
                <div>
                    <a href="attendance_tracker.php?year=<?= $year ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Main Tracker
                    </a>
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
                    <option value="2025" <?= $year == 2025 ? 'selected' : '' ?>>2025</option>
                    <option value="2024" <?= $year == 2024 ? 'selected' : '' ?>>2024</option>
                    <option value="2023" <?= $year == 2023 ? 'selected' : '' ?>>2023</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label fw-bold">Minimum Qualified Sessions</label>
                <input type="number" name="min_qualified" class="form-control" 
                       value="<?= $min_qualified_sessions ?>" min="1" max="<?= count($all_sessions) ?>"
                       placeholder="e.g., 9">
                <small class="text-muted">Sessions with both attendance & feedback</small>
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
            </div>
            
            <div class="col-md-4 d-flex align-items-end justify-content-end">
                <a href="?export=true&year=<?= $year ?>&min_qualified=<?= $min_qualified_sessions ?>" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export to CSV
                </a>
            </div>
        </form>
    </div>

    <!-- STATISTICS -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number"><?= count($all_sessions) ?></div>
                <div class="stat-label">Total Sessions</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number"><?= count($exploded_participants) ?></div>
                <div class="stat-label">License Entries (≥<?= $min_qualified_sessions ?> qualified)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">
                    <?= count(array_filter($exploded_participants, fn($p) => $p['total_qualified'] >= $min_qualified_sessions)) ?>
                </div>
                <div class="stat-label">Licenses Meeting Criteria</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">
                    <?= count(array_unique(array_column($exploded_participants, 'participant_id'))) ?>
                </div>
                <div class="stat-label">Unique Participants</div>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE -->
    <div class="stat-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">📊 License-Based Attendance Matrix</h4>
            <span class="badge bg-info">Each license listed separately</span>
        </div>
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Filter Active:</strong> Only showing participants with at least <strong><?= $min_qualified_sessions ?></strong> qualified session(s) (both attendance AND feedback).
            Participants with multiple PRC licenses are listed separately for each license number.
            <br>
            <i class="fas fa-arrow-right mt-2 d-inline-block"></i> <strong>Scroll horizontally</strong> to see all sessions →
        </div>

        <div class="table-wrapper" style="max-height: 70vh;">
            <table class="table table-bordered table-hover attendance-table">
                <thead>
                    <tr>
                        <th class="name-column">Participant Name</th>
                        <th class="license-column">CPD License No.</th>
                        <th class="profession-column">Profession</th>
                        <th style="min-width: 120px;">Qualified Sessions</th>
                        <th style="min-width: 100px;">Qualified for Points</th>
                        
                        <?php 
                        // Add columns for each session
                        foreach ($all_sessions as $session): 
                        ?>
                        <th class="session-header" style="min-width: 140px;">
                            <div class="text-center">
                                <?php if (!empty($session['session_head'])): ?>
                                    <div class="badge bg-light text-dark mb-1" style="font-size: 0.65rem;">
                                        <?= htmlspecialchars($session['session_head']) ?>
                                    </div>
                                <?php endif; ?>
                                <strong><?= htmlspecialchars($session['session_title']) ?></strong>
                                <br>
                                <small class="text-white-50">
                                    <?= date('M j, Y', strtotime($session['session_date'])) ?>
                                </small>
                            </div>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($exploded_participants)): ?>
                        <tr>
                            <td colspan="<?= count($all_sessions) + 5 ?>" class="text-center text-muted py-4">
                                No participants found with at least <?= $min_qualified_sessions ?> qualified session(s) for <?= $year ?>
                                <br><small>Participants must have both attendance AND feedback for at least <?= $min_qualified_sessions ?> session(s) to appear here.</small>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($exploded_participants as $p): ?>
                        <tr>
                            <td class="name-column">
                                <strong><?= htmlspecialchars($p['participant_name']) ?></strong>
                            </td>
                            <td class="license-column">
                                <code class="text-primary"><?= htmlspecialchars($p['cpd_license_no']) ?></code>
                            </td>
                            <td class="profession-column">
                                <span class="badge bg-secondary profession-badge">
                                    <?= htmlspecialchars($p['profession']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php 
                                $total_sessions_count = count($all_sessions);
                                $qualified_count = isset($p['total_qualified']) ? $p['total_qualified'] : 0;
                                ?>
                                <strong class="text-primary" style="font-size: 1rem;">
                                    <?= $qualified_count ?> / <?= $total_sessions_count ?>
                                </strong>
                            </td>
                            <td class="text-center">
                                <?php if (isset($p['total_qualified']) && $p['total_qualified'] > 0): ?>
                                    <span class="qualified-badge">
                                        ✓ Yes
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <?php foreach ($all_sessions as $session_num => $session): ?>
                            <td>
                                <?php if (isset($p['sessions'][$session_num]['qualified']) && $p['sessions'][$session_num]['qualified']): ?>
                                    <span class="check-mark">✓</span>
                                <?php endif; ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- LEGEND -->
    <div class="stat-card bg-light">
        <h5 class="mb-3"><i class="fas fa-question-circle"></i> Legend</h5>
        <div class="row">
            <div class="col-md-6">
                <p><span class="check-mark">✓</span> = Participant attended AND submitted feedback (qualified for CPD points)</p>
                <p><strong>Empty cell</strong> = Did not attend OR did not submit feedback (not qualified)</p>
                <p><strong>Qualified Sessions:</strong> Shows count like "9/19" (9 qualified out of 19 total sessions)</p>
            </div>
            <div class="col-md-6">
                <p><strong>Qualified for Points:</strong> Shows total number of sessions where both attendance and feedback were completed</p>
                <p><strong>Multiple Entries:</strong> Participants with multiple PRC licenses appear once per license</p>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>