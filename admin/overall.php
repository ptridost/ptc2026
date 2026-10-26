<?php
session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: ../Loginn.php');
    exit();
}

require_once __DIR__ . '/../dbcon.php';
require_once __DIR__ . '/../event_year.php';

$availableYears = getAvailableEventYears($dbcon);
$year = resolveEventYear($dbcon, $_GET['year'] ?? null);
$sessions = [];
$sessionAttendance = [];
$sessionAttendees = [];
$yearAttendees = [];
$countryNamesByCode = [];
$countryNamesByLabel = [];

try {
    $countryResult = $dbcon->query('SELECT iso2, name FROM world.countries WHERE iso2 IS NOT NULL AND name IS NOT NULL');
    while ($countryRow = $countryResult->fetch_assoc()) {
        $countryNamesByCode[strtoupper(trim($countryRow['iso2']))] = $countryRow['name'];
        $countryNamesByLabel[strtoupper(trim($countryRow['name']))] = $countryRow['name'];
    }
} catch (mysqli_sql_exception $exception) {
    // If the optional country lookup database is unavailable, retain readable labels for common local values.
    $countryNamesByCode = ['PH' => 'Philippines', 'QA' => 'Qatar', 'AE' => 'United Arab Emirates', 'AU' => 'Australia', 'CA' => 'Canada', 'DE' => 'Germany', 'GB' => 'United Kingdom', 'SA' => 'Saudi Arabia', 'TW' => 'Taiwan', 'VN' => 'Vietnam'];
}

function summaryProfessionGroup(string $profession): string
{
    $normalized = strtolower(trim($profession));
    if (str_starts_with($normalized, 'agricultural and biosystems engine') || str_starts_with($normalized, 'agricultural & biosystems engine')) {
        return 'Agricultural and Biosystems Engineer';
    }
    return match ($normalized) {
        'chemical technician' => 'Chemical Technician',
        'chemist' => 'Chemist',
        'chemical engineer' => 'Chemical Engineer',
        'mechanical engineer' => 'Mechanical Engineer',
        'agricultural and biosystems engineer', 'agricultural & biosystems engineer' => 'Agricultural and Biosystems Engineer',
        'not specified', 'none', '' => 'Others',
        'others', 'other' => 'Others',
        default => preg_match('/engineer|engineering/i', $profession) ? 'Other Engineering' : 'Others',
    };
}

function summaryOrganizationGroup(string $organization): string
{
    $normalized = strtolower(trim($organization));
    if ($normalized === '' || in_array($normalized, ['n/a', 'na', 'none', 'not specified'], true)) {
        return 'Others';
    }
    if (preg_match('/\b(government|govt|department|bureau|agency|authority|ministry|police|public|lgu|dost|ptri|pnp|dpwh|denr|fda|bpi|pdea|emb|food and drug administration|philippine textile research institute)\b/i', $organization)) {
        return 'Government';
    }
    if (preg_match('/\b(university|universit|college|school|academe|polytechnic|biscast|msu[- ]?iit|institute of technology|uplb|tup|pup|cmu|bsu|mmsu|clsu|vsu|slu)\b/i', $organization)) {
        return 'Academe';
    }
    if (preg_match('/\b(private|corporation|corp\.?|inc\.?|company|manufactur|industry|industries|laborator|pharmaceutical|holding|enterprise|trading|foods|mills?|urc|ipi|edc)\b/i', $organization)) {
        return 'Private/Industry';
    }
    return 'Others';
}

$sessionStmt = $dbcon->prepare(
    'SELECT session_number, session_head, session_title, session_date, session_time
     FROM tblptcsession
     WHERE YEAR(session_date) = ?
     ORDER BY session_date ASC, session_time ASC, session_number ASC'
);
$sessionStmt->bind_param('i', $year);
$sessionStmt->execute();
$sessionResult = $sessionStmt->get_result();
while ($session = $sessionResult->fetch_assoc()) {
    $sessionId = (int) $session['session_number'];
    $sessions[$sessionId] = $session;
    $sessionAttendance[$sessionId] = [
        'total' => 0,
        'professions' => [],
        'professionGroups' => [],
        'organizations' => [],
        'organizationGroups' => [],
        'countries' => [],
    ];
    $sessionAttendees[$sessionId] = [];
}
$sessionStmt->close();

