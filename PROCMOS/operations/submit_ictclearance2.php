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

if(isset($_POST['txtDivision'])){
	
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

		// var_dump($_POST['txtPRno']);
		// die();

		$arrayQty = [];
		$arrayDesc = [];

		require_once("../accessory/validate_DivisionSection.php");

		$requisitioner = $ptridivision . "-" . $ptrisection;

		$location = "OD-PICTS";

		$ictclearance_status = "For Signature (Head, PICTS)";
		$account_ID = $_SESSION['account_ID'];
		$newStatus = "For ICT Clearance 2";

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

			// var_dump($approvedBy);
			// die();

			if($_POST['txtPRno'] == "Not Yet Validated"){
				$directICT = "For Validation";

				$stmt = $con->prepare("INSERT INTO tbl_ictclearance (purchrequest_No, ictclearance_requisitioner, ictclearance_No, 	ictclearance_remarks, ictclearance_status) VALUES (:purchrequest_No, :ictclearance_requisitioner, :ictclearance_No, :ictclearance_remarks, :ictclearance_status)");

				$stmt->bindParam(':purchrequest_No', $directICT);
				// $stmt->bindParam(':purchrequest_totalcost', $_POST['txtFinalFee']);
				$stmt->bindParam(':ictclearance_requisitioner', $requisitioner);
				$stmt->bindParam(':ictclearance_No', $_POST['txtICTClearanceNo']);			
				$stmt->bindParam(':ictclearance_remarks', $_POST['txtRemarks']);			
				// $stmt->bindParam(':ictclearance_reviewedesig', $acct_signature);
				$stmt->bindParam(':ictclearance_status', $ictclearance_status);
			}
			else{
				$stmt = $con->prepare("INSERT INTO tbl_ictclearance (purchrequest_No, ictclearance_requisitioner, ictclearance_No, 	ictclearance_remarks, ictclearance_status) VALUES (:purchrequest_No, :ictclearance_requisitioner, :ictclearance_No, :ictclearance_remarks, :ictclearance_status)");

				$stmt->bindParam(':purchrequest_No', $_POST['txtPRno']);
				
				$stmt->bindParam(':ictclearance_requisitioner', $requisitioner);
				$stmt->bindParam(':ictclearance_No', $_POST['txtICTClearanceNo']);			
				$stmt->bindParam(':ictclearance_remarks', $_POST['txtRemarks']);			
				// $stmt->bindParam(':ictclearance_reviewedesig', $acct_signature);
				$stmt->bindParam(':ictclearance_status', $ictclearance_status);
			}							

			if($stmt->execute()){
				$query = "SELECT ictclearance_ID from tbl_ictclearance ORDER BY ictclearance_ID DESC LIMIT 1";
				$result = mysqli_query($conProcedural,$query);
				
				while ($row = mysqli_fetch_assoc($result)) {
					$ID = $row['ictclearance_ID'];
				}
				

				if(isset($ID)){
					// var_dump($_POST['txtNumberOfRows']);
					// die();

					for ($j=0; $j<count($arrayDesc); $j++) { 
						
						$stmt2 = $con->prepare("INSERT INTO tbl_ictclearancedetails (ictclearance_ID, ictdetails_qty, ictdetails_description) VALUES (:ictclearance_ID, :ictdetails_qty, :ictdetails_description)");

						$stmt2->bindParam(':ictclearance_ID', $ID);
						$stmt2->bindParam(':ictdetails_qty', $arrayQty[$j]);
						$stmt2->bindParam(':ictdetails_description', nl2br(htmlentities($arrayDesc[$j])));

						if($stmt2->execute()){
							//do nothing
							//submission to Head, PICTS first (approval)
							// echo '<script type="text/javascript">window.location.href = "../pages/ictclearance_pdf.php?ID='.$ID.'&acctID='.$account_ID.'";</script>';
							$success = "TRUE";
						}
						else{
							echo '<script type="text/javascript">alert("ERROR! ICT DETAILS FAILED!");</script>';
						}
					}

					if($success == "TRUE"){
						require_once("generateCRN.php");

						$presentICTcrn = $ictclearance_no;
						$newICTno_crn = $presentICTcrn + 1;

						// echo $newPRno_crn;

						$stmt4 = $con->prepare("INSERT INTO tbl_CRNs (pr_No, pr_nogia, ictclearance_no, quotation_no, aoc_no, po_no) VALUES (:pr_No, :pr_nogia, :ictclearance_no, :quotationNo, :aoc_no, :po_no)");

						$stmt4->bindParam(':pr_No', $pr_No);
						$stmt4->bindParam(':pr_nogia', $pr_Nogia);
						$stmt4->bindParam(':ictclearance_no', $newICTno_crn);
						$stmt4->bindParam(':quotationNo', $quotation_no);
						$stmt4->bindParam(':aoc_no', $aoc_no);
						$stmt4->bindParam(':po_no', $po_no);
												

						if($stmt4->execute()){

							$presentICTpermonthcrn = $ictclearance_permonth;
							$newICTpermonth_crn = $presentICTpermonthcrn + 1;


							$stmt5 = $con->prepare("INSERT INTO tbl_ictcrn (ictclearance_permonth) VALUES (:ictclearance_permonth)");

							$stmt5->bindParam(':ictclearance_permonth', $newICTpermonth_crn);

							if($stmt5->execute()){
								$location = "OD-PICTS";

								if($_POST['txtPRno'] == "Not Yet Validated"){
									$stmt = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_location=:PICTSlocation, purchrequest_status=:newPRStatus, ictclearance_No=:ictclearance_No WHERE purchrequest_ID=:purchrequest_ID");

	
									// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
									$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
									$stmt->bindParam(':PICTSlocation', $location);				
									$stmt->bindParam(':newPRStatus', $newStatus);
									$stmt->bindParam(':ictclearance_No', $_POST['txtICTClearanceNo']);
									$stmt->bindParam(':purchrequest_ID', $_POST['txtPRID']);
								}
								else{
									$stmt = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus, ictclearance_No=:ictclearance_No WHERE purchrequest_No=:purchrequest_No");

	
									// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
									$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
									$stmt->bindParam(':newPRStatus', $newStatus);
									$stmt->bindParam(':ictclearance_No', $_POST['txtICTClearanceNo']);
									$stmt->bindParam(':purchrequest_No', $_POST['txtPRno']);
								}							

								if($stmt->execute()){
									// $output = "SUCCESS";
									echo '<script type="text/javascript">alert("SUCCESS! ICT Clearance created for PR No. '.$_POST['txtPRno'].'");</script>';
									echo '<script type="text/javascript">window.location.href = "../pages/attach_ictclearance.php";</script>';	
								}
								else{
									echo '<script type="text/javascript">alert("ERROR! Update PR Failed!");</script>';
								}
							}
							else{
								echo '<script type="text/javascript">alert("ERROR! ICT Clearance CRN per month Failed!");</script>';
							}
						}
						else{
							echo '<script type="text/javascript">alert("ERROR! ICT Clearance CRN Failed!");</script>';
						}
					}
					else{
						//do nothing
					}



					// var_dump($stmt2);
					// die();





					// $stmt3 = $con->prepare("UPDATE tbl_appointments SET appointmentStatus='FOR SIGNATURE' WHERE appointmentID=:appointmentID");
					// $stmt3->bindParam(':appointmentID', $_POST['txtpassAppointID']);

					// if($stmt3->execute()){
					// 	$presentTOURcrn = $_POST['txtTOURcrn'];
					// 	$newTOURcrn = $presentTOURcrn + 1;

					// 	$stmt4 = $con->prepare("INSERT INTO tbl_CRNs (crnRDD, crnLab, crnICYT, crnTours, crnPICTS) VALUES (:crnRDD, :crnLab, :crnICYT, :crnTours, :crnPICTS)");

					// 	$stmt4->bindParam(':crnRDD', $_POST['txtRDDcrn']);
					// 	$stmt4->bindParam(':crnLab', $_POST['txtLABcrn']);
					// 	$stmt4->bindParam(':crnICYT', $_POST['txtICYTcrn']);
					// 	$stmt4->bindParam(':crnTours', $newTOURcrn);
					// 	$stmt4->bindParam(':crnPICTS', $_POST['txtPICTScrn']);

					// 	if($stmt4->execute()){
					// 		$stmt = $con->prepare("INSERT INTO tbl_activityLogs (activityName, activityAction) VALUES (:fName, :actConfAction)");

				 //        	$actConfAction = "ACCOMPLISHED TIPS PLANT TOUR SERVICE FORM WITH AN ID OF $ID";

					// 		$stmt->bindParam(':fName', $_SESSION['AdminFullName']);
					// 		$stmt->bindParam(':actConfAction', $actConfAction);

					// 		if($stmt->execute()){
					// 			$_SESSION['TOURID'] = $ID;

					// 			// var_dump($_SESSION['Name']);
					// 			// die();

					// 			echo '<script type="text/javascript">alert("SUBMITTED SUCCESFULLY");</script>';
					// 			echo '<script type="text/javascript">window.location.href = "../pages/tourpdfsummary.php";</script>';
					// 			// sleep(3);
					// 			echo '<script type="text/javascript">window.open("../pages/requestSubmitted.php", "_blank");</script>';
					// 		}
					// 		else{
					// 			echo '<script type="text/javascript">alert("ERROR! ACTIVITY LOG FAILED");</script>';
					// 			echo '<script type="text/javascript">window.location.href = "../pages/tsd_service.php";</script>';
					// 		}
					// 	}
					// 	else{
					// 		echo '<script type="text/javascript">alert("ERROR! NEW CRN FAILED");</script>';
					// 	}
					// }
					// else{
					// 	echo '<script type="text/javascript">alert("ERROR! UPDATE APPOINTMENT STATUS FAILED");</script>';
					// }
				}
				else{
					echo '<script type="text/javascript">alert("ERROR! NO ID!");</script>';
				}
			}
			else{
				echo '<script type="text/javascript">alert("Error! ICT Clearance Submission Failed");</script>';
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
		// echo '<script type="text/javascript">alert("Error! Submit Failed");</script>';
		echo '<script type="text/javascript">alert("Error! "'. $e->getMessage() .');</script>';
		// echo '<script type="text/javascript">window.location.href = "../pages/training.php";</script>';
	}
	$con = null;	
}

?>