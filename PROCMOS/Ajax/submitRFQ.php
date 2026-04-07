<?php
require_once("../DatabaseConnection/databaseconnection.php");

	// var_dump([$_POST]);
	// die();
	// $training = [];

	// foreach ($_POST['Training'] as $key => $value) {
	// 	$training[$key] = $value;
	// 	//var_dump($training[$key]);

	// }


	// echo json_encode($training);
	// die();

if(isset($_POST['pr_no'])){
	
	$newStatus = "For AOC";

	// echo $_POST['remarks'];

	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus, pr_remarks=:newRemarks, date_RFQ=NOW() WHERE purchrequest_No=:purchrequest_No");

	$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	$stmt->bindParam(':newPRStatus', $newStatus);
	$stmt->bindParam(':newRemarks', $_POST['remarks']);
	$stmt->bindParam(':purchrequest_No', $_POST['pr_no']);

	if($stmt->execute()){
		// $output = "SUCCESS";

		echo "SUCCESS! PR No: ".$_POST['pr_no']." is now For AOC";

		// echo '<script type="text/javascript">alert("SUCCESS! PR No: '.$_POST['pr_no'].' is now For AOC");</script>';
		// echo '<script type="text/javascript">window.location.href = "../pages/attach_ictclearance.php";</script>';	
	}
	else{
		echo "ERROR: Update PR Failed!";	
	}	

		



}

?>