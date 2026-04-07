<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['prNo'])){


	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus WHERE purchrequest_No=:prNo");

	$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	$stmt->bindParam(':newPRStatus', $_POST['newStatus']);
	$stmt->bindParam(':prNo', $_POST['prNo']);


	if($stmt->execute()){
		// $output = "SUCCESS";
		
		echo "PR is successfully been processed for the next step: " . $_POST['newStatus'];		
	}
	else{
		$output = "ERROR: Updating PR New Status and Process failed!";
		echo $output;
	}
	
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No PR Number parameter";
	echo $output . " " . $_POST['prNo'];
}
	


?>