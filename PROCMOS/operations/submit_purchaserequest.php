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

		$arrayStock = [];
		$arrayUnit = [];
		$arrayDesc = [];
		$arrayQty = [];
		$arrayUnitCost = [];
		$arrayTotalCost = [];

		require_once("../accessory/validate_DivisionSection.php");

		

		// $location = $ptridivision . "-" . $ptrisection;

		// $status = "For Signature (Requestor/Project Leader)";

		$location = "FAD-PPGSS";
		$status = "For Clearance (PPMP)";



		// if($ptridivision == "OD"){
			// $status = "For Signature (Requestor/Project Leader)";
		// }
		// else{
		// 	$location = "FAD-PPGSS";
		// 	$status = "For Clearance (PPMP)";
		// }

		for ($i=1; $i <=$_POST['txtNumberOfRows']; $i++) { 
			// $arrayweight = $_POST['txtweight'.$i];
			array_unshift($arrayStock, $_POST['txtstock'.$i]);
			array_unshift($arrayUnit, $_POST['txtUnitQty'.$i]);
			array_unshift($arrayDesc, $_POST['txtItem'.$i]);
			array_unshift($arrayQty, $_POST['txtQuantity'.$i]);
			array_unshift($arrayUnitCost, $_POST['txtUnitFee'.$i]);
			array_unshift($arrayTotalCost, $_POST['txtTotal'.$i]);
			// echo $arrayweight;
		}

		$query2= "SELECT account_ID FROM tbl_accounts WHERE account_Username ='".$_SESSION['Username']."'";
		$result2 = mysqli_query($conProcedural,$query2);

		while ($row2 = mysqli_fetch_assoc($result2)) {
			$account_ID = $row2['account_ID'];
		}

		if(!empty($account_ID)){
			// var_dump($finaltourDate);
			// var_dump($finaltourTime);
			require_once("validateApprovedBy.php");

			// var_dump($approvedBy);
			// die();

			$stmt = $con->prepare("INSERT INTO tbl_purchaserequest (account_ID, purchrequest_charging, purchrequest_purpose, purchrequest_totalfee, purchrequest_requestor, purchrequest_approvedBy, purchrequest_location, pr_lastprocessedby, pr_rfqDeliveryPeriod, pr_rfqPricePeriod, pr_rfqDeliveryPlace, purchrequest_status) VALUES (:account_ID, :purchrequest_charging, :purchrequest_purpose, :purchrequest_totalfee, :purchrequest_requestor, :purchrequest_approvedBy, :purchrequest_location, :pr_lastprocessedby, :pr_rfqDeliveryPeriod, :pr_rfqPricePeriod, :pr_rfqDeliveryPlace, :purchrequest_status)");

			$stmt->bindParam(':account_ID', $account_ID);
			// $stmt->bindParam(':purchrequest_totalcost', $_POST['txtFinalFee']);
			$stmt->bindParam(':purchrequest_charging', $_POST['txtCharging']);
			$stmt->bindParam(':purchrequest_purpose', $_POST['txtPurpose']);			
			$stmt->bindParam(':purchrequest_totalfee', $_POST['txtFinalFee']);			
			$stmt->bindParam(':purchrequest_requestor', $_POST['select_requestor']);
			$stmt->bindParam(':purchrequest_approvedBy', $approvedBy);
			$stmt->bindParam(':purchrequest_location', $location);
			$stmt->bindParam(':pr_lastprocessedby', $_SESSION['Name']);
			$stmt->bindParam(':pr_rfqDeliveryPeriod', $_POST['select_deliveryperiod']);
			$stmt->bindParam(':pr_rfqPricePeriod', $_POST['select_pricevalidity']);
			$stmt->bindParam(':pr_rfqDeliveryPlace', $_POST['txt_DeliveryPlace']);
			$stmt->bindParam(':purchrequest_status', $status);
				

			if($stmt->execute()){
				$query = "SELECT purchrequest_ID from tbl_purchaserequest ORDER BY purchrequest_ID DESC LIMIT 1";
				$result = mysqli_query($conProcedural,$query);
				
				while ($row = mysqli_fetch_assoc($result)) {
					$ID = $row['purchrequest_ID'];
				}
				

				if(isset($ID)){
					// var_dump($_POST['txtNumberOfRows']);
					// die();

					for ($j=0; $j<count($arrayDesc); $j++) { 
						
						$stmt2 = $con->prepare("INSERT INTO purchaserequest_details (purchrequest_ID, prd_Stock, prd_Unit, prd_Description, prd_Qty, prd_UnitCost, prd_TotalCost) VALUES (:purchrequest_ID, :purchrequest_Stock, :purchrequest_Unit, :purchrequest_Desc, :purchrequest_Qty, :purchrequest_UnitCost, :purchrequest_TotalCost)");

						$stmt2->bindParam(':purchrequest_ID', $ID);
						$stmt2->bindParam(':purchrequest_Stock', $arrayStock[$j]);
						$stmt2->bindParam(':purchrequest_Unit', $arrayUnit[$j]);
						$stmt2->bindParam(':purchrequest_Desc', nl2br(htmlentities($arrayDesc[$j])));
						$stmt2->bindParam(':purchrequest_Qty', $arrayQty[$j]);
						$stmt2->bindParam(':purchrequest_UnitCost', $arrayUnitCost[$j]);
						$stmt2->bindParam(':purchrequest_TotalCost', $arrayTotalCost[$j]);

						if($stmt2->execute()){
							//do nothing
						}
						else{
							echo '<script type="text/javascript">alert("ERROR! REQUEST FAILED!");</script>';
						}
					}



					// var_dump($stmt2);
					// die();

					// require_once("generateCRN.php");

					// $presentPRcrn = $pr_No;
					// $newPRno_crn = $presentPRcrn + 1;

					// // echo $newPRno_crn;

					// $stmt4 = $con->prepare("INSERT INTO tbl_CRNs (pr_No, ictclearance_no, quotation_no) VALUES (:pr_No, :ictclearance_no, :quotationNo)");

					// $stmt4->bindParam(':pr_No', $newPRno_crn);
					// $stmt4->bindParam(':ictclearance_no', $ictclearance_no);
					// $stmt4->bindParam(':quotationNo', $quotation_no);	

					echo '<script type="text/javascript">window.location.href = "../pages/print_pr_pdf.php?ID='.$ID.'&acctID='.$account_ID.'";</script>';

					// if($stmt4->execute()){
						
					// }
					// else{
					// 	echo '<script type="text/javascript">alert("ERROR! CRN FAILED!");</script>';
					// }






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
				echo '<script type="text/javascript">alert("Error! Tour Submit Failed");</script>';
			}
		}
		else{
			// echo "error";
			echo '<script type="text/javascript">alert("Error! No Tour Date & Time");</script>';
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
		echo '<script type="text/javascript">alert("Error! "'. $e->getMessage() .');</script>';
		// echo '<script type="text/javascript">window.location.href = "../pages/training.php";</script>';
	}
	$con = null;	
}

?>