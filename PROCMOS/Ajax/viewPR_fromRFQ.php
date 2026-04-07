<?php
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['id'])){
	$purchaserequestNo = $_POST['id'];


	$query = "SELECT purchrequest_ID, account_ID FROM `tbl_purchaserequest` WHERE tbl_purchaserequest.purchrequest_No = '$purchaserequestNo'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_ID = $row['purchrequest_ID'];
		$account_ID = $row['account_ID'];
	}

	

	

	$prID_acctID = array($purchrequest_ID, $account_ID);
	$pr_rfq_data['IDs'] = $prID_acctID;

	echo json_encode($pr_rfq_data);

	

	
}
else{
	echo "ERROR: NO ID PARAMETER";
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

