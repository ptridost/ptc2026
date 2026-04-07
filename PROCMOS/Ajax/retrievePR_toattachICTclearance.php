<?php
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['id'])){
	$purchaserequestID = $_POST['id'];

	$query = "SELECT account_ID, purchrequest_charging, purchrequest_requestor, prd_Description, prd_Qty, prd_Unit FROM `tbl_purchaserequest` INNER JOIN `purchaserequest_details` on tbl_purchaserequest.purchrequest_ID = purchaserequest_details.purchrequest_ID WHERE tbl_purchaserequest.purchrequest_ID = '$purchaserequestID' ORDER BY prd_ID DESC";

	$result = mysqli_query($conProcedural,$query);	

	while ($row = mysqli_fetch_assoc($result)) {
		$account_ID = $row['account_ID'];
		$purchrequest_charging = $row['purchrequest_charging'];
		$purchrequest_requestor = $row['purchrequest_requestor'];
		$prd_Description[] = strip_tags($row['prd_Description']);
		$pr_Qty[] = $row['prd_Qty'];
		$pr_Unit[] = $row['prd_Unit'];
	}

	if (strpos($purchrequest_charging, "GAA") !== false){
	    // echo "True";
		//for getting account ID
		$query = "SELECT acct_Division, acct_Section FROM `tbl_acctdetails` WHERE `account_ID` = '$account_ID'";
		$result = mysqli_query($conProcedural,$query);

		while ($row = mysqli_fetch_assoc($result)) {
			$acct_DivisionReq = $row['acct_Division'];
			$acct_SectionReq = $row['acct_Section'];
		}
	}
	else{
	    // echo "False";
	    //for getting requestor account
	    if($purchrequest_requestor == 5){
	    	$query = "SELECT acct_Division, acct_Section FROM `tbl_acctdetails` WHERE `account_ID` = '$account_ID'";
			$result = mysqli_query($conProcedural,$query);

			while ($row = mysqli_fetch_assoc($result)) {
				$acct_DivisionReq = $row['acct_Division'];
				$acct_SectionReq = $row['acct_Section'];
			}
	    }
	    else{
	    	$query = "SELECT acct_Division, acct_Section FROM `tbl_acctdetails` WHERE `account_ID` = '$purchrequest_requestor'";
			$result = mysqli_query($conProcedural,$query);

			while ($row = mysqli_fetch_assoc($result)) {
				$acct_DivisionReq = $row['acct_Division'];
				$acct_SectionReq = $row['acct_Section'];
			}
	    }
	}


	$pr_chargingdivisionsection = array($purchrequest_charging, $acct_DivisionReq, $acct_SectionReq);


	// $customerCategory = array($AllnumberG2C, $AllnumberG2B, $AllnumberG2G, $AllnumberG2A);
	// $customerClassification = array($ts_AllnumberG2A, $ts_AllnumberG2O, $ts_AllnumberG2E, $ts_AllnumberG2G, $ts_AllnumberG2P, $ts_AllnumberG2OFW, $ts_AllnumberG2N);
	// $customerEducation = array($tsLevelElem, $tsLevelHS, $tsLevelCollege, $tsLevelMasters, $tsLevelOthers);
	// $customerGender = array($maleCustomers, $femaleCustomers);
	// $customerAgeBracket = array($Allagebracket_0to20, $Allagebracket_21to30, $Allagebracket_31to40, $Allagebracket_41to50, $Allagebracket_51to59, $Allagebracket_60to80, $Allagebracket_81to100);
	// $customerRegion = array($region_NCR, $regionOne_Ilocos, $regionTwo_Cagayan, $regionThree_Central, $regionFourA_CALABARZON, $regionFourB_MIMAROPA, $regionFive_Bicol, $regionSix_WesternVisayas, $regionSeven_CentralVisayas, $regionEight_EasternVisayas, $regionNine_Zamboanga, $regionTen_Mindanao, $regionEleven_Davao, $regionTwelve_SOCCSKSARGEN, $region_CAR, $region_ARMM, $region_CARAGA);

	// $pr_data['cpr'] = $pr_chargingpurposerequestor;
	$pr_data['cds'] = $pr_chargingdivisionsection;
	$pr_data['particulars'] = $prd_Description;
	$pr_data['qty'] = $pr_Qty;
	$pr_data['unit'] = $pr_Unit;

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

