<?php
session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: ../Loginn.php');
    exit();
}

require '../dbcon.php';
require_once '../event_year.php';

$availableYears = getAvailableEventYears($dbcon);
$selectedYear = resolveEventYear($dbcon, $_GET['year'] ?? null);
$requestedSessionId = filter_input(INPUT_GET, 'session_id', FILTER_VALIDATE_INT);
$yearStart = sprintf('%d-01-01', $selectedYear);
$yearEnd = sprintf('%d-01-01', $selectedYear + 1);

$sessionStmt = $dbcon->prepare(
    'SELECT session_number, session_head, session_title, session_date
     FROM tblptcsession
     WHERE session_date >= ? AND session_date < ?
     ORDER BY session_date ASC, session_time ASC, session_number ASC'
);
$sessionStmt->bind_param('ss', $yearStart, $yearEnd);
$sessionStmt->execute();
$yearSessions = $sessionStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$sessionStmt->close();

$sessionIds = array_map(static fn(array $session): int => (int) $session['session_number'], $yearSessions);
$selectedSessionId = in_array($requestedSessionId, $sessionIds, true) ? $requestedSessionId : null;

$ratingSql = "
    SELECT
        s.session_number,
        s.session_head,
        s.session_title,
        s.session_date,
        sp.speaker_id,
        sp.speaker_Name,
        COALESCE(feedback.feedback_count, 0) AS feedback_count,
        COALESCE(ratings.rating_count, 0) AS rating_count,
        COALESCE(ratings.sum_q1 / NULLIF(feedback.feedback_count, 0), 0) AS avg_q1,
        COALESCE(ratings.sum_q2 / NULLIF(feedback.feedback_count, 0), 0) AS avg_q2,
        COALESCE(ratings.sum_q3 / NULLIF(feedback.feedback_count, 0), 0) AS avg_q3,
        COALESCE(ratings.sum_q4 / NULLIF(feedback.feedback_count, 0), 0) AS avg_q4
    FROM tblptcsession s
    INNER JOIN tblspeakerses sp ON sp.session_id = s.session_number
    LEFT JOIN (
        SELECT f.session_id, COUNT(*) AS feedback_count
        FROM tblfeedback f
        INNER JOIN tblptcsession fs ON fs.session_number = f.session_id
        WHERE fs.session_date >= ? AND fs.session_date < ?
        GROUP BY f.session_id
    ) feedback ON feedback.session_id = s.session_number
    LEFT JOIN (
        SELECT sr.session_id, sr.speaker_id,
            COUNT(DISTINCT sr.participant_id) AS rating_count,
            SUM(sr.q1) AS sum_q1, SUM(sr.q2) AS sum_q2,
            SUM(sr.q3) AS sum_q3, SUM(sr.q4) AS sum_q4
        FROM tblspeaker_rating sr
        INNER JOIN tblptcsession rs ON rs.session_number = sr.session_id
        WHERE rs.session_date >= ? AND rs.session_date < ?
        GROUP BY sr.session_id, sr.speaker_id
    ) ratings ON ratings.session_id = s.session_number AND ratings.speaker_id = sp.speaker_id
    WHERE s.session_date >= ? AND s.session_date < ?";

if ($selectedSessionId !== null) {
    $ratingSql .= ' AND s.session_number = ?';
}

$ratingSql .= ' GROUP BY s.session_number, s.session_head, s.session_title, s.session_date, sp.speaker_id, sp.speaker_Name
                ORDER BY s.session_date ASC, s.session_number ASC, sp.speaker_Name ASC';

$ratingStmt = $dbcon->prepare($ratingSql);
if ($selectedSessionId === null) {
    $ratingStmt->bind_param('ssssss', $yearStart, $yearEnd, $yearStart, $yearEnd, $yearStart, $yearEnd);
} else {
    $ratingStmt->bind_param('ssssssi', $yearStart, $yearEnd, $yearStart, $yearEnd, $yearStart, $yearEnd, $selectedSessionId);
}
$ratingStmt->execute();
$ratingRows = $ratingStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$ratingStmt->close();

$ratingsBySession = [];
foreach ($ratingRows as $rating) {
    $sessionKey = (int) $rating['session_number'];
    if (!isset($ratingsBySession[$sessionKey])) {
        $ratingsBySession[$sessionKey] = [
            'session_head' => $rating['session_head'],
            'session_title' => $rating['session_title'],
            'session_date' => $rating['session_date'],
            'speakers' => [],
        ];
    }

    $rating['overall'] = ((float) $rating['avg_q1'] + (float) $rating['avg_q2'] + (float) $rating['avg_q3'] + (float) $rating['avg_q4']) / 4;
    $ratingsBySession[$sessionKey]['speakers'][] = $rating;
}

