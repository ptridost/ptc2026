<?php
require_once("../DatabaseConnection/databaseconnection.php");

// $query = "SELECT rdd_Date, rdd_Name, rdd_Designation, rdd_Company, rdd_Address, rdd_Email, rdd_Telephone, rdd_Fax, rdd_Description, rdd_Reference, rdd_DueDate, rdd_FinalTotal FROM `tbl_rddprocessing` WHERE rdd_ID = '$rddID'";



$query = "SELECT `acct_Division`, `acct_Section`, acct_Name, acct_Designation FROM `tbl_acctdetails` WHERE `account_ID` = '$acctID'";
$result = mysqli_query($conProcedural,$query);

while ($row = mysqli_fetch_assoc($result)) {
	$acct_DivisionEnd = $row['acct_Division'];
	$acct_SectionEnd = $row['acct_Section'];
	$acct_Name = $row['acct_Name'];
	$acct_Designation = $row['acct_Designation'];
}

// if($acctID == 65){
// 	$acct_SectionEnd = "Natural Fiber Utilization Section";
// }





$query = "SELECT purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_purpose, purchrequest_totalfee, purchrequest_requestor, purchrequest_approvedBy, pr_requestoresig, pr_ppgssesig, pr_chieffadesig, pr_approveesig,	date_prrequestoresig, ictclearance_No, po_no, prd_Stock, prd_Unit, prd_Description, prd_Qty, prd_UnitCost, prd_TotalCost FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE tbl_purchaserequest.purchrequest_ID = '$purchaserequestID' ORDER BY purchaserequest_details.prd_ID DESC";
$result = mysqli_query($conProcedural,$query);



while ($row = mysqli_fetch_assoc($result)) {
	$purchrequest_No = $row['purchrequest_No'];
	$prNo_dtd = $row['prNo_dtd'];
	$purchrequest_DateTime = $row['purchrequest_DateTime'];
	$purchrequest_charging = $row['purchrequest_charging'];
	$purchrequest_purpose = $row['purchrequest_purpose'];
	$purchrequest_totalfee = $row['purchrequest_totalfee'];
	$purchrequest_requestor = $row['purchrequest_requestor'];
	$purchrequest_approvedBy = $row['purchrequest_approvedBy'];
	$pr_requestoresig = $row['pr_requestoresig'];
	// $pr_ppgssesig = $row['pr_ppgssesig'];
	// $pr_chieffadesig = $row['pr_chieffadesig'];
	$pr_approveesig = $row['pr_approveesig'];
	$date_prrequestoresig = $row['date_prrequestoresig'];
	$ictclearance_No = $row['ictclearance_No'];
	$poNumber = $row['po_no'];
	$prd_Stock[] = $row['prd_Stock'];
	$prd_Unit[] = $row['prd_Unit'];
	$prd_Description[] = $row['prd_Description'];
	$prd_Qty[] = $row['prd_Qty'];
	$prd_UnitCost[] = $row['prd_UnitCost'];
	$prd_TotalCost[] = $row['prd_TotalCost'];
}

// die($query);


if(!empty($prNo_dtd)){
	$prNo_dtdNew = date('d-M-Y', strtotime($prNo_dtd));
}
else{
	//do nothing	
	$prNo_dtdNew = $prNo_dtd; 
}

// var_dump($prNo_dtd);
// die();



if(!empty($date_prrequestoresig)){
	$date_prrequestoresigNew = date('m/d/Y', strtotime($date_prrequestoresig));
}
else{
	$date_prrequestoresigNew = date('m/d/Y', strtotime($purchrequest_DateTime));
}

if($purchrequest_No == "Not Yet Validated"){
	$purchrequest_No = "";
}
else{
	//do nothing	
}


// var_dump($purchaserequestID);
// die();

