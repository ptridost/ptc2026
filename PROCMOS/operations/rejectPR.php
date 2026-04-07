<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['txtPR_IDreject'])){

	


	$query = "SELECT account_ID FROM tbl_purchaserequest WHERE purchrequest_ID = '". $_POST['txtPR_IDreject'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$account_ID = $row['account_ID'];
	}



	$query = "SELECT acct_Division, acct_Section FROM tbl_acctdetails WHERE account_ID = '". $account_ID ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
	}



	if($acct_Division == "Office of the Director"){
		$ptridivision = "OD";
	}
	else if($acct_Division == "Research and Development Division"){
		$ptridivision = "RDD";
	}
	else if($acct_Division == "Finance and Administrative Division"){
		$ptridivision = "FAD";
	}
	else if($acct_Division == "Technical Services Division"){
		$ptridivision = "TSD";
	}
	else{
		$ptridivision = $acct_Division;
	}

	if($acct_Section == "Planning and Information & Communication Technology Staff"){
		$ptrisection = "PICTS";
	}
	else if($acct_Section == "Technology Transfer, Information, and Promotion Staff"){
		$ptrisection = "TIPS";
	}
	else if($acct_Section == "Natural Fiber Utilization Section"){
		$ptrisection = "NFUS";
	}
	else if($acct_Section == "Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section"){
		$ptrisection = "CDABUS";
	}
	else if($acct_Section == "Property, Procurement and General Services Section"){
		$ptrisection = "PPGSS";
	}
	else if($acct_Section == "Human Resources and Records Management Section"){
		$ptrisection = "HRRMS";
	}
	else if($acct_Section == "Budget and Treasury Section"){
		$ptrisection = "BTS";
	}
	else if($acct_Section == "Accounting Section"){
		$ptrisection = "ACCTG";
	}
	else if($acct_Section == "Physical and Chemical Testing Section"){
		$ptrisection = "PCTS";
	}
	else if($acct_Section == "Mechanical and Chemical Processing Section"){
		$ptrisection = "MCPS";
	}
	else{
		$ptrisection = $acct_Section;
	}


	$location = $ptridivision . "-" . $ptrisection;

	// $location = "FAD-PPGSS";
	// // $DateTimeNow = "NOW()";
	$newStatus = "For Revision";

	// var_dump($newStatus);
	// die();

	



	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, pr_remarks=:pr_remarks, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:prID");

	// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
	$stmt->bindParam(':newLocation', $location);
	$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	$stmt->bindParam(':pr_remarks', nl2br($_POST['txtPR_Remarksreject']));
	$stmt->bindParam(':newPRStatus', $newStatus);
	$stmt->bindParam(':prID', $_POST['txtPR_IDreject']);


	if($stmt->execute()){
		// $output = "SUCCESS";

		echo '<script type="text/javascript">alert("Success! This PR has been reverted back to '.$location.' with specific remarks FOR REVISION"); window.location.href = "../pages/pr_signatureaction.php";</script>';
	}
	else{
		echo '<script type="text/javascript">alert("ERROR: Reject PR failed!"); window.location.href = "../pages/pr_signatureaction.php";</script>';
		$output = "ERROR: Reject PR failed!";
	}
	
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No ID parameter";
	echo '<script type="text/javascript">alert("'.$output.' '.$_POST['txtPR_IDreject'].'"); window.location.href = "../pages/pr_signatureaction.php";</script>';
}
	


?>