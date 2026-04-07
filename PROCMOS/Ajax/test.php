<?php
require_once("../DatabaseConnection/databaseconnection.php");


	
if(isset($_POST['id'])){

	$query = "SELECT acct_signature FROM tbl_acctdetails WHERE account_ID = '". $_SESSION['account_ID'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_signature = $row['acct_signature'];
	}
	
	$location = "Chief, FAD";
	// $DateTimeNow = "NOW()";
	$ictclearance_status = "Signed (Head, PICTS)";
	$newStatus = "For Clearance (Chief, FAD)";

	$stmt = $con->prepare("UPDATE tbl_ictclearance SET ictclearance_approvedesig=:eSigattachment, ictclearance_approvedesigdate=NOW(), ictclearance_status=:newICTStatus WHERE purchrequest_No=:prNo");

	$stmt->bindParam(':eSigattachment', $acct_signature);
	$stmt->bindParam(':newICTStatus', $ictclearance_status);
	$stmt->bindParam(':prNo', $_POST['pr_No']);

	if($stmt->execute()){
		// echo "SUCCESS UPDATE";
		$stmt4 = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus WHERE purchrequest_No=:prNo");

		$stmt4->bindParam(':newLocation', $location);
		$stmt4->bindParam(':newLastProcessedBy', $_SESSION['Name']);
		$stmt4->bindParam(':newPRStatus', $newStatus);
		$stmt4->bindParam(':prNo', $_POST['pr_No']);


		if($stmt4->execute()){
			// $output = "SUCCESS";
			echo "SUCCESS! Your e-signature is now attached to the ICT Clearance";
			// echo $_SESSION['account_ID'];		
		}
		else{
			$output = "ERROR: Updating PR new status failed!";
			echo $output;
		}
	}
	else{
		echo "ERROR: UPDATE ICT Clearance FAILED!";
	}
}
else{
	echo "ERROR: NO ID PARAMETER" . $_POST['id'];
}

?>