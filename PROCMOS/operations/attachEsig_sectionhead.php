<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['txtPR_IDsection'])){

	

	$query = "SELECT acct_signature FROM tbl_acctdetails WHERE account_ID = '". $_SESSION['account_ID'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_signature = $row['acct_signature'];
	}

	$query = "SELECT purchrequest_location FROM tbl_purchaserequest WHERE purchrequest_ID = '". $_POST['txtPR_IDsection'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$location = $row['purchrequest_location'];
	}

	$division = substr($location,0,strripos($location,"-"));

	if($division == "OD"){
		$location = "FAD-PPGSS";
		// $DateTimeNow = "NOW()";
		$newStatus = "For Clearance (PPGSS)";
	}
	else{
		if($division == "RDD"){
			$location = "RDD-NFUS";
		}
		else if($division == "TSD"){
			$location = "TSD-MCPS";
		}
		
		$newStatus = "For Clearance (Division Chief)";	
	}

	



	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, pr_sectionheadesig=:eSigattachment, date_prsectionheadesig=NOW(), pr_remarks=:pr_remarks, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:prID");

	$stmt->bindParam(':newLocation', $location);
	// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
	$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	$stmt->bindParam(':eSigattachment', $acct_signature);
	$stmt->bindParam(':pr_remarks', nl2br($_POST['txtAffixRemarks_section']));
	$stmt->bindParam(':newPRStatus', $newStatus);
	$stmt->bindParam(':prID', $_POST['txtPR_IDsection']);


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