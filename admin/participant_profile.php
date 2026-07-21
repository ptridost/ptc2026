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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_participant'])) {
    $fields = ['firstName','middleName','lastName','username','birthdate','Gender','Citizenship','Country','Region','CityProvince','profession','other_profession','Others_prc','ChemEn_prc','Chem_prc','ChemTech_prc','MechEn_prc','AgriEn_prc','organization','position'];
    $values = [];
    foreach ($fields as $field) $values[] = trim((string) ($_POST[$field] ?? ''));
    $values[] = max(0, (int) ($_POST['cpd'] ?? 0));
    $values[] = $participantId;
    $updateStmt = $dbcon->prepare("UPDATE participant SET firstName=?, middleName=?, lastName=?, username=?, birthdate=?, Gender=?, Citizenship=?, Country=?, Region=?, CityProvince=?, profession=?, other_profession=?, Others_prc=?, ChemEn_prc=?, Chem_prc=?, ChemTech_prc=?, MechEn_prc=?, AgriEn_prc=?, organization=?, position=?, cpd=? WHERE id=? AND type='Participant'");
    $types = str_repeat('s', count($fields)) . 'ii';
    $updateStmt->bind_param($types, ...$values);
    $updateStmt->execute();
    $updateStmt->close();
    header('Location: participant_profile.php?id=' . $participantId . '&updated=1');
    exit();
}

$participantStmt = $dbcon->prepare("SELECT * FROM participant WHERE id = ? AND type = 'Participant' LIMIT 1");
$participantStmt->bind_param('i', $participantId);
$participantStmt->execute();
$participant = $participantStmt->get_result()->fetch_assoc();
$participantStmt->close();
if (!$participant) {
    http_response_code(404);
    exit('Participant not found.');
}

$attendanceYears = getAvailableEventYears($dbcon);
$participantYearsStmt = $dbcon->prepare("SELECT DISTINCT YEAR(s.session_date) AS attendance_year FROM tblattendance a INNER JOIN tblptcsession s ON s.session_number=a.session_id WHERE a.participant_id=? ORDER BY attendance_year DESC");
$participantYearsStmt->bind_param('i', $participantId);
$participantYearsStmt->execute();
$participantAttendanceYears = array_map('intval', array_column($participantYearsStmt->get_result()->fetch_all(MYSQLI_ASSOC), 'attendance_year'));
$participantYearsStmt->close();
$defaultAttendanceYear = $participantAttendanceYears[0] ?? getCurrentEventYear($dbcon, (string) ($participant['congress_type'] ?? 'PTC'));
$selectedYear = filter_input(INPUT_GET, 'attendance_year', FILTER_VALIDATE_INT) ?: $defaultAttendanceYear;
if ($attendanceYears && !in_array($selectedYear, $attendanceYears, true)) $selectedYear = $attendanceYears[0];

// Match the year-specific CPD calculation used by certificate generation.
$certificateCpdStmt = $dbcon->prepare("SELECT COUNT(DISTINCT a.session_id) AS sessions_count FROM tblattendance a INNER JOIN tblptcsession s ON s.session_number=a.session_id WHERE a.participant_id=? AND YEAR(a.date_submitted)=? AND LOWER(s.session_head) LIKE 'session%'");
$certificateCpdStmt->bind_param('ii', $participantId, $selectedYear);
$certificateCpdStmt->execute();
$certificateSessionCount = (int) ($certificateCpdStmt->get_result()->fetch_assoc()['sessions_count'] ?? 0);
$certificateCpdStmt->close();
$certificateFeedbackStmt = $dbcon->prepare("SELECT COUNT(*) AS feedback_count FROM tblfeedback WHERE participant_id=? AND YEAR(fDate)=?");
$certificateFeedbackStmt->bind_param('ii', $participantId, $selectedYear);
$certificateFeedbackStmt->execute();
$certificateFeedbackCount = (int) ($certificateFeedbackStmt->get_result()->fetch_assoc()['feedback_count'] ?? 0);
$certificateFeedbackStmt->close();
$certificateCpdMap = ['Chemical Engineer' => 25, 'Chemical Technician' => 22, 'Chemist' => 22];
$certificateCpdAwards = [];
if ($certificateFeedbackCount > 0) {
    foreach (array_filter(array_map('trim', explode(',', (string) ($participant['profession'] ?? '')))) as $certificateProfession) {
        $certificateProfession = ucwords(strtolower($certificateProfession));
        if (isset($certificateCpdMap[$certificateProfession])) {
            $certificateCpdAwards[$certificateProfession] = $certificateSessionCount >= 9
                ? $certificateCpdMap[$certificateProfession]
                : $certificateSessionCount;
        }
    }
}
$certificateCpdDisplay = $certificateCpdAwards
    ? implode(', ', array_map(static fn($profession, $units) => $units . ' (' . $profession . ')', array_keys($certificateCpdAwards), $certificateCpdAwards))
    : '0';
