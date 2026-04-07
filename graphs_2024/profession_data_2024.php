

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
    profession
FROM participant
WHERE datetime_registered LIKE '2024%' 
  AND (
    birthdate LIKE '__/__/____' OR 
    birthdate LIKE '%, ____' OR 
    birthdate LIKE '% ____'
  )
GROUP BY profession;";

// Execute the query
$result = mysqli_query($conn, $sqlQuery);

// Check for query execution errors
if (!$result) {
    die(json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]));
}
$sqlQuery_chem_eng = "
    SELECT COUNT(DISTINCT CONCAT(firstName, ' ', middleName, ' ', lastName)) AS 'Newly Registered Participants', other_profession FROM participant WHERE datetime_registered LIKE '2024%' AND ( birthdate LIKE '__/__/____' OR birthdate LIKE '%, ____' OR birthdate LIKE '% ____' ) AND ( profession LIKE '%Others' OR profession = 'None' OR profession = 'Others' ) AND LOWER(other_profession) IN ('chemical engineer', 'chemical engineering'); 
";

$result_chem_eng = mysqli_query($conn, $sqlQuery_chem_eng);
$row_chem_eng = mysqli_fetch_assoc($result_chem_eng);

// Fetch the data
$data['total'] = 0;
$data['Chemical Technician'] = 0;
$data['Chemist'] = 0;
$data['Chemical Engineer'] = 0;
$data['Others'] = 0;
$data['None'] = 0;
// Process the results
while ($row = mysqli_fetch_assoc($result)) {
    $row_array = explode(",",$row['profession']);
    foreach($row_array as $individual_profession){
        $data[$individual_profession] += $row['Newly Registered Participants'];
    }
    $data['total'] += (int )$row['Newly Registered Participants'];
}

$data['Chemical Engineer'] = $row_chem_eng['Newly Registered Participants'];
$data['Others'] -=$row_chem_eng['Newly Registered Participants'];
// Close the connection
mysqli_close($conn);

// Return the data as JSON
echo json_encode([$data]);
?>
