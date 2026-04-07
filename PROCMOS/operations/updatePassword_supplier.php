<?php
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['txtTempPass'])){
	try {
		$stmt = $con->prepare("UPDATE tbl_suppliers SET supplier_Password=:newPW WHERE supplierEmail=:supplierEmail AND supplier_Password=:tempPW");
			$stmt->bindParam(':newPW', $_POST['txtConfirmPass']);
			$stmt->bindParam(':supplierEmail', $_SESSION['suppEmail']);
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
			echo '<script type="text/javascript">alert("ERROR! PASSWORD UPDATING FAILED"); history.back();</script>';
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