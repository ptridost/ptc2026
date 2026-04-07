<?php
	require_once("../DatabaseConnection/databaseconnection.php");

	$stmt = $con->prepare("UPDATE tbl_logs SET logoutTime=NOW() WHERE logID=:logID");
	$stmt->bindParam(':logID', $_SESSION['logID']);

	if($stmt->execute()){
		if(!session_id())
		{
	    	session_start();
		}

		// session_destroy();
		unset($_SESSION['logID']);
		unset($_SESSION['Username']);
		unset($_SESSION['Division']);
		unset($_SESSION['Section']);
		unset($_SESSION['account_ID']);
		unset($_SESSION['Name']);
		unset($_SESSION['Project']);
		unset($_SESSION['AccountType']);

		header("Location: ../pages/index.php");
	}
	else{
		echo '<script type="text/javascript">alert("ERROR! LOGBOOK FAILED");</script>';
	}
?>