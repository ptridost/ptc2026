<?php
require_once("../DatabaseConnection/databaseconnection.php");

// var_dump($_SESSION['AccountType']);
// die();

if($_SESSION['AccountType'] == "PICTS"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_location = 'OD-PICTS' AND purchrequest_status = 'For ICT Clearance 2' ORDER BY purchrequest_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	
	$i = 1;

	while ($row = mysqli_fetch_assoc($result)) {
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

		echo '<tr id="row'.$i.'">
	                            <td>'.$purchrequest_ID.'</td>
	                            <td>'.$purchrequest_No.'</td>
	                            <td>'.$purchrequest_DateTime.'</td>
	                            <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




	                            <td>'.$purchrequest_location.'</td>
	                            <td>'.$pr_datetimelastaction.'</td>
	                            <td>'.$pr_lastprocessedby.'</td>
	                            <td>'.$purchrequest_status.'</td>
	                            <td><a href="#txtPRno" title="Edit ICT Clearance" id="lnkUpdateICTclearance" class="btn formsbutton"><i class="fas fa-edit"></i></a></a></td>
	                            <td><a id="lnkAttachEsigICTclearance" class="btn formsbutton">✔</a></a></td>
	                        </tr>';

		$i++;
	}
	
}
else{
	// $query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (purchrequest_location = 'OD-PICTS' AND purchrequest_status = 'For ICT Clearance') ORDER BY purchrequest_ID ASC";

	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (purchrequest_location = 'OD-PICTS' AND purchrequest_status = 'For ICT Clearance') OR ((tbl_purchaserequest.account_ID = '10' OR tbl_purchaserequest.account_ID = '35') AND (purchrequest_No = 'Not Yet Validated')) ORDER BY purchrequest_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	$i = 1;

	while ($row = mysqli_fetch_assoc($result)) {
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

		echo '<tr id="row'.$i.'">
	                            <td>'.$purchrequest_ID.'</td>
	                            <td>'.$purchrequest_No.'</td>
	                            <td>'.$purchrequest_DateTime.'</td>
	                            <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




	                            <td>'.$purchrequest_location.'</td>
	                            <td>'.$pr_datetimelastaction.'</td>
	                            <td>'.$pr_lastprocessedby.'</td>
	                            <td>'.$purchrequest_status.'</td>
	                            <td><a id="lnkAttachICTclearance" href="#lblPRFormNo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create ICT Clearance</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
	                        </tr>';

		$i++;
	}
}

?>