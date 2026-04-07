<?php
require_once("../DatabaseConnection/databaseconnection.php");

if($_SESSION['AccountType'] == "PPGSS"){
	$query = "SELECT purchrequest_ID, quotation_no, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_location = 'FAD-PPGSS' AND purchrequest_status = 'For RFQ' ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	

	
}
else{
	$query = "SELECT purchrequest_ID, quotation_no, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (purchrequest_No != 'Not Yet Validated' AND purchrequest_location = 'FAD-PPGSS') AND (purchrequest_status = 'For RFQ' OR purchrequest_status = 'Approved PR') ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();
}

while ($row = mysqli_fetch_assoc($result)) {
	$purchrequest_ID[] = $row['purchrequest_ID'];
	$purchrequest_No[] = $row['purchrequest_No'];
	$prquotation_no[] = $row['quotation_no'];
	$prNo_dtd[] = $row['prNo_dtd'];
	$purchrequest_DateTime[] = $row['purchrequest_DateTime'];
	$purchrequest_location[] = $row['purchrequest_location'];
	$pr_datetimelastaction[] = $row['pr_datetimelastaction'];
	$pr_lastprocessedby[] = $row['pr_lastprocessedby'];
	$purchrequest_status[] = $row['purchrequest_status'];
	$account_ID[] = $row['account_ID'];
	$acct_Division[] = $row['acct_Division'];
	$acct_Section[] = $row['acct_Section'];
}

?>