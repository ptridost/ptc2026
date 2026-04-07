<?php
require_once("../DatabaseConnection/databaseconnection.php");

$query = "SELECT `account_ID`, `acct_Name`, `acct_Designation` FROM `tbl_acctdetails` WHERE `acct_Requestor`='YES'";
$result = mysqli_query($conProcedural,$query);

while ($row = mysqli_fetch_assoc($result)) {
	$landingaccount_ID[] = $row['account_ID'];
	$landingacct_Name[] = $row['acct_Name'];
	$landingacct_Designation[] = $row['acct_Designation'];
}

// var_dump($query);
// die();
// var_dump(count($serviceID));
// die();

?>