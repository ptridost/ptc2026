<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require '../dbcon.php';
require '../event_year.php';

$searchTerm = trim($_GET['q'] ?? '');
$allowedPageSizes = [10, 25, 50];
$pageSize = (int) ($_GET['per_page'] ?? 10);
if (!in_array($pageSize, $allowedPageSizes, true)) $pageSize = 10;
$page = max(1, (int) ($_GET['page'] ?? 1));
$searchColumns = "CONCAT_WS(' ', p.id, p.firstName, p.middleName, p.lastName, p.Gender, p.Country, p.profession, p.other_profession, p.position, p.organization)";
$searchClause = $searchTerm === '' ? '' : " AND {$searchColumns} LIKE ?";
$searchValue = '%' . $searchTerm . '%';
$availableYears = getAvailableEventYears($dbcon);
$selectedYear = resolveEventYear($dbcon, $_GET['year'] ?? null);

$sessions = [];
$sessionStmt = $dbcon->prepare("SELECT session_number, session_title, session_head FROM tblptcsession WHERE YEAR(session_date) = ? ORDER BY session_date ASC, session_number ASC");
$sessionStmt->bind_param('i', $selectedYear);
$sessionStmt->execute();
$sessionResult = $sessionStmt->get_result();
while ($session = $sessionResult->fetch_assoc()) {
    $sessions[] = [
        'id' => (string) $session['session_number'],
        'number' => (string) $session['session_number'],
        'title' => $session['session_title'] ?: $session['session_head'] ?: 'Untitled session',
    ];
}
$sessionStmt->close();

$countStmt = $dbcon->prepare("SELECT COUNT(*) AS total FROM participant p WHERE p.type = 'Participant'" . $searchClause);
if ($searchTerm !== '') $countStmt->bind_param('s', $searchValue);
$countStmt->execute();
$total = (int) $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

$totalPages = max(1, (int) ceil($total / $pageSize));
$page = min($page, $totalPages);
$offset = ($page - 1) * $pageSize;
$sql = "SELECT p.id, p.firstName, p.middleName, p.lastName, p.Gender, p.Country, p.profession, p.other_profession, p.position, p.organization
        FROM participant p WHERE p.type = 'Participant'{$searchClause} ORDER BY p.lastName, p.firstName LIMIT ? OFFSET ?";
$participantStmt = $dbcon->prepare($sql);
if ($searchTerm !== '') $participantStmt->bind_param('sii', $searchValue, $pageSize, $offset);
else $participantStmt->bind_param('ii', $pageSize, $offset);
$participantStmt->execute();
$result = $participantStmt->get_result();
$participants = [];
$participantIds = [];
while ($row = $result->fetch_assoc()) {
    $id = (int) $row['id'];
    $participantIds[] = $id;
    $participants[$id] = [
        'id' => (string) $id,
        'full_name' => trim($row['firstName'] . ' ' . ($row['middleName'] ?? '') . ' ' . $row['lastName']),
        'gender' => $row['Gender'] ?? '',
        'profession' => trim(($row['profession'] ?? '') . (($row['other_profession'] ?? '') ? ' · ' . $row['other_profession'] : '')),
        'position' => $row['position'] ?? '',
        'organization' => $row['organization'] ?? '',
        'country' => $row['Country'] ?? '',
        'sessions' => [],
    ];
}
$participantStmt->close();

if ($participantIds) {
    $idList = implode(',', array_map('intval', $participantIds));
    $sessionIds = array_map(static fn(array $session): int => (int) $session['id'], $sessions);
    $sessionIdList = implode(',', $sessionIds);
    foreach ($sessionIds ? ['attendance' => 'tblattendance', 'feedback' => 'tblfeedback'] : [] as $statusKey => $table) {
        $statusResult = $dbcon->query("SELECT DISTINCT participant_id, session_id FROM {$table} WHERE participant_id IN ({$idList}) AND session_id IN ({$sessionIdList})");
        while ($status = $statusResult->fetch_assoc()) {
            $participantId = (int) $status['participant_id'];
            $sessionId = (string) $status['session_id'];
            if (!isset($participants[$participantId])) continue;
            if (!isset($participants[$participantId]['sessions'][$sessionId])) {
                $participants[$participantId]['sessions'][$sessionId] = ['attendance' => false, 'feedback' => false];
            }
            $participants[$participantId]['sessions'][$sessionId][$statusKey] = true;
        }
    }
}

echo json_encode([
    'available_years' => $availableYears,
    'selected_year' => $selectedYear,
    'sessions' => $sessions,
    'participants' => array_values($participants),
    'total' => $total,
    'page' => $page,
    'per_page' => $pageSize,
    'total_pages' => $totalPages,
    'first_record' => $total ? $offset + 1 : 0,
    'last_record' => min($offset + $pageSize, $total),
], JSON_UNESCAPED_UNICODE);