$query = "SELECT tbl_ictclearance.ictclearance_ID, ictclearance_requisitioner, ictclearance_date, ictclearance_remarks, ictclearance_reviewedesig, ictclearance_approvedesig, ictdetails_qty, ictdetails_description FROM `tbl_ictclearance` INNER JOIN `tbl_ictclearancedetails` on tbl_ictclearance.ictclearance_ID = tbl_ictclearancedetails.ictclearance_ID WHERE ictclearance_No = '$ictclearance_No' ORDER BY tbl_ictclearancedetails.ictdetails_ID DESC";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$ictclearance_ID = $row['ictclearance_ID'];
	$ictclearance_requisitioner = $row['ictclearance_requisitioner'];
	$ictclearance_date = $row['ictclearance_date'];
	$ictclearance_remarks = $row['ictclearance_remarks'];
	$ictclearance_reviewedesig = $row['ictclearance_reviewedesig'];
	$ictclearance_approvedesig = $row['ictclearance_approvedesig'];
	$ictdetails_qty[] = $row['ictdetails_qty'];
	$ictdetails_description[] = $row['ictdetails_description'];
}

// var_dump($query);
// die();

if(!empty($ictclearance_date)){
	$ictclearance_dateNew = date('d-M-Y', strtotime($ictclearance_date));
}
else{
	//do nothing	
}




//for getting requestor account
$query = "SELECT acct_Name, acct_Designation, acct_Signature FROM `tbl_acctdetails` WHERE `account_ID` = '$purchrequest_requestor'";
$result = mysqli_query($conProcedural,$query);

while ($row = mysqli_fetch_assoc($result)) {
	$acct_DivisionReq = $row['acct_Division'];
	$acct_SectionReq = $row['acct_Section'];
	$req_Name = $row['acct_Name'];
	$req_Designation = $row['acct_Designation'];
	$req_Signature = $row['acct_Signature'];
}

if($req_Designation == "Director IV"){
	$req_Designation = "Project Leader";
}
else{
	//do nothing
	//as is
}

//($_SESSION['Username'] == "592") || 

// die($req_Designation);

// ($_SESSION['Username'] == "592") || Crystal Obis

if((($_SESSION['Username'] == "537") || ($_SESSION['Username'] == "629")  || ($_SESSION['Username'] == "415")  || ($_SESSION['Username'] == "461")) && ($req_Designation == "OIC, Research and Development Division")){
	$req_Designation = "Project Leader";

}
else if((($_SESSION['Username'] == "592") || ($_SESSION['Username'] == "537") || ($_SESSION['Username'] == "629")  || ($_SESSION['Username'] == "415")) && ($req_Designation == "Supervising Science Research Specialist")){
	$req_Designation = "Head, OD-TIPS";

}

else if((($_SESSION['Username'] == "536") || ($_SESSION['Username'] == "544") || ($_SESSION['Username'] == "633")) && ($purchrequest_requestor == "58" && $req_Designation == "Head, TIPS")){
	$req_Designation = "Project Leader";

}
else if(($_SESSION['Username'] == "536") && ($purchrequest_requestor == "71" && $req_Designation == "Sr. SRS, Project Leader")){
	$req_Designation = "Project Leader";

}
else{
	//do nothing
	//as is
}

// var_dump($req_Signature);
// die();


//for getting approved By account
$query = "SELECT acct_Name, acct_Designation, acct_Signature FROM `tbl_acctdetails` WHERE `account_ID` = '$purchrequest_approvedBy'";
$result = mysqli_query($conProcedural,$query);

while ($row = mysqli_fetch_assoc($result)) {
	$acct_DivisionAppr = $row['acct_Division'];
	$acct_SectionAppr = $row['acct_Section'];
	$appr_Name = $row['acct_Name'];
	$appr_Designation = $row['acct_Designation'];
	$appr_Signature = $row['acct_Signature'];
}

if(empty($ictclearance_ID)){
	$query = "SELECT account_ID, prd_Unit, prd_Qty, prd_Description FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE tbl_purchaserequest.purchrequest_No = '$purchrequest_No' ORDER BY purchaserequest_details.prd_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	while ($row = mysqli_fetch_assoc($result)) {
		$account_ID = $row['account_ID'];
		$prdUnit[] = $row['prd_Unit'];
		$RFQictdetails_description[] = $row['prd_Description'];
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
		$query = "SELECT account_ID, prd_Unit, prd_Qty, prd_Description FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE tbl_purchaserequest.purchrequest_No = '$purchrequest_No' ORDER BY purchaserequest_details.prd_ID DESC";
		$result = mysqli_query($conProcedural,$query);

		// var_dump($query);
		// die();

		while ($row = mysqli_fetch_assoc($result)) {
			$account_ID = $row['account_ID'];
			$prdUnit[] = $row['prd_Unit'];
			$RFQictdetails_description[] = $row['prd_Description'];
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
			$RFQictdetails_description[] = $row['ictdetails_description'];
			$ictdetails_qtyrfq[] = $row['ictdetails_qty'];
			
		}
	}
}