$attendancePageSize = 5;
$attendancePage = max(1, (int) ($_GET['attendance_page'] ?? 1));
$attendanceCountStmt = $dbcon->prepare("SELECT COUNT(*) AS total FROM tblattendance a INNER JOIN tblptcsession s ON s.session_number=a.session_id WHERE a.participant_id=? AND YEAR(s.session_date)=?");
$attendanceCountStmt->bind_param('ii', $participantId, $selectedYear);
$attendanceCountStmt->execute();
$attendanceTotal = (int) $attendanceCountStmt->get_result()->fetch_assoc()['total'];
$attendanceCountStmt->close();
$attendanceTotalPages = max(1, (int) ceil($attendanceTotal / $attendancePageSize));
$attendancePage = min($attendancePage, $attendanceTotalPages);
$attendanceOffset = ($attendancePage - 1) * $attendancePageSize;
$attendanceStmt = $dbcon->prepare("SELECT s.session_number, s.session_head, s.session_title, s.session_date, s.session_time FROM tblattendance a INNER JOIN tblptcsession s ON s.session_number = a.session_id WHERE a.participant_id = ? AND YEAR(s.session_date)=? ORDER BY s.session_date DESC, s.session_time DESC LIMIT ? OFFSET ?");
$attendanceStmt->bind_param('iiii', $participantId, $selectedYear, $attendancePageSize, $attendanceOffset);
$attendanceStmt->execute();
$attendanceResult = $attendanceStmt->get_result();

function profileValue($value): string {
    $value = trim((string) $value);
    return $value === '' ? 'Not provided' : htmlspecialchars($value);
}

