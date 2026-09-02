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

$searchColumns = "CONCAT_WS(' ', fname, mName, lName, Gender, Country, Institution, Position, date_submitted)";
$searchClause = $searchTerm === '' ? '' : " AND {$searchColumns} LIKE ?";
$searchValue = '%' . $searchTerm . '%';

$totalStmt = $dbcon->prepare("SELECT COUNT(DISTINCT participant_id) AS total FROM tblattendance WHERE session_id = ?");
$totalStmt->bind_param('i', $sessionId);
$totalStmt->execute();
$totalParticipants = (int) ($totalStmt->get_result()->fetch_assoc()['total'] ?? 0);
$totalStmt->close();

$countSql = "SELECT COUNT(*) AS total FROM tblattendance WHERE session_id = ?" . $searchClause;
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

$listSql = "SELECT fname, mName, lName, Gender, Country, Institution, Position, date_submitted
    FROM tblattendance
    WHERE session_id = ?" . $searchClause . "
    ORDER BY date_submitted DESC, attendance_id DESC
    LIMIT ? OFFSET ?";
$listStmt = $dbcon->prepare($listSql);
if ($searchTerm === '') {
    $listStmt->bind_param('iii', $sessionId, $pageSize, $offset);
} else {
    $listStmt->bind_param('isii', $sessionId, $searchValue, $pageSize, $offset);
}
$listStmt->execute();
$attendanceRows = $listStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$listStmt->close();

$firstRecord = $filteredTotal === 0 ? 0 : $offset + 1;
$lastRecord = $filteredTotal === 0 ? 0 : min($filteredTotal, $offset + $pageSize);

function attendanceQuery(array $overrides = []): string
{
    $params = array_merge($_GET, $overrides);
    foreach ($params as $key => $value) {
        if ($value === '' || $value === null) {
            unset($params[$key]);
        }
    }
    return htmlspecialchars(http_build_query($params));
}

