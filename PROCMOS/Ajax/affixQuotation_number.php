<?php
require_once("../DatabaseConnection/databaseconnection.php");
	
if(isset($_POST['quotationNo'])){

	$rfqStatus = "For RFQ";

	$stmt = $con->prepare("UPDATE tbl_purchaserequest SET quotation_no=:quotation_no, pr_lastprocessedby=:lastProcessor, date_quotationno=NOW(), pr_datetimelastaction=NOW(), pr_Category=:prCategory, pr_rfqDuration=:duration, purchrequest_status=:rfqStatus WHERE purchrequest_No=:prNo");

	$stmt->bindParam(':quotation_no', $_POST['quotationNo']);
	$stmt->bindParam(':lastProcessor', $_SESSION['Name']);
	$stmt->bindParam(':prNo', $_POST['pr_no']);

	$stmt->bindParam(':prCategory', $_POST['prcategory']);
	$stmt->bindParam(':duration', $_POST['rfqDuration']);
	$stmt->bindParam(':rfqStatus', $rfqStatus);

	// $action = "User: " .$_SESSION['Name']. " affixed Quotation No.: " . $_POST['quotationNo'] . " to PR No.: " . $_POST['pr_no'] .;

	if($stmt->execute()){
		// echo "SUCCESS UPDATE";
		
		// $output = "SUCCESS";

		require_once("../operations/generateCRN.php");

		$presentRFQcrn = $quotation_no;
		$newRFQno_crn = $presentRFQcrn + 1;

		// echo $newPRno_crn;

		$stmt4 = $con->prepare("INSERT INTO tbl_CRNs (pr_No, pr_nogia, ictclearance_no, quotation_no, aoc_no, po_no) VALUES (:pr_No, :pr_nogia, :ictclearance_no, :quotationNo, :aoc_no, :po_no)");

		$stmt4->bindParam(':pr_No', $pr_No);
		$stmt4->bindParam(':pr_nogia', $pr_Nogia);
		$stmt4->bindParam(':ictclearance_no', $ictclearance_no);
		$stmt4->bindParam(':quotationNo', $newRFQno_crn);
		$stmt4->bindParam(':aoc_no', $aoc_no);
		$stmt4->bindParam(':po_no', $po_no);

		if($stmt4->execute()){

			// $stmt5 = $con->prepare("INSERT INTO tbl_activitylogs (activityUsername, activityName, activityAction) VALUES (:activityUsername, :activityName, :activityAction)");

			// $stmt5->bindParam(':activityUsername', $_SESSION['Username']);
			// $stmt5->bindParam(':activityName', $_SESSION['Name']);
			// $stmt5->bindParam(':activityAction', $action);
			

			// if($stmt5->execute()){
			// 	echo "SUCCESS! Quotation Number: " . $_POST['quotationNo'] . " is attached to PR No: " . $_POST['pr_no'];
			// }
			// else{
			// 	echo '<script type="text/javascript">alert("ERROR! ACTIVITY LOG FAILED!");</script>';
			// }
		// echo $_SESSION['account_ID'];		
				echo "SUCCESS! Quotation Number: " . $_POST['quotationNo'] . " is attached to PR No: " . $_POST['pr_no'];
		}
		else{
			echo '<script type="text/javascript">alert("ERROR! CRN FAILED!");</script>';
		}		
	}
	else{
		$output = "ERROR: Updating affixing Quotation Number!";
		echo $output;
	}
}
else{
	echo "ERROR: NO ID PARAMETER" . $_POST['quotationNo'];
}

?>