<?php
require_once("../DatabaseConnection/databaseconnection.php");

$query = "SELECT aoc_ID, po_Date, po_Number, po_mode, prNumber, po_Supplier, po_Address, po_Email, po_Telnum, po_remarks, po_TotalAmountinwords, po_TotalAmount, po_conforme, po_conformedate, po_Unit, po_Description, po_Qty, po_UnitCost, po_totalCost FROM `tbl_po` INNER JOIN `tbl_podetails` on tbl_po.po_ID = tbl_podetails.po_ID WHERE tbl_po.po_ID = '$poID' ORDER BY tbl_podetails.podetails_ID DESC";
$result = mysqli_query($conProcedural,$query);

// var_dump($query);
// die();


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
	$po_conforme = $row['po_conforme'];
	$po_conformedate = $row['po_conformedate'];
	$po_Unit[] = $row['po_Unit'];
	$po_Description[] = $row['po_Description'];
	$po_Qty[] = $row['po_Qty'];
	$po_UnitCost[] = $row['po_UnitCost'];
	$po_totalCost[] = $row['po_totalCost'];
}

if($po_conforme){
	$query = "SELECT supplierContactPerson FROM `tbl_suppliers` WHERE supplierName = '$po_Supplier'";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	while ($row = mysqli_fetch_assoc($result)) {
		$supplierContactPerson = $row['supplierContactPerson'];
	}
}
else{
	//do nothing
}

// var_dump($supplierContactPerson);
// 	die();

// <td class="centered" colspan="3"><img src="'. encodeImageToBase64('../supplier/esigs/'.$po_conforme) .'" width="150" height="50"/ ></td>

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
	$prNo_dtd = $row['prNo_dtd'];
	$purchrequest_charging = $row['purchrequest_charging'];
	$acct_Name = $row['acct_Name'];
	$acct_Designation = $row['acct_Designation'];
}

if(!empty($po_Date)){
	$po_DateNew = date('m/d/y', strtotime($po_Date));
}
else{
	//do nothing	
}

if(!empty($prNo_dtd)){
	$prNo_DateNew = date('m/d/y', strtotime($prNo_dtd));
}
else{
	//do nothing	
}

if(!empty($po_conformedate)){
	$po_conformedateNew = date('m/d/y', strtotime($po_conformedate));
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
	$appr_Name = $row['acct_Name'];
	$appr_Designation = $row['acct_Designation'];

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



?>