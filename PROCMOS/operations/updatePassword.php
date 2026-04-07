<?php
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['txtTempPass'])){
	try {
		$stmt = $con->prepare("UPDATE tbl_accounts SET account_Password=:newPW WHERE account_username=:acctUsername AND account_Password=:tempPW");
			$stmt->bindParam(':newPW', $_POST['txtConfirmPass']);
			$stmt->bindParam(':acctUsername', $_SESSION['Username']);
			$stmt->bindParam(':tempPW', $_POST['txtTempPass']);
			// $stmt->bindParam(':rddOP_ORDate', 'NOW()');

		if($stmt->execute()){
			$rowsUpdated = $stmt->rowCount();
			if($rowsUpdated > 0){
				echo '<script type="text/javascript">alert("SUCCESS! PASSWORD IS NOW UPDATED"); history.back();</script>';
			}
			else{
				echo '<script type="text/javascript">alert("ERROR! TEMPORARY PASSWORD DOES NOT MATCH WITH OUR RECORDS"); history.back();</script>';
			}
		}
		else{
			echo '<script type="text/javascript">alert("ERROR! PASSWORD UPDATING FAILED");</script>';
			if($_SESSION['AdminType'] == "Division Chief" || $_SESSION['AdminType'] == "Planning Officer" || $_SESSION['AdminType'] == "QMR" || $_SESSION['AdminType'] == "Director"){
				echo '<script type="text/javascript">window.location.href = "../director/reports.php";</script>';
			}
			else{
				echo '<script type="text/javascript">window.location.href = "../director/dashboard.php";</script>';
			}
		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
		echo '<script type="text/javascript">alert("Error:'.$e->getMessage().'"); history.back();</script>';
	}
	$con = null;
}
else{
	echo '<script type="text/javascript">alert("PLEASE COMPLETE THE FORM!"); history.back();</script>';
}
?>