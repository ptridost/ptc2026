<?php
require_once("../DatabaseConnection/databaseconnection.php");
require_once("../operations/generateCRN.php");

if(isset($_POST['prNo'])){



	$prNo = $_POST['prNo'];

	$query = "SELECT tbl_ictclearance.ictclearance_ID FROM `tbl_ictclearance` WHERE tbl_ictclearance.purchrequest_No = '$prNo'";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	while ($row = mysqli_fetch_assoc($result)) {
		$ictclearance_ID = $row['ictclearance_ID'];
		
	}


	$query = "SELECT ictclearance_requisitioner, ictdetails_description, ictdetails_qty FROM `tbl_ictclearance` INNER JOIN `tbl_ictclearancedetails` on tbl_ictclearance.ictclearance_ID = tbl_ictclearancedetails.ictclearance_ID WHERE tbl_ictclearance.ictclearance_ID = '$ictclearance_ID'";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	while ($row = mysqli_fetch_assoc($result)) {
		$RFQ_EndUser = $row['ictclearance_requisitioner'];		
	}

	$query = "SELECT date_quotationno, account_ID FROM `tbl_purchaserequest` WHERE purchrequest_No = '$prNo'";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	while ($row = mysqli_fetch_assoc($result)) {
		$RFQ_Date = $row['date_quotationno'];	
		$end_user = $row['account_ID'];		
	}

	if(empty($RFQ_EndUser)){
		$query2= "SELECT acct_Division, acct_Section FROM tbl_acctdetails WHERE account_ID ='".$end_user."'";
		$result2 = mysqli_query($conProcedural,$query2);

		while ($row2 = mysqli_fetch_assoc($result2)) {
			$acct_Division = $row2['acct_Division'];	
			$acct_Section = $row2['acct_Section'];
		}

		if($acct_Division == "Office of the Director"){
			$ptridivision = "OD";
		}
		else if($acct_Division == "Research and Development Division"){
			$ptridivision = "RDD";
		}
		else if($acct_Division == "Finance and Administrative Division"){
			$ptridivision = "FAD";
		}
		else if($acct_Division == "Technical Services Division"){
			$ptridivision = "TSD";
		}
		// else{
		// 	$ptridivision = $_POST['txtDivision'];
		// }

		if($acct_Section == "Planning and Information & Communication Technology Staff"){
			$ptrisection = "PICTS";
		}
		
		else if($acct_Section == "Technology Transfer, Information, and Promotion Staff"){
			$ptrisection = "TIPS";
		}
		else if($acct_Section == "Natural Fiber Utilization Section"){
			$ptrisection = "NFUS";
		}
		else if($acct_Section == "Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section"){
			$ptrisection = "CDABUS";
		}
		else if($acct_Section == "Property, Procurement and General Services Section"){
			$ptrisection = "PPGSS";
		}
		else if($acct_Section == "Human Resources and Records Management Section"){
			$ptrisection = "HRRMS";
		}
		else if($acct_Section == "Budget and Treasury Section"){
			$ptrisection = "BTS";
		}
		else if($acct_Section == "Accounting Section"){
			$ptrisection = "ACCTG";
		}
		else if($acct_Section == "Physical and Chemical Testing Section"){
			$ptrisection = "PCTS";
		}
		else if($acct_Section == "Mechanical and Chemical Processing Section"){
			$ptrisection = "MCPS";
		}
		else{
			$ptrisection = $acct_Section;
		}
		// else{
		// 	$ptrisection = $_POST['txtSection'];
		// }
		$RFQ_EndUser = $ptridivision . "-" . $ptrisection;
	}
	else{
		//do nothing
	}

	

	// if($RFQ_Date == null){
	// 	$rfq_dateCRNenduser = array($nowDateOnlyISO, $Quotation_numcrn, $RFQ_EndUser);
	// }
	// else{
	// 	$rfq_dateCRNenduser = array($RFQ_Date, $Quotation_numcrn, $RFQ_EndUser);
	// }
	$newRFQ_Date = date_create($RFQ_Date)->format('d-M-Y');

	$rfq_dateCRNenduser = ($RFQ_Date == null) ? array($nowDateOnlyISO, $Quotation_numcrn, $RFQ_EndUser) : array($newRFQ_Date, $Quotation_numcrn, $RFQ_EndUser);

	// $rfq_dateCRNenduser = array($nowDateOnlyISO, $Quotation_numcrn, $RFQ_EndUser);
	$rfq_data['dce'] = $rfq_dateCRNenduser;

	echo json_encode($rfq_data);
























	

	
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

