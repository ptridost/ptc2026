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
    						COUNT(DISTINCT participant_id) as total,
    						COUNT(DISTINCT CASE WHEN Country = 'Philippines' THEN participant_id END) as Philippines,
    						COUNT(DISTINCT CASE WHEN Country = 'Australia' THEN participant_id END) as Australia,
    						COUNT(DISTINCT CASE WHEN Country = 'Pakistan' THEN participant_id END) as Pakistan,
    						COUNT(DISTINCT CASE WHEN Country = 'China' THEN participant_id END) as China,
    						COUNT(DISTINCT CASE WHEN Country = 'India' THEN participant_id END) as India,
    						COUNT(DISTINCT CASE WHEN Country = 'Taiwan' THEN participant_id END) as Taiwan,
    						COUNT(DISTINCT CASE WHEN Country = 'Yemen' THEN participant_id END) as Yemen,
    						COUNT(DISTINCT CASE WHEN Country = 'Malaysia' THEN participant_id END) as Malaysia
    						from sampledb.tblattendance
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

