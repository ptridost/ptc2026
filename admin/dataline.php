<?php
header('Content-Type: application/json');
$conn = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb");
// $sqlQuery = "SELECT count(DISTINCT participant_id) as total , 
// 			COUNT(DISTINCT CASE WHEN Age = '61 and above' THEN participant_id END) as '61 and above',
// 			COUNT(DISTINCT CASE WHEN Age = '51-60' THEN participant_id END) as '51-60' ,
// 			COUNT(DISTINCT CASE WHEN Age = '41-50' THEN participant_id END) as '41-50',
// 			COUNT(DISTINCT CASE WHEN Age = '31-40' THEN participant_id END) as '31-40',
// 			COUNT(DISTINCT CASE WHEN Age = '21-30' THEN participant_id END) as '21-30',
// 			COUNT(DISTINCT CASE WHEN Age = 'Less than 21' THEN participant_id END) as 'Less than 21',
// 			COUNT(DISTINCT CASE WHEN Age = '' THEN participant_id END) as 'None',
// 			from sampledb.tblattendance";
$sqlQuery = "SELECT 
    			COUNT(DISTINCT id) as total,
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
    			tblattendance ON participant_id = participant.id";
$result = mysqli_query($conn,$sqlQuery);
$data = array();
foreach ($result as $row) {
	$data[] = $row;
}
mysqli_close($conn);
echo json_encode($data);
?>