function pageWindow(int $currentPage, int $totalPages): array
{
    if ($totalPages <= 7) {
        return range(1, $totalPages);
    }

    $pages = [1, $totalPages];
    for ($page = $currentPage - 1; $page <= $currentPage + 1; $page++) {
        if ($page > 1 && $page < $totalPages) {
            $pages[] = $page;
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
    <title>Attendance List</title>
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
        .attendance-page { flex: 1; padding: 2rem 1.5rem; }
        .attendance-shell { max-width: none; margin: 0; }
        .attendance-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .attendance-header h1 { margin: 0; color: #102a43; font-size: 1.9rem; font-weight: 700; }
        .attendance-header p { margin: .45rem 0 0; color: #627d98; }
        .attendance-card, .attendance-stat {
            border: 1px solid #d9e2ec;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
        }
        .attendance-stat {
            min-width: 220px;
            padding: 1.15rem 1.35rem;
            text-align: right;
        }
        .attendance-stat__label {
            display: block;
            color: #627d98;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .attendance-stat__value {
            color: #102a43;
            font-size: 2.6rem;
            font-weight: 700;
            line-height: 1;
        }
        .attendance-card { padding: 1.35rem; }
        .attendance-card.is-loading .attendance-table,
        .attendance-card.is-loading .attendance-pagination { opacity: .55; pointer-events: none; }
        .attendance-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .attendance-search {
            display: flex;
            align-items: center;
            width: min(440px, 100%);
            min-height: 46px;
            padding: 0 .9rem;
            border: 1px solid #bcccdc;
            border-radius: 10px;
            background: #fff;
        }
        .attendance-search:focus-within {
            border-color: #1668b2;
            box-shadow: 0 0 0 3px rgba(22, 104, 178, .12);
        }
        .attendance-search i { color: #829ab1; }
        .attendance-search input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            padding: 0 .75rem;
            color: #243b53;
            font-size: .92rem;
        }
        .attendance-search button {
            border: 0;
            background: transparent;
            color: #829ab1;
        }
        .attendance-table { overflow-x: auto; }
        .attendance-table table { margin-bottom: 0; }
        .attendance-table thead th {
            border-bottom-width: 1px;
            color: #486581;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        .attendance-table tbody td {
            padding: 1rem .75rem;
            color: #243b53;
            vertical-align: top;
        }
        .attendance-table tbody tr:last-child td { border-bottom: 0; }
        .attendance-name { font-weight: 700; }
        .attendance-empty {
            padding: 2.5rem 1rem;
            text-align: center;
            color: #627d98;
        }
        .attendance-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1rem;
            color: #627d98;
            font-size: .78rem;
        }
        .attendance-pagination form { margin: 0; }
        .attendance-pagination label {
            display: flex;
            align-items: center;
            gap: .55rem;
            white-space: nowrap;
        }
        .attendance-pagination select {
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
            .attendance-page { padding: 5rem 1rem 1.5rem; }
            .attendance-header, .attendance-toolbar, .attendance-pagination {
                align-items: stretch;
                flex-direction: column;
            }
            .attendance-stat { text-align: left; }
            .attendance-search { width: 100%; }
            .pagination-buttons { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
<?php include 'nav&footer/usernav.php'; ?>
<main class="attendance-page">
    <div class="attendance-shell">
        <section class="attendance-header">
            <div>
                <h1>Attendance List</h1>
                <p><?= htmlspecialchars($session['session_head'] . ' - ' . $session['session_title']) ?></p>
            </div>
            <div class="attendance-stat">
                <span class="attendance-stat__label">Total Participants</span>
                <span class="attendance-stat__value"><?= number_format($totalParticipants) ?></span>
            </div>
        </section>

        <section class="attendance-card">
            <div class="attendance-toolbar">
                <form method="get" id="attendanceSearchForm" class="w-100">
                    <input type="hidden" name="id" value="<?= $sessionId ?>">
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="per_page" value="<?= $pageSize ?>">
                    <label class="attendance-search" for="attendanceSearch">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" id="attendanceSearch" name="q" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search attendee, gender, country, institution, or position" autocomplete="off">
                        <button type="button" id="clearAttendanceSearch" aria-label="Clear search"<?= $searchTerm === '' ? ' hidden' : '' ?>><i class="fa-solid fa-xmark"></i></button>
                    </label>
                </form>
            </div>

            <div class="attendance-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Country</th>
                            <th>Institution</th>
                            <th>Position</th>
                            <th>Date and Time</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($attendanceRows): ?>
                        <?php foreach ($attendanceRows as $row): ?>
                            <tr>
                                <td class="attendance-name"><?= htmlspecialchars(trim($row['fname'] . ' ' . $row['mName'] . ' ' . $row['lName'])) ?></td>
                                <td><?= htmlspecialchars($row['Gender']) ?></td>
                                <td><?= htmlspecialchars($row['Country']) ?></td>
                                <td><?= htmlspecialchars($row['Institution']) ?></td>
                                <td><?= htmlspecialchars($row['Position']) ?></td>
                                <td><?= date('g:i A, l - d M Y', strtotime($row['date_submitted'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="attendance-empty">No attendance records found<?= $searchTerm !== '' ? ' for this search.' : ' for this session yet.' ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="attendance-pagination">
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
                    <a href="?<?= attendanceQuery(['page' => max(1, $page - 1)]) ?>"<?= $page === 1 ? ' style="pointer-events:none;opacity:.45;"' : '' ?>><i class="fa-solid fa-chevron-left"></i><span>Previous</span></a>
                    <?php
                    $pages = pageWindow($page, $totalPages);
                    $previousPageInWindow = null;
                    foreach ($pages as $windowPage):
                        if ($previousPageInWindow !== null && $windowPage - $previousPageInWindow > 1): ?>
                            <span class="gap">...</span>
                        <?php endif; ?>
                        <?php if ($windowPage === $page): ?>
                            <span class="active"><?= $windowPage ?></span>
                        <?php else: ?>
                            <a href="?<?= attendanceQuery(['page' => $windowPage]) ?>"><?= $windowPage ?></a>
                        <?php endif; ?>
                    <?php
                        $previousPageInWindow = $windowPage;
                    endforeach;
                    ?>
                    <a href="?<?= attendanceQuery(['page' => min($totalPages, $page + 1)]) ?>"<?= $page === $totalPages ? ' style="pointer-events:none;opacity:.45;"' : '' ?>><span>Next</span><i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </div>
        </section>
    </div>
</main>
<?php include 'nav&footer/footer.php'; ?>
<script>
    (function () {
        const form = document.getElementById('attendanceSearchForm');
        const input = document.getElementById('attendanceSearch');
        const clear = document.getElementById('clearAttendanceSearch');
        const card = document.querySelector('.attendance-card');
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
                    throw new Error('Unable to load attendance records.');
                }

                const documentResponse = new DOMParser().parseFromString(await response.text(), 'text/html');
                const updatedTable = documentResponse.querySelector('.attendance-table');
                const updatedPagination = documentResponse.querySelector('.attendance-pagination');

                if (!updatedTable || !updatedPagination) {
                    throw new Error('Attendance results were not returned.');
                }

                card.querySelector('.attendance-table').replaceWith(updatedTable);
                card.querySelector('.attendance-pagination').replaceWith(updatedPagination);
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