$fullName = trim($participant['firstName'] . ' ' . ($participant['middleName'] ?? '') . ' ' . $participant['lastName']);
$initials = strtoupper(substr($participant['firstName'], 0, 1) . substr($participant['lastName'], 0, 1));
$professions = array_filter(array_map('trim', explode(',', (string) ($participant['profession'] ?? ''))));
if (!empty($participant['other_profession'])) $professions[] = $participant['other_profession'];
$licenses = [
    'Chemical Engineer' => $participant['ChemEn_prc'] ?? '', 'Chemist' => $participant['Chem_prc'] ?? '',
    'Chemical Technician' => $participant['ChemTech_prc'] ?? '', 'Mechanical Engineer' => $participant['MechEn_prc'] ?? '',
    'Agricultural Engineer' => $participant['AgriEn_prc'] ?? '', 'Other PRC License' => $participant['Others_prc'] ?? ''
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PTC | <?php echo htmlspecialchars($fullName); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="participant_profile.css">
    <link rel="stylesheet" href="nav&footer/usernav.css"><link rel="stylesheet" href="nav&footer/footer.css">
</head>
<body>
<?php include 'nav&footer/usernav.php'; ?>
<main class="participant-profile-page">
    <div class="profile-breadcrumb"><a href="participants.php"><i class="fa-solid fa-arrow-left"></i>Participants</a><span>/</span><span>Profile</span></div>
    <section class="profile-hero">
        <div class="profile-avatar"><?php echo htmlspecialchars($initials); ?></div>
        <div class="profile-identity"><span class="profile-id">Registration ID <?php echo (int) $participant['id']; ?></span><h1><?php echo htmlspecialchars($fullName); ?></h1><p><?php echo profileValue($participant['position']); ?> at <?php echo profileValue($participant['organization']); ?></p></div>
        <div class="profile-actions"><button type="button" class="profile-button profile-button--secondary" data-bs-toggle="modal" data-bs-target="#editParticipantModal"><i class="fa-regular fa-pen-to-square"></i>Edit Record</button></div>
    </section>

    <div class="profile-grid">
        <section class="profile-card"><h2><i class="fa-regular fa-user"></i>Personal Information</h2><dl class="profile-details"><div><dt>First name</dt><dd><?php echo profileValue($participant['firstName']); ?></dd></div><div><dt>Middle name</dt><dd><?php echo profileValue($participant['middleName']); ?></dd></div><div><dt>Last name</dt><dd><?php echo profileValue($participant['lastName']); ?></dd></div><div><dt>Date of birth</dt><dd><?php echo profileValue($participant['birthdate']); ?></dd></div><div><dt>Gender</dt><dd><?php echo profileValue($participant['Gender']); ?></dd></div><div><dt>Citizenship</dt><dd><?php echo profileValue($participant['Citizenship']); ?></dd></div><div><dt>Location</dt><dd><?php echo profileValue(implode(', ', array_filter([$participant['CityProvince'] ?? '', $participant['Region'] ?? '', $participant['Country'] ?? '']))); ?></dd></div></dl></section>
        <section class="profile-card"><h2><i class="fa-regular fa-address-card"></i>Contact & Registration</h2><dl class="profile-details"><div class="profile-detail-wide"><dt>Email address</dt><dd><a href="mailto:<?php echo htmlspecialchars($participant['username']); ?>"><?php echo profileValue($participant['username']); ?></a></dd></div><div><dt>Registered</dt><dd><?php echo date('M j, Y g:i A', strtotime($participant['datetime_registered'])); ?></dd></div><div><dt>Account status</dt><dd><span class="profile-status"><?php echo profileValue($participant['status']); ?></span></dd></div><div><dt>Congress type</dt><dd><?php echo profileValue($participant['congress_type']); ?></dd></div><div><dt>Certificate CPD units (<?php echo $selectedYear; ?>)</dt><dd><?php echo htmlspecialchars($certificateCpdDisplay); ?></dd></div></dl></section>
        <section class="profile-card profile-card--wide"><h2><i class="fa-solid fa-briefcase"></i>Professional Information</h2><div class="professional-grid"><div><span class="profile-label">Organization</span><strong><?php echo profileValue($participant['organization']); ?></strong></div><div><span class="profile-label">Position</span><strong><?php echo profileValue($participant['position']); ?></strong></div><div><span class="profile-label">Professions</span><div class="profile-tags"><?php if ($professions): foreach (array_unique($professions) as $profession): ?><span><?php echo htmlspecialchars($profession); ?></span><?php endforeach; else: ?><em>Not provided</em><?php endif; ?></div></div><div><span class="profile-label">PRC Licenses</span><div class="license-list"><?php $hasLicense = false; foreach ($licenses as $label => $value): if (trim((string) $value) !== ''): $hasLicense = true; ?><span><b><?php echo $label; ?></b><?php echo htmlspecialchars($value); ?></span><?php endif; endforeach; if (!$hasLicense): ?><em>None recorded</em><?php endif; ?></div></div></div></section>
        <section class="profile-card profile-card--wide">
            <div class="profile-card-heading attendance-heading">
                <div><h2><i class="fa-solid fa-calendar-check"></i>Attendance History</h2><span><?php echo $attendanceTotal; ?> attended session<?php echo $attendanceTotal === 1 ? '' : 's'; ?> in <?php echo $selectedYear; ?></span></div>
                <div class="attendance-tools">
                    <form method="get"><input type="hidden" name="id" value="<?php echo $participantId; ?>"><label for="attendance_year">Event year</label><select id="attendance_year" name="attendance_year" onchange="this.form.submit()"><?php if ($attendanceYears): foreach ($attendanceYears as $year): ?><option value="<?php echo $year; ?>" <?php echo $selectedYear === $year ? 'selected' : ''; ?>><?php echo $year; ?></option><?php endforeach; else: ?><option value="<?php echo $selectedYear; ?>"><?php echo $selectedYear; ?></option><?php endif; ?></select></form>
                    <a class="certificate-button" target="_blank" href="certificate/generate_certificate.php?id=<?php echo $participantId; ?>&year=<?php echo $selectedYear; ?>&source=admin"><i class="fa-solid fa-certificate"></i>View <?php echo $selectedYear; ?> Certificate</a>
                </div>
            </div>
            <div class="profile-attendance-table"><table><thead><tr><th>Session</th><th>Title</th><th>Date</th><th>Time</th></tr></thead><tbody><?php if ($attendanceResult->num_rows): while ($session = $attendanceResult->fetch_assoc()): ?><tr><td><strong><?php echo htmlspecialchars($session['session_head']); ?></strong></td><td><?php echo htmlspecialchars($session['session_title']); ?></td><td><?php echo date('M j, Y', strtotime($session['session_date'])); ?></td><td><?php echo date('g:i A', strtotime($session['session_time'])); ?></td></tr><?php endwhile; else: ?><tr><td colspan="4" class="profile-empty">No attendance records found for <?php echo $selectedYear; ?>.</td></tr><?php endif; ?></tbody></table></div>
            <?php if ($attendanceTotalPages > 1): ?><nav class="attendance-pagination" aria-label="Attendance pages"><a class="<?php echo $attendancePage === 1 ? 'disabled' : ''; ?>" href="?id=<?php echo $participantId; ?>&attendance_year=<?php echo $selectedYear; ?>&attendance_page=<?php echo max(1, $attendancePage - 1); ?>"><i class="fa-solid fa-chevron-left"></i>Previous</a><span>Page <?php echo $attendancePage; ?> of <?php echo $attendanceTotalPages; ?></span><a class="<?php echo $attendancePage === $attendanceTotalPages ? 'disabled' : ''; ?>" href="?id=<?php echo $participantId; ?>&attendance_year=<?php echo $selectedYear; ?>&attendance_page=<?php echo min($attendanceTotalPages, $attendancePage + 1); ?>">Next<i class="fa-solid fa-chevron-right"></i></a></nav><?php endif; ?>
        </section>
    </div>

    <div class="modal fade" id="editParticipantModal" tabindex="-1" aria-labelledby="editParticipantModalLabel" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content profile-edit-modal"><form method="post" action="participant_profile.php?id=<?php echo $participantId; ?>"><div class="modal-header"><div><h5 class="modal-title" id="editParticipantModalLabel">Edit Participant Record</h5><small>Update personal, professional, and license information.</small></div><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div class="profile-edit-grid">
        <?php $editFields = [
            'firstName'=>'First name','middleName'=>'Middle name','lastName'=>'Last name','username'=>'Email address','birthdate'=>'Date of birth',
            'Gender'=>'Gender','Citizenship'=>'Citizenship','Country'=>'Country','Region'=>'Region','CityProvince'=>'City / Province',
            'profession'=>'Profession(s)','other_profession'=>'Other profession','organization'=>'Organization','position'=>'Position','cpd'=>'CPD units',
            'ChemEn_prc'=>'Chemical Engineer PRC','Chem_prc'=>'Chemist PRC','ChemTech_prc'=>'Chemical Technician PRC','MechEn_prc'=>'Mechanical Engineer PRC','AgriEn_prc'=>'Agricultural Engineer PRC','Others_prc'=>'Other PRC license'
        ]; foreach ($editFields as $field => $label): $inputType = $field === 'birthdate' ? 'date' : ($field === 'cpd' ? 'number' : 'text'); ?>
            <label><span><?php echo $label; ?></span><input class="form-control" type="<?php echo $inputType; ?>" name="<?php echo $field; ?>" value="<?php echo htmlspecialchars((string) ($participant[$field] ?? '')); ?>" <?php echo in_array($field, ['firstName','lastName','username'], true) ? 'required' : ''; ?>></label>
        <?php endforeach; ?>
    </div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" name="save_participant" class="btn btn-primary">Save Changes</button></div></form></div></div></div>
</main>
<?php $attendanceStmt->close(); include 'nav&footer/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
