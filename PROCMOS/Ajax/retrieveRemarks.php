<?php
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['id'])){
	$purchaserequestID = $_POST['id'];


	$query = "SELECT pr_Remarks FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE tbl_purchaserequest.purchrequest_ID = '$purchaserequestID'";
	$result = mysqli_query($conProcedural,$query);



	while ($row = mysqli_fetch_assoc($result)) {
		$pr_Remarks = $row['pr_Remarks'];
	}

	if(!empty($pr_Remarks)){
		echo $pr_Remarks . "\n\n\n\n(Please revise this in Edit PR page)";
	}
	else{
		echo "PR HAS NO REMARKS";	
	}

	

	
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

