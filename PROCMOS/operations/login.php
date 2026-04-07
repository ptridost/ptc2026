<?php

	$account_ID = "";
	$division = "";
	$section = "";
	$FullName = "";
	$pass ="";
	$type = "";



	if(isset($_POST['txtUsername'])){
		$query = "SELECT * FROM tbl_accounts WHERE account_Username = '". $_POST['txtUsername'] ."' and account_Password = '". $_POST['txtPassword'] ."'";
		$result = mysqli_query($conProcedural,$query);

		while ($row = mysqli_fetch_assoc($result)) {
			$account_ID = $row['account_ID'];
			$Username = $row['account_Username'];
			$pass = $row['account_Password'];
			$type = $row['account_Type'];
		}

		if(sha1($pass) == sha1($_POST['txtPassword'])){
			$query2 = "SELECT * FROM tbl_acctdetails WHERE account_ID = '". $account_ID ."'";
			$result2 = mysqli_query($conProcedural,$query2);

			while ($row2 = mysqli_fetch_assoc($result2)) {
				$FullName = $row2['acct_Name'];
				$division = $row2['acct_Division'];
				$section = $row2['acct_Section'];
				$project = $row2['acct_Project'];	
			}

			if(!empty($account_ID)){
				$stmt = $con->prepare("INSERT INTO tbl_logs (logName) VALUES (:logName)");

				$stmt->bindParam(':logName', $FullName);

				if($stmt->execute()){
					$query4 = "SELECT logID FROM tbl_logs ORDER BY logID DESC LIMIT 1";
					$result4 = mysqli_query($conProcedural,$query4);

					while ($row4 = mysqli_fetch_assoc($result4)) {
						$_SESSION['logID'] = $row4['logID'];
						
					}

					$_SESSION['account_ID'] = $account_ID;
					$_SESSION['Name'] = $FullName;
					$_SESSION['Username'] = $Username;
					$_SESSION['Division'] = $division;
					$_SESSION['Project'] = $project;
					$_SESSION['Section'] = $section;
					$_SESSION['AccountType'] = $type;


					echo '<script type="text/javascript">alert("Bonjour '.$_SESSION['Name'].'");</script>';

					if($type == "End-User"){

						// $query = "SELECT appFilename FROM `tbl_app` WHERE appUploadedBy = $FullName ORDER BY appID DESC";
						// $result = mysqli_query($conProcedural,$query);

						// while ($row = mysqli_fetch_assoc($result)) {
						// 	$appFilename = $row['appFilename'];
						// 	// $account_tempPassword = $row['account_tempPassword'];
						// 	// $acct_displayName = $row['acct_displayName'];
						// 	// $account_Status = $row['account_Status'];
						// }

						// if(!empty($appFilename)){
						// 	echo '<script type="text/javascript">window.location.href = "../pages/dashboard.php";</script>';
						// }
						// else{
						// 	echo '<script type="text/javascript">window.location.href = "../pages/ppmp.php";</script>';
						// }
						// if($Username == "494"){
						// 	echo '<script type="text/javascript">alert("ERROR! This account is no longer valid");</script>';
						// }
						// else{
							
						// }

						echo '<script type="text/javascript">window.location.href = "../pages/dashboard.php";</script>';
					}
					else if($type == "PPGSS"){
						echo '<script type="text/javascript">window.location.href = "../pages/dashboard.php";</script>';	
					}
					else if($type == "Administrator"){
						echo '<script type="text/javascript">window.location.href = "../admin/editpr.php";</script>';	
					}
					else{
						echo '<script type="text/javascript">window.location.href = "../pages/dashboard.php";</script>';
					}
				}
				else{
					echo '<script type="text/javascript">alert("ERROR! Logs failed");</script>';
				}
			}
			else{
				echo '<script type="text/javascript">alert("ERROR! No Account ID!");</script>';
			}
		}
		else{
			echo '<script type="text/javascript">alert("Incorrect Username or Password");</script>';
		}
	}
	else{
		//do nothing
	}

?>