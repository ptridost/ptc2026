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

		$arrayItemNo = [];
		$arrayQuantity = [];
		$arrayUnit = [];
		$arrayParticulars = [];
		$arraySupplierName = [];
		$arraySupplierPrice = [];
		$arraySupplierDesc = [];

		$newStatus = "For AOC 2";
		$aocStatus = "For Selection of End-User";

		require_once("../accessory/validate_DivisionSection.php");

		
		$prNumber = $_POST['txtPRno'];

		$totalRowsInPage = 1;
	
		for ($j=1; $j <=$_POST['txtNumberOfSuppliers'] ; $j++) { 
			for ($i=1; $i <=$_POST['txtNumberOfRows']; $i++) { 
				// $arrayweight = $_POST['txtweight'.$i];
				array_unshift($arrayItemNo, $_POST['txtItem'.$i]);
				array_unshift($arrayQuantity, $_POST['txtUnit'.$i]);
				array_unshift($arrayUnit, $_POST['txtQty'.$i]);
				array_unshift($arrayParticulars, $_POST['txtItemParticulars'.$i]);
				array_unshift($arraySupplierName, $_POST['txtSupplier'.$j]);
				array_unshift($arraySupplierPrice, $_POST['txtSupplierPrice'.$totalRowsInPage]);
				array_unshift($arraySupplierDesc, $_POST['txtItemDesc'.$totalRowsInPage]);

				$totalRowsInPage++;
			}
		}
		
		// var_dump(max(count($_POST['chkLabfaci']), count($_POST['chkPilotfaci']), $_POST['chkOthersfaci']));
		// die();

		$query2= "SELECT purchrequest_totalfee, purchrequest_requestor, purchrequest_charging FROM tbl_purchaserequest WHERE purchrequest_No ='".$_POST['txtPRno']."'";
		$result2 = mysqli_query($conProcedural,$query2);

		while ($row2 = mysqli_fetch_assoc($result2)) {
			$purchrequest_totalfee = $row2['purchrequest_totalfee'];
			$purchrequest_requestor = $row2['purchrequest_requestor'];
			$purchrequest_charging = $row2['purchrequest_charging'];
		}

		$query2= "SELECT acct_Division, acct_Section FROM tbl_acctdetails WHERE account_ID ='".$purchrequest_requestor."'";
		$result2 = mysqli_query($conProcedural,$query2);

		while ($row2 = mysqli_fetch_assoc($result2)) {
			$acct_Division = $row2['acct_Division'];	
			$acct_Section = $row2['acct_Section'];
		}

		if($acct_Division == "Office of the Director"){
			$ptridivision = "OD";
		}
		else if($acct_Division == "Research and Development Division"){
			$ptridivision = "RDD";
		}
		else if($acct_Division == "Finance and Administrative Division"){
			$ptridivision = "FAD";
		}
		else if($acct_Division == "Technical Services Division"){
			$ptridivision = "TSD";
		}
		// else{
		// 	$ptridivision = $_POST['txtDivision'];
		// }

		if($acct_Section == "Planning and Information & Communication Technology Staff"){
			$ptrisection = "PICTS";
		}
		
		else if($acct_Section == "Technology Transfer, Information, and Promotion Staff"){
			$ptrisection = "TIPS";
		}
		else if($acct_Section == "Natural Fiber Utilization Section"){
			$ptrisection = "NFUS";
		}
		else if($acct_Section == "Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section"){
			$ptrisection = "CDABUS";
		}
		else if($acct_Section == "Property, Procurement and General Services Section"){
			$ptrisection = "PPGSS";
		}
		else if($acct_Section == "Human Resources and Records Management Section"){
			$ptrisection = "HRRMS";
		}
		else if($acct_Section == "Budget and Treasury Section"){
			$ptrisection = "BTS";
		}
		else if($acct_Section == "Accounting Section"){
			$ptrisection = "ACCTG";
		}
		else if($acct_Section == "Physical and Chemical Testing Section"){
			$ptrisection = "PCTS";
		}
		else if($acct_Section == "Mechanical and Chemical Processing Section"){
			$ptrisection = "MCPS";
		}
		else{
			$ptrisection = $acct_Section;
		}
		// else{
		// 	$ptrisection = $_POST['txtSection'];
		// }
		$location = $ptridivision . "-" . $ptrisection;
		$location = "FAD-PPGSS";

		if(!empty($purchrequest_totalfee)){
			if($purchrequest_totalfee > 50000){
				$twg = $_POST['select_TWG'];
			}
			else{
				$twg = "N/A";
			}

			$stmt = $con->prepare("INSERT INTO tbl_aoc (aoc_number, pr_number, quotation_number, totalcost, aoc_twg, aoc_status) VALUES (:aoc_number, :pr_number, :quotation_number, :totalcost, :aoc_twg, :aoc_status)");

			$stmt->bindParam(':aoc_number', $_POST['txtAOCNo']);
			// $stmt->bindParam(':purchrequest_totalcost', $_POST['txtFinalFee']);
			$stmt->bindParam(':pr_number', $_POST['txtPRno']);
			$stmt->bindParam(':quotation_number', $_POST['txtqNo']);			
			$stmt->bindParam(':totalcost', $purchrequest_totalfee);			
			$stmt->bindParam(':aoc_twg', $twg);	
			$stmt->bindParam(':aoc_status', $aocStatus);
		

			if($stmt->execute()){
				$query = "SELECT aoc_ID from tbl_aoc ORDER BY aoc_ID DESC LIMIT 1";
				$result = mysqli_query($conProcedural,$query);
				
				while ($row = mysqli_fetch_assoc($result)) {
					$ID = $row['aoc_ID'];
				}

				
				

				if(isset($ID)){
					// var_dump($_POST['txtNumberOfRows']);
					// die();

					for ($m=0; $m<count($arrayParticulars); $m++) { 
						
						$stmt2 = $con->prepare("INSERT INTO tblaoc_details (aoc_ID, itemNo, quantity, unit, particulars, supplierName, supplierPrice, supplierDesc) VALUES (:aoc_ID, :itemNo, :quantity, :unit, :particulars, :supplierName, :supplierPrice, :supplierDesc)");

						$stmt2->bindParam(':aoc_ID', $ID);
						$stmt2->bindParam(':itemNo', $arrayItemNo[$m]);
						$stmt2->bindParam(':quantity', $arrayQuantity[$m]);
						$stmt2->bindParam(':unit', $arrayUnit[$m]);
						$stmt2->bindParam(':particulars', nl2br(htmlentities($arrayParticulars[$m])));
						$stmt2->bindParam(':supplierName', $arraySupplierName[$m]);
						$stmt2->bindParam(':supplierPrice', $arraySupplierPrice[$m]);
						$stmt2->bindParam(':supplierDesc', nl2br($arraySupplierDesc[$m]));

						if($stmt2->execute()){
							//do nothing
						}
						else{
							echo '<script type="text/javascript">alert("ERROR! AOC DETAILS FAILED!");</script>';
						}
					}


					$stmt3 = $con->prepare("UPDATE tbl_purchaserequest SET aoc_No=:aoc_No, aocNO_dtd=NOW(), pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_location=:newLocation, purchrequest_status=:newPRStatus WHERE purchrequest_No=:prNo");

					$stmt3->bindParam(':aoc_No', $_POST['txtAOCNo']);
					$stmt3->bindParam(':newLastProcessedBy', $_SESSION['Name']);
					$stmt3->bindParam(':newLocation', $location);
					$stmt3->bindParam(':newPRStatus', $newStatus);
					$stmt3->bindParam(':prNo', $_POST['txtPRno']);

					if($stmt3->execute()){

						require_once("../operations/generateCRN.php");

						$presentAOCno_crn = $aoc_no;
						$newAOCno_crn = $presentAOCno_crn + 1;

						$stmt4 = $con->prepare("INSERT INTO tbl_CRNs (pr_No, pr_nogia, ictclearance_no, quotation_no, aoc_no, po_no) VALUES (:pr_No, :pr_nogia, :ictclearance_no, :quotationNo, :aoc_no, :po_no)");

						$stmt4->bindParam(':pr_No', $pr_No);
						$stmt4->bindParam(':pr_nogia', $pr_Nogia);
						$stmt4->bindParam(':ictclearance_no', $ictclearance_no);
						$stmt4->bindParam(':quotationNo', $quotation_no);
						$stmt4->bindParam(':aoc_no', $newAOCno_crn);
						$stmt4->bindParam(':po_no', $po_no);


						if($stmt4->execute()){


							// $_SESSION['successAOCno'] = $_POST['txtAOCNo'];
							// echo '<script type="text/javascript">alert("SUCCESS! AOC No. '.$_POST['txtAOCNo'].' is now created. Please upload the PDF file in the next page"); history.back();</script>';
							echo '<script type="text/javascript">window.location.href = "../pages/print_aoc_pdf.php?ID='.$ID.'&prNo='.$prNumber.'";</script>';
							// echo '<script type="text/javascript">
							// 		window.open("../pages/print_aoc_pdf.php?ID='.$ID.'&prNo='.$prNumber.'", "_blank");</script>';
						}
						else{
							echo '<script type="text/javascript">alert("ERROR! CRN failed!");</script>';
						}
					}
					else{
						echo '<script type="text/javascript">alert("ERROR! PR Update failed!");</script>';
					}
				}
				else{
					echo '<script type="text/javascript">alert("ERROR! NO ID!");</script>';
				}
			}
			else{
				echo '<script type="text/javascript">alert("Error! AOC Submit Failed");</script>';
			}
		}
		else{
			echo '<script type="text/javascript">alert("Error! No Total Cost");</script>';
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
	die();	
}

?>