$speakerCount = count($ratingRows);
$responseCount = array_sum(array_map(static fn(array $rating): int => (int) $rating['rating_count'], $ratingRows));
$feedbackCount = array_sum(array_map(static fn(array $rating): int => (int) $rating['feedback_count'], $ratingRows));
$overallRating = $speakerCount === 0 ? 0 : array_sum(array_map(
    static fn(array $rating): float => ((float) $rating['avg_q1'] + (float) $rating['avg_q2'] + (float) $rating['avg_q3'] + (float) $rating['avg_q4']) / 4,
    $ratingRows
)) / $speakerCount;

function ratingClass(float $rating): string
{
    if ($rating >= 4.5) return 'rating-badge--excellent';
    if ($rating >= 3.5) return 'rating-badge--good';
    if ($rating >= 2.5) return 'rating-badge--average';
    return 'rating-badge--poor';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resource Speaker Ratings</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admindashboard.css">
    <link rel="stylesheet" href="nav&footer/usernav.css">
    <link rel="stylesheet" href="nav&footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --rating-ink: #102a43; --rating-blue: #1668b2; --rating-muted: #627d98; --rating-line: #d9e2ec; --rating-bg: #f5f7fb; }
        body { display: flex; flex-direction: column; min-height: 100vh; background: var(--rating-bg); color: var(--rating-ink); }
        .speaker-rating-page { flex: 1; padding: 2rem 1.5rem 3rem; }
        .speaker-rating-shell { max-width: 1440px; margin: 0 auto; }
        .speaker-rating-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
        .speaker-rating-eyebrow { margin: 0 0 .4rem; color: var(--rating-blue); font-size: .75rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .speaker-rating-header h1 { margin: 0; color: var(--rating-ink); font-size: clamp(1.75rem, 2.5vw, 2.25rem); font-weight: 750; }
        .speaker-rating-header p { margin: .45rem 0 0; color: var(--rating-muted); }
        .speaker-rating-filter, .speaker-rating-stat, .session-rating-card { border: 1px solid var(--rating-line); border-radius: 16px; background: #fff; box-shadow: 0 12px 30px rgba(15, 23, 42, .06); }
        .speaker-rating-content { transition: opacity .15s ease; }
        .speaker-rating-content.is-loading { opacity: .55; pointer-events: none; }
        .speaker-rating-filter { display: flex; align-items: end; gap: 1rem; padding: 1rem 1.25rem; margin-bottom: 1.25rem; }
        .speaker-rating-filter__field { min-width: 190px; }
        .speaker-rating-filter label { display: block; margin-bottom: .35rem; color: #486581; font-size: .75rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
        .speaker-rating-filter select { width: 100%; min-height: 40px; border: 1px solid #bcccdc; border-radius: 8px; color: #243b53; }
        .speaker-rating-reset { padding: .62rem .85rem; color: #486581; font-size: .86rem; font-weight: 700; text-decoration: none; }
        .speaker-rating-reset:hover { color: var(--rating-blue); }
        .speaker-rating-search { display: flex; align-items: center; flex: 1; min-height: 42px; max-width: 400px; padding: 0 .85rem; border: 1px solid #bcccdc; border-radius: 8px; color: #829ab1; }
        .speaker-rating-search input { width: 100%; border: 0; outline: 0; padding-left: .7rem; color: #243b53; font-size: .9rem; }
        .speaker-rating-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
        .speaker-rating-stat { padding: 1.1rem 1.25rem; }
        .speaker-rating-stat span { display: block; color: var(--rating-muted); font-size: .74rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
        .speaker-rating-stat strong { display: block; margin-top: .35rem; color: var(--rating-ink); font-size: 1.8rem; line-height: 1; }
        .speaker-rating-stat small { display: block; margin-top: .4rem; color: var(--rating-muted); font-size: .78rem; }
        .session-rating-list { display: grid; gap: 1rem; }
        .session-rating-card { overflow: hidden; }
        .session-rating-card__head { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--rating-line); }
        .session-rating-card__head h2 { margin: 0; color: var(--rating-ink); font-size: 1.08rem; font-weight: 750; }
        .session-rating-card__head p { margin: .3rem 0 0; color: var(--rating-muted); font-size: .88rem; }
        .session-rating-card__date { padding: .35rem .6rem; border-radius: 999px; color: #1668b2; background: #e8f1fa; font-size: .75rem; font-weight: 800; white-space: nowrap; }
        .speaker-rating-table { overflow-x: auto; }
        .speaker-rating-table table { min-width: 960px; margin: 0; }
        .speaker-rating-table th { padding: .9rem .8rem; border-bottom-width: 1px; color: #486581; font-size: .72rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; white-space: normal; }
        .speaker-rating-table td { padding: .95rem .8rem; color: #243b53; vertical-align: middle; }
        .speaker-rating-table tbody tr:last-child td { border-bottom: 0; }
        .speaker-rating-name { font-weight: 750; }
        .rating-score { color: #102a43; font-variant-numeric: tabular-nums; font-weight: 700; }
        .rating-badge { display: inline-flex; min-width: 57px; justify-content: center; padding: .32rem .5rem; border-radius: 999px; font-size: .8rem; font-weight: 800; }
        .rating-badge--excellent { color: #166534; background: #dcfce7; }
        .rating-badge--good { color: #075985; background: #e0f2fe; }
        .rating-badge--average { color: #92400e; background: #fef3c7; }
        .rating-badge--poor { color: #b42318; background: #fee4e2; }
        .rating-response { color: #627d98; font-size: .84rem; font-variant-numeric: tabular-nums; }
        .speaker-rating-empty { padding: 3rem 1.5rem; border: 1px dashed #bcccdc; border-radius: 16px; color: var(--rating-muted); background: #fff; text-align: center; }
        .speaker-rating-empty i { display: block; margin-bottom: .8rem; color: #829ab1; font-size: 1.6rem; }
        .speaker-rating-empty[hidden], .session-rating-card[hidden] { display: none; }
        @media (max-width: 991.98px) { .speaker-rating-page { padding: 5rem 1rem 2rem; } .speaker-rating-header, .speaker-rating-filter { align-items: stretch; flex-direction: column; } .speaker-rating-filter__field, .speaker-rating-search { max-width: none; } .speaker-rating-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 560px) { .speaker-rating-stats { grid-template-columns: 1fr; } .session-rating-card__head { flex-direction: column; } }
    </style>
</head>
<body>
<?php include 'nav&footer/usernav.php'; ?>
<main class="speaker-rating-page">
    <div class="speaker-rating-shell">
        <header class="speaker-rating-header">
            <div>
                <p class="speaker-rating-eyebrow">Feedback evaluation</p>
                <h1>Resource Speaker Ratings</h1>
                <p>Average ratings from participant feedback for the <?= htmlspecialchars((string) $selectedYear) ?> event sessions.</p>
            </div>
        </header>

        <div class="speaker-rating-content" id="speakerRatingContent">
        <form class="speaker-rating-filter" id="speakerRatingFilter" method="get">
            <div class="speaker-rating-filter__field">
                <label for="ratingYear">Event year</label>
                <select id="ratingYear" name="year">
                    <?php foreach ($availableYears as $year): ?><option value="<?= $year ?>"<?= $selectedYear === $year ? ' selected' : '' ?>><?= $year ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="speaker-rating-filter__field">
                <label for="ratingSession">Session</label>
                <select id="ratingSession" name="session_id">
                    <option value="">All sessions in <?= $selectedYear ?></option>
                    <?php foreach ($yearSessions as $yearSession): ?><option value="<?= (int) $yearSession['session_number'] ?>"<?= $selectedSessionId === (int) $yearSession['session_number'] ? ' selected' : '' ?>><?= htmlspecialchars($yearSession['session_head']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <label class="speaker-rating-search" for="speakerSearch"><i class="fa-solid fa-magnifying-glass"></i><input id="speakerSearch" type="search" placeholder="Search a speaker or session" autocomplete="off"></label>
            <?php if ($selectedSessionId !== null): ?><a class="speaker-rating-reset" href="?year=<?= $selectedYear ?>">Clear session filter</a><?php endif; ?>
        </form>

        <section class="speaker-rating-stats" aria-label="Rating totals">
            <article class="speaker-rating-stat"><span>Sessions shown</span><strong><?= number_format(count($ratingsBySession)) ?></strong><small>With resource speakers</small></article>
            <article class="speaker-rating-stat"><span>Speakers rated</span><strong><?= number_format($speakerCount) ?></strong><small>In selected results</small></article>
            <article class="speaker-rating-stat"><span>Rating responses</span><strong><?= number_format($responseCount) ?></strong><small>Submitted speaker ratings</small></article>
            <article class="speaker-rating-stat"><span>Average rating</span><strong><?= number_format($overallRating, 2) ?></strong><small>Out of 5.00</small></article>
        </section>

        <section class="session-rating-list" id="sessionRatingList">
            <?php foreach ($ratingsBySession as $sessionNumber => $sessionRatings): ?>
                <article class="session-rating-card" data-searchable="<?= htmlspecialchars(strtolower($sessionRatings['session_head'] . ' ' . $sessionRatings['session_title'])) ?>">
                    <header class="session-rating-card__head">
                        <div><h2><?= htmlspecialchars($sessionRatings['session_head']) ?></h2><p><?= htmlspecialchars($sessionRatings['session_title']) ?></p></div>
                        <span class="session-rating-card__date"><?= $sessionRatings['session_date'] ? date('d M Y', strtotime($sessionRatings['session_date'])) : 'Date not set' ?></span>
                    </header>
                    <div class="speaker-rating-table">
                        <table class="table">
                            <thead><tr><th>Resource speaker</th><th>Knowledge and insights</th><th>Content and objectives</th><th>Time management</th><th>Response to questions</th><th>Overall</th><th>Responses</th></tr></thead>
                            <tbody>
                            <?php foreach ($sessionRatings['speakers'] as $speaker): ?>
                                <tr data-searchable="<?= htmlspecialchars(strtolower($speaker['speaker_Name'])) ?>">
                                    <td class="speaker-rating-name"><?= htmlspecialchars($speaker['speaker_Name']) ?></td>
                                    <td class="rating-score"><?= number_format((float) $speaker['avg_q1'], 2) ?></td>
                                    <td class="rating-score"><?= number_format((float) $speaker['avg_q2'], 2) ?></td>
                                    <td class="rating-score"><?= number_format((float) $speaker['avg_q3'], 2) ?></td>
                                    <td class="rating-score"><?= number_format((float) $speaker['avg_q4'], 2) ?></td>
                                    <td><span class="rating-badge <?= ratingClass((float) $speaker['overall']) ?>"><?= number_format((float) $speaker['overall'], 2) ?></span></td>
                                    <td class="rating-response"><?= number_format((int) $speaker['rating_count']) ?> rated / <?= number_format((int) $speaker['feedback_count']) ?> feedback</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </article>
            <?php endforeach; ?>
            <div class="speaker-rating-empty" id="noSearchResults"<?= $ratingsBySession ? ' hidden' : '' ?>><i class="fa-regular fa-star"></i>No speaker ratings were found for the selected filters.</div>
        </section>
        </div>
    </div>
</main>
<?php include 'nav&footer/footer.php'; ?>
<script>
    (function () {
        let requestController;

        function bindRatingInteractions() {
            const content = document.getElementById('speakerRatingContent');
            const form = document.getElementById('speakerRatingFilter');
            const search = document.getElementById('speakerSearch');
            const cards = Array.from(document.querySelectorAll('.session-rating-card'));
            const empty = document.getElementById('noSearchResults');

            function filterSpeakers() {
                const term = search.value.trim().toLowerCase();
                let visibleCards = 0;
                cards.forEach(function (card) {
                    const sessionMatch = card.dataset.searchable.includes(term);
                    const rows = Array.from(card.querySelectorAll('tbody tr'));
                    let visibleRows = 0;
                    rows.forEach(function (row) {
                        const matches = sessionMatch || row.dataset.searchable.includes(term);
                        row.hidden = !matches;
                        if (matches) visibleRows++;
                    });
                    card.hidden = visibleRows === 0;
                    if (visibleRows > 0) visibleCards++;
                });
                empty.hidden = visibleCards !== 0;
            }

            async function loadFilteredRatings(changedField) {
                const params = new URLSearchParams(new FormData(form));
                if (changedField === 'ratingYear') {
                    params.delete('session_id');
                }

                if (requestController) requestController.abort();
                const controller = new AbortController();
                requestController = controller;
                content.classList.add('is-loading');
                content.setAttribute('aria-busy', 'true');

                const url = new URL(window.location.href);
                url.search = params.toString();

                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        signal: controller.signal
                    });
                    if (!response.ok) throw new Error('Unable to load ratings.');

                    const documentResponse = new DOMParser().parseFromString(await response.text(), 'text/html');
                    const updatedContent = documentResponse.getElementById('speakerRatingContent');
                    if (!updatedContent) throw new Error('Ratings content was not returned.');

                    content.replaceWith(updatedContent);
                    history.pushState(null, '', url);
                    bindRatingInteractions();
                } catch (error) {
                    if (error.name !== 'AbortError') form.submit();
                } finally {
                    if (requestController === controller) {
                        const currentContent = document.getElementById('speakerRatingContent');
                        currentContent.classList.remove('is-loading');
                        currentContent.removeAttribute('aria-busy');
                    }
                }
            }

            search.addEventListener('input', filterSpeakers);
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                loadFilteredRatings();
            });
            form.querySelectorAll('select').forEach(function (select) {
                select.addEventListener('change', function () {
                    loadFilteredRatings(select.id);
                });
            });
        }

        bindRatingInteractions();
    }());
</script>
</body>
</html>
