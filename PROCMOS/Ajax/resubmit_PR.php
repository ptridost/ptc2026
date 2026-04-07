<?php
require_once("../DatabaseConnection/databaseconnection.php");
	
if(isset($_POST['id'])){

	$newStatus = "For Clearance (PPMP)";
	$newLocation = "FAD-PPGSS";
	$prID = $_POST['id'];
	$remarks = $_POST['remarks'];

	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_status=:purchrequest_status, pr_lastprocessedby=:pr_lastprocessedby, purchrequest_location=:purchrequest_location, pr_datetimelastaction=NOW() WHERE purchrequest_ID=:prID");

	$stmt->bindParam(':prID', $_POST['id']);
	$stmt->bindParam(':purchrequest_location', $newLocation);
	$stmt->bindParam(':pr_lastprocessedby', $_SESSION['Name']);
	$stmt->bindParam(':purchrequest_status', $newStatus);

	if($stmt->execute()){
		// echo "SUCCESS UPDATE";
		
		// $output = "SUCCESS";

		$stmt = $con->prepare("INSERT INTO tbl_activityLogs (activityUsername, activityName, activityAction) VALUES (:Username, :fName, :actConfAction)");

    	$actConfAction = "Re-submitted PR with ID of $prID, remarks: ($remarks)";

    	$stmt->bindParam(':Username', $_SESSION['Username']);
		$stmt->bindParam(':fName', $_SESSION['Name']);
		$stmt->bindParam(':actConfAction', $actConfAction);

		if($stmt->execute()){
			echo "PR ID # <strong>". $_POST['id'] ."</strong> has successfully been resubmitted to PPGSS! <br><br>Remarks: ($remarks)";
		}
		else{
			$output = "ERROR! ACTIVITY LOGS FAILED!";
			echo $output;
		}	
	}
	else{
		$output = "ERROR: Re-submitting PR!";
		echo $output;
	}
}
else{
	echo "ERROR: NO ID PARAMETER" . $_POST['id'];
}

?>