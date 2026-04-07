<?php
header('Content-Type: application/json');
$conn = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb");
// $sqlQuery = "SELECT count(*) as total , 
// 				sum( case when Country = 'PH' then 1 else 0 end ) as 'Philippines', 
// 				sum( case when Country = 'PK' then 1 else 0 end ) as 'Pakistan', 
// 				sum( case when Country = 'AU' then 1 else 0 end ) as 'Australia', 
// 				sum( case when Country = 'US' then 1 else 0 end ) as 'United States', 
// 				sum( case when Country = 'CA' then 1 else 0 end ) as 'Canada', 
// 				sum( case when Country = 'GR' then 1 else 0 end ) as 'Greece', 
// 				sum( case when Country = 'KH' then 1 else 0 end ) as 'Cambodia', 
// 				sum( case when Country = 'SE' then 1 else 0 end ) as 'Sweden', 
// 				sum( case when Country = 'TW' then 1 else 0 end ) as 'Taiwan' 
// 				from sampledb.participant";

$sqlQuery = "SELECT 
			COUNT(DISTINCT participant_id) as total,
			COUNT(DISTINCT CASE WHEN Country = 'Philippines' THEN participant_id END) as Philippines,
			COUNT(DISTINCT CASE WHEN Country = 'Australia' THEN participant_id END) as Australia,
			COUNT(DISTINCT CASE WHEN Country = 'Pakistan' THEN participant_id END) as Pakistan,
			COUNT(DISTINCT CASE WHEN Country = 'China' THEN participant_id END) as China,
			COUNT(DISTINCT CASE WHEN Country = 'India' THEN participant_id END) as India,
			COUNT(DISTINCT CASE WHEN Country = 'Taiwan' THEN participant_id END) as Taiwan,
			COUNT(DISTINCT CASE WHEN Country = 'Yemen' THEN participant_id END) as Yemen,
			COUNT(DISTINCT CASE WHEN Country = 'Malaysia' THEN participant_id END) as Malaysia
			from sampledb.tblattendance";
$result = mysqli_query($conn,$sqlQuery);
$data = array();
foreach ($result as $row) {
	$data[] = $row;
}
mysqli_close($conn);
echo json_encode($data);
?>