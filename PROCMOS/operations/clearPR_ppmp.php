<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['txtPR_IDaffixPPMP'])){

	

	$query = "SELECT acct_signature FROM tbl_acctdetails WHERE account_ID = '". $_SESSION['account_ID'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_signature = $row['acct_signature'];
	}

	$location = "FAD-PPGSS";
	// $DateTimeNow = "NOW()";
	$newStatus = "For Clearance (PPGSS)";

	



	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, pr_ppmpclearance_esig=:eSigattachment, date_pr_ppmpclearance_esig=NOW(), pr_remarks=:pr_remarks, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:prID");

	// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
	$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	$stmt->bindParam(':eSigattachment', $acct_signature);
	$stmt->bindParam(':pr_remarks', nl2br($_POST['txtAffixRemarksPPMP']));
	$stmt->bindParam(':newPRStatus', $newStatus);
	$stmt->bindParam(':prID', $_POST['txtPR_IDaffixPPMP']);


	if($stmt->execute()){
		// $output = "SUCCESS";

		echo '<script type="text/javascript">alert("Success! Your clearance has been validated for this PR"); history.back()</script>';
	}
	else{
		echo '<script type="text/javascript">alert("ERROR: Updating PR clearance failed!"); history.back()</script>';
		$output = "ERROR: Updating PR E-signature failed!";
	}
	
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No ID parameter";
	echo '<script type="text/javascript">alert("'.$output.' '.$_POST['txtPR_IDaffixPPMP'].'"); history.back()</script>';
}
	


?>