<?php
require_once("../DatabaseConnection/databaseconnection.php");

$query = "SELECT ictclearance_ID, tbl_ictclearance.ictclearance_No, ictclearance_date, purchrequest_ID, account_ID, purchrequest_location, 	ictclearance_reviewedesigdate, pr_lastprocessedby, ictclearance_status FROM `tbl_ictclearance` INNER JOIN tbl_purchaserequest ON tbl_ictclearance.purchrequest_No = tbl_purchaserequest.purchrequest_ID ORDER BY ictclearance_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	

	while ($row = mysqli_fetch_assoc($result)) {
		$ictclearance_ID[] = $row['ictclearance_ID'];
		$ictclearance_No[] = $row['ictclearance_No'];
		$ictclearance_date[] = $row['ictclearance_date'];
		$purchrequest_ID[] = $row['purchrequest_ID'];
		$account_ID[] = $row['account_ID'];
		$purchrequest_location[] = $row['purchrequest_location'];
		$ictclearance_reviewedesigdate[] = $row['ictclearance_reviewedesigdate'];
		$pr_lastprocessedby[] = $row['pr_lastprocessedby'];
		$ictclearance_status[] = $row['ictclearance_status'];
	}

?>