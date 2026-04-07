


<?php
header('Content-Type: application/json');

// Database connection
$conn = mysqli_connect("localhost", "root", "ptc@ptri@picts", "sampledb");

// Check connection
if (!$conn) {
    die(json_encode(['error' => 'Failed to connect to the database']));
}

// Get the session_id from POST data
$session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : 0;

// Prepare the SQL query
$sqlQuery = "
    SELECT 
        COUNT(DISTINCT participant_id) AS total, 
        COUNT(DISTINCT CASE WHEN Gender = 'Male' THEN participant_id END) AS male, 
        COUNT(DISTINCT CASE WHEN Gender = 'Female' THEN participant_id END) AS female, 
        COUNT(DISTINCT CASE WHEN Gender = 'Not Specify' THEN participant_id END) AS notspecify 
    FROM 
        sampledb.tblattendance 
    WHERE 
        session_id = $session_id
";

// Execute the query
$result = mysqli_query($conn, $sqlQuery);

// Check for errors in the query
if (!$result) {
    die(json_encode(['error' => mysqli_error($conn)]));
}

// Fetch the result
$data = mysqli_fetch_assoc($result);

// Close the database connection
mysqli_close($conn);

// Return the JSON response
echo json_encode($data);
?>
