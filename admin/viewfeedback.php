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

$searchTerm = trim((string) ($_GET['q'] ?? ''));
$page = max(1, (int) ($_GET['page'] ?? 1));
$pageSize = (int) ($_GET['per_page'] ?? 15);
$allowedPageSizes = [10, 15, 25, 50];
if (!in_array($pageSize, $allowedPageSizes, true)) {
    $pageSize = 15;
}

$sessionStmt = $dbcon->prepare('SELECT session_head, session_title FROM tblptcsession WHERE session_number = ? LIMIT 1');
$sessionStmt->bind_param('i', $sessionId);
$sessionStmt->execute();
$session = $sessionStmt->get_result()->fetch_assoc();
$sessionStmt->close();

if (!$session) {
    http_response_code(404);
    exit('Session not found.');
}

$searchColumns = "CONCAT_WS(' ', Fname, Mname, Lname, email, organization, position, comment, information, fDate)";
$searchClause = $searchTerm === '' ? '' : " AND {$searchColumns} LIKE ?";
$searchValue = '%' . $searchTerm . '%';

$totalStmt = $dbcon->prepare('SELECT COUNT(DISTINCT participant_id) AS total FROM tblfeedback WHERE session_id = ?');
$totalStmt->bind_param('i', $sessionId);
$totalStmt->execute();
$totalFeedback = (int) ($totalStmt->get_result()->fetch_assoc()['total'] ?? 0);
$totalStmt->close();

$countSql = 'SELECT COUNT(*) AS total FROM tblfeedback WHERE session_id = ?' . $searchClause;
$countStmt = $dbcon->prepare($countSql);
if ($searchTerm === '') {
    $countStmt->bind_param('i', $sessionId);
} else {
    $countStmt->bind_param('is', $sessionId, $searchValue);
}
$countStmt->execute();
$filteredTotal = (int) ($countStmt->get_result()->fetch_assoc()['total'] ?? 0);
$countStmt->close();

$totalPages = max(1, (int) ceil($filteredTotal / $pageSize));
$page = min($page, $totalPages);
$offset = ($page - 1) * $pageSize;

$listSql = "SELECT fDate, Fname, Mname, Lname, email, organization, position, comment, information
    FROM tblfeedback
    WHERE session_id = ?" . $searchClause . "
    ORDER BY fDate DESC, feedback_id DESC
    LIMIT ? OFFSET ?";
$listStmt = $dbcon->prepare($listSql);
if ($searchTerm === '') {
    $listStmt->bind_param('iii', $sessionId, $pageSize, $offset);
} else {
    $listStmt->bind_param('isii', $sessionId, $searchValue, $pageSize, $offset);
}
$listStmt->execute();
$feedbackRows = $listStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$listStmt->close();

$firstRecord = $filteredTotal === 0 ? 0 : $offset + 1;
$lastRecord = $filteredTotal === 0 ? 0 : min($filteredTotal, $offset + $pageSize);

function feedbackQuery(array $overrides = []): string
{
    $params = array_merge($_GET, $overrides);
    foreach ($params as $key => $value) {
        if ($value === '' || $value === null) {
            unset($params[$key]);
        }
    }
    return htmlspecialchars(http_build_query($params));
}

