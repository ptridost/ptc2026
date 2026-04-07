<?php
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['id'])){
	$purchaserequestID = $_POST['id'];
	$purchaserequestNumber = $_POST['prNo'];
	$ictNumber = $_POST['ictNo'];
	$quotationNumber = $_POST['rfqNo'];




	$query = "SELECT purchrequest_charging, purchrequest_requestor, purchrequest_totalfee FROM `tbl_purchaserequest` WHERE purchrequest_ID = '$purchaserequestID'";

	$result = mysqli_query($conProcedural,$query);	

	while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_charging = $row['purchrequest_charging'];
		$purchrequest_requestor = $row['purchrequest_requestor'];
		$purchrequest_totalfee = $row['purchrequest_totalfee'];
	}



	//for getting requestor account
	$query = "SELECT acct_Division, acct_Section FROM `tbl_acctdetails` WHERE `account_ID` = '$purchrequest_requestor'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_DivisionReq = $row['acct_Division'];
		$acct_SectionReq = $row['acct_Section'];
	}

	if($ictNumber != "Not Yet Assigned"){
		$query = "SELECT ictdetails_description FROM `tbl_ictclearance` INNER JOIN `tbl_ictclearancedetails` on tbl_ictclearance.ictclearance_ID = tbl_ictclearancedetails.ictclearance_ID WHERE purchrequest_No = '$purchaserequestNumber' ORDER BY ictdetails_ID DESC";
		$result = mysqli_query($conProcedural,$query);

		while ($row = mysqli_fetch_assoc($result)) {
			$ictdetails_description[] = strip_tags($row['ictdetails_description']);

		}

		$query = "SELECT prd_Qty, prd_Unit FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE purchrequest_No = '$purchaserequestNumber' ORDER BY prd_ID DESC";
		$result = mysqli_query($conProcedural,$query);

		while ($row = mysqli_fetch_assoc($result)) {
			$pr_Qty[] = $row['prd_Qty'];
			$pr_Unit[] = $row['prd_Unit'];
		}
	}
	else{
		$query = "SELECT prd_Description, prd_Qty, prd_Unit FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE purchrequest_No = '$purchaserequestNumber' ORDER BY prd_ID DESC";
		$result = mysqli_query($conProcedural,$query);

		while ($row = mysqli_fetch_assoc($result)) {
			$ictdetails_description[] = strip_tags($row['prd_Description']);
			$pr_Qty[] = $row['prd_Qty'];
			$pr_Unit[] = $row['prd_Unit'];
		}
	}

	$query = "SELECT rfq_Company, rfq_Address, rfq_suppPrice, rfq_suppDesc FROM `tbl_rfq` INNER JOIN `tbl_rfqdetails` on tbl_rfq.rfq_ID = tbl_rfqdetails.rfq_ID WHERE pr_No = '$purchaserequestNumber'";

	// $query = "SELECT rfq_Company, rfq_Address,  FROM `tbl_rfq` INNER JOIN `tbl_rfqdetails` on tbl_rfq.rfq_ID = tbl_rfqdetails.rfq_ID WHERE pr_No = '$purchaserequestNumber'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$rfq_Company[] = $row['rfq_Company'];
		$rfq_Address[] = $row['rfq_Address'];
		$rfq_suppPrice[] = $row['rfq_suppPrice'];
		$rfq_suppDesc[] = strip_tags($row['rfq_suppDesc']);
	}


	$pr_chargingdivisionsection = array($purchrequest_charging, $acct_DivisionReq, $acct_SectionReq, $purchrequest_totalfee);


	// $customerCategory = array($AllnumberG2C, $AllnumberG2B, $AllnumberG2G, $AllnumberG2A);
	// $customerClassification = array($ts_AllnumberG2A, $ts_AllnumberG2O, $ts_AllnumberG2E, $ts_AllnumberG2G, $ts_AllnumberG2P, $ts_AllnumberG2OFW, $ts_AllnumberG2N);
	// $customerEducation = array($tsLevelElem, $tsLevelHS, $tsLevelCollege, $tsLevelMasters, $tsLevelOthers);
	// $customerGender = array($maleCustomers, $femaleCustomers);
	// $customerAgeBracket = array($Allagebracket_0to20, $Allagebracket_21to30, $Allagebracket_31to40, $Allagebracket_41to50, $Allagebracket_51to59, $Allagebracket_60to80, $Allagebracket_81to100);
	// $customerRegion = array($region_NCR, $regionOne_Ilocos, $regionTwo_Cagayan, $regionThree_Central, $regionFourA_CALABARZON, $regionFourB_MIMAROPA, $regionFive_Bicol, $regionSix_WesternVisayas, $regionSeven_CentralVisayas, $regionEight_EasternVisayas, $regionNine_Zamboanga, $regionTen_Mindanao, $regionEleven_Davao, $regionTwelve_SOCCSKSARGEN, $region_CAR, $region_ARMM, $region_CARAGA);

	// $pr_data['cpr'] = $pr_chargingpurposerequestor;
	$pr_data['cds'] = $pr_chargingdivisionsection;
	$pr_data['particulars'] = $ictdetails_description;
	$pr_data['qty'] = $pr_Qty;
	$pr_data['unit'] = $pr_Unit;
	$pr_data['rfq'] = $rfq_Company;
	$pr_data['rfqAddress'] = $rfq_Address;
	$pr_data['suppPrice'] = $rfq_suppPrice;
	$pr_data['suppDesc'] = $rfq_suppDesc;

	// echo $pr_data;

	// $customerData['Category'] = $customerCategory;
	// $customerData['Classification'] = $customerClassification;
	// $customerData['Education'] = $customerEducation;
	// $customerData['Gender'] = $customerGender;
	// $customerData['Age'] = $customerAgeBracket;
	// $customerData['Region'] = $customerRegion;

	// $customerCategory['G2G'] = $AllnumberG2G;
	// $customerCategory['G2B'] = $AllnumberG2B;
	// $customerCategory['G2C'] = $AllnumberG2C;

	// echo count($satisfactionID);

	echo json_encode($pr_data);

	
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