if ($sessions) {
    $attendanceStmt = $dbcon->prepare(
        "SELECT a.session_id, a.participant_id, a.Institution, a.Country, p.profession
         FROM tblattendance a
         INNER JOIN tblptcsession s ON s.session_number = a.session_id
         LEFT JOIN participant p ON p.id = a.participant_id
         WHERE YEAR(s.session_date) = ?
         ORDER BY a.attendance_id DESC"
    );
    $attendanceStmt->bind_param('i', $year);
    $attendanceStmt->execute();
    $attendanceResult = $attendanceStmt->get_result();

    while ($attendance = $attendanceResult->fetch_assoc()) {
        $sessionId = (int) $attendance['session_id'];
        $participantId = (int) $attendance['participant_id'];

        // Keep one attendance record per participant/session; newest wins if duplicates exist.
        if (!isset($sessionAttendees[$sessionId]) || isset($sessionAttendees[$sessionId][$participantId])) {
            continue;
        }
        $sessionAttendees[$sessionId][$participantId] = true;
        $yearAttendees[$participantId] = true;
        $sessionAttendance[$sessionId]['total']++;

        $organization = trim((string) ($attendance['Institution'] ?? ''));
        if ($organization === '' || in_array(strtolower($organization), ['n/a', 'na', 'none', 'not specified'], true)) {
            $organization = 'Others';
        }
        $sessionAttendance[$sessionId]['organizations'][$organization] = ($sessionAttendance[$sessionId]['organizations'][$organization] ?? 0) + 1;
        $organizationGroup = summaryOrganizationGroup($organization);
        $sessionAttendance[$sessionId]['organizationGroups'][$organizationGroup] = ($sessionAttendance[$sessionId]['organizationGroups'][$organizationGroup] ?? 0) + 1;

        $countryCode = strtoupper(trim((string) ($attendance['Country'] ?? '')));
        $countryRaw = trim((string) ($attendance['Country'] ?? ''));
        $country = $countryRaw === ''
            ? 'Not specified'
            : ($countryNamesByCode[$countryCode] ?? $countryNamesByLabel[$countryCode] ?? $countryRaw);
        $sessionAttendance[$sessionId]['countries'][$country] = ($sessionAttendance[$sessionId]['countries'][$country] ?? 0) + 1;

        $professionValues = array_filter(array_map('trim', explode(',', (string) ($attendance['profession'] ?? ''))));
        $availableProfessionGroups = [];
        foreach ($professionValues as $profession) {
            $availableProfessionGroups[summaryProfessionGroup($profession)] = true;
        }

        // Assign every attendee to one profession only. Engineering affiliations take priority.
        $professionPriority = [
            'Chemical Engineer',
            'Mechanical Engineer',
            'Agricultural and Biosystems Engineer',
            'Other Engineering',
            'Chemist',
            'Chemical Technician',
            'Others',
        ];
        $primaryProfession = 'Others';
        foreach ($professionPriority as $professionGroup) {
            if (isset($availableProfessionGroups[$professionGroup])) {
                $primaryProfession = $professionGroup;
                break;
            }
        }
        $sessionAttendance[$sessionId]['professions'][$primaryProfession] = ($sessionAttendance[$sessionId]['professions'][$primaryProfession] ?? 0) + 1;
        $sessionAttendance[$sessionId]['professionGroups'][$primaryProfession] = ($sessionAttendance[$sessionId]['professionGroups'][$primaryProfession] ?? 0) + 1;
    }
    $attendanceStmt->close();
}