function feedbackPageWindow(int $currentPage, int $totalPages): array
{
    if ($totalPages <= 7) {
        return range(1, $totalPages);
    }

    $pages = [1, $totalPages];
    for ($pageNumber = $currentPage - 1; $pageNumber <= $currentPage + 1; $pageNumber++) {
        if ($pageNumber > 1 && $pageNumber < $totalPages) {
            $pages[] = $pageNumber;
        }
    }

    $pages = array_values(array_unique($pages));
    sort($pages);
    return $pages;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback List</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admindashboard.css">
    <link rel="stylesheet" href="nav&footer/usernav.css">
    <link rel="stylesheet" href="nav&footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f5f7fb;
        }
        .feedback-page { flex: 1; padding: 2rem 1.5rem; }
        .feedback-shell { max-width: none; margin: 0; }
        .feedback-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .feedback-header h1 { margin: 0; color: #102a43; font-size: 1.9rem; font-weight: 700; }
        .feedback-header p { margin: .45rem 0 0; color: #627d98; }
        .feedback-card, .feedback-stat {
            border: 1px solid #d9e2ec;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
        }
        .feedback-stat {
            min-width: 220px;
            padding: 1.15rem 1.35rem;
            text-align: right;
        }
        .feedback-stat__label {
            display: block;
            color: #627d98;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .feedback-stat__value {
            color: #102a43;
            font-size: 2.6rem;
            font-weight: 700;
            line-height: 1;
        }
        .feedback-card { padding: 1.35rem; }
        .feedback-card.is-loading .feedback-table,
        .feedback-card.is-loading .feedback-pagination { opacity: .55; pointer-events: none; }
        .feedback-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .feedback-search {
            display: flex;
            align-items: center;
            width: min(520px, 100%);
            min-height: 46px;
            padding: 0 .9rem;
            border: 1px solid #bcccdc;
            border-radius: 10px;
            background: #fff;
        }
        .feedback-search:focus-within {
            border-color: #1668b2;
            box-shadow: 0 0 0 3px rgba(22, 104, 178, .12);
        }
        .feedback-search i { color: #829ab1; }
        .feedback-search input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            padding: 0 .75rem;
            color: #243b53;
            font-size: .92rem;
        }
        .feedback-search button {
            border: 0;
            background: transparent;
            color: #829ab1;
        }
        .feedback-table { overflow-x: auto; }
        .feedback-table table { margin-bottom: 0; min-width: 1120px; }
        .feedback-table thead th {
            border-bottom-width: 1px;
            color: #486581;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        .feedback-table tbody td {
            padding: 1rem .75rem;
            color: #243b53;
            vertical-align: top;
        }
        .feedback-table tbody tr:last-child td { border-bottom: 0; }
        .feedback-name { font-weight: 700; }
        .feedback-comment {
            max-width: 260px;
            white-space: normal;
            word-break: break-word;
        }
        .feedback-info {
            max-width: 220px;
            white-space: normal;
            word-break: break-word;
        }
        .feedback-empty {
            padding: 2.5rem 1rem;
            text-align: center;
            color: #627d98;
        }
        .feedback-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1rem;
            color: #627d98;
            font-size: .78rem;
        }
        .feedback-pagination form { margin: 0; }
        .feedback-pagination label {
            display: flex;
            align-items: center;
            gap: .55rem;
            white-space: nowrap;
        }
        .feedback-pagination select {
            width: 74px;
            border: 1px solid #bcccdc;
            border-radius: 8px;
            color: #486581;
        }
        .pagination-buttons {
            display: flex;
            align-items: center;
            gap: .35rem;
        }
        .pagination-buttons a,
        .pagination-buttons span {
            display: inline-flex;
            min-width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            padding: 0 .75rem;
            border: 1px solid #bcccdc;
            border-radius: 8px;
            color: #486581;
            background: #fff;
            font-weight: 600;
            text-decoration: none;
        }
        .pagination-buttons a:hover { color: #1668b2; border-color: #1668b2; background: #f0f7fc; }
        .pagination-buttons .active { color: #fff; border-color: #1668b2; background: #1668b2; }
        .pagination-buttons .gap { border-color: transparent; background: transparent; }
        @media (max-width: 991.98px) {
            .feedback-page { padding: 5rem 1rem 1.5rem; }
            .feedback-header, .feedback-toolbar, .feedback-pagination {
                align-items: stretch;
                flex-direction: column;
            }
            .feedback-stat { text-align: left; }
            .feedback-search { width: 100%; }
            .pagination-buttons { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
<?php include 'nav&footer/usernav.php'; ?>
<main class="feedback-page">
    <div class="feedback-shell">
        <section class="feedback-header">
            <div>
                <h1>Feedback List</h1>
                <p><?= htmlspecialchars($session['session_head'] . ' - ' . $session['session_title']) ?></p>
            </div>
            <div class="feedback-stat">
                <span class="feedback-stat__label">Total Feedback Submitted</span>
                <span class="feedback-stat__value"><?= number_format($totalFeedback) ?></span>
            </div>
        </section>

        <section class="feedback-card">
            <div class="feedback-toolbar">
                <form method="get" id="feedbackSearchForm" class="w-100">
                    <input type="hidden" name="id" value="<?= $sessionId ?>">
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="per_page" value="<?= $pageSize ?>">
                    <label class="feedback-search" for="feedbackSearch">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" id="feedbackSearch" name="q" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search participant, email, organization, position, comment, or information" autocomplete="off">
                        <button type="button" id="clearFeedbackSearch" aria-label="Clear search"<?= $searchTerm === '' ? ' hidden' : '' ?>><i class="fa-solid fa-xmark"></i></button>
                    </label>
                </form>
            </div>

            <div class="feedback-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date and Time</th>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Organization</th>
                            <th>Position</th>
                            <th>Comment</th>
                            <th>Information</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($feedbackRows): ?>
                        <?php foreach ($feedbackRows as $row): ?>
                            <tr>
                                <td><?= date('g:i A, l - d M Y', strtotime($row['fDate'])) ?></td>
                                <td class="feedback-name"><?= htmlspecialchars(trim($row['Fname'] . ' ' . $row['Mname'] . ' ' . $row['Lname'])) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['organization']) ?></td>
                                <td><?= htmlspecialchars($row['position']) ?></td>
                                <td class="feedback-comment"><?= nl2br(htmlspecialchars((string) $row['comment'])) ?></td>
                                <td class="feedback-info"><?= htmlspecialchars((string) $row['information']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="feedback-empty">No feedback records found<?= $searchTerm !== '' ? ' for this search.' : ' for this session yet.' ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="feedback-pagination">
                <div>Showing <strong><?= $firstRecord ?>-<?= $lastRecord ?></strong> of <strong><?= $filteredTotal ?></strong><?= $searchTerm !== '' ? ' matching records' : ' records' ?></div>
                <form method="get">
                    <input type="hidden" name="id" value="<?= $sessionId ?>">
                    <input type="hidden" name="q" value="<?= htmlspecialchars($searchTerm) ?>">
                    <input type="hidden" name="page" value="1">
                    <label>Rows per page
                        <select name="per_page" onchange="this.form.submit()">
                            <?php foreach ($allowedPageSizes as $size): ?>
                                <option value="<?= $size ?>"<?= $pageSize === $size ? ' selected' : '' ?>><?= $size ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </form>
                <div class="pagination-buttons">
                    <a href="?<?= feedbackQuery(['page' => max(1, $page - 1)]) ?>"<?= $page === 1 ? ' style="pointer-events:none;opacity:.45;"' : '' ?>><i class="fa-solid fa-chevron-left"></i><span>Previous</span></a>
                    <?php
                    $pages = feedbackPageWindow($page, $totalPages);
                    $previousPageInWindow = null;
                    foreach ($pages as $windowPage):
                        if ($previousPageInWindow !== null && $windowPage - $previousPageInWindow > 1): ?>
                            <span class="gap">...</span>
                        <?php endif; ?>
                        <?php if ($windowPage === $page): ?>
                            <span class="active"><?= $windowPage ?></span>
                        <?php else: ?>
                            <a href="?<?= feedbackQuery(['page' => $windowPage]) ?>"><?= $windowPage ?></a>
                        <?php endif; ?>
                    <?php
                        $previousPageInWindow = $windowPage;
                    endforeach;
                    ?>
                    <a href="?<?= feedbackQuery(['page' => min($totalPages, $page + 1)]) ?>"<?= $page === $totalPages ? ' style="pointer-events:none;opacity:.45;"' : '' ?>><span>Next</span><i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </div>
        </section>
    </div>
</main>
<?php include 'nav&footer/footer.php'; ?>
<script>
    (function () {
        const form = document.getElementById('feedbackSearchForm');
        const input = document.getElementById('feedbackSearch');
        const clear = document.getElementById('clearFeedbackSearch');
        const card = document.querySelector('.feedback-card');
        let debounceTimer;
        let searchController;

        async function submitSearch() {
            const pageInput = form.querySelector('input[name="page"]');
            const searchParams = new URLSearchParams(new FormData(form));

            pageInput.value = '1';
            searchParams.set('page', '1');

            if (searchController) {
                searchController.abort();
            }

            const controller = new AbortController();
            searchController = controller;
            card.classList.add('is-loading');

            const url = new URL(window.location.href);
            url.search = searchParams.toString();

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: controller.signal
                });

                if (!response.ok) {
                    throw new Error('Unable to load feedback records.');
                }

                const documentResponse = new DOMParser().parseFromString(await response.text(), 'text/html');
                const updatedTable = documentResponse.querySelector('.feedback-table');
                const updatedPagination = documentResponse.querySelector('.feedback-pagination');

                if (!updatedTable || !updatedPagination) {
                    throw new Error('Feedback results were not returned.');
                }

                card.querySelector('.feedback-table').replaceWith(updatedTable);
                card.querySelector('.feedback-pagination').replaceWith(updatedPagination);
                history.replaceState(null, '', url);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    form.submit();
                }
            } finally {
                if (searchController === controller) {
                    card.classList.remove('is-loading');
                }
            }
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            clearTimeout(debounceTimer);
            submitSearch();
        });

        input.addEventListener('input', function () {
            clear.hidden = input.value.length === 0;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(submitSearch, 250);
        });

        clear.addEventListener('click', function () {
            clearTimeout(debounceTimer);
            input.value = '';
            clear.hidden = true;
            input.focus();
            submitSearch();
        });
    }());
</script>
</body>
</html>
