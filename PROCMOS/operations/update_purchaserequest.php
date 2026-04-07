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

		// var_dump($schedDate);
		// die();

		$arrayStock = [];
		$arrayUnit = [];
		$arrayDesc = [];
		$arrayQty = [];
		$arrayUnitCost = [];
		$arrayTotalCost = [];

		require_once("../accessory/validate_DivisionSection.php");

		$location = $ptridivision . "-" . $ptrisection;


		// $status = "For Signature (Requestor/Project Leader)";

		$ID = $_POST['txtPR_ID'];
		

		// var_dump($_POST['txtNumberOfRows']);
		// die();

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
		// var_dump(max(count($_POST['chkLabfaci']), count($_POST['chkPilotfaci']), $_POST['chkOthersfaci']));
		// die();

		// $query2= "SELECT account_ID FROM tbl_accounts WHERE account_Username ='".$_SESSION['Username']."'";
		// $result2 = mysqli_query($conProcedural,$query2);

		// while ($row2 = mysqli_fetch_assoc($result2)) {
		// 	$account_ID = $row2['account_ID'];
		// }

		$query2= "SELECT account_ID FROM tbl_purchaserequest WHERE purchrequest_ID ='".$_POST['txtPR_ID']."'";
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



			if($_SESSION['AccountType'] == "Administrator"){
				$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_charging=:purchrequest_charging, purchrequest_purpose=:purchrequest_purpose, purchrequest_totalfee=:purchrequest_totalfee, purchrequest_requestor=:purchrequest_requestor, purchrequest_approvedBy=:purchrequest_approvedBy WHERE purchrequest_ID=:prID");
			}		
			else{
				$query2= "SELECT purchrequest_status, pr_lastprocessedby FROM tbl_purchaserequest WHERE purchrequest_ID ='".$_POST['txtPR_ID']."'";
				$result2 = mysqli_query($conProcedural,$query2);

				while ($row2 = mysqli_fetch_assoc($result2)) {
					$purchrequest_status = $row2['purchrequest_status'];
					$pr_lastprocessedby = $row2['pr_lastprocessedby'];
				}

				if($purchrequest_status == "For Revision"){
					if($pr_lastprocessedby == "Ciara Janina J. Imperial" || $pr_lastprocessedby == "Mark Ryan Aluan" || $pr_lastprocessedby == "Erlidona G. Versoza"){
						$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_status='For Signature Sec (Director)', pr_datetimelastaction=NOW(), purchrequest_charging=:purchrequest_charging, purchrequest_purpose=:purchrequest_purpose, purchrequest_totalfee=:purchrequest_totalfee, purchrequest_requestor=:purchrequest_requestor, purchrequest_approvedBy=:purchrequest_approvedBy WHERE purchrequest_ID=:prID");
					}
					else{
						$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_status='For Signature (Director)', pr_datetimelastaction=NOW(), purchrequest_charging=:purchrequest_charging, purchrequest_purpose=:purchrequest_purpose, purchrequest_totalfee=:purchrequest_totalfee, purchrequest_requestor=:purchrequest_requestor, purchrequest_approvedBy=:purchrequest_approvedBy WHERE purchrequest_ID=:prID");
					}
				}
				else{
					$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_DateTime=NOW(), pr_datetimelastaction=NOW(), purchrequest_charging=:purchrequest_charging, purchrequest_purpose=:purchrequest_purpose, purchrequest_totalfee=:purchrequest_totalfee, purchrequest_requestor=:purchrequest_requestor, purchrequest_approvedBy=:purchrequest_approvedBy WHERE purchrequest_ID=:prID");
				}
			}

			// $stmt = $con->prepare("INSERT INTO tbl_purchaserequest (account_ID, purchrequest_charging, purchrequest_purpose, purchrequest_totalfee, purchrequest_requestor, purchrequest_approvedBy, purchrequest_location, pr_lastprocessedby, purchrequest_status) VALUES (:account_ID, :purchrequest_charging, :purchrequest_purpose, :purchrequest_totalfee, :purchrequest_requestor, :purchrequest_approvedBy, :purchrequest_location, :pr_lastprocessedby, :purchrequest_status)");

			$stmt->bindParam(':purchrequest_charging', $_POST['txtCharging']);
			$stmt->bindParam(':purchrequest_purpose', $_POST['txtPurpose']);			
			$stmt->bindParam(':purchrequest_totalfee', $_POST['txtFinalFee']);			
			$stmt->bindParam(':purchrequest_requestor', $_POST['select_requestor']);
			$stmt->bindParam(':purchrequest_approvedBy', $approvedBy);
			$stmt->bindParam(':prID', $_POST['txtPR_ID']);
				
			if($stmt->execute()){
				

				$updatedStatus = "UPDATED";
				
				for ($j=0; $j<count($arrayDesc); $j++) { 
					
					$stmt2 = $con->prepare("INSERT INTO purchaserequest_details (purchrequest_ID, prd_Stock, prd_Unit, prd_Description, prd_Qty, prd_UnitCost, prd_TotalCost, prd_status) VALUES (:purchrequest_ID, :purchrequest_Stock, :purchrequest_Unit, :purchrequest_Desc, :purchrequest_Qty, :purchrequest_UnitCost, :purchrequest_TotalCost, :prd_status)");

					$stmt2->bindParam(':purchrequest_ID', $ID);
					$stmt2->bindParam(':purchrequest_Stock', $arrayStock[$j]);
					$stmt2->bindParam(':purchrequest_Unit', $arrayUnit[$j]);
					$stmt2->bindParam(':purchrequest_Desc', nl2br(htmlentities($arrayDesc[$j])));
					$stmt2->bindParam(':purchrequest_Qty', $arrayQty[$j]);
					$stmt2->bindParam(':purchrequest_UnitCost', $arrayUnitCost[$j]);
					$stmt2->bindParam(':purchrequest_TotalCost', $arrayTotalCost[$j]);
					$stmt2->bindParam(':prd_status', $updatedStatus);

					if($stmt2->execute()){
						//do nothing
						if($j == count($arrayDesc) - 1){
							//last insert
							$raw = "RAW";

							$query = "DELETE FROM purchaserequest_details WHERE purchrequest_ID = '". $_POST['txtPR_ID'] ."' AND prd_status = '". $raw ."'";
							$result = mysqli_query($conProcedural,$query);

							while ($row = mysqli_fetch_assoc($result)) {
								$deleteResult = $row['deleteResult'];
							}

							if(!$deleteResult){
								$stmt = $con->prepare("UPDATE purchaserequest_details SET prd_status=:prd_status WHERE purchrequest_ID=:prID AND prd_status=:former_status");

								// $stmt = $con->prepare("INSERT INTO tbl_purchaserequest (account_ID, purchrequest_charging, purchrequest_purpose, purchrequest_totalfee, purchrequest_requestor, purchrequest_approvedBy, purchrequest_location, pr_lastprocessedby, purchrequest_status) VALUES (:account_ID, :purchrequest_charging, :purchrequest_purpose, :purchrequest_totalfee, :purchrequest_requestor, :purchrequest_approvedBy, :purchrequest_location, :pr_lastprocessedby, :purchrequest_status)");

								$stmt->bindParam(':prd_status', $raw);
								$stmt->bindParam(':prID', $_POST['txtPR_ID']);
								$stmt->bindParam(':former_status', $updatedStatus);

								if($stmt->execute()){
									if($_SESSION['AccountType'] == "Administrator"){
										$remarks = $_POST['txtRemarks'];

										$stmt = $con->prepare("INSERT INTO tbl_activityLogs (activityUsername, activityName, activityAction) VALUES (:Username, :fName, :actConfAction)");
        								
							        	$actConfAction = "UPDATED PR details of PR ID: $ID, remarks: ($remarks)";

							        	$stmt->bindParam(':Username', $_SESSION['Username']);
										$stmt->bindParam(':fName', $_SESSION['Name']);
										$stmt->bindParam(':actConfAction', $actConfAction);

										if($stmt->execute()){
											echo '<script type="text/javascript">alert("SUCCESS! PR HAS BEEN UPDATED!"); window.location.href = "../pages/print_pr_pdf.php?ID='.$ID.'&acctID='.$account_ID.'";</script>';
										}
										else{
											echo '<script type="text/javascript">alert("ERROR! ACTIVITY LOGS FAILED!");</script>';
										}
									}
									else{
										$stmt = $con->prepare("INSERT INTO tbl_activityLogs (activityUsername, activityName, activityAction) VALUES (:Username, :fName, :actConfAction)");
        								
							        	$actConfAction = "UPDATED PR details of PR ID: $ID";

							        	$stmt->bindParam(':Username', $_SESSION['Username']);
										$stmt->bindParam(':fName', $_SESSION['Name']);
										$stmt->bindParam(':actConfAction', $actConfAction);

										if($stmt->execute()){
											echo '<script type="text/javascript">alert("SUCCESS! PR HAS BEEN UPDATED!"); window.location.href = "../pages/print_pr_pdf.php?ID='.$ID.'&acctID='.$account_ID.'";</script>';
										}
										else{
											echo '<script type="text/javascript">alert("ERROR! ACTIVITY LOGS FAILED!");</script>';
										}										
									}
								}
								else{
									echo '<script type="text/javascript">alert("ERROR! UPDATE PR DETAILS FAILED!");</script>';
								}
							}
							else{
								echo '<script type="text/javascript">alert("ERROR! DELETE RAW PR DETAILS FAILED!");</script>';
							}

							
						}
						else{
							//do nothing
						}
					}
					else{
						echo '<script type="text/javascript">alert("ERROR! UPDATE PR DETAILS FAILED!");</script>';
					}
				}

				
				
				


				

					
			}
			else{
				echo '<script type="text/javascript">alert("ERROR! UPDATE PR FAILED!");</script>';
			}
		}
		else{
			// echo "error";
			echo '<script type="text/javascript">alert("Error! No Account ID");</script>';
		}

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