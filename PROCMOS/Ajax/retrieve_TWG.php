<?php
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['twg_group'])){
	$twg_group = $_POST['twg_group']; // ID


	$query = "SELECT twg_Name FROM `tbl_twgs` WHERE twg_Group = '$twg_group'";
	$result = mysqli_query($conProcedural,$query);



	while ($row = mysqli_fetch_assoc($result)) {
		$twg_Name[] = $row['twg_Name'];
		// $twg_Position[] = $row['twg_Position'];
	}


	$twg_chosen['name'] = $twg_Name;
	// $twg_chosen['position'] = $twg_Position;


	echo json_encode($twg_chosen);

	
}
else{
	echo '<script type="text/javascript">alert("ERROR!");</script>';
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

