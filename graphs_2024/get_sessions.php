<?php
// File: ./graphs_2024/get_sessions.php
header('Content-Type: application/json');

require_once(dirname(__DIR__) . '/dbcon.php');

$year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');

$sessions = [];
$sql = "SELECT session_head, session_number FROM tblptcsession WHERE YEAR(session_date) = ? ORDER BY session_number";
$stmt = $dbcon->prepare($sql);
if ($stmt) {
    // UPDATED: More compatible way to bind parameters
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $sessions[] = $row;
    }
    $stmt->close();
}

echo json_encode($sessions);