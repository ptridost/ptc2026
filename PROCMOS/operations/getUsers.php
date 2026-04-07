<?php
require_once("../DatabaseConnection/databaseconnection.php");

$query = "SELECT tbl_accounts.account_ID, acct_Name, account_Username, account_Password, acct_Designation, acct_Division, acct_Section, acct_Requestor, acct_altOIC, acct_signature FROM `tbl_accounts` INNER JOIN `tbl_acctdetails` on tbl_accounts.account_ID = tbl_acctdetails.account_ID ORDER BY tbl_accounts.account_ID DESC";
$result = mysqli_query($conProcedural,$query);

while ($row = mysqli_fetch_assoc($result)) {
	$account_ID[] = $row['account_ID'];
	$account_Username[] = $row['account_Username'];
	$account_Password[] = $row['account_Password'];
	$acct_Name[] = $row['acct_Name'];
	$acct_Designation[] = $row['acct_Designation'];
	$acct_Division[] = $row['acct_Division'];
	$acct_Section[] = $row['acct_Section'];
	$acct_Requestor[] = $row['acct_Requestor'];
	$acct_altOIC[] = $row['acct_altOIC'];
	$acct_signature[] = $row['acct_signature'];
	// $account_tempPassword = $row['account_tempPassword'];
	// $acct_displayName = $row['acct_displayName'];
	// $account_Status = $row['account_Status'];
}

?>