<?php
require_once("../DatabaseConnection/databaseconnection.php");

if($_SESSION['AccountType'] == "End-User"){
	// $appointID = $_SESSION['ProceedID'];
	// require_once("../operations/verifyAddress.php");
	// require_once("../operations/getClient.php");
	// require_once("../operations/getCtourdetails.php");
	// // echo '<script type="text/javascript">alert("TEST");</script>';

	// if($appointmentService == "TIPS Plant Tour Services"){
	// 	//PROCEED
	// 	$serviceCRN = $appointmentService;
	// 	require_once("../operations/generateCRN.php");			
	// }
	// else{
	// 	echo '<script type="text/javascript">alert("FAILED");</script>';		
	// 	header("Location: index.php");
	// }
	// var_dump($_SESSION['Division']);
	// var_dump($_SESSION['Section']);
	// die();
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (tbl_accounts.account_ID = ".$_SESSION['account_ID']." OR acct_Project LIKE '%".$_SESSION['Project']."%') AND (purchrequest_No = 'Not Yet Validated' OR purchrequest_status = 'For Public Bidding' OR purchrequest_status = 'For Revision') ORDER BY purchrequest_ID ASC";
	

	// var_dump($query);
	// die();

	

	

}
else if($_SESSION['AccountType'] == "PICTS-Clearance"){
	// $appointID = $_SESSION['ProceedID'];
	// require_once("../operations/verifyAddress.php");
	// require_once("../operations/getClient.php");
	// require_once("../operations/getCtourdetails.php");
	// // echo '<script type="text/javascript">alert("TEST");</script>';

	// if($appointmentService == "TIPS Plant Tour Services"){
	// 	//PROCEED
	// 	$serviceCRN = $appointmentService;
	// 	require_once("../operations/generateCRN.php");			
	// }
	// else{
	// 	echo '<script type="text/javascript">alert("FAILED");</script>';		
	// 	header("Location: index.php");
	// }
	// var_dump($_SESSION['Division']);
	// var_dump($_SESSION['Section']);
	// die();
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (tbl_accounts.account_ID = ".$_SESSION['account_ID']." OR acct_Project LIKE '%".$_SESSION['Project']."%') AND purchrequest_No = 'Not Yet Validated' ORDER BY purchrequest_ID ASC";

}
else if($_SESSION['AccountType'] == "PPGSS"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'FAD-PPGSS' ORDER BY purchrequest_ID ASC";
}
else if($_SESSION['AccountType'] == "Chief, FAD"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Chief, FAD' AND purchrequest_No = 'Not Yet Validated' ORDER BY purchrequest_ID ASC";

}
else if($_SESSION['AccountType'] == "Director"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_No = 'Not Yet Validated' ORDER BY purchrequest_ID ASC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
}
else if($_SESSION['AccountType'] == "Administrator"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) ORDER BY purchrequest_ID ASC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
}
else if($_SESSION['AccountType'] == "Sec, Director"){
	// $appointID = $_SESSION['ProceedID'];
	// require_once("../operations/verifyAddress.php");
	// require_once("../operations/getClient.php");
	// require_once("../operations/getCtourdetails.php");
	// // echo '<script type="text/javascript">alert("TEST");</script>';

	// if($appointmentService == "TIPS Plant Tour Services"){
	// 	//PROCEED
	// 	$serviceCRN = $appointmentService;
	// 	require_once("../operations/generateCRN.php");			
	// }
	// else{
	// 	echo '<script type="text/javascript">alert("FAILED");</script>';		
	// 	header("Location: index.php");
	// }
	// var_dump($_SESSION['Division']);
	// var_dump($_SESSION['Section']);
	// die();
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (tbl_accounts.account_ID = ".$_SESSION['account_ID']." OR acct_Project LIKE '%".$_SESSION['Project']."%') AND purchrequest_No = 'Not Yet Validated' ORDER BY purchrequest_ID ASC";
}
else{

	if($_SESSION['Username'] == "140"){
		$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (tbl_accounts.account_ID = ".$_SESSION['account_ID']." OR acct_Project LIKE '%".$_SESSION['Project']."%') AND purchrequest_No = 'Not Yet Validated' ORDER BY purchrequest_ID ASC";
	}

	else{

		require_once("../accessory/validate_DivisionSection.php");

		$location = $ptridivision . "-" . $ptrisection;

		$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = '". $location ."') AND purchrequest_No = 'Not Yet Validated' ORDER BY purchrequest_ID ASC";
	}
}

	$result = mysqli_query($conProcedural,$query);

	$i = 1;

	if($_SESSION['AccountType'] == "Administrator"){
		while ($row = mysqli_fetch_assoc($result)) {
			// $purchrequest_ID[] = $row['purchrequest_ID'];
			// $purchrequest_DateTime[] = $row['purchrequest_DateTime'];
			// $account_ID[] = $row['account_ID'];
			// $acct_Division[] = $row['acct_Division'];
			// $acct_Section[] = $row['acct_Section'];
			$purchrequest_ID = $row['purchrequest_ID'];
			$purchrequest_No = $row['purchrequest_No'];
			$prNo_dtd = $row['prNo_dtd'];
			$purchrequest_DateTime = $row['purchrequest_DateTime'];
			$purchrequest_location = $row['purchrequest_location'];
			$pr_datetimelastaction = $row['pr_datetimelastaction'];
			$pr_lastprocessedby = $row['pr_lastprocessedby'];
			$purchrequest_status = $row['purchrequest_status'];
			$account_ID = $row['account_ID'];
			$acct_Division = $row['acct_Division'];
			$acct_Section = $row['acct_Section'];
			$acct_Project = $row['acct_Project'];

			echo '<tr id="row'.$i.'">
				    <td>'.$purchrequest_ID.'</td>
				    <td>'.$purchrequest_No.'</td>
				    <td>'.$purchrequest_DateTime.'</td>
				    




				    <td>'.$purchrequest_location.'</td>
				    <td>'.$pr_datetimelastaction.'</td>
				    <td>'.$pr_lastprocessedby.'</td>
				    <td>'.$acct_Project.'</td>
				    <td>'.$purchrequest_status.'</td>
				    <td><a id="lnkEditPR" href="#lblPRFormNo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Edit PR Details</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a><a id="lnkEditMore" href="#lblPRFormNo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Edit More PR</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
				</tr>';
		}
	}
	else{
		while ($row = mysqli_fetch_assoc($result)) {
			// $purchrequest_ID[] = $row['purchrequest_ID'];
			// $purchrequest_DateTime[] = $row['purchrequest_DateTime'];
			// $account_ID[] = $row['account_ID'];
			// $acct_Division[] = $row['acct_Division'];
			// $acct_Section[] = $row['acct_Section'];
			$purchrequest_ID = $row['purchrequest_ID'];
			$purchrequest_No = $row['purchrequest_No'];
			$prNo_dtd = $row['prNo_dtd'];
			$purchrequest_DateTime = $row['purchrequest_DateTime'];
			$purchrequest_location = $row['purchrequest_location'];
			$pr_datetimelastaction = $row['pr_datetimelastaction'];
			$pr_lastprocessedby = $row['pr_lastprocessedby'];
			$purchrequest_status = $row['purchrequest_status'];
			$account_ID = $row['account_ID'];
			$acct_Division = $row['acct_Division'];
			$acct_Section = $row['acct_Section'];
			$acct_Project = $row['acct_Project'];

			echo '<tr id="row'.$i.'">
				    <td>'.$purchrequest_ID.'</td>
				    <td>'.$purchrequest_No.'</td>
				    <td>'.$purchrequest_DateTime.'</td>
				    




				    <td>'.$purchrequest_location.'</td>
				    <td>'.$pr_datetimelastaction.'</td>
				    <td>'.$pr_lastprocessedby.'</td>
				    <td>'.$acct_Project.'</td>
				    <td>'.$purchrequest_status.'</td>
				    <td><a id="lnkEditPR" href="#lblPRFormNo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Edit This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
				</tr>';
		}
	}

	

	

?>