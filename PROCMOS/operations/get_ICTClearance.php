<?php
require_once("../DatabaseConnection/databaseconnection.php");

$query = "SELECT ictclearance_No, ictclearance_requisitioner, ictclearance_date, ictclearance_remarks, ictclearance_reviewedesig, ictclearance_approvedesig, ictdetails_qty, ictdetails_description FROM `tbl_ictclearance` INNER JOIN `tbl_ictclearancedetails` on tbl_ictclearance.ictclearance_ID = tbl_ictclearancedetails.ictclearance_ID WHERE tbl_ictclearance.ictclearance_ID = '$ictclearance_ID' ORDER BY tbl_ictclearancedetails.ictdetails_ID DESC";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$ictclearance_No = $row['ictclearance_No'];
	$ictclearance_requisitioner = $row['ictclearance_requisitioner'];
	$ictclearance_date = $row['ictclearance_date'];
	$ictclearance_remarks = $row['ictclearance_remarks'];
	$ictclearance_reviewedesig = $row['ictclearance_reviewedesig'];
	$ictclearance_approvedesig = $row['ictclearance_approvedesig'];
	$ictdetails_qty[] = $row['ictdetails_qty'];
	$ictdetails_description[] = $row['ictdetails_description'];
}

if(!empty($ictclearance_date)){
	$ictclearance_dateNew = date('d-M-Y', strtotime($ictclearance_date));
}
else{
	//do nothing	
}

?>