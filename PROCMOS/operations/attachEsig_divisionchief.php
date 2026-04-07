<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['txtPR_IDchief'])){

	

	$query = "SELECT acct_signature FROM tbl_acctdetails WHERE account_ID = '". $_SESSION['account_ID'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_signature = $row['acct_signature'];
	}

	$location = "FAD-PPGSS";
	// $DateTimeNow = "NOW()";
	$newStatus = "For Clearance (PPGSS)";

	



	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, pr_divisionchiefesig=:eSigattachment, date_prdivisionheadesig=NOW(), pr_remarks=:pr_remarks, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:prID");

	$stmt->bindParam(':newLocation', $location);
	// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
	$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	$stmt->bindParam(':eSigattachment', $acct_signature);
	$stmt->bindParam(':pr_remarks', nl2br($_POST['txtAffixRemarks_chief']));
	$stmt->bindParam(':newPRStatus', $newStatus);
	$stmt->bindParam(':prID', $_POST['txtPR_IDchief']);


	if($stmt->execute()){
		// $output = "SUCCESS";
		
		echo '<script type="text/javascript">alert("Success! Your e-signature has been attached to the PR"); history.back()</script>';	
	}
	else{
		echo '<script type="text/javascript">alert("ERROR: Updating PR E-signature failed!"); history.back()</script>';
		$output = "ERROR: Updating PR E-signature failed!";
	}
	
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No ID parameter";
	echo '<script type="text/javascript">alert("'.$output.' '.$_POST['txtPR_IDsection'].'"); history.back()</script>';
}
	


?>