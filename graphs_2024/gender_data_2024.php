<?php
header('Content-Type: application/json');

// Create a connection to the database
$conn = mysqli_connect("localhost", "root", "ptc@ptri@picts", "sampledb");

// Check connection
if (!$conn) {
    die(json_encode(['error' => 'Failed to connect to the database: ' . mysqli_connect_error()]));
}

$sqlQuery = "
SELECT 
    COUNT(DISTINCT CONCAT(firstName, ' ', middleName, ' ', lastName)) AS 'Newly Registered Participants',
    Gender
FROM participant
WHERE datetime_registered LIKE '2024%' 
  AND (
    birthdate LIKE '__/__/____' OR 
    birthdate LIKE '%, ____' OR 
    birthdate LIKE '% ____'
  )
GROUP BY Gender;";

// Execute the query
$result = mysqli_query($conn, $sqlQuery);

// Check for query execution errors
if (!$result) {
    die(json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]));
}

// Fetch the data
$data['total'] = 0;
// Process the results
while ($row = mysqli_fetch_assoc($result)) {
    $data[$row['Gender']] = (int )$row['Newly Registered Participants'];
    $data['total'] += (int )$row['Newly Registered Participants'];
}

// Close the connection
mysqli_close($conn);

// Return the data as JSON
echo json_encode([$data]);
?>

