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

	// if($_POST['validateForICT'] == "YES"){
	// 	//FOR ICT CLEARANCE
	// 	$location = "OD-PICTS";
	// // $DateTimeNow = "NOW()";
	// 	$newStatus = "For ICT Clearance";
	// }
	// else{
	// 	$location = "Chief, FAD";
	// // $DateTimeNow = "NOW()";
	// 	$newStatus = "For Clearance (Chief, FAD)";
	// }	

	$location = "FAD-PPGSS";
	$newStatus = "Approved PR";

	// $location = "Chief, FAD";
	// $newStatus = "For Clearance (Chief, FAD)";


	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_No=:purchrequest_No, prNo_dtd=NOW(), purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, pr_ppgssesig=:eSigattachment, date_prppgssesig=NOW(), pr_remarks=:pr_remarks, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:prID");

	$stmt->bindParam(':purchrequest_No', $_POST['prNumber']);
	$stmt->bindParam(':newLocation', $location);
	// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
	$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
	$stmt->bindParam(':eSigattachment', $acct_signature);
	$stmt->bindParam(':pr_remarks', $_POST['remarks']);
	$stmt->bindParam(':newPRStatus', $newStatus);
	$stmt->bindParam(':prID', $_POST['id']);

	

	if($stmt->execute()){
		// $output = "SUCCESS";

		require_once("../operations/generateCRN.php");

		if($_POST['validateGIAGAA'] == "GAA"){
			$presentPRno_crn = $pr_No;
			$newPRno_crn = $presentPRno_crn + 1;

			$stmt4 = $con->prepare("INSERT INTO tbl_CRNs (pr_No, pr_nogia, ictclearance_no, quotation_no, aoc_no, po_no) VALUES (:pr_No, :pr_nogia, :ictclearance_no, :quotationNo, :aoc_no, :po_no)");

			$stmt4->bindParam(':pr_No', $newPRno_crn);
			$stmt4->bindParam(':pr_nogia', $pr_Nogia);
			$stmt4->bindParam(':ictclearance_no', $ictclearance_no);
			$stmt4->bindParam(':quotationNo', $quotation_no);
			$stmt4->bindParam(':aoc_no', $aoc_no);
			$stmt4->bindParam(':po_no', $po_no);
		}
		else if($_POST['validateGIAGAA'] == "GIA"){
			$presentPRno_crn = $pr_Nogia;
			$newPRno_crn = $presentPRno_crn + 1;

			$stmt4 = $con->prepare("INSERT INTO tbl_CRNs (pr_No, pr_nogia, ictclearance_no, quotation_no, aoc_no, po_no) VALUES (:pr_No, :pr_nogia, :ictclearance_no, :quotationNo, :aoc_no, :po_no)");
			
			$stmt4->bindParam(':pr_No', $pr_No);
			$stmt4->bindParam(':pr_nogia', $newPRno_crn);
			$stmt4->bindParam(':ictclearance_no', $ictclearance_no);
			$stmt4->bindParam(':quotationNo', $quotation_no);
			$stmt4->bindParam(':aoc_no', $aoc_no);
			$stmt4->bindParam(':po_no', $po_no);
		}

		

		// $stmt4 = $con->prepare("INSERT INTO tbl_CRNs (pr_No) VALUES (:pr_No)");

		// $stmt4->bindParam(':pr_No', $newPRno_crn);
		

		if($stmt4->execute()){
			// echo "PR No. <strong>". $_POST['prNumber'] ."</strong> has been created and approved!";

			$query = "SELECT ictclearance_No FROM tbl_purchaserequest WHERE purchrequest_ID = '". $_POST['id'] ."'";
			$result = mysqli_query($conProcedural,$query);

			while ($row = mysqli_fetch_assoc($result)) {
				$ictNo = $row['ictclearance_No'];
			}

			if($ictNo == "Not Yet Assigned"){
				echo "PR No. <strong>". $_POST['prNumber'] ."</strong> has been created!";
			}
			else{
				$stmt = $con->prepare("UPDATE tbl_ictclearance SET purchrequest_No=:purchrequest_No WHERE ictclearance_No=:ictNo");

				$stmt->bindParam(':purchrequest_No', $_POST['prNumber']);
				$stmt->bindParam(':ictNo', $ictNo);

				if($stmt->execute()){
					echo "PR No. <strong>". $_POST['prNumber'] ."</strong> has been created!";
				}
				else{
					$output = "ERROR: Update ICT Clearance - PR Number failed!";
					echo $output;
				}
			}
		}
		else{
			$output = "ERROR: PR Number CRN failed!";
			echo $output;
		}
	}
	else{
		$output = "ERROR: Updating PR Number & E-signature failed! (PPGSS)";
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