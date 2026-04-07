<?php
require_once("../DatabaseConnection/databaseconnection.php");
require_once("../accessory/convertNumberToWords.php");

// echo "string test";

if(isset($_POST['prNo'])){

	// $query = "SELECT aoc_chosensupplier, supplierPrice FROM `tbl_aoc` INNER JOIN `tblaoc_details` ON tbl_aoc.aoc_ID = tblaoc_details.aoc_ID WHERE quotation_number = '". $_POST['rfqNo'] ."' AND aoc_number = '". $_POST['aocNo'] ."' ORDER BY `tbl_aoc`.aoc_ID DESC";

	$query = "SELECT aoc_ID, aoc_chosensupplier FROM `tbl_aoc` WHERE quotation_number = '". $_POST['rfqNo'] ."' AND aoc_number = '". $_POST['aocNo'] ."' ORDER BY aoc_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();	

	while ($row = mysqli_fetch_assoc($result)) {
		$aoc_ID = $row['aoc_ID'];
		$aoc_chosensupplier = $row['aoc_chosensupplier'];
	}



	$query = "SELECT supplierName, itemNo, quantity, unit, supplierDesc, supplierPrice FROM `tbl_aoc` INNER JOIN `tblaoc_details` ON tbl_aoc.aoc_ID = tblaoc_details.aoc_ID WHERE tbl_aoc.aoc_ID = '". $aoc_ID ."' AND chosenSupplier = 'YES' ORDER BY `tbl_aoc`.aoc_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// $query = "SELECT supplierPrice, quantity FROM `tblaoc_details` WHERE aoc_ID = '". $aoc_ID ."' AND supplierName = '". $aoc_chosensupplier ."' ORDER BY aocdetails_ID DESC";
	// $result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$supplierName[] = $row['supplierName'];
		$itemNo[] = $row['itemNo'];
		$unit[] = $row['unit'];
		$supplierDesc[] = $row['supplierDesc'];

		$temp_supplierPrice = $row['supplierPrice'];
		$temp_quantity = $row['quantity'];

		$all_Itemprice[] = $temp_supplierPrice * $temp_quantity;
	}

	$query = "SELECT quantity, supplierPrice FROM `tbl_aoc` INNER JOIN `tblaoc_details` ON tbl_aoc.aoc_ID = tblaoc_details.aoc_ID WHERE tbl_aoc.aoc_ID = '". $aoc_ID ."' AND chosenSupplier = 'YES' ORDER BY `tbl_aoc`.aoc_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// $query = "SELECT supplierPrice, quantity FROM `tblaoc_details` WHERE aoc_ID = '". $aoc_ID ."' AND supplierName = '". $aoc_chosensupplier ."' ORDER BY aocdetails_ID DESC";
	// $result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$supplierPrice[] = $row['supplierPrice'];
		$quantity[] = $row['quantity'];
	}

	$totalActualAmount = array_sum($all_Itemprice);

	$totalamountInWords = convertNumber($totalActualAmount);

	// var_dump($_POST['modePR']);
	// var_dump($_POST['poRemarks']);

	// var_dump($quantity[2]);

	$query = "SELECT rfq_Address, rfq_email, rfq_telNum FROM `tbl_rfq` WHERE rfq_No = '". $_POST['rfqNo'] ."' AND rfq_Company = '". $supplierName[0] ."' ORDER BY rfq_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();	

	while ($row = mysqli_fetch_assoc($result)) {
		$rfq_Address = $row['rfq_Address'];
		$rfq_email = $row['rfq_email'];
		$rfq_telNum = $row['rfq_telNum'];
	}

	$query = "SELECT purchrequest_charging, acct_Name, acct_Designation FROM `tbl_purchaserequest` INNER JOIN `tbl_acctdetails` ON tbl_purchaserequest.purchrequest_requestor = tbl_acctdetails.account_ID WHERE purchrequest_No = '". $_POST['prNo'] ."'  ORDER BY purchrequest_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();	

	while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_charging = $row['purchrequest_charging'];
		$acct_Name = $row['acct_Name'];
		$acct_Designation = $row['acct_Designation'];
	}

	// var_dump($rfq_Address);

	$stmt = $con->prepare("INSERT INTO tbl_po (aoc_ID, po_Number, po_mode, prNumber, po_Supplier, po_Address, po_Email, po_Telnum, po_remarks, po_TotalAmountinwords, po_TotalAmount) VALUES (:aoc_ID, :po_Number, :po_mode, :prNumber, :po_Supplier, :po_Address, :po_Email, :po_Telnum, :po_remarks, :po_TotalAmountinwords, :po_TotalAmount)");

	$stmt->bindParam(':aoc_ID', $aoc_ID);
	$stmt->bindParam(':po_Number', $_POST['poNo']);
	$stmt->bindParam(':po_mode', $_POST['modePR']);			
	$stmt->bindParam(':prNumber', $_POST['prNo']);			
	$stmt->bindParam(':po_Supplier', $supplierName[0]);
	$stmt->bindParam(':po_Address', $rfq_Address);
	$stmt->bindParam(':po_Email', $rfq_email);
	$stmt->bindParam(':po_Telnum', $rfq_telNum);
	$stmt->bindParam(':po_remarks', $_POST['poRemarks']);
	$stmt->bindParam(':po_TotalAmountinwords', $totalamountInWords);
	$stmt->bindParam(':po_TotalAmount', $totalActualAmount);

	if($stmt->execute()){
		$query = "SELECT po_ID from tbl_po ORDER BY po_ID DESC LIMIT 1";
		$result = mysqli_query($conProcedural,$query);
		
		while ($row = mysqli_fetch_assoc($result)) {
			$ID = $row['po_ID'];
		}
		

		if(isset($ID)){
			
			// var_dump($all_Itemprice[2]);

			for ($j=0; $j<count($supplierDesc); $j++) { 
				
				$stmt2 = $con->prepare("INSERT INTO tbl_podetails (po_ID, po_Unit, po_Description, po_Qty, po_UnitCost, po_totalCost) VALUES (:po_ID, :po_Unit, :po_Description, :po_Qty, :po_UnitCost, :po_totalCost)");

				$stmt2->bindParam(':po_ID', $ID);
				$stmt2->bindParam(':po_Unit', $unit[$j]);
				$stmt2->bindParam(':po_Description', $supplierDesc[$j]);
				$stmt2->bindParam(':po_Qty', $quantity[$j]);
				$stmt2->bindParam(':po_UnitCost', $supplierPrice[$j]);
				$stmt2->bindParam(':po_totalCost', $all_Itemprice[$j]);

				if($stmt2->execute()){
					//do nothing
					if($j == count($supplierDesc) - 1){

						require_once("../operations/generateCRN.php");

						$presentPOcrn = $po_no;
						$newPOno_crn = $presentPOcrn + 1;

						// echo $newPRno_crn;

						$stmt4 = $con->prepare("INSERT INTO tbl_CRNs (pr_No, pr_nogia, ictclearance_no, quotation_no, aoc_no, po_no) VALUES (:pr_No, :pr_nogia, :ictclearance_no, :quotationNo, :aoc_no, :po_no)");

						$stmt4->bindParam(':pr_No', $pr_No);
						$stmt4->bindParam(':pr_nogia', $pr_Nogia);
						$stmt4->bindParam(':ictclearance_no', $ictclearance_no);
						$stmt4->bindParam(':quotationNo', $quotation_no);
						$stmt4->bindParam(':aoc_no', $aoc_no);
						$stmt4->bindParam(':po_no', $newPOno_crn);
												

						if($stmt4->execute()){

							$newStatus = "PO Created";

							

							$stmt5 = $con->prepare("UPDATE tbl_purchaserequest SET po_no=:poNum, poNo_dtd=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus WHERE purchrequest_No=:prNo");

							$stmt5->bindParam(':poNum', $_POST['poNo']);
							$stmt5->bindParam(':newLastProcessedBy', $_SESSION['Name']);
							$stmt5->bindParam(':newPRStatus', $newStatus);
							$stmt5->bindParam(':prNo', $_POST['prNo']);

							// $_SESSION['successEsig'] = true;

							if($stmt5->execute()){
								$updateSuccess = "SUCCESS";

								$successMessage = "PO No. <strong>". $_POST['poNo'] ."</strong> has successfully been created";

								$arrayPOsuccess = array($successMessage, $ID);




								$createPO['success'] = $arrayPOsuccess;

								echo json_encode($createPO);	
							}
							else{
								$output = "ERROR: Updating PR new status failed!";
								echo json_encode($output);
							}


							
						}
						else{
							$output = "ERROR: CRN failed!";
							echo json_encode($output);
						}

						
					}
					else{
						//do nothing
					}
				}
				else{
					$output = "ERROR: PO details failed!";
					echo json_encode($output);
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

			// echo '<script type="text/javascript">window.location.href = "../pages/print_pr_pdf.php?ID='.$ID.'&acctID='.$account_ID.'";</script>';

			
		}
		else{
			$output = "ERROR: No PO ID!";
			echo json_encode($output);
		}
	}
	else{
		$output = "ERROR: Inserting PO into the database!";
		echo json_encode($output);
	}

	// $stmt = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus WHERE purchrequest_No=:prNo");

	// $stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	// $stmt->bindParam(':newPRStatus', $_POST['newStatus']);
	// $stmt->bindParam(':prNo', $_POST['prNo']);


	// if($stmt->execute()){
	// 	// $output = "SUCCESS";
		
	// 	echo "PR is successfully been processed for the next step: " . $_POST['newStatus'];		
	// }
	// else{
	// 	$output = "ERROR: Updating PR New Status and Process failed!";
	// 	echo $output;
	// }
	
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No PR Number parameter" .  " " . $_POST['prNo'];
	echo json_encode($output);
	// echo "PR No. <strong>". $_POST['prNo'] ."</strong> has successfully been cancelled";
	// 	$output = "ERROR: Updating PR New Status and Process failed!";
	// 	echo $output;
}
	


?>