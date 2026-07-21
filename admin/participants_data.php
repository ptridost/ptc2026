<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require '../dbcon.php';
$searchTerm = trim($_GET['q'] ?? '');
$allowedPageSizes = [10, 25, 50];
$pageSize = (int) ($_GET['per_page'] ?? 10);
if (!in_array($pageSize, $allowedPageSizes, true)) $pageSize = 10;
$page = max(1, (int) ($_GET['page'] ?? 1));

$searchColumns = "CONCAT_WS(' ', id, firstName, middleName, lastName, birthdate, username, profession, other_profession, Others_prc, ChemEn_prc, Chem_prc, ChemTech_prc, MechEn_prc, AgriEn_prc, organization, position)";
$searchClause = $searchTerm === '' ? '' : " AND {$searchColumns} LIKE ?";
$searchValue = '%' . $searchTerm . '%';

$countStmt = $dbcon->prepare("SELECT COUNT(*) AS total FROM participant WHERE type = 'Participant'" . $searchClause);
if ($searchTerm !== '') $countStmt->bind_param('s', $searchValue);
$countStmt->execute();
$total = (int) $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

$totalPages = max(1, (int) ceil($total / $pageSize));
$page = min($page, $totalPages);
$offset = ($page - 1) * $pageSize;
$sql = "SELECT id, firstName, middleName, lastName, birthdate, username, profession, other_profession, Others_prc, ChemEn_prc, Chem_prc, ChemTech_prc, MechEn_prc, AgriEn_prc, organization, position FROM participant WHERE type = 'Participant'" . $searchClause . " ORDER BY id DESC LIMIT ? OFFSET ?";
$dataStmt = $dbcon->prepare($sql);
if ($searchTerm !== '') $dataStmt->bind_param('sii', $searchValue, $pageSize, $offset);
else $dataStmt->bind_param('ii', $pageSize, $offset);
$dataStmt->execute();
$result = $dataStmt->get_result();
$participants = [];

while ($row = $result->fetch_assoc()) {
    $professionParts = [];
    if (!empty($row['profession'])) $professionParts[] = $row['profession'];
    if (!empty($row['other_profession'])) $professionParts[] = $row['other_profession'];
    $licenseLabels = ['Others_prc' => 'PRC', 'ChemEn_prc' => 'Chemical Engineer', 'Chem_prc' => 'Chemist', 'ChemTech_prc' => 'Chemical Technician', 'MechEn_prc' => 'Mechanical Engineer', 'AgriEn_prc' => 'Agricultural Engineer'];
    foreach ($licenseLabels as $field => $label) {
        if (!empty($row[$field])) $professionParts[] = $label . ': ' . $row[$field];
    }
    $participants[] = [
        'id' => (string) $row['id'],
        'full_name' => trim($row['firstName'] . ' ' . ($row['middleName'] ?? '') . ' ' . $row['lastName']),
        'birthdate' => $row['birthdate'] ?? '',
        'email' => $row['username'] ?? '',
        'profession' => implode(' · ', array_unique($professionParts)),
        'organization' => $row['organization'] ?? '',
        'position' => $row['position'] ?? '',
    ];
}
$dataStmt->close();

echo json_encode([
    'participants' => $participants,
    'total' => $total,
    'page' => $page,
    'per_page' => $pageSize,
    'total_pages' => $totalPages,
    'first_record' => $total ? $offset + 1 : 0,
    'last_record' => min($offset + $pageSize, $total),
], JSON_UNESCAPED_UNICODE);
