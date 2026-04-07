<?php
require_once("../DatabaseConnection/databaseconnection.php");

	
	$query = "SELECT aoc_number FROM `tbl_aoc` WHERE aoc_pdf IS NULL ORDER BY `aoc_ID` DESC";

	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$aoc_number = $row['aoc_number'];
	}

	if(!is_null($aoc_number)){
		$_SESSION['successAOCno'] = $aoc_number;
		echo '<script type="text/javascript">alert("Please continue to upload the PDF for AOC no. '. $aoc_number .'"); $("#modalUploadAOC").modal();</script>';
	}
	else{
		//do nothing
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

