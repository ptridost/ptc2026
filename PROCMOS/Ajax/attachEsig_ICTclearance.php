<?php
require_once("../DatabaseConnection/databaseconnection.php");


	
if(isset($_POST['id'])){

	$query = "SELECT acct_signature FROM tbl_acctdetails WHERE account_ID = '". $_SESSION['account_ID'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_signature = $row['acct_signature'];
	}

	// $query2 = "SELECT ictclearance_No FROM tbl_purchaserequest WHERE purchrequest_No = '". $_POST['pr_No'] ."'";
	$query2 = "SELECT ictclearance_No FROM tbl_purchaserequest WHERE purchrequest_ID = '". $_POST['id'] ."'";
	$result2 = mysqli_query($conProcedural,$query2);

	while ($row2 = mysqli_fetch_assoc($result2)) {
		$ictclearance_No = $row2['ictclearance_No'];
	}

	$query = "SELECT ictclearance_ID FROM tbl_ictclearance WHERE ictclearance_No = '". $ictclearance_No ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$ictclearance_ID = $row['ictclearance_ID'];
	}
	
	$location = "Chief, FAD";
	// $DateTimeNow = "NOW()";
	$ictclearance_status = "Signed (Head, PICTS)";
	$newStatus = "For Clearance (Chief, FAD)";

	$stmt = $con->prepare("UPDATE tbl_ictclearance SET ictclearance_approvedesig=:eSigattachment, ictclearance_approvedesigdate=NOW(), ictclearance_status=:newICTStatus WHERE ictclearance_ID=:prNo");

	$stmt->bindParam(':eSigattachment', $acct_signature);
	$stmt->bindParam(':newICTStatus', $ictclearance_status);
	$stmt->bindParam(':prNo', $ictclearance_ID);

	if($stmt->execute()){
		// echo "SUCCESS UPDATE";

		$stmt4 = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_location=:PPGSSlocation, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:purchrequest_ID");

			$stmt4->bindParam(':PPGSSlocation', $location);
			$stmt4->bindParam(':newLastProcessedBy', $_SESSION['Name']);
			$stmt4->bindParam(':newPRStatus', $newStatus);
			$stmt4->bindParam(':purchrequest_ID', $_POST['id']);

		// if($_POST['pr_No'] == "Not Yet Validated"){
		// 	$location = "FAD-PPGSS";
		// 	$newStatus = "For Clearance (PPMP)";

		// 	$stmt4 = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_location=:PPGSSlocation, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:purchrequest_ID");

		// 	$stmt4->bindParam(':PPGSSlocation', $location);
		// 	$stmt4->bindParam(':newLastProcessedBy', $_SESSION['Name']);
		// 	$stmt4->bindParam(':newPRStatus', $newStatus);
		// 	$stmt4->bindParam(':purchrequest_ID', $_POST['id']);
		// }
		// else{
		// 	$stmt4 = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus WHERE purchrequest_No=:prNo");

		// 	$stmt4->bindParam(':newLocation', $location);
		// 	$stmt4->bindParam(':newLastProcessedBy', $_SESSION['Name']);
		// 	$stmt4->bindParam(':newPRStatus', $newStatus);
		// 	$stmt4->bindParam(':prNo', $_POST['pr_No']);
		// }		

		// $_SESSION['successEsig'] = true;

		if($stmt4->execute()){
			// $output = "SUCCESS";
			// echo "SUCCESS! Your e-signature is now attached to the ICT Clearance";
			echo "Your e-signature is now attached to the ICT Clearance No. <strong>". $ictclearance_No ."</strong>";
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