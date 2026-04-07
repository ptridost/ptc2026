<?php
require_once("../DatabaseConnection/databaseconnection.php");

//GET PREPARED PRs

	$query = "SELECT COUNT(tbl_purchaserequest.purchrequest_ID) as numberOfPreparedPRs FROM tbl_purchaserequest WHERE purchrequest_No != 'Not Yet Validated' AND purchrequest_DateTime LIKE '%2025%'";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	

	while ($row = mysqli_fetch_assoc($result)) {
		$numberOfPreparedPRs = $row['numberOfPreparedPRs'];
	}

//GET APPROVED PRs

	$query = "SELECT COUNT(tbl_purchaserequest.purchrequest_ID) as numberOfApprovedPRs FROM tbl_purchaserequest WHERE `pr_approveesig` = 'director-esig.jpg' AND purchrequest_DateTime LIKE '%2025%'";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	

	while ($row = mysqli_fetch_assoc($result)) {
		$numberOfApprovedPRs = $row['numberOfApprovedPRs'];
	}


//GET PENDING PRs

	$query = "SELECT COUNT(tbl_purchaserequest.purchrequest_ID) as numberOfPendingPRs FROM tbl_purchaserequest WHERE `pr_approveesig` != 'director-esig.jpg' AND purchrequest_No != 'Not Yet Validated' AND purchrequest_DateTime LIKE '%2025%'";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	

	while ($row = mysqli_fetch_assoc($result)) {
		$numberOfPendingPRs = $row['numberOfPendingPRs'];
	}

//GET CANCELLED PRs

	$query = "SELECT COUNT(tbl_purchaserequest.purchrequest_ID) as numberOfCancelledPRs FROM tbl_purchaserequest WHERE `purchrequest_status` = 'Cancelled PR' AND purchrequest_DateTime LIKE '%2025%'";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();
	

	while ($row = mysqli_fetch_assoc($result)) {
		$numberOfCancelledPRs = $row['numberOfCancelledPRs'];
	}


?>