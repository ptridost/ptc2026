<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['prID'])){


	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_DateTime=:pr_reqDate, purchrequest_No=:prNum, prNo_dtd=:prNum_dtd, quotation_no=:quotation_No, date_quotationno=:quotationNo_dtd, purchrequest_location=:prLocation, purchrequest_status=:prStatus WHERE purchrequest_ID=:prID");

	$stmt->bindParam(':pr_reqDate', $_POST['pr_reqDate']);
	$stmt->bindParam(':prNum', $_POST['prNum']);
	$stmt->bindParam(':prNum_dtd', $_POST['prNum_dtd']);
	$stmt->bindParam(':quotation_No', $_POST['quotation_No']);
	$stmt->bindParam(':quotationNo_dtd', $_POST['quotationNo_dtd']);
	$stmt->bindParam(':prLocation', $_POST['prLocation']);
	$stmt->bindParam(':prStatus', $_POST['prStatus']);
	$stmt->bindParam(':prID', $_POST['prID']);

	if($stmt->execute()){
		$remarks = $_POST['prRemarks'];
							        	
		$stmt = $con->prepare("INSERT INTO tbl_activityLogs (activityUsername, activityName, activityAction) VALUES (:Username, :fName, :actConfAction)");

    	$actConfAction = "UPDATED more PR details, remarks: ($remarks)";

    	$stmt->bindParam(':Username', $_SESSION['Username']);
		$stmt->bindParam(':fName', $_SESSION['Name']);
		$stmt->bindParam(':actConfAction', $actConfAction);

		if($stmt->execute()){
			echo "PR No. <strong>". $_POST['prNum'] ."</strong> has successfully been updated! <br><br>Remarks: ($remarks)";
		}
		else{
			$output = "ERROR! ACTIVITY LOGS FAILED!";
			echo $output;
		}
	}
	else{
		$output = "ERROR! PR UPDATE FAILED!";
		echo $output;
	}

	

	// var_dump($txtSupplier);

	


	// if($stmt->execute()){
	// 	// $output = "SUCCESS";
	// 	echo "PR No. <strong>". $_POST['prNo'] ."</strong> has successfully been cancelled";		
	// }
	// else{
	// 	$output = "ERROR: Updating PR New Status and Process failed!";
	// 	echo $output;
	// }
	
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No PR ID. parameter";
	echo $output . " " . $_POST['prID'];
}
	


?>