<?php
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['id'])){
	$purchaserequestNo = $_POST['prNo'];
	$rfqID = $_POST['id'];
	$rfqDate = $_POST['rfqDate'];

	$query = "SELECT pr_rfqDuration FROM `tbl_purchaserequest` WHERE tbl_purchaserequest.purchrequest_No = '$purchaserequestNo'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$pr_rfqDuration = $row['pr_rfqDuration'];
	}

	$rfqDateNew = date('Y-m-d', strtotime($rfqDate));

	// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
	$date1=date_create($rfqDateNew);
	$date2=date_create($nowDateOnly);
	$diff=date_diff($date1,$date2);

	// var_dump($pr_datetimelastactionNew);
	// var_dump($nowDateOnly);

	// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
	// var_dump($diff);
	// var_dump($diff->format("%R%a days"));
	// die();

	$numberOfDaysDiff = $diff->format("%a");

	if($numberOfDaysDiff > $pr_rfqDuration){
		$openQuotation = "true";
	}
	else{
		$openQuotation = "false";

		$remainingDays = $pr_rfqDuration - $numberOfDaysDiff;

		$stmt = $con->prepare("INSERT INTO tbl_activityLogs (activityUsername, activityName, activityAction) VALUES (:Username, :fName, :actConfAction)");
        								
    	$actConfAction = "OPENED QUOTATIONS of PR NO. ".$purchaserequestNo.", remarks: FAILED! ".$remainingDays." days are still needed to pass by";

    	$stmt->bindParam(':Username', $_SESSION['Username']);
		$stmt->bindParam(':fName', $_SESSION['Name']);
		$stmt->bindParam(':actConfAction', $actConfAction);

		$stmt->execute();

		// if($stmt->execute()){
		// 	echo '<script type="text/javascript">alert("SUCCESS! PR HAS BEEN UPDATED!"); window.location.href = "../pages/print_pr_pdf.php?ID='.$ID.'&acctID='.$account_ID.'";</script>';
		// }
		// else{
		// 	echo '<script type="text/javascript">alert("ERROR! ACTIVITY LOGS FAILED!");</script>';
		// }
	}

	// echo $openQuotation;

	$rfq_validateRemainingDays = array($openQuotation, $remainingDays);
	$rfqData['validation'] = $rfq_validateRemainingDays;

	echo json_encode($rfqData);

	

	
}
else{
	$rfqData['error'] = $error;

	echo json_encode($rfqData);

	// echo "ERROR: NO ID PARAMETER";
	// echo '<script type="text/javascript">alert("ERROR!");</script>';
}



// $query = "SELECT `acct_Division`, `acct_Section`, acct_Name, acct_Designation FROM `tbl_acctdetails` WHERE `account_ID` = '$acctID'";
// $result = mysqli_query($conProcedural,$query);

// while ($row = mysqli_fetch_assoc($result)) {
// 	$acct_DivisionEnd = $row['acct_Division'];
// 	$acct_SectionEnd = $row['acct_Section'];
// 	$acct_Name = $row['acct_Name'];
// 	$acct_Designation = $row['acct_Designation'];
// }

// var_dump($req_Signature);
// die();





?>

