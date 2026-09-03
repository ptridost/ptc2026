<?php
session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: ../Loginn.php');
    exit();
}

require '../dbcon.php';

$sessionId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$sessionId) {
    http_response_code(400);
    exit('Invalid session ID.');
}

$sessionStmt = $dbcon->prepare('SELECT session_head, session_title, session_date, session_time FROM tblptcsession WHERE session_number = ? LIMIT 1');
$sessionStmt->bind_param('i', $sessionId);
$sessionStmt->execute();
$session = $sessionStmt->get_result()->fetch_assoc();
$sessionStmt->close();

if (!$session) {
    http_response_code(404);
    exit('Session not found.');
}

function reportRow(mysqli $dbcon, string $sql, int $sessionId): array
{
    $stmt = $dbcon->prepare($sql);
    $stmt->bind_param('i', $sessionId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?? [];
    $stmt->close();
    return $row;
}

function reportRows(mysqli $dbcon, string $sql, int $sessionId): array
{
    $stmt = $dbcon->prepare($sql);
    $stmt->bind_param('i', $sessionId);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

$overview = reportRow($dbcon, "
    SELECT
        COUNT(DISTINCT participant_id) AS attendees,
        COUNT(DISTINCT CASE WHEN Gender = 'Male' THEN participant_id END) AS male,
        COUNT(DISTINCT CASE WHEN Gender = 'Female' THEN participant_id END) AS female,
        COUNT(DISTINCT CASE WHEN Gender IS NULL OR Gender = '' OR Gender NOT IN ('Male', 'Female') THEN participant_id END) AS unspecified_gender
    FROM tblattendance
    WHERE session_id = ?
", $sessionId);

$age = reportRow($dbcon, "
    SELECT
        COUNT(DISTINCT CASE WHEN p.age = '61 and above' THEN a.participant_id END) AS age_61_plus,
        COUNT(DISTINCT CASE WHEN p.age = '51-60' THEN a.participant_id END) AS age_51_60,
        COUNT(DISTINCT CASE WHEN p.age = '41-50' THEN a.participant_id END) AS age_41_50,
        COUNT(DISTINCT CASE WHEN p.age = '31-40' THEN a.participant_id END) AS age_31_40,
        COUNT(DISTINCT CASE WHEN p.age = '21-30' THEN a.participant_id END) AS age_21_30,
        COUNT(DISTINCT CASE WHEN p.age = 'Less than 21' THEN a.participant_id END) AS age_under_21,
        COUNT(DISTINCT CASE WHEN p.age IS NULL OR p.age = '' THEN a.participant_id END) AS age_unspecified
    FROM tblattendance a
    INNER JOIN participant p ON p.id = a.participant_id
    WHERE a.session_id = ?
", $sessionId);

$profession = reportRow($dbcon, "
    SELECT
        COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemical Engineer', p.profession) OR LOWER(p.other_profession) LIKE '%chemical engineer%' THEN a.participant_id END) AS chemical_engineer,
        COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemical Technician', p.profession) THEN a.participant_id END) AS chemical_technician,
        COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemist', p.profession) THEN a.participant_id END) AS chemist,
        COUNT(DISTINCT CASE WHEN p.profession = 'Others' THEN a.participant_id END) AS others,
        COUNT(DISTINCT CASE WHEN p.profession = 'None' OR p.profession IS NULL OR p.profession = '' THEN a.participant_id END) AS none
    FROM tblattendance a
    INNER JOIN participant p ON p.id = a.participant_id
    WHERE a.session_id = ?
", $sessionId);

$feedback = reportRow($dbcon, 'SELECT COUNT(*) AS total FROM tblfeedback WHERE session_id = ?', $sessionId);
$speakers = reportRow($dbcon, 'SELECT COUNT(*) AS total FROM tblspeakerses WHERE session_id = ?', $sessionId);
$locations = reportRows($dbcon, "
    SELECT COALESCE(NULLIF(TRIM(Country), ''), 'Not specified') AS label, COUNT(DISTINCT participant_id) AS total
    FROM tblattendance
    WHERE session_id = ?
    GROUP BY COALESCE(NULLIF(TRIM(Country), ''), 'Not specified')
    ORDER BY total DESC, label ASC
", $sessionId);

$attendees = (int) ($overview['attendees'] ?? 0);
$feedbackTotal = (int) ($feedback['total'] ?? 0);
$speakerTotal = (int) ($speakers['total'] ?? 0);
$completionRate = $attendees === 0 ? 0 : (int) round(($feedbackTotal / $attendees) * 100);

$genderChart = ['labels' => ['Male', 'Female', 'Not specified'], 'data' => [(int) ($overview['male'] ?? 0), (int) ($overview['female'] ?? 0), (int) ($overview['unspecified_gender'] ?? 0)]];
$ageChart = ['labels' => ['61 and above', '51-60', '41-50', '31-40', '21-30', 'Less than 21', 'Not specified'], 'data' => [(int) ($age['age_61_plus'] ?? 0), (int) ($age['age_51_60'] ?? 0), (int) ($age['age_41_50'] ?? 0), (int) ($age['age_31_40'] ?? 0), (int) ($age['age_21_30'] ?? 0), (int) ($age['age_under_21'] ?? 0), (int) ($age['age_unspecified'] ?? 0)]];
$professionChart = ['labels' => ['Chemical engineer', 'Chemical technician', 'Chemist', 'Others', 'Not specified'], 'data' => [(int) ($profession['chemical_engineer'] ?? 0), (int) ($profession['chemical_technician'] ?? 0), (int) ($profession['chemist'] ?? 0), (int) ($profession['others'] ?? 0), (int) ($profession['none'] ?? 0)]];
$locationChart = ['labels' => array_column($locations, 'label'), 'data' => array_map('intval', array_column($locations, 'total'))];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Summary Report</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admindashboard.css">
    <link rel="stylesheet" href="nav&footer/usernav.css">
    <link rel="stylesheet" href="nav&footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --report-ink: #102a43; --report-blue: #1668b2; --report-muted: #627d98; --report-line: #d9e2ec; --report-bg: #f5f7fb; }
        body { display: flex; flex-direction: column; min-height: 100vh; background: var(--report-bg); color: var(--report-ink); }
        .report-page { flex: 1; padding: 2rem 1.5rem 3rem; }
        .report-shell { max-width: 1440px; margin: 0 auto; }
        .report-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 1.25rem; margin-bottom: 1.5rem; }
        .report-eyebrow { margin: 0 0 .45rem; color: var(--report-blue); font-size: .76rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .report-header h1 { margin: 0; color: var(--report-ink); font-size: clamp(1.75rem, 2.5vw, 2.25rem); font-weight: 750; }
        .report-header p { margin: .45rem 0 0; color: var(--report-muted); }
        .report-print { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1rem; border: 1px solid #1668b2; border-radius: 9px; color: #fff; background: #1668b2; font-size: .88rem; font-weight: 700; }
        .report-print:hover { background: #0f5594; }
        .report-metrics { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .report-metric, .report-card { border: 1px solid var(--report-line); border-radius: 16px; background: #fff; box-shadow: 0 12px 30px rgba(15, 23, 42, .06); }
        .report-metric { display: flex; align-items: center; gap: 1rem; padding: 1.25rem; }
        .report-metric__icon { display: grid; width: 46px; height: 46px; place-items: center; border-radius: 12px; color: var(--report-blue); background: #e8f1fa; font-size: 1.15rem; }
        .report-metric__label { display: block; color: var(--report-muted); font-size: .74rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
        .report-metric__value { display: block; margin-top: .2rem; color: var(--report-ink); font-size: 1.9rem; font-weight: 750; line-height: 1; }
        .report-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .report-card { overflow: hidden; }
        .report-card--wide { grid-column: span 2; }
        .report-card__head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.05rem 1.25rem; border-bottom: 1px solid var(--report-line); }
        .report-card__head h2 { margin: 0; color: var(--report-ink); font-size: 1.05rem; font-weight: 750; }
        .report-card__head span { color: var(--report-muted); font-size: .78rem; }
        .report-chart { position: relative; height: 320px; padding: 1.1rem 1.25rem 1.25rem; }
        .report-chart--bar { height: 345px; }
        .report-summary { display: grid; grid-template-columns: 1.2fr .8fr; gap: 1rem; margin-top: 1rem; }
        .report-details { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; padding: 1.25rem; }
        .report-detail { padding-right: 1rem; border-right: 1px solid var(--report-line); }
        .report-detail:last-child { padding-right: 0; border-right: 0; }
        .report-detail span { display: block; color: var(--report-muted); font-size: .76rem; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; }
        .report-detail strong { display: block; margin-top: .35rem; color: var(--report-ink); font-size: .98rem; }
        .report-location-list { max-height: 225px; overflow: auto; padding: .35rem 1.25rem 1rem; }
        .report-location-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .65rem 0; border-bottom: 1px solid #edf2f7; color: #486581; font-size: .9rem; }
        .report-location-row:last-child { border-bottom: 0; }
        .report-location-row strong { color: var(--report-ink); }
        .report-empty { display: grid; min-height: 225px; place-items: center; padding: 2rem; color: var(--report-muted); text-align: center; }
        @media (max-width: 991.98px) { .report-page { padding: 5rem 1rem 2rem; } .report-header { align-items: stretch; flex-direction: column; } .report-print { width: fit-content; } .report-metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); } .report-summary { grid-template-columns: 1fr; } }
        @media (max-width: 640px) { .report-metrics, .report-grid { grid-template-columns: 1fr; } .report-card--wide { grid-column: span 1; } .report-details { grid-template-columns: 1fr; } .report-detail { padding: 0 0 1rem; border: 0; border-bottom: 1px solid var(--report-line); } .report-detail:last-child { padding-bottom: 0; border-bottom: 0; } .report-chart, .report-chart--bar { height: 290px; } }
        @media print { body { background: #fff; } .report-page { padding: 0; } .report-print, .navbar, footer { display: none !important; } .report-card, .report-metric { box-shadow: none; } }
    </style>
</head>
<body>
<?php include 'nav&footer/usernav.php'; ?>
<main class="report-page">
    <div class="report-shell">
        <header class="report-header">
            <div>
                <p class="report-eyebrow">Attendance and feedback monitoring</p>
                <h1>Session Summary Report</h1>
                <p><?= htmlspecialchars($session['session_head'] . ' - ' . $session['session_title']) ?></p>
            </div>
            <button class="report-print" type="button" onclick="window.print()"><i class="fa-solid fa-print"></i>Print report</button>
        </header>

        <section class="report-metrics" aria-label="Session totals">
            <article class="report-metric"><span class="report-metric__icon"><i class="fa-solid fa-users"></i></span><div><span class="report-metric__label">Participants attended</span><strong class="report-metric__value"><?= number_format($attendees) ?></strong></div></article>
            <article class="report-metric"><span class="report-metric__icon"><i class="fa-solid fa-comments"></i></span><div><span class="report-metric__label">Feedback received</span><strong class="report-metric__value"><?= number_format($feedbackTotal) ?></strong></div></article>
            <article class="report-metric"><span class="report-metric__icon"><i class="fa-solid fa-microphone-lines"></i></span><div><span class="report-metric__label">Session speakers</span><strong class="report-metric__value"><?= number_format($speakerTotal) ?></strong></div></article>
            <article class="report-metric"><span class="report-metric__icon"><i class="fa-solid fa-chart-line"></i></span><div><span class="report-metric__label">Feedback rate</span><strong class="report-metric__value"><?= number_format($completionRate) ?>%</strong></div></article>
        </section>

        <section class="report-grid">
            <article class="report-card"><div class="report-card__head"><h2>Gender distribution</h2><span>Attendees</span></div><div class="report-chart"><canvas id="genderChart"></canvas></div></article>
            <article class="report-card"><div class="report-card__head"><h2>Age distribution</h2><span>Attendees</span></div><div class="report-chart"><canvas id="ageChart"></canvas></div></article>
            <article class="report-card report-card--wide"><div class="report-card__head"><h2>Professional background</h2><span>Attendees may belong to more than one profession</span></div><div class="report-chart report-chart--bar"><canvas id="professionChart"></canvas></div></article>
            <article class="report-card report-card--wide"><div class="report-card__head"><h2>Participant locations</h2><span><?= count($locations) ?> location<?= count($locations) === 1 ? '' : 's' ?> represented</span></div><?php if ($locations): ?><div class="report-chart report-chart--bar"><canvas id="locationChart"></canvas></div><?php else: ?><div class="report-empty">No participant location data is available for this session.</div><?php endif; ?></article>
        </section>

        <section class="report-summary">
            <article class="report-card"><div class="report-card__head"><h2>Session details</h2></div><div class="report-details"><div class="report-detail"><span>Session date</span><strong><?= $session['session_date'] ? date('d M Y', strtotime($session['session_date'])) : 'Not set' ?></strong></div><div class="report-detail"><span>Session time</span><strong><?= $session['session_time'] ? date('g:i A', strtotime($session['session_time'])) : 'Not set' ?></strong></div><div class="report-detail"><span>Session ID</span><strong>#<?= $sessionId ?></strong></div></div></article>
            <article class="report-card"><div class="report-card__head"><h2>Location totals</h2></div><?php if ($locations): ?><div class="report-location-list"><?php foreach ($locations as $location): ?><div class="report-location-row"><span><?= htmlspecialchars($location['label']) ?></span><strong><?= number_format((int) $location['total']) ?></strong></div><?php endforeach; ?></div><?php else: ?><div class="report-empty">No locations recorded.</div><?php endif; ?></article>
        </section>
    </div>
</main>
<?php include 'nav&footer/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    const palette = ['#1668b2', '#2f9e44', '#e8590c', '#7048e8', '#d6336c', '#0ca678', '#f08c00', '#748ffc', '#495057'];
    const genderData = <?= json_encode($genderChart, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const ageData = <?= json_encode($ageChart, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const professionData = <?= json_encode($professionChart, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const locationData = <?= json_encode($locationChart, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const commonOptions = { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16, usePointStyle: true } } } };
    const makeDoughnut = (id, data) => new Chart(document.getElementById(id), { type: 'doughnut', data: { labels: data.labels, datasets: [{ data: data.data, backgroundColor: palette, borderColor: '#fff', borderWidth: 3 }] }, options: { ...commonOptions, cutout: '62%' } });
    const makeBar = (id, data) => new Chart(document.getElementById(id), { type: 'bar', data: { labels: data.labels, datasets: [{ data: data.data, backgroundColor: data.labels.map((_, index) => palette[index % palette.length]), borderRadius: 6, maxBarThickness: 54 }] }, options: { ...commonOptions, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false }, ticks: { color: '#627d98' } }, y: { beginAtZero: true, ticks: { precision: 0, color: '#627d98' }, grid: { color: '#e9eef5' } } } } });
    makeDoughnut('genderChart', genderData);
    makeDoughnut('ageChart', ageData);
    makeBar('professionChart', professionData);
    if (locationData.labels.length) makeBar('locationChart', locationData);
</script>
</body>
</html>
