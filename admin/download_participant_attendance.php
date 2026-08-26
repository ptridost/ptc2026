<?php
session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: ../Loginn.php');
    exit();
}

require '../dbcon.php';
require_once '../event_year.php';

$participantId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$participantId) {
    http_response_code(400);
    exit('Invalid participant ID.');
}

$participantStmt = $dbcon->prepare("SELECT id, firstName, middleName, lastName, congress_type FROM participant WHERE id = ? AND type = 'Participant' LIMIT 1");
$participantStmt->bind_param('i', $participantId);
$participantStmt->execute();
$participant = $participantStmt->get_result()->fetch_assoc();
$participantStmt->close();

if (!$participant) {
    http_response_code(404);
    exit('Participant not found.');
}

$selectedYear = resolveEventYear($dbcon, $_GET['attendance_year'] ?? null, (string) ($participant['congress_type'] ?? 'PTC'));

$firstName = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($participant['firstName'] ?? ''));
$lastName = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($participant['lastName'] ?? ''));
$filenameBase = $lastName . $firstName;
if ($filenameBase === '') {
    $filenameBase = 'participant' . $participantId;
}

$attendanceStmt = $dbcon->prepare(
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
     ORDER BY s.session_number ASC, s.session_date ASC, s.session_time ASC, a.date_submitted ASC"
);
$attendanceStmt->bind_param('ii', $participantId, $selectedYear);
$attendanceStmt->execute();
$attendanceResult = $attendanceStmt->get_result();

$filename = sprintf('%s_attendance_%d.csv', $filenameBase, $selectedYear);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
if ($output === false) {
    $attendanceStmt->close();
    $dbcon->close();
    exit();
}

fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
fputcsv($output, ['Session', 'Title', 'Date', 'Time', 'Attendance Submitted']);

while ($row = $attendanceResult->fetch_assoc()) {
    fputcsv($output, [
        $row['session_head'],
        $row['session_title'],
        !empty($row['session_date']) ? date('M j, Y', strtotime($row['session_date'])) : '',
        !empty($row['session_time']) ? date('g:i A', strtotime($row['session_time'])) : '',
        !empty($row['date_submitted']) ? date('g:i A, l - d M Y', strtotime($row['date_submitted'])) : '',
    ]);
}

fclose($output);
$attendanceStmt->close();
$dbcon->close();
exit();
