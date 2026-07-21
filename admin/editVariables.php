
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Speaker Information</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./editSpeakers.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        window.location.href = 'editVariables.php'; 
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
$certificateYears = $availableYears;
if (!in_array($currentPtcYear, $certificateYears, true)) {
    array_unshift($certificateYears, $currentPtcYear);
}
if (!in_array($latestCertificateAssetYear, $certificateYears, true)) {
    array_unshift($certificateYears, $latestCertificateAssetYear);
}
rsort($certificateYears);

// Close the database connection
$dbcon->close();
?>

<body>
	<div class="container mt-5 form__container">
		<h3 id="title"class="pt-4">Edit Overall Session Settings</h3>
		<form id="editSettings" method="POST" action="editVariables.php"> <!-- Change action to your PHP processing file -->
		    <input type="hidden" id="hidden1" name="settings"> <!-- This will hold the session ID -->

            <div class="mb-4 p-3 border rounded bg-light">
                <h5 class="mb-3">Event Year Settings</h5>
                <div class="mb-3">
                    <label for="current_ptc_year" class="form-label">Current PTC Year</label>
                    <select class="form-control" id="current_ptc_year" name="current_ptc_year" required>
                        <?php foreach ($availableYears as $availableYear): ?>
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
                        <?php foreach ($availableYears as $availableYear): ?>
                            <option value="<?php echo $availableYear; ?>" <?php echo $latestCertificateAssetYear === $availableYear ? 'selected' : ''; ?>>
                                <?php echo $availableYear; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Used as fallback when the selected certificate year has no uploaded asset folder yet.</small>
                </div>

                <h6 class="mt-4">Certificate Eligibility Rules</h6>
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
                    <div class="row align-items-center mb-3">
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
		        <a href="./admindashboard.php" type="button" name="cancel" class="btn btn-danger"> Cancel</a>
		        <button type="submit" class="btn btn-primary" id="saveChanges" form="editSettings" name="editSettings">Save changes</button>
		    </div>
		</form>
	</div>
   
</body>

</html>

