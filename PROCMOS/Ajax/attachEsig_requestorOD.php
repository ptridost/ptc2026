<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['id'])){

	

	$query = "SELECT acct_signature FROM tbl_acctdetails WHERE account_ID = '". $_SESSION['account_ID'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_signature = $row['acct_signature'];
	}

	// require_once("../accessory/validate_DivisionSection.php");

	$location = "FAD-PPGSS";
	// $DateTimeNow = "NOW()";
	$newStatus = "For PR number";



	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, pr_approveesig=:eSigattachment, date_prapproveesig=NOW(), pr_remarks=:pr_remarks, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:prID");

	$stmt->bindParam(':newLocation', $location);
	// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
	$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	$stmt->bindParam(':eSigattachment', $acct_signature);
	$stmt->bindParam(':pr_remarks', $_POST['remarks']);
	$stmt->bindParam(':newPRStatus', $newStatus);
	$stmt->bindParam(':prID', $_POST['id']);


	if($stmt->execute()){
		// $output = "SUCCESS";
		
		echo "PR ID # <strong>". $_POST['id'] ."</strong> has been approved!";
	}
	else{
		$output = "ERROR: Updating PR E-signature failed!";
		echo $output;
	}
	
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No ID parameter";
	echo $output . " " . $_POST['id'];
}
	


?>