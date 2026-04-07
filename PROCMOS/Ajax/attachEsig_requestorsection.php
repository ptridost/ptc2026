<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['id'])){

	

	$query = "SELECT acct_signature FROM tbl_acctdetails WHERE account_ID = '". $_SESSION['account_ID'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$acct_signature = $row['acct_signature'];
	}

	$query = "SELECT purchrequest_location, purchrequest_requestor FROM tbl_purchaserequest WHERE purchrequest_ID = '". $_POST['id'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$location = $row['purchrequest_location'];
		$requestor = $row['purchrequest_requestor'];
	}

	if($location == "Chief, FAD"){
		$division = "FAD";
	}
	else{
		$division = substr($location,0,strripos($location,"-"));
	}
	// $section = trim($location,$division."-");
	
	// if($_SESSION['account_ID'] != $requestor){
	// 	if($_POST['oicESIG'] == "NO"){
	// 		$output = "ERROR: You are not the End-User that need to sign this PR! Override required";
	// 		echo $output;

	// 		die();
	// 	}
	// 	else{
	// 		//do nothing
	// 		$successOverride = $_POST['oicESIG'];
	// 	}
	// }
	// else{
	// 	//do nothing
	// }

	// var_dump($division);
	// die();
		

	
	





	if($division == "FAD"){
		if($successOverride == "YES"){
			// if($requestor == "58"){
			// 	if($_SESSION['Username'] == "140"){
			// 		$location = "FAD-PPGSS";
			// 		// $DateTimeNow = "NOW()";
			// 		$newStatus = "For Clearance (PPMP)";
			// 		// $newStatus = "For Clearance (Division Chief)";
			// 	}
			// 	else{
			// 		$output = "ERROR: You are not authorised to override signature!";
			// 		echo $output;

			// 		die();
			// 	}
			// }
			// else{
			// 	$output = "ERROR: You are not authorised to override signature!";
			// 	echo $output;

			// 	die();
			// }
		}
		else{
			$location = "Chief, FAD";
			// $DateTimeNow = "NOW()";
			$newStatus = "For Signature (Division Chief)";

			$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, pr_sectionheadesig=:eSigattachment, date_prsectionheadesig=NOW(), pr_remarks=:pr_remarks, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:prID");

			$stmt->bindParam(':newLocation', $location);
			// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
			$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
			$stmt->bindParam(':eSigattachment', $acct_signature);
			$stmt->bindParam(':pr_remarks', nl2br($_POST['remarks']));
			$stmt->bindParam(':newPRStatus', $newStatus);
			$stmt->bindParam(':prID', $_POST['id']);


			if($stmt->execute()){
				// $output = "SUCCESS";
				echo "PR ID # <strong>". $_POST['id'] ."</strong> has been cleared by the Section Head";
				// die("success");	
			}
			else{
				$output = "ERROR: Updating PR E-signature failed!";
				echo $output;

				die();
			}
		}
	}
	else{
		if($_SESSION['Username'] == "338"){
			$location = "FAD-PPGSS";
			// $DateTimeNow = "NOW()";
			$newStatus = "For Clearance (PPMP)";
			// $newStatus = "For Clearance (Division Chief)";
		}
		else{
			$location = "RDD-NFUS";
			$newStatus = "For Signature (Division Chief)";

			// var_dump($location);
			// die();

			$stmt = $con->prepare("UPDATE tbl_purchaserequest SET purchrequest_location=:newLocation, pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, pr_sectionheadesig=:eSigattachment, date_prsectionheadesig=NOW(), pr_remarks=:pr_remarks, purchrequest_status=:newPRStatus WHERE purchrequest_ID=:prID");

			$stmt->bindParam(':newLocation', $location);
			// $stmt->bindParam(':newDateTimeLastAction', $DateTimeNow);
			$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
			$stmt->bindParam(':eSigattachment', $acct_signature);
			$stmt->bindParam(':pr_remarks', nl2br($_POST['remarks']));
			$stmt->bindParam(':newPRStatus', $newStatus);
			$stmt->bindParam(':prID', $_POST['id']);


			if($stmt->execute()){
				// $output = "SUCCESS";
				echo "PR ID # <strong>". $_POST['id'] ."</strong> has been cleared by the Section Head";
				// die("success");	
			}
			else{
				$output = "ERROR: Updating PR E-signature failed!";
				echo $output;

				die();
			}
		}


		// if($_SESSION['Username'] == "078"){
		// 	$location = "FAD-PPGSS";
		// 	// $DateTimeNow = "NOW()";
		// 	$newStatus = "For Clearance (PPMP)";
		// 	// $newStatus = "For Clearance (Division Chief)";
		// }
		// else if($_SESSION['Username'] == "120"){
		// 	$location = "FAD-PPGSS";
		// 	// $DateTimeNow = "NOW()";
		// 	$newStatus = "For Clearance (PPMP)";
		// 	// $newStatus = "For Clearance (Division Chief)";

		// }
		// else if($_SESSION['Username'] == "192"){
		// 	if($successOverride == "YES"){
		// 		$location = "FAD-PPGSS";
		// 		// $DateTimeNow = "NOW()";
		// 		$newStatus = "For Clearance (PPMP)";
		// 		// $newStatus = "For Clearance (Division Chief)";
		// 	}
		// 	else{
		// 		$location = "TSD-MCPS";
		// 		// $DateTimeNow = "NOW()";
		// 		$newStatus = "For Clearance (Division Chief)";
		// 		// $newStatus = "For Clearance (Division Chief)";
		// 	}
			
		// }
		// else if($_SESSION['Username'] == "211"){
		// 	if($successOverride == "YES"){
		// 		$location = "FAD-PPGSS";
		// 		// $DateTimeNow = "NOW()";
		// 		$newStatus = "For Clearance (PPMP)";
		// 		// $newStatus = "For Clearance (Division Chief)";
		// 	}
		// 	else{
		// 		$location = "RDD-NFUS";
		// 		// $DateTimeNow = "NOW()";
		// 		$newStatus = "For Clearance (Division Chief)";
		// 		// $newStatus = "For Clearance (Division Chief)";
		// 	}
		// }
		// else if($_SESSION['Username'] == "118"){
		// 	$location = "FAD-PPGSS";
		// 	// $DateTimeNow = "NOW()";
		// 	$newStatus = "For Clearance (PPMP)";
		// }
		// else{
		// 	if($successOverride == "YES"){
		// 		$output = "ERROR: You are not authorised to override signature!";
		// 		echo $output;

		// 		die();
		// 	}
		// 	else{
		// 		$newStatus = "For Clearance (Section Head)";
		// 	}
		// }
	}


	
	
	


	

	// $output = "\nERROR: Updating PR E-signature failed!";
	// echo $output;

	// die();

}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No ID parameter";
	echo $output . " " . $_POST['id'];
}
	


?>