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
                                COUNT(DISTINCT CASE WHEN participant.age = '61 and above' THEN participant_id END) as '61 and above',
                                COUNT(DISTINCT CASE WHEN participant.age = '51-60' THEN participant_id END) as '51-60' ,
                                COUNT(DISTINCT CASE WHEN participant.age = '41-50' THEN participant_id END) as '41-50',
                                COUNT(DISTINCT CASE WHEN participant.age = '31-40' THEN participant_id END) as '31-40',
                                COUNT(DISTINCT CASE WHEN participant.age = '21-30' THEN participant_id END) as '21-30',
                                COUNT(DISTINCT CASE WHEN participant.age = 'Less than 21' THEN participant_id END) as 'Less than 21',
                                COUNT(DISTINCT CASE WHEN participant.age = '' THEN participant_id END) as 'None'
                            FROM 
                                participant 
                            INNER JOIN 
                                tblattendance ON participant_id = participant.id
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