function reportCountList(array $counts, string $emptyMessage): void
{
    if (!$counts) {
        echo '<p class="summary-empty">' . htmlspecialchars($emptyMessage, ENT_QUOTES, 'UTF-8') . '</p>';
        return;
    }
    arsort($counts, SORT_NUMERIC);
    uksort($counts, static function ($a, $b) use ($counts) {
        return ($counts[$b] <=> $counts[$a]) ?: strcasecmp($a, $b);
    });
    echo '<ul class="summary-count-list">';
    foreach ($counts as $label => $count) {
        echo '<li><span>' . htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8') . '</span><strong>' . number_format((int) $count) . '</strong></li>';
    }
    echo '</ul>';
}

if (($_GET['export'] ?? '') === 'csv') {
    $csvSafe = static function ($value): string {
        $value = (string) $value;
        return preg_match('/^\s*[=+\-@]/u', $value) ? "'" . $value : $value;
    };

    $professionColumns = [
        'Chemical Technician',
        'Chemist',
        'Chemical Engineer',
        'Mechanical Engineer',
        'Agricultural and Biosystems Engineer',
        'Other Engineering',
        'Others',
        'Others',
    ];
    $organizationColumns = ['Government', 'Academe', 'Private/Industry', 'Others'];
    $countryColumns = [];
    foreach ($sessionAttendance as $counts) {
        foreach ($counts['countries'] as $country => $_count) {
            $countryColumns[$country] = true;
        }
    }
    $countryColumns = array_keys($countryColumns);
    natcasesort($countryColumns);
    $countryColumns = array_values($countryColumns);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="overall-summary-' . $year . '.csv"');
    header('X-Content-Type-Options: nosniff');
    $output = fopen('php://output', 'wb');
    fwrite($output, "\xEF\xBB\xBF");
    $headers = ['Event year', 'Session', 'Session title', 'Date', 'Time', 'Total attendees'];
    foreach ($professionColumns as $category) {
        $headers[] = $category;
    }
    foreach ($organizationColumns as $category) {
        $headers[] = $category;
    }
    foreach ($countryColumns as $country) {
        $headers[] = $country;
    }
    fputcsv($output, array_map($csvSafe, $headers));

    foreach ($sessions as $sessionId => $session) {
        $row = [
            $year,
            $session['session_head'],
            $session['session_title'],
            $session['session_date'],
            $session['session_time'],
            $sessionAttendance[$sessionId]['total'],
        ];
        foreach ($professionColumns as $category) {
            $row[] = $sessionAttendance[$sessionId]['professionGroups'][$category] ?? 0;
        }
        foreach ($organizationColumns as $category) {
            $row[] = $sessionAttendance[$sessionId]['organizationGroups'][$category] ?? 0;
        }
        foreach ($countryColumns as $country) {
            $row[] = $sessionAttendance[$sessionId]['countries'][$country] ?? 0;
        }
        fputcsv($output, array_map($csvSafe, $row));
    }

    $uniqueAttendeeRow = [
        $year,
        'TOTAL UNIQUE ATTENDEES',
        '',
        '',
        '',
        count($yearAttendees),
    ];
    $uniqueAttendeeRow = array_merge(
        $uniqueAttendeeRow,
        array_fill(0, count($professionColumns) + count($organizationColumns) + count($countryColumns), '')
    );
    fputcsv($output, array_map($csvSafe, $uniqueAttendeeRow));

    fclose($output);
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Overall Summary Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="admindashboard.css">
    <link rel="stylesheet" href="nav&footer/usernav.css">
    <link rel="stylesheet" href="nav&footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .summary-page { min-height: calc(100vh - 4rem); padding: 2rem clamp(1rem, 3vw, 2.5rem); }
        .summary-shell { max-width: 1500px; margin: 0 auto; }
        .summary-header { display:flex; align-items:flex-end; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; }
        .summary-header h1 { margin:0; color:#102a43; font-size:clamp(1.5rem,2.5vw,2.1rem); font-weight:750; }
        .summary-header p { margin:.4rem 0 0; color:#627d98; }
        .summary-filter { display:flex; align-items:center; gap:.65rem; }
        .summary-filter label { color:#486581; font-size:.85rem; font-weight:650; }
        .summary-filter select { min-width:120px; }
        .summary-toolbar { display:flex; align-items:center; gap:.7rem; }
        .summary-export { display:inline-flex; align-items:center; gap:.5rem; padding:.62rem .9rem; border:1px solid #1668b2; border-radius:8px; color:#fff; background:#1668b2; font-size:.85rem; font-weight:650; text-decoration:none; white-space:nowrap; }
        .summary-export:hover { color:#fff; background:#0f5594; }
        .summary-card { overflow:hidden; border:1px solid #d9e2ec; border-radius:14px; background:#fff; box-shadow:0 8px 25px rgba(15,23,42,.06); }
        .summary-card__top { display:flex; justify-content:space-between; gap:1rem; padding:1rem 1.25rem; border-bottom:1px solid #e6edf4; color:#627d98; font-size:.85rem; }
        .summary-card__footer { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.25rem; border-top:2px solid #d9e2ec; color:#334e68; font-weight:700; }
        .summary-card__footer strong { color:#1668b2; font-size:1.25rem; }
        .summary-session { border-bottom:1px solid #e6edf4; }
        .summary-session:last-child { border-bottom:0; }
        .summary-session__toggle { display:grid; grid-template-columns:minmax(0,1fr) auto auto; align-items:center; gap:1rem; width:100%; padding:1rem 1.25rem; border:0; background:#fff; color:#102a43; text-align:left; }
        .summary-session__toggle:hover, .summary-session__toggle[aria-expanded="true"] { background:#f4f8fc; }
        .summary-session__identity strong,.summary-session__identity small { display:block; }
        .summary-session__identity strong { font-weight:700; }
        .summary-session__identity small { margin-top:.18rem; color:#627d98; }
        .summary-session__total { min-width:8.5rem; text-align:right; }
        .summary-session__total strong { display:block; color:#1668b2; font-size:1.2rem; }
        .summary-session__total small { color:#627d98; }
        .summary-session__chevron { color:#829ab1; transition:transform .2s; }
        .summary-session__toggle[aria-expanded="true"] .summary-session__chevron { transform:rotate(180deg); }
        .summary-session__details { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:1rem; padding:0 1.25rem 1.25rem; }
        .summary-session__details[hidden] { display:none !important; }
        .summary-section { min-width:0; padding:1rem; border:1px solid #e6edf4; border-radius:10px; background:#fbfcfe; }
        .summary-section h2 { margin:0 0 .75rem; color:#334e68; font-size:.85rem; font-weight:750; text-transform:uppercase; letter-spacing:.04em; }
        .summary-count-list { max-height:300px; overflow:auto; margin:0; padding:0; list-style:none; }
        .summary-count-list li { display:flex; justify-content:space-between; gap:.75rem; padding:.48rem 0; border-bottom:1px solid #edf2f7; color:#486581; font-size:.84rem; }
        .summary-count-list li:last-child { border-bottom:0; }
        .summary-count-list li span { overflow-wrap:anywhere; }
        .summary-count-list strong { flex:none; color:#102a43; }
        .summary-empty,.summary-no-sessions { margin:0; color:#829ab1; font-size:.88rem; }
        .summary-no-sessions { padding:2.5rem 1rem; text-align:center; }
        @media(max-width:800px) { .summary-session__details { grid-template-columns:1fr; } }
        @media(max-width:600px) { .summary-page { padding:1rem .7rem 2rem; } .summary-header { align-items:stretch; flex-direction:column; } .summary-toolbar { align-items:stretch; flex-direction:column; } .summary-filter { justify-content:space-between; } .summary-session__toggle { grid-template-columns:minmax(0,1fr) auto; padding:.9rem; } .summary-session__total { grid-column:1; grid-row:2; text-align:left; } .summary-session__chevron { grid-column:2; grid-row:1 / span 2; } .summary-session__details { padding:0 .75rem .85rem; } .summary-card__top { padding:.8rem .9rem; } }
        @media print { body.admin-shell-active { padding:0 !important; background:#fff; } .admin-sidebar,.admin-sidebar-toggle,.admin-sidebar-backdrop,.admin-footer,.summary-toolbar { display:none !important; } .summary-page { padding:0; } .summary-card { box-shadow:none; } .summary-session__details { display:grid !important; break-inside:avoid; } .summary-session__toggle { background:#fff !important; } .summary-count-list { max-height:none; overflow:visible; } }
    </style>
</head>
<body>
<?php include __DIR__ . '/nav&footer/usernav.php'; ?>
<main class="summary-page">
    <div class="summary-shell">
        <header class="summary-header">
            <div>
                <h1>Overall Summary Report</h1>
                <p>Session attendance, professions, organizations, and countries. Each attendee counts once under one profession; engineering affiliations take priority.</p>
            </div>
            <div class="summary-toolbar">
                <form class="summary-filter" method="get">
                    <label for="report-year">Event year</label>
                    <select class="form-select" id="report-year" name="year" onchange="this.form.submit()">
                        <?php foreach ($availableYears as $availableYear): ?>
                            <option value="<?= (int) $availableYear ?>" <?= $availableYear === $year ? 'selected' : '' ?>><?= (int) $availableYear ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <a class="summary-export" href="?year=<?= (int) $year ?>&amp;export=csv"><i class="fa-solid fa-download" aria-hidden="true"></i>Export CSV</a>
            </div>
        </header>

        <section class="summary-card" aria-label="Session summary">
            <div class="summary-card__top"><span><?= count($sessions) ?> session<?= count($sessions) === 1 ? '' : 's' ?> in <?= (int) $year ?></span><span>Counts are based on distinct attendees per session</span></div>
            <?php if (!$sessions): ?>
                <p class="summary-no-sessions">No sessions are available for <?= (int) $year ?>.</p>
            <?php else: ?>
                <?php foreach ($sessions as $sessionId => $session): ?>
                    <?php $detailsId = 'session-details-' . (int) $sessionId; $counts = $sessionAttendance[$sessionId]; ?>
                    <article class="summary-session">
                        <button class="summary-session__toggle" type="button" aria-expanded="false" aria-controls="<?= $detailsId ?>">
                            <span class="summary-session__identity">
                                <strong><?= htmlspecialchars((string) $session['session_head'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <small><?= htmlspecialchars((string) $session['session_title'], ENT_QUOTES, 'UTF-8') ?> · <?= $session['session_date'] ? htmlspecialchars(date('M j, Y', strtotime($session['session_date'])), ENT_QUOTES, 'UTF-8') : 'Date not set' ?><?= $session['session_time'] ? ' · ' . htmlspecialchars(date('g:i A', strtotime($session['session_time'])), ENT_QUOTES, 'UTF-8') : '' ?></small>
                            </span>
                            <span class="summary-session__total"><strong><?= number_format($counts['total']) ?></strong><small>attendees</small></span>
                            <i class="summary-session__chevron fa-solid fa-chevron-down" aria-hidden="true"></i>
                        </button>
                        <div class="summary-session__details" id="<?= $detailsId ?>" hidden>
                            <section class="summary-section"><h2>Professions</h2><?php reportCountList($counts['professions'], 'No profession data recorded.'); ?></section>
                            <section class="summary-section"><h2>Organizations</h2><?php reportCountList($counts['organizations'], 'No organization data recorded.'); ?></section>
                            <section class="summary-section"><h2>Countries</h2><?php reportCountList($counts['countries'], 'No country data recorded.'); ?></section>
                        </div>
                    </article>
                <?php endforeach; ?>
                <div class="summary-card__footer">
                    <span>Unique attendees across <?= (int) $year ?> (each participant counted once)</span>
                    <strong><?= number_format(count($yearAttendees)) ?></strong>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>
<?php include __DIR__ . '/nav&footer/footer.php'; ?>
<script>
document.querySelectorAll('.summary-session__toggle').forEach((button) => {
    button.addEventListener('click', () => {
        const expanded = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', String(!expanded));
        document.getElementById(button.getAttribute('aria-controls')).hidden = expanded;
    });
});
</script>
</body>
</html>
