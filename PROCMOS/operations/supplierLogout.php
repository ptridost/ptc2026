<?php
	require_once("../DatabaseConnection/databaseconnection.php");

	$stmt = $con->prepare("UPDATE tbl_logs SET logoutTime=NOW() WHERE logID=:logID");
	$stmt->bindParam(':logID', $_SESSION['supplogID']);

	if($stmt->execute()){
		if(!session_id())
		{
	    	session_start();
		}

		// session_destroy();
		unset($_SESSION['suppID']);
		unset($_SESSION['suppEmail']);
		unset($_SESSION['suppName']);

		header("Location: ../supplier/index.php");
	}
	else{
		echo '<script type="text/javascript">alert("ERROR! LOGBOOK FAILED");</script>';
	}
?>