<?php
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['prNo'])){
	

	
	$query = "SELECT aoc_pdf, aoc_number FROM `tbl_aoc` WHERE pr_number = '".$_POST['prNo']."'";

	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$aocpdf_filename = $row['aoc_pdf'];
		$aoc_number = $row['aoc_number'];
	}

	$aocFilenameNumber = array($aocpdf_filename, $aoc_number);

	$aoc_data['filenumber'] = $aocFilenameNumber;

	echo json_encode($aoc_data);

	
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

