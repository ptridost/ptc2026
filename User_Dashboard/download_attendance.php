<?php
date_default_timezone_set('Asia/Manila');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['participant_id'])) {
    header('Location: ../Loginn.php');
    exit();
}

require('../dbcon.php');
require_once('../event_year.php');

$participantId = (int) $_SESSION['participant_id'];
$targetYear = resolveEventYear($dbcon, $_GET['year'] ?? null, $_SESSION['congress_type'] ?? 'PTC');

$stmt = $dbcon->prepare(
    "SELECT
        s.session_head,
        s.session_title,
        s.session_date,
        s.session_time,
        a.date_submitted
     FROM tblattendance a
     INNER JOIN tblptcsession s ON s.session_number = a.session_id
     WHERE a.participant_id = ?
       AND YEAR(s.session_date) = ?
     ORDER BY s.session_date DESC, s.session_time DESC, a.date_submitted DESC"
);
$stmt->bind_param('ii', $participantId, $targetYear);
$stmt->execute();
$result = $stmt->get_result();

$filename = sprintf('attendance-%d-participant-%d.csv', $targetYear, $participantId);

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'wb');

if ($output === false) {
    $stmt->close();
    $dbcon->close();
    exit();
}

fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
fputcsv($output, ['Session', 'Title', 'Date', 'Time', 'Attendance Submitted']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['session_head'],
        $row['session_title'],
        !empty($row['session_date']) ? date('F j, Y', strtotime($row['session_date'])) : '',
        !empty($row['session_time']) ? date('g:i A', strtotime($row['session_time'])) : '',
        !empty($row['date_submitted']) ? date('F j, Y g:i A', strtotime($row['date_submitted'])) : '',
    ]);
}

fclose($output);
$stmt->close();
$dbcon->close();
exit();
