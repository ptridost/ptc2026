<?php
$embeddedSettingsView = isset($_GET['embedded']) || isset($_POST['embedded']);
$settingsReturnUrl = $embeddedSettingsView ? 'editVariables.php?embedded=1' : 'editVariables.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Speaker Information</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./editSpeakers.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if ($embeddedSettingsView): ?>
    <style>
        html, body { width: 100%; height: 100%; overflow: hidden; background: #fff; font-family: "Poppins", sans-serif; }
        *, *::before, *::after { box-sizing: border-box; }
        body :where(h1, h2, h3, h4, h5, h6, p, a, button, input, select, textarea, label, span, small) { font-family: "Poppins", sans-serif; }
        .form__container { width: 100%; max-width: none; height: 100%; margin-top: 0 !important; padding: .75rem 1rem; overflow: hidden; border-radius: 0; background: #fff !important; }
        #title { display: none; }
        #editSettings { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .65rem 1rem; height: 100%; }
        #editSettings > .mb-4, #editSettings > .modal-footer { grid-column: 1 / -1; }
        #editSettings > .mb-3 { margin: 0 !important; }
        #editSettings > .mb-3 .form-label { margin-bottom: .25rem; font-size: .78rem; font-weight: 600; }
        #editSettings .form-control { min-height: 36px; padding: .35rem .6rem; font-size: .82rem; }
        .settings-overview { margin: 0 !important; padding: .75rem !important; border-color: #e2e8f0 !important; background: #fff !important; }
        .settings-overview h5 { margin-bottom: .6rem !important; font-size: .95rem; }
        .event-year-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
        .event-year-grid .mb-3 { margin: 0 !important; }
        .event-year-grid small { display: block; margin-top: .15rem; font-size: .68rem; line-height: 1.2; }
        .certificate-heading { display: flex; align-items: end; justify-content: space-between; gap: 1rem; margin: .7rem 0 .45rem; }
        .certificate-heading h6 { margin: 0; font-size: .84rem; }
        .certificate-year-picker { width: 145px; }
        .certificate-year-picker label { margin-bottom: .15rem; font-size: .66rem; font-weight: 700; text-transform: uppercase; }
        .certificate-rule-row { margin: 0 !important; padding: .65rem; border: 1px solid #d9e2ec; border-radius: .4rem; background: #fff; }
        .certificate-rule-row > div { width: auto; flex: 1 1 0; }
        .certificate-rule-row > div:first-of-type { flex: 0 0 60px; }
        .certificate-rule-row .form-label { font-size: .7rem; line-height: 1.15; }
        .certificate-rule-row .offset-md-2 { margin-left: 60px; }
        #editSettings > .modal-footer { align-self: end; padding: .65rem 0 0; }
        @media (max-width: 767.98px) {
            html, body, .form__container { overflow-y: auto; }
            #editSettings { display: block; height: auto; }
            #editSettings > .mb-3 { margin-bottom: .75rem !important; }
            .event-year-grid { grid-template-columns: 1fr; }
            .certificate-heading { align-items: stretch; flex-direction: column; }
            .certificate-year-picker { width: 100%; }
            .certificate-rule-row { display: block; }
            .certificate-rule-row > div { margin-bottom: .5rem; }
            .certificate-rule-row .offset-md-2 { margin-left: 0; }
        }
    </style>
    <?php endif; ?>
</head>

<?php
// Start session to check if the user is logged in as an admin
session_start();
if (empty($_SESSION['admin_id'])) {
    header("Location: ../Loginn.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "ptc@ptri@picts";
$dbname = "sampledb";

$dbcon = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($dbcon->connect_error) {
    die("Connection failed: " . $dbcon->connect_error);
}

require_once('../event_year.php');

function saveVariable(mysqli $dbcon, string $name, string $value): bool
{
    $check = $dbcon->prepare("SELECT id FROM variables WHERE name = ? LIMIT 1");
    $check->bind_param("s", $name);
    $check->execute();
    $result = $check->get_result();
    $existing = $result->fetch_assoc();
    $check->close();

    if ($existing) {
        $stmt = $dbcon->prepare("UPDATE variables SET value = ? WHERE name = ?");
        $stmt->bind_param("ss", $value, $name);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    $nextIdResult = $dbcon->query("SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM variables");
    $nextId = (int) $nextIdResult->fetch_assoc()['next_id'];
    $stmt = $dbcon->prepare("INSERT INTO variables (id, name, value) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $nextId, $name, $value);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $settingsToSave = [
        'acceptingTimeFeedbackMorning' => $_POST['atfm'] ?? '',
        'acceptingTimeFeedbackAfternoon' => $_POST['atfa'] ?? '',
        'closingTimeFeedback' => $_POST['ctf'] ?? '',
        'acceptingTimeAttendanceMorning' => $_POST['atam'] ?? '',
        'acceptingTimeAttendanceAfternoon' => $_POST['ataa'] ?? '',
        'current_ptc_year' => (string) (int) ($_POST['current_ptc_year'] ?? date('Y')),
        'latest_certificate_asset_year' => (string) (int) ($_POST['latest_certificate_asset_year'] ?? date('Y')),
    ];

    $certificateYears = array_map('intval', $_POST['certificate_years'] ?? []);
    foreach ($certificateYears as $certificateYear) {
        $minSessions = max(1, (int) ($_POST['certificate_min_sessions'][$certificateYear] ?? 6));
        $minFeedback = max(0, (int) ($_POST['certificate_min_feedback'][$certificateYear] ?? 0));
        $feedbackRequired = isset($_POST['certificate_feedback_required'][$certificateYear]) ? '1' : '0';
        $distributionDate = $_POST['certificate_distribution_date'][$certificateYear] ?? '';
        $validDate = $_POST['certificate_valid_date'][$certificateYear] ?? '';

        $settingsToSave['certificate_min_sessions_' . $certificateYear] = (string) $minSessions;
        $settingsToSave['certificate_min_feedback_' . $certificateYear] = (string) $minFeedback;
        $settingsToSave['certificate_feedback_required_' . $certificateYear] = $feedbackRequired;
        $settingsToSave['certificate_distribution_date_' . $certificateYear] = $distributionDate;
        $settingsToSave['certificate_valid_date_' . $certificateYear] = $validDate;
    }

    // Check if the query executed successfully
    $allSaved = true;
    foreach ($settingsToSave as $name => $value) {
        $allSaved = saveVariable($dbcon, $name, trim((string) $value)) && $allSaved;
    }

    if ($allSaved) {
        $message = "Settings updated successfully!";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '$message',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = '$settingsReturnUrl';
                    });
                });
            </script>";
    } else {
        $message = "Error updating settings.";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '$message',
                        showConfirmButton: true
                    });
                });
            </script>";
    }
}

