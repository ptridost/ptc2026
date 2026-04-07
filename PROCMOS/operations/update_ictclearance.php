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



if(isset($_POST['txtPRno'])){
	
	try {


	// prepare sql and bind parameters
	// $stmt = $con->prepare("INSERT INTO tbl_Training (trainingTitle, trainingDate, trainingName, trainingAge, trainingSex, trainingAddress, trainingContact, trainingEmail, trainingKinds, trainingCollegeName, trainingUniversityAddress, trainingCourse) VALUES (:trainingTitle, :trainingDate, :trainingName, :trainingAge, :trainingSex, :trainingAddress, :trainingContact, :trainingEmail, :trainingKin -eto yung mali kaya nag e-error nung una-(g) s, :trainingCollegeName, :trainingUniversityAddress, :trainingCourse)");

	// var_dump($_POST['txtDate']);
	// die();

		// $aDoor = $_POST['chkLabfaci'];
		// if(empty($aDoor)) 
		// {
		// 	echo "You didn't ssdasdaselect any buildings.";
		// }
		// else{
		// 	echo "You selected any buildings.";	
		// }

		// $N = count($aDoor);

		// echo("You selected $N door(s): ");
		// for($i=0; $i < $N; $i++)
		// {
		// 	echo($aDoor[$i] . "[ans] ");
		// }

		// var_dump($_POST['chkLabfaci']);
		// var_dump($_POST['chkPilotfaci']);
		// var_dump($_POST['chkOthersfaci']);
		// die();
		// $passAppointID = $_POST['txtpassAppointID'];
		// $query = "SELECT appoint_confirmedDate, appoint_confirmedTime from tbl_appointments WHERE appointmentID=$passAppointID";
		// $result = mysqli_query($conProcedural,$query);
		
		// while ($row = mysqli_fetch_assoc($result)) {
		// 	$schedDate = $row['appoint_confirmedDate'];
		// 	$schedTime = $row['appoint_confirmedTime'];
		// }

		// var_dump($schedDate);
		// die();

		$arrayQty = [];
		$arrayDesc = [];

		// require_once("../accessory/validate_DivisionSection.php");

		// $requisitioner = $ptridivision . "-" . $ptrisection;

		$location = "Chief, FAD";
		// $DateTimeNow = "NOW()";
		$ictclearance_status = "Signed (Head, PICTS)";
		$newStatus = "For Clearance (Chief, FAD)";
		
		$account_ID = $_SESSION['account_ID'];

		// var_dump($_POST['txtNumberOfRows']);
		// die();



		for ($i=1; $i <=$_POST['txtNumberOfRows']; $i++) { 
			// $arrayweight = $_POST['txtweight'.$i];
			array_unshift($arrayQty, $_POST['txtUnitQty'.$i]);
			array_unshift($arrayDesc, $_POST['txtItem'.$i]);
			// echo $arrayweight;
		}
		// var_dump(max(count($_POST['chkLabfaci']), count($_POST['chkPilotfaci']), $_POST['chkOthersfaci']));
		// die();

		$query = "SELECT acct_signature FROM tbl_acctdetails WHERE account_ID = '". $_SESSION['account_ID'] ."'";
		$result = mysqli_query($conProcedural,$query);

		while ($row = mysqli_fetch_assoc($result)) {
			$acct_signature = $row['acct_signature'];
		}

		

		if(!empty($acct_signature)){
			// var_dump($finaltourDate);
			// var_dump($finaltourTime);
			// require_once("validateApprovedBy.php");

			

			$query = "SELECT ictclearance_ID FROM tbl_ictclearance WHERE purchrequest_No = '". $_POST['txtPRno'] ."'";
			$result = mysqli_query($conProcedural,$query);

			while ($row = mysqli_fetch_assoc($result)) {
				$ictclearance_ID = $row['ictclearance_ID'];
			}

			// var_dump($query);
			// die();

			$query = "DELETE FROM tbl_ictclearancedetails WHERE ictclearance_ID = '". $ictclearance_ID ."'";
			$result = mysqli_query($conProcedural,$query);

			while ($row = mysqli_fetch_assoc($result)) {
				$deleteResult = $row['deleteResult'];
			}

			echo '<script type="text/javascript">console.log("'.$deleteResult.'");</script>';
			

			for ($j=0; $j<count($arrayDesc); $j++) { 
						
				$stmt = $con->prepare("INSERT INTO tbl_ictclearancedetails (ictclearance_ID, ictdetails_qty, ictdetails_description) VALUES (:ictclearance_ID, :ictdetails_qty, :ictdetails_description)");

				$stmt->bindParam(':ictclearance_ID', $ictclearance_ID);
				$stmt->bindParam(':ictdetails_qty', $arrayQty[$j]);
				$stmt->bindParam(':ictdetails_description', nl2br(htmlentities($arrayDesc[$j])));

				if($stmt->execute()){
					//do nothing
					//submission to Head, PICTS first (approval)
					// echo '<script type="text/javascript">window.location.href = "../pages/ictclearance_pdf.php?ID='.$ID.'&acctID='.$account_ID.'";</script>';
					$success = "TRUE";
				}
				else{
					echo '<script type="text/javascript">alert("ERROR! ICT DETAILS FAILED!");</script>';
				}
			}

			// for ($j=0; $j<count($arrayDesc); $j++) { 

			// 	//ICT CLEARANCE ID KAILANGAN
			// 	$stmt = $con->prepare("UPDATE tbl_ictclearancedetails SET ictdetails_qty=:ictdetails_qty, ictdetails_description=:ictdetails_description WHERE ictclearance_ID=:ID");



			// 	$stmt->bindParam(':ictdetails_qty', $arrayQty[$j]);
			// 	$stmt->bindParam(':ictdetails_description', $arrayDesc[$j]);
			// 	$stmt->bindParam(':ID', $ictclearance_ID);

			// 	if($stmt->execute()){
			// 		$success = "TRUE";
			// 	}

			// 	else{
			// 		echo '<script type="text/javascript">alert("ERROR! ICT DETAILS FAILED!");</script>';
			// 	}
			// }

			// var_dump($success);
			// die();
			
			

			if($success == "TRUE"){
				$stmt2 = $con->prepare("UPDATE tbl_ictclearance SET ictclearance_remarks=:newRemarks, ictclearance_approvedesig=:eSigattachment, ictclearance_approvedesigdate=NOW(), ictclearance_status=:newICTStatus WHERE ictclearance_ID=:prNo");

				$stmt2->bindParam(':newRemarks', $_POST['txtRemarks']);
				$stmt2->bindParam(':eSigattachment', $acct_signature);
				$stmt2->bindParam(':newICTStatus', $ictclearance_status);
				$stmt2->bindParam(':prNo', $_POST['txtPRID']);


				if($stmt2->execute()){
					if($_POST['txtPRno'] == "Not Yet Validated"){
						$location = "FAD-PPGSS";
						$newStatus = "For Clearance (PPMP)";

						$stmt4 = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_location=:PPGSSlocation, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:purchrequest_ID");

						$stmt4->bindParam(':PPGSSlocation', $location);
						$stmt4->bindParam(':newLastProcessedBy', $_SESSION['Name']);
						$stmt4->bindParam(':newPRStatus', $newStatus);
						$stmt4->bindParam(':purchrequest_ID', $_POST['txtPRID']);
					}

					else{
						$stmt4 = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus WHERE purchrequest_No=:prNo");

						$stmt4->bindParam(':newLocation', $location);
						$stmt4->bindParam(':newLastProcessedBy', $_SESSION['Name']);
						$stmt4->bindParam(':newPRStatus', $newStatus);
						$stmt4->bindParam(':prNo', $_POST['txtPRno']);
					}				


					if($stmt4->execute()){
						// $output = "SUCCESS";
						echo '<script type="text/javascript">alert("SUCCESS! Your e-signature is now attached to the ICT Clearance"); history.back();</script>';
						// echo $_SESSION['account_ID'];		
					}
					else{
						echo '<script type="text/javascript">alert("ERROR: Updating PR new status failed!");</script>';
					}
				}
				else{
					echo '<script type="text/javascript">alert("ERROR: UPDATE ICT Clearance FAILED!");</script>';
				}
			}
			else{
				//do nothing
			}				
		}
		else{
			// echo "error";
			echo '<script type="text/javascript">alert("Error! No User Esig");</script>';
		}

		die();

		//echo "New records created successfully";
		// echo '<script type="text/javascript">alert("New records created successfully!");</script>';
		// echo '<script type="text/javascript">
		// 	$("#modalInform").modal();

		// </script>';

		// echo '<script type="text/javascript">window.location.href = "../pages/training.php";</script>';
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
		echo '<script type="text/javascript">alert("Error! Submit Failed");</script>';
		// echo '<script type="text/javascript">window.location.href = "../pages/training.php";</script>';
	}
	$con = null;	
}

?>