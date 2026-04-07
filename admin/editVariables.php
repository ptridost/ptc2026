
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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $acceptingTimeFeedbackMorning = $_POST['atfm'];
    $acceptingTimeFeedbackAfternoon = $_POST['atfa'];
    $closingTimeFeedback = $_POST['ctf'];
    $acceptingTimeAttendanceMorning = $_POST['atam'];
    $acceptingTimeAttendanceAfternoon = $_POST['ataa'];

    // Prepare the SQL update query
    $stmt = $dbcon->prepare("UPDATE variables SET value = ? WHERE name = ?");

    // Update Accepting Time for Feedback Morning
    $stmt->bind_param("ss", $acceptingTimeFeedbackMorning, $name);
    $name = 'acceptingTimeFeedbackMorning';
    $stmt->execute();

    // Update Accepting Time for Feedback Afternoon
    $stmt->bind_param("ss", $acceptingTimeFeedbackAfternoon, $name);
    $name = 'acceptingTimeFeedbackAfternoon';
    $stmt->execute();

    // Update Closing Time for Feedback
    $stmt->bind_param("ss", $closingTimeFeedback, $name);
    $name = 'closingTimeFeedback';
    $stmt->execute();

    // Update Accepting Time for Attendance Morning
    $stmt->bind_param("ss", $acceptingTimeAttendanceMorning, $name);
    $name = 'acceptingTimeAttendanceMorning';
    $stmt->execute();

    // Update Accepting Time for Attendance Afternoon
    $stmt->bind_param("ss", $acceptingTimeAttendanceAfternoon, $name);
    $name = 'acceptingTimeAttendanceAfternoon';
    $stmt->execute();

    // Check if the query executed successfully
    if ($stmt -> execute()) {
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

    $stmt->close();
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

// Close the database connection
$dbcon->close();
?>

<body>
	<div class="container mt-5 form__container">
		<h3 id="title"class="pt-4">Edit Overall Session Settings</h3>
		<form id="editSettings" method="POST" action="editVariables.php"> <!-- Change action to your PHP processing file -->
		    <input type="hidden" id="hidden1" name="settings"> <!-- This will hold the session ID -->

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