//78 caloy or 3 malou
//102 Marga
$query = "SELECT acct_signature FROM `tbl_accounts` INNER JOIN `tbl_acctdetails` on tbl_accounts.account_ID = tbl_acctdetails.account_ID WHERE tbl_accounts.account_ID = 102";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$ppgss_esig = $row['acct_signature'];
	
}

$query = "SELECT quotation_no, date_quotationno, pr_rfqDuration, pr_rfqDeliveryPeriod, pr_rfqPricePeriod FROM `tbl_purchaserequest` WHERE purchrequest_No = '$purchrequest_No'";
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









//FOR PO
$query = "SELECT aoc_ID, po_Date, po_Number, po_mode, prNumber, po_Supplier, po_Address, po_Email, po_Telnum, po_remarks, po_TotalAmountinwords, po_TotalAmount, po_Unit, po_Description, po_Qty, po_UnitCost, po_totalCost FROM `tbl_po` INNER JOIN `tbl_podetails` on tbl_po.po_ID = tbl_podetails.po_ID WHERE po_Number = '$poNumber' ORDER BY tbl_po.po_ID DESC";
$result = mysqli_query($conProcedural,$query);



while ($row = mysqli_fetch_assoc($result)) {
	$aoc_ID = $row['aoc_ID'];
	$po_Date = $row['po_Date'];
	$po_Number = $row['po_Number'];
	$po_mode = $row['po_mode'];
	$prNumber = $row['prNumber'];
	$po_Supplier = $row['po_Supplier'];
	$po_Address = $row['po_Address'];
	$po_Email = $row['po_Email'];
	$po_Telnum = $row['po_Telnum'];
	$po_remarks = $row['po_remarks'];
	$po_TotalAmountinwords = $row['po_TotalAmountinwords'];
	$po_TotalAmount = $row['po_TotalAmount'];
	$po_Unit[] = $row['po_Unit'];
	$po_Description[] = $row['po_Description'];
	$po_Qty[] = $row['po_Qty'];
	$po_UnitCost[] = $row['po_UnitCost'];
	$po_totalCost[] = $row['po_totalCost'];
}

$query = "SELECT aoc_deliveryterm, aoc_paymentterm, aoc_placeofdelivery FROM `tbl_aoc` WHERE aoc_ID = '$aoc_ID'";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$aoc_deliveryterm = $row['aoc_deliveryterm'];
	$aoc_paymentterm = $row['aoc_paymentterm'];
	$aoc_placeofdelivery = $row['aoc_placeofdelivery'];
}

$query = "SELECT prNo_dtd, purchrequest_charging, acct_Name, acct_Designation FROM `tbl_purchaserequest` INNER JOIN `tbl_acctdetails` on tbl_purchaserequest.purchrequest_requestor = tbl_acctdetails.account_ID WHERE purchrequest_No = '$prNumber'";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$po_prNo_dtd = $row['prNo_dtd'];
	$po_purchrequest_charging = $row['purchrequest_charging'];
	$po_acct_Name = $row['acct_Name'];
	$po_acct_Designation = $row['acct_Designation'];
}

if(!empty($po_Date)){
	$po_DateNew = date('m/d/y', strtotime($po_Date));
}
else{
	//do nothing	
}

if(!empty($po_prNo_dtd)){
	$prNo_DateNew = date('m/d/y', strtotime($po_prNo_dtd));
}
else{
	//do nothing	
}

//DIRECTOR
$query = "SELECT acct_Name, acct_Designation FROM `tbl_acctdetails` WHERE account_ID = '5'";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$po_appr_Name = $row['acct_Name'];
	$po_appr_Designation = $row['acct_Designation'];

}

//ACCOUNTANT
$query = "SELECT acct_Name, acct_Designation FROM `tbl_acctdetails` WHERE account_ID = '63'";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();

while ($row = mysqli_fetch_assoc($result)) {
	$acctg_Name = $row['acct_Name'];
	$acctg_Designation = $row['acct_Designation'];

}
//FOR PO




?>

