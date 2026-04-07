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
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE tbl_accounts.account_ID = ".$_SESSION['account_ID']." OR purchrequest_charging LIKE '%".$_SESSION['Project']."%' ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

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
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (tbl_accounts.account_ID = ".$_SESSION['account_ID']." OR acct_Project LIKE '%".$_SESSION['Project']."%') ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	



}
else if($_SESSION['AccountType'] == "PPGSS"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'FAD-PPGSS' ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

}

else if($_SESSION['AccountType'] == "PPGSS-GAA"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'FAD-PPGSS' AND `purchrequest_charging` LIKE '%GAA%' ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	}

else if($_SESSION['AccountType'] == "PPGSS-GIA"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'FAD-PPGSS' AND `purchrequest_charging` LIKE '%GIA%' ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	}

else if($_SESSION['AccountType'] == "Chief, FAD"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Chief, FAD' ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	}

else if($_SESSION['AccountType'] == "Administrator"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) ORDER BY purchrequest_ID ASC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	}

else if($_SESSION['AccountType'] == "Director"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) ORDER BY purchrequest_ID ASC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	}
else if($_SESSION['AccountType'] == "Sec, Director"){
	
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) ORDER BY purchrequest_ID ASC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	$i = 1;
	
}
else{

	require_once("../accessory/validate_DivisionSection.php");

	$location = $ptridivision . "-" . $ptrisection;

	// var_dump($location);
	// die();

	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE tbl_purchaserequest.account_ID = ".$_SESSION['account_ID']." OR purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = '". $location ."' ORDER BY purchrequest_ID ASC";



	// var_dump($query);
	// die();

	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	
}

	$i = 1;

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
		$acct_Project = $row['purchrequest_charging'];

		// var_dump(date("l jS \of F Y h:i:s A", strtotime($pr_datetimelastaction, "+1 day")));
		$pr_datetimelastactionNew = date('Y-m-d', strtotime($pr_datetimelastaction));

		$diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
		$date1=date_create($pr_datetimelastactionNew);
		$date2=date_create($nowDateOnly);
		$diff=date_diff($date1,$date2);

		// var_dump($pr_datetimelastactionNew);
		// var_dump($nowDateOnly);

		// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
		// var_dump($diff);
		// var_dump($diff->format("%R%a days"));
		// die();

		$numberOfDaysDiff = $diff->format("%R%a days");
		if($numberOfDaysDiff > 30){
			echo '<tr id="row'.$i.'" class="overdue">
                      <td>'.$purchrequest_DateTime.'</td>
                      <td>'.$purchrequest_No.'</td>




                      <td>Not Yet Assigned</td>
                      <td>Not Yet Assigned</td>
                      <td>'.$purchrequest_location.'</td>
                      <td>'.$acct_Project.'</td>
                      <td>'.$purchrequest_status.'</td>
                  </tr>';
		}
		else{
			echo '<tr id="row'.$i.'">
                      <td>'.$purchrequest_DateTime.'</td>
                      <td>'.$purchrequest_No.'</td>




                      <td>Not Yet Assigned</td>
                      <td>Not Yet Assigned</td>
                      <td>'.$purchrequest_location.'</td>
                      <td>'.$acct_Project.'</td>
                      <td>'.$purchrequest_status.'</td>
                  </tr>';
		}

		  

        $i++;
	}

// $query = "SELECT purchrequest_ID, purchrequest_DateTime, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) ORDER BY purchrequest_ID DESC";
// $result = mysqli_query($conProcedural,$query);

// while ($row = mysqli_fetch_assoc($result)) {
// 	$purchrequest_ID[] = $row['purchrequest_ID'];
// 	$purchrequest_DateTime[] = $row['purchrequest_DateTime'];
// 	$account_ID[] = $row['account_ID'];
// 	$acct_Division[] = $row['acct_Division'];
// 	$acct_Section[] = $row['acct_Section'];
// }






// $query = "SELECT purchrequest_ID, purchrequest_DateTime, purchrequest_requestor, purchrequest_approvedBy, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) ORDER BY purchrequest_ID DESC";
// $result = mysqli_query($conProcedural,$query);

// while ($row = mysqli_fetch_assoc($result)) {
// 	$purchrequest_ID[] = $row['purchrequest_ID'];
// 	$purchrequest_DateTime[] = $row['purchrequest_DateTime'];
// 	$purchrequest_requestor[] = $row['purchrequest_requestor'];
// 	$purchrequest_approvedBy[] = $row['purchrequest_approvedBy'];
// 	$acct_Division[] = $row['acct_Division'];
// 	$acct_Section[] = $row['acct_Section'];
// }

// var_dump(count($serviceID));
// die();

?>