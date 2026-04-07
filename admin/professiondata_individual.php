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
    COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemical Engineer', profession) THEN participant_id END) AS ChemicalEngineer,
    COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemical Technician', profession) THEN participant_id END) AS ChemicalTechnician,
    COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemist', profession) THEN participant_id END) AS Chemist,
    COUNT(DISTINCT CASE WHEN profession = 'Others' THEN participant_id END) AS Others,
    COUNT(DISTINCT CASE WHEN profession = 'None' THEN participant_id END) AS None
        FROM 
    sampledb.participant
    INNER JOIN 
        tblattendance ON participant_id = participant.id
    WHERE 
        session_id = $session_id ;
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

