<?php

if($_POST['txtDivision']){
	//for post submit ICT Clearance
	if($_POST['txtDivision'] == "Office of the Director"){
		$ptridivision = "OD";
	}
	else if($_POST['txtDivision'] == "Research and Development Division"){
		$ptridivision = "RDD";
	}
	else if($_POST['txtDivision'] == "Finance and Administrative Division"){
		$ptridivision = "FAD";
	}
	else if($_POST['txtDivision'] == "Technical Services Division"){
		$ptridivision = "TSD";
	}
	else{
		$ptridivision = $_POST['txtDivision'];
	}

	if($_POST['txtSection'] == "Planning and Information & Communication Technology Staff"){
		$ptrisection = "PICTS";
	}
	else if($_POST['txtSection'] == "Technology Transfer, Information, and Promotion Staff"){
		$ptrisection = "TIPS";
	}
	else if($_POST['txtSection'] == "Natural Fiber Utilization Section"){
		$ptrisection = "NFUS";
	}
	else if($_POST['txtSection'] == "Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section"){
		$ptrisection = "CDABUS";
	}
	else if($_POST['txtSection'] == "Property, Procurement and General Services Section"){
		$ptrisection = "PPGSS";
	}
	else if($_POST['txtSection'] == "Human Resources and Records Management Section"){
		$ptrisection = "HRRMS";
	}
	else if($_POST['txtSection'] == "Budget and Treasury Section"){
		$ptrisection = "BTS";
	}
	else if($_POST['txtSection'] == "Accounting Section"){
		$ptrisection = "ACCTG";
	}
	else if($_POST['txtSection'] == "Physical and Chemical Testing Section"){
		$ptrisection = "PCTS";
	}
	else if($_POST['txtSection'] == "Mechanical and Chemical Processing Section"){
		$ptrisection = "MCPS";
	}
	else{
		$ptrisection = $_POST['txtSection'];
	}
}
else{
	//for session submit PR
	if($_SESSION['Division'] == "Office of the Director"){
		$ptridivision = "OD";
	}
	else if($_SESSION['Division'] == "Research and Development Division"){
		$ptridivision = "RDD";
	}
	else if($_SESSION['Division'] == "Finance and Administrative Division"){
		$ptridivision = "FAD";
	}
	else if($_SESSION['Division'] == "Technical Services Division"){
		$ptridivision = "TSD";
	}
	else{
		$ptridivision = $_SESSION['Division'];
	}

	if($_SESSION['Section'] == "Planning and Information & Communication Technology Staff"){
		$ptrisection = "PICTS";
	}
	
	else if($_SESSION['Section'] == "Technology Transfer, Information, and Promotion Staff"){
		$ptrisection = "TIPS";
	}
	else if($_SESSION['Section'] == "Natural Fiber Utilization Section"){
		$ptrisection = "NFUS";
	}
	else if($_SESSION['Section'] == "Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section"){
		$ptrisection = "CDABUS";
	}
	else if($_SESSION['Section'] == "Property, Procurement and General Services Section"){
		$ptrisection = "PPGSS";
	}
	else if($_SESSION['Section'] == "Human Resources and Records Management Section"){
		$ptrisection = "HRRMS";
	}
	else if($_SESSION['Section'] == "Budget and Treasury Section"){
		$ptrisection = "BTS";
	}
	else if($_SESSION['Section'] == "Accounting Section"){
		$ptrisection = "ACCTG";
	}
	else if($_SESSION['Section'] == "Physical and Chemical Testing Section"){
		$ptrisection = "PCTS";
	}
	else if($_SESSION['Section'] == "Mechanical and Chemical Processing Section"){
		$ptrisection = "MCPS";
	}
	else{
		$ptrisection = $_SESSION['Section'];
	}
}


?>