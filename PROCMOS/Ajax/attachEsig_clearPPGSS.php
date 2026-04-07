<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['id'])){



	$query = "SELECT acct_signature FROM tbl_acctdetails WHERE account_ID = '". $_SESSION['account_ID'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_signature = $row['acct_signature'];
	}

	

	require_once("../accessory/validate_DivisionSection.php");

	if($_POST['validateForICT'] == "YES"){
		//FOR ICT CLEARANCE
		$location = "OD-PICTS";
	// $DateTimeNow = "NOW()";
		$newStatus = "For ICT Clearance";
	}
	else{
		$location = "Chief, FAD";
	// $DateTimeNow = "NOW()";
		$newStatus = "For Clearance (Chief, FAD)";
	}	

	$ID = $_POST['id'];
	$remarks = $_POST['remarks'];




	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, pr_ppgssesig=:eSigattachment, date_prppgssesig=NOW(), pr_remarks=:pr_remarks, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:prID");

	$stmt->bindParam(':newLocation', $location);
	// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
	$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	$stmt->bindParam(':eSigattachment', $acct_signature);
	$stmt->bindParam(':pr_remarks', $_POST['remarks']);
	$stmt->bindParam(':newPRStatus', $newStatus);
	$stmt->bindParam(':prID', $_POST['id']);

	

	if($stmt->execute()){
		
		$query = "SELECT ictclearance_No FROM tbl_purchaserequest WHERE purchrequest_ID = '". $_POST['id'] ."'";
		$result = mysqli_query($conProcedural,$query);

		while ($row = mysqli_fetch_assoc($result)) {
			$ictNo = $row['ictclearance_No'];
		}

		if($ictNo == "Not Yet Assigned"){

			$stmt = $con->prepare("INSERT INTO tbl_activityLogs (activityUsername, activityName, activityAction) VALUES (:Username, :fName, :actConfAction)");
        								
        	$actConfAction = "CLEARED PR ID # $ID from PPGSS, remarks: ($remarks)";

        	$stmt->bindParam(':Username', $_SESSION['Username']);
			$stmt->bindParam(':fName', $_SESSION['Name']);
			$stmt->bindParam(':actConfAction', $actConfAction);

			if($stmt->execute()){
				echo "PR ID # <strong>". $_POST['id'] ."</strong> has been cleared from PPGSS";
			}
			else{
				$output = "ERROR: Activity Log failed!";
				echo $output;
			}

			
		}
		else{
			$stmt = $con->prepare("UPDATE tbl_ictclearance SET purchrequest_No=:purchrequest_No WHERE ictclearance_No=:ictNo");

			$stmt->bindParam(':purchrequest_No', $_POST['id']);
			$stmt->bindParam(':ictNo', $ictNo);

			if($stmt->execute()){

				$stmt = $con->prepare("INSERT INTO tbl_activityLogs (activityUsername, activityName, activityAction) VALUES (:Username, :fName, :actConfAction)");
        								
	        	$actConfAction = "CLEARED PR ID # $ID from PPGSS AND UPDATED THE ICT CLEARANCE NO. $ictNo, remarks: ($remarks)";

	        	$stmt->bindParam(':Username', $_SESSION['Username']);
				$stmt->bindParam(':fName', $_SESSION['Name']);
				$stmt->bindParam(':actConfAction', $actConfAction);

				if($stmt->execute()){
					echo "PR ID # <strong>". $_POST['id'] ."</strong> has been cleared from PPGSS";
				}
				else{
					$output = "ERROR: Activity Log w/ ICT failed!";
					echo $output;
				}
			}
			else{
				$output = "ERROR: Update ICT Clearance - PR Number failed!";
				echo $output;
			}
		}
	}
	else{
		$output = "ERROR: E-signature failed! (PPGSS)";
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