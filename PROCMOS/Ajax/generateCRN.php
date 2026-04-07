<?php
	require_once("../DatabaseConnection/databaseconnection.php");

	$currentYear = date("Y");
	$currentYearTour = date("y");
	$currentMonth = date("m");


	$query = "SELECT * FROM tbl_CRNs ORDER BY crnID DESC LIMIT 1";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$pr_No = $row['pr_no'];
		$pr_Nogia = $row['pr_nogia'];
		$ictclearance_no = $row['ictclearance_no'];
		$quotation_no = $row['quotation_no'];
		$aoc_no = $row['aoc_no'];
		$po_no = $row['po_no'];
	}	

	$crn = str_pad($pr_No, 4, 0, STR_PAD_LEFT); // = 0000
	
	$PRno_crn = $currentYearTour . $currentMonth . "-" . $crn;

	$crnGIA = str_pad($pr_Nogia, 4, 0, STR_PAD_LEFT); // = 0000
	
	$PRno_crnGIA = $currentYearTour . $currentMonth . "/" . $crnGIA;
	//running-number - currentYear=23 . currentMonth=09 (September)
	//0003-2309

	// var_dump($PRno_crn);
	// die();

	


	$crnQuotation = str_pad($quotation_no, 4, 0, STR_PAD_LEFT); // = 0000
	
	$Quotation_numcrn = $crnQuotation . "-" . $currentYearTour;
	//running-number - currentMonth=10 (October) . currentYear=23
	//003-1023
	


	$crnAOC = str_pad($aoc_no, 3, 0, STR_PAD_LEFT); // = 000
	
	$AOC_numcrn = $currentYearTour . "-" . $crnAOC;
	//running-number - currentMonth=10 (October) . currentYear=23
	//003-1023


	$crnPO = str_pad($po_no, 4, 0, STR_PAD_LEFT); // = 000
	
	$PO_numcrn = $currentYearTour . $currentMonth . "-" . $crnPO;
	//running-number - currentMonth=10 (October) . currentYear=23
	//003-1023
	

	$query = "SELECT ictclearance_permonth FROM tbl_ictcrn ORDER BY ictcrn_ID DESC LIMIT 1";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$ictclearance_permonth = $row['ictclearance_permonth'];
	}

	$crnICTclearance = str_pad($ictclearance_no, 3, 0, STR_PAD_LEFT); // = 000

	$crnICTclearance_permonth = str_pad($ictclearance_permonth, 2, 0, STR_PAD_LEFT); // = 00
	
	$ICTClearanceno_crn = $currentMonth . "-" . $crnICTclearance_permonth . "-" . $crnICTclearance;
	//running-number - currentMonth=10 (October) . currentYear=23
	//003-1023

    $prNumbers = array($PRno_crn, $PRno_crnGIA);

    $pr_data['Nos'] = $prNumbers;
	echo json_encode($pr_data);
?>
