<?php
require_once("../DatabaseConnection/databaseconnection.php");

if($_POST['txtFinalFee'] <= 50000){
	$approvedBy = 9;
}
else{
	$query = "SELECT `account_ID` FROM `tbl_acctdetails` WHERE `acct_altOIC`='1'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$account_IDforAB = $row['account_ID'];
	}

	// var_dump($account_ID . "<br>");

	if(empty($account_IDforAB)){
		//no altOIC = default Approved By


		$approvedBy = 5;
	}
	else{
		//altOIC is activated for Approved By field

		$approvedBy = $account_IDforAB;
	}
}

// var_dump($query);
// die();
// var_dump(count($serviceID));
// die();

?>