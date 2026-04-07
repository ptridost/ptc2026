<?php

if($_SESSION['AccountType'] == "PPGSS-GAA"){
	$query = "SELECT appID, appDate, appFilename, appDivision, appProject, appUploadedBy, appStatus FROM `tbl_app` WHERE appFilename LIKE '%GAA%' ORDER BY appID DESC";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$appID[] = $row['appID'];
		$appDate[] = $row['appDate'];
		$appFilename[] = $row['appFilename'];
		$appDivision[] = $row['appDivision'];
		$appProject[] = $row['appProject'];
		$appUploadedBy[] = $row['appUploadedBy'];
		$appStatus[] = $row['appStatus'];
		// $account_tempPassword = $row['account_tempPassword'];
		// $acct_displayName = $row['acct_displayName'];
		// $account_Status = $row['account_Status'];
	}
}

else if($_SESSION['AccountType'] == "PPGSS-GIA"){
	$query = "SELECT appID, appDate, appFilename, appDivision, appProject, appUploadedBy, appStatus FROM `tbl_app` WHERE appFilename LIKE '%GIA%' ORDER BY appID DESC";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$appID[] = $row['appID'];
		$appDate[] = $row['appDate'];
		$appFilename[] = $row['appFilename'];
		$appDivision[] = $row['appDivision'];
		$appProject[] = $row['appProject'];
		$appUploadedBy[] = $row['appUploadedBy'];
		$appStatus[] = $row['appStatus'];
		// $account_tempPassword = $row['account_tempPassword'];
		// $acct_displayName = $row['acct_displayName'];
		// $account_Status = $row['account_Status'];
	}
}

else if($_SESSION['AccountType'] == "PPGSS-PPMP"){
	$query = "SELECT appID, appDate, appFilename, appDivision, appProject, appUploadedBy, appStatus FROM `tbl_app` WHERE appFilename LIKE '%GIA%' OR appFilename LIKE '%GAA%' ORDER BY appID DESC";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$appID[] = $row['appID'];
		$appDate[] = $row['appDate'];
		$appFilename[] = $row['appFilename'];
		$appDivision[] = $row['appDivision'];
		$appProject[] = $row['appProject'];
		$appUploadedBy[] = $row['appUploadedBy'];
		$appStatus[] = $row['appStatus'];
		// $account_tempPassword = $row['account_tempPassword'];
		// $acct_displayName = $row['acct_displayName'];
		// $account_Status = $row['account_Status'];
	}
}

else if($_SESSION['AccountType'] == "End-User"){
	$query = "SELECT appID, appDate, appFilename, appDivision, appProject, appUploadedBy, appStatus FROM `tbl_app` WHERE appUploadedBy = '".$_SESSION['Name']."' OR appProject = '".$_SESSION['Project']."' ORDER BY appID DESC";
	$result = mysqli_query($conProcedural,$query);

	

	while ($row = mysqli_fetch_assoc($result)) {
		$appID[] = $row['appID'];
		$appDate[] = $row['appDate'];
		$appFilename[] = $row['appFilename'];
		$appDivision[] = $row['appDivision'];
		$appProject[] = $row['appProject'];
		$appUploadedBy[] = $row['appUploadedBy'];
		$appStatus[] = $row['appStatus'];
		// $account_tempPassword = $row['account_tempPassword'];
		// $acct_displayName = $row['acct_displayName'];
		// $account_Status = $row['account_Status'];
	}
}

else{
	$query = "SELECT appID, appDate, appFilename, appDivision, appProject, appUploadedBy, appStatus FROM `tbl_app` ORDER BY appID DESC";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$appID[] = $row['appID'];
		$appDate[] = $row['appDate'];
		$appFilename[] = $row['appFilename'];
		$appDivision[] = $row['appDivision'];
		$appProject[] = $row['appProject'];
		$appUploadedBy[] = $row['appUploadedBy'];
		$appStatus[] = $row['appStatus'];
		// $account_tempPassword = $row['account_tempPassword'];
		// $acct_displayName = $row['acct_displayName'];
		// $account_Status = $row['account_Status'];
	}
}


?>