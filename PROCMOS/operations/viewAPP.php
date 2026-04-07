<?php
require_once("../DatabaseConnection/databaseconnection.php");

	$query = "SELECT appFilename FROM `tbl_app` WHERE appUploadedBy = 'Marilou G. Agustin' AND appStatus = 'APPROVED' ORDER BY appID DESC";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$appFilename = $row['appFilename'];
		// $account_tempPassword = $row['account_tempPassword'];
		// $acct_displayName = $row['acct_displayName'];
		// $account_Status = $row['account_Status'];
	}

	if(!empty($appFilename)){
		// echo '<script type="text/javascript">window.open("../APPs/'.$appFilename.'", "_blank");</script>';
		// header("Location: ../APPs/".$appFilename.);
		// echo '<script type="text/javascript">alert("The APP is now downloading, please check your tab");</script>';
		// echo '<script type="text/javascript">window.location.href = "../pages/APP.php";</script>';
		// echo '<script type="text/javascript">window.location.href = "../APPs/'.$appFilename.'";</script>';
		header("Location: ../APPs/".$appFilename."");

	}
	else{
		echo '<script type="text/javascript">alert("Sorry! The Head, PPGSS has not uploaded the APP yet");</script>';
		echo '<script type="text/javascript">window.location.href = "../pages/APP.php";</script>';
	}

?>