<?php
require_once("../DatabaseConnection/databaseconnection.php");

$query = "SELECT aoc_ID, tbl_aoc.aoc_number, tbl_purchaserequest.purchrequest_No, aoc_date, purchrequest_ID, account_ID, purchrequest_location, pr_lastprocessedby, aoc_pdf, aoc_status FROM `tbl_aoc` INNER JOIN tbl_purchaserequest ON tbl_aoc.pr_number = tbl_purchaserequest.purchrequest_No ORDER BY aoc_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	

	while ($row = mysqli_fetch_assoc($result)) {
		$aoc_ID[] = $row['aoc_ID'];
		$aoc_number[] = $row['aoc_number'];
		$purchrequest_No[] = $row['purchrequest_No'];
		$aoc_date[] = $row['aoc_date'];
		$purchrequest_ID[] = $row['purchrequest_ID'];
		$account_ID[] = $row['account_ID'];
		$purchrequest_location[] = $row['purchrequest_location'];
		$pr_lastprocessedby[] = $row['pr_lastprocessedby'];
		$aoc_pdf[] = $row['aoc_pdf'];
		$aoc_status[] = $row['aoc_status'];
	}

?>