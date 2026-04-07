<?php
header('Content-Type: application/json');
$conn = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb");
$sqlQuery = "SELECT count(DISTINCT participant_id) as total , 
			COUNT(DISTINCT CASE WHEN Gender = 'Male' THEN participant_id END) AS male , 
			COUNT(DISTINCT CASE WHEN Gender = 'female' THEN participant_id END) AS female , 
			COUNT(DISTINCT CASE WHEN Gender = 'Not Specify' THEN participant_id END) AS notspecify 
			from sampledb.tblattendance";
$result = mysqli_query($conn,$sqlQuery);
$data = array();
foreach ($result as $row) {
	$data[] = $row;
}
mysqli_close($conn);
echo json_encode($data);
?>