$dbcon->close();
?>

<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "ptc@ptri@picts";
$dbname = "sampledb";

$dbcon = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($dbcon->connect_error) {
    die("Connection failed: " . $dbcon->connect_error);
}

require_once('../event_year.php');

// SQL query to fetch settings from the 'variables' table
$sql_settings = "SELECT * FROM variables";
$result = $dbcon->query($sql_settings);

// Initialize an associative array to store the settings
$settings = [];

// Fetch the settings and store them in the array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $settings[$row['name']] = $row['value'];
    }
} else {
    echo "No settings found.";
}

$availableYears = getAvailableEventYears($dbcon);
$currentPtcYear = getCurrentEventYear($dbcon);
$latestCertificateAssetYear = (int) ($settings['latest_certificate_asset_year'] ?? 2025);
$settingYears = $availableYears;
if (!in_array($currentPtcYear, $settingYears, true)) {
    array_unshift($settingYears, $currentPtcYear);
}
if (!in_array($latestCertificateAssetYear, $settingYears, true)) {
    array_unshift($settingYears, $latestCertificateAssetYear);
}
$certificateYears = $settingYears;
rsort($settingYears);
rsort($certificateYears);

// Close the database connection
$dbcon->close();
?>

<body>
	<div class="container mt-5 form__container">
		<h3 id="title"class="pt-4">Edit Overall Session Settings</h3>
		<form id="editSettings" method="POST" action="editVariables.php<?php echo $embeddedSettingsView ? '?embedded=1' : ''; ?>"> <!-- Change action to your PHP processing file -->
		    <input type="hidden" id="hidden1" name="settings"> <!-- This will hold the session ID -->
            <?php if ($embeddedSettingsView): ?><input type="hidden" name="embedded" value="1"><?php endif; ?>

            <div class="mb-4 p-3 border rounded bg-light settings-overview">
                <h5 class="mb-3">Event Year Settings</h5>
                <div class="event-year-grid">
                <div class="mb-3">
                    <label for="current_ptc_year" class="form-label">Current PTC Year</label>
                    <select class="form-control" id="current_ptc_year" name="current_ptc_year" required>
                        <?php foreach ($settingYears as $availableYear): ?>
                            <option value="<?php echo $availableYear; ?>" <?php echo $currentPtcYear === $availableYear ? 'selected' : ''; ?>>
                                <?php echo $availableYear; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">This controls the default year used by dashboards and certificate checks.</small>
                </div>

                <div class="mb-3">
                    <label for="latest_certificate_asset_year" class="form-label">Latest Certificate Asset Year</label>
                    <select class="form-control" id="latest_certificate_asset_year" name="latest_certificate_asset_year" required>
                        <?php foreach ($settingYears as $availableYear): ?>
                            <option value="<?php echo $availableYear; ?>" <?php echo $latestCertificateAssetYear === $availableYear ? 'selected' : ''; ?>>
                                <?php echo $availableYear; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Used as fallback when the selected certificate year has no uploaded asset folder yet.</small>
                </div>
                </div>

                <div class="certificate-heading">
                    <h6>Certificate Eligibility Rules</h6>
                    <?php if ($embeddedSettingsView): ?>
                    <div class="certificate-year-picker">
                        <label for="certificate_rule_year">Rule year</label>
                        <select id="certificate_rule_year" class="form-select form-select-sm">
                            <?php foreach ($certificateYears as $certificateYear): ?><option value="<?php echo $certificateYear; ?>"><?php echo $certificateYear; ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>
                <?php foreach ($certificateYears as $certificateYear): ?>
                    <?php
                        $minSessionsName = 'certificate_min_sessions_' . $certificateYear;
                        $minFeedbackName = 'certificate_min_feedback_' . $certificateYear;
                        $feedbackRequiredName = 'certificate_feedback_required_' . $certificateYear;
                        $distributionDateName = 'certificate_distribution_date_' . $certificateYear;
                        $validDateName = 'certificate_valid_date_' . $certificateYear;
                        $minSessionsValue = htmlspecialchars($settings[$minSessionsName] ?? '6');
                        $feedbackRequiredValue = filter_var($settings[$feedbackRequiredName] ?? false, FILTER_VALIDATE_BOOLEAN);
                        $minFeedbackValue = htmlspecialchars($settings[$minFeedbackName] ?? ($feedbackRequiredValue ? '1' : '0'));
                        $distributionDateValue = htmlspecialchars($settings[$distributionDateName] ?? '');
                        $validDateValue = htmlspecialchars($settings[$validDateName] ?? '');
                    ?>
                    <input type="hidden" name="certificate_years[]" value="<?php echo $certificateYear; ?>">
                    <div class="row align-items-center mb-3 certificate-rule-row" data-certificate-year="<?php echo $certificateYear; ?>">
                        <div class="col-md-2 fw-bold"><?php echo $certificateYear; ?></div>
                        <div class="col-md-2">
                            <label class="form-label mb-1">Minimum attended sessions</label>
                            <input type="number" class="form-control" name="certificate_min_sessions[<?php echo $certificateYear; ?>]" value="<?php echo $minSessionsValue; ?>" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1">Minimum feedback submitted</label>
                            <input type="number" class="form-control" name="certificate_min_feedback[<?php echo $certificateYear; ?>]" value="<?php echo $minFeedbackValue; ?>" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1">Certificate date</label>
                            <input type="date" class="form-control" name="certificate_distribution_date[<?php echo $certificateYear; ?>]" value="<?php echo $distributionDateValue; ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1">Valid until</label>
                            <input type="date" class="form-control" name="certificate_valid_date[<?php echo $certificateYear; ?>]" value="<?php echo $validDateValue; ?>">
                        </div>
                        <div class="col-md-12 offset-md-2 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="feedback_required_<?php echo $certificateYear; ?>" name="certificate_feedback_required[<?php echo $certificateYear; ?>]" value="1" <?php echo $feedbackRequiredValue ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="feedback_required_<?php echo $certificateYear; ?>">
                                    Legacy feedback required flag
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

		    <div class="mb-3">
		        <label for="atm" class="form-label">Accepting Time for Feedback Morning Sessions</label>
		        <input type="text" class="form-control" id="atfm" name="atfm" value="<?php echo htmlspecialchars($settings['acceptingTimeFeedbackMorning'] ?? ''); ?>" required>
		    </div>
		    <div class="mb-3">
		        <label for="ata" class="form-label">Accepting Time for Feedback Afternoon Sessions</label>
		        <input type="text" class="form-control" id="atfa" name="atfa" value="<?php echo htmlspecialchars($settings['acceptingTimeFeedbackAfternoon'] ?? ''); ?>" required>
		    </div>
		    <div class="mb-3">
		        <label for="ctf" class="form-label">Closing Time for Feedback</label>
		        <input type="text" class="form-control" id="ctf" name="ctf" value="<?php echo htmlspecialchars($settings['closingTimeFeedback'] ?? ''); ?>" required>
		    </div>
		    <div class="mb-3">
		        <label for="atam" class="form-label">Accepting Time for Attendance Morning Sessions</label>
		        <input type="text" class="form-control" id="atam" name="atam" value="<?php echo htmlspecialchars($settings['acceptingTimeAttendanceMorning'] ?? ''); ?>" required>
		    </div>
		    <div class="mb-3">
		        <label for="ataa" class="form-label">Accepting Time for Attendance Afternoon Sessions</label>
		        <input type="text" class="form-control" id="ataa" name="ataa" value="<?php echo htmlspecialchars($settings['acceptingTimeAttendanceAfternoon'] ?? ''); ?>" required>
		    </div>

		    <div class="modal-footer">
		        <?php if ($embeddedSettingsView): ?>
                <button type="button" class="btn btn-outline-secondary" onclick="window.parent.bootstrap.Modal.getInstance(window.parent.document.getElementById('sessionSettingsModal')).hide()">Cancel</button>
                <?php else: ?>
                <a href="./admindashboard.php" type="button" name="cancel" class="btn btn-danger">Cancel</a>
                <?php endif; ?>
		        <button type="submit" class="btn btn-primary" id="saveChanges" form="editSettings" name="editSettings">Save changes</button>
		    </div>
		</form>
	</div>
    <?php if ($embeddedSettingsView): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const picker = document.getElementById('certificate_rule_year');
            const rows = document.querySelectorAll('.certificate-rule-row');
            function showSelectedRule() {
                rows.forEach(function (row) {
                    row.hidden = row.dataset.certificateYear !== picker.value;
                });
            }
            picker.addEventListener('change', showSelectedRule);
            showSelectedRule();
        });
    </script>
    <?php endif; ?>
   
</body>

</html>
