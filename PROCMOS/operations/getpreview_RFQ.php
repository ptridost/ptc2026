<?php
require_once("../DatabaseConnection/databaseconnection.php");

$query = "SELECT tbl_ictclearance.ictclearance_ID FROM `tbl_ictclearance` WHERE tbl_ictclearance.purchrequest_No = '$pr_NoQ'";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$ictclearance_ID = $row['ictclearance_ID'];
	
}

if(empty($ictclearance_ID)){
	$query = "SELECT account_ID, prd_Unit, prd_Qty, prd_Description FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE tbl_purchaserequest.purchrequest_No = '$pr_NoQ' ORDER BY purchaserequest_details.prd_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	while ($row = mysqli_fetch_assoc($result)) {
		$account_ID = $row['account_ID'];
		$prdUnit[] = $row['prd_Unit'];
		$ictdetails_description[] = $row['prd_Description'];
		$prd_qty[] = $row['prd_Qty'];
	}



	$query = "SELECT acct_Division, acct_Section FROM `tbl_accounts` INNER JOIN `tbl_acctdetails` on tbl_accounts.account_ID = tbl_acctdetails.account_ID WHERE tbl_accounts.account_ID = " . $account_ID;
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
	}

	//for session submit PR
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
	else{
		$ptridivision = $acct_Division;
	}

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

	$ictclearance_requisitioner = $ptridivision . "-" . $ptrisection;
}
else{

	if( strpos( $ictclearance_remarks, "With Non-ICT" ) !== false) {
		// die($ictclearance_remarks . "true non ict");
		$query = "SELECT account_ID, prd_Unit, prd_Qty, prd_Description FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE tbl_purchaserequest.purchrequest_No = '$pr_NoQ' ORDER BY purchaserequest_details.prd_ID DESC";
		$result = mysqli_query($conProcedural,$query);

		// var_dump($query);
		// die();

		while ($row = mysqli_fetch_assoc($result)) {
			$account_ID = $row['account_ID'];
			$prdUnit[] = $row['prd_Unit'];
			$ictdetails_description[] = $row['prd_Description'];
			$prd_qty[] = $row['prd_Qty'];
		}
	}
	else{
		// die($ictclearance_remarks . "false all ict");
		$query = "SELECT ictclearance_requisitioner, ictdetails_description, ictdetails_qty FROM `tbl_ictclearance` INNER JOIN `tbl_ictclearancedetails` on tbl_ictclearance.ictclearance_ID = tbl_ictclearancedetails.ictclearance_ID WHERE tbl_ictclearance.ictclearance_ID = '$ictclearance_ID' ORDER BY tbl_ictclearancedetails.ictdetails_ID DESC";
		$result = mysqli_query($conProcedural,$query);

		// var_dump($query);
		// die();

		while ($row = mysqli_fetch_assoc($result)) {
			$ictclearance_requisitioner = $row['ictclearance_requisitioner'];
			$ictdetails_description[] = $row['ictdetails_description'];
			$ictdetails_qty[] = $row['ictdetails_qty'];
			
		}
	}


	
}

// if($ictdetails_description == null){
// 	$query = "SELECT prd_Unit, prd_Description, prd_Qty FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE tbl_purchaserequest.purchrequest_ID = '$purchaserequestID'";
// 	$result = mysqli_query($conProcedural,$query);



// 	while ($row = mysqli_fetch_assoc($result)) {
// 		$prd_Unit[] = $row['prd_Unit'];
// 		$ictdetails_description[] = $row['prd_Description'];
// 		$prd_Qty[] = $row['prd_Qty'];
// 	}
// }
// else{

// }

// $ppgss_ID = $_SESSION['account_ID'];
//78 ID caloy / 3 ID Ma'am Malou 
//102 Marga
$query = "SELECT acct_signature FROM `tbl_accounts` INNER JOIN `tbl_acctdetails` on tbl_accounts.account_ID = tbl_acctdetails.account_ID WHERE tbl_accounts.account_ID = 102";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$ppgss_esig = $row['acct_signature'];
	
}

$query = "SELECT quotation_no, date_quotationno, pr_rfqDuration, pr_rfqDeliveryPeriod, pr_rfqPricePeriod FROM `tbl_purchaserequest` WHERE purchrequest_No = '$pr_NoQ'";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$Quotation_numcrn = $row['quotation_no'];
	$date_quotationno = $row['date_quotationno'];
	$pr_rfqDuration = $row['pr_rfqDuration'];
	$pr_rfqDeliveryPeriod = $row['pr_rfqDeliveryPeriod'];
	$pr_rfqPricePeriod = $row['pr_rfqPricePeriod'];
}


if(!empty($date_quotationno)){
	$dateQuotation = date_create($date_quotationno)->format('d-M-Y');
}
else{
	$dateQuotation = $nowDateOnlyISO;
}

// var_dump($ppgss_esig);
// die();

?>