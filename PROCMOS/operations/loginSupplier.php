<?php
	require_once("../DatabaseConnection/databaseconnection.php");

	

	if (!empty($_POST['txtloginEmail']) && !empty($_POST['txtloginPassword'])) {
		$email = $_POST['txtloginEmail'];
	    $password = $_POST['txtloginPassword'];

	    // Use prepared statements
	    $stmt = mysqli_prepare($conProcedural, "SELECT supplierID, supplierEmail, supplierName, supplier_Password, supplier_Status FROM tbl_suppliers WHERE supplierEmail = ? and supplier_Password = ? LIMIT 1");
	    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
	    mysqli_stmt_execute($stmt);
	    $result = mysqli_stmt_get_result($stmt);

	    if ($row = mysqli_fetch_assoc($result)) {
	        // Verify password (replace with password_verify() if using hashes)
	        if (sha1($row['supplier_Password']) === sha1($password)) {
	    	// die(sha1($row['supplier_Password']));
	        // if (password_verify(sha1($password), sha1($row['supplier_Password']))){
	            // Auth success
	            $_SESSION['suppID'] = $row['supplierID'];
	            $_SESSION['suppEmail'] = $row['supplierEmail'];
	            $_SESSION['suppName'] = $row['supplierName'];

	            echo '<script>alert("Bonjour '.$_SESSION['suppName'].'!"); window.location.href = "../supplier/dashboard.php";</script>';
	        } 
	        else {
	            echo '<script>alert("Incorrect Username or Password"); window.location.href = "../supplier/index.php";</script>';
	        }
	    } 
	    else {
	        echo '<script>alert("User not found!"); window.location.href = "../supplier/index.php";</script>';
	    }
	}
	else {
	    echo '<script>alert("Please input your Username/Password!"); window.location.href = "../supplier/index.php";</script>';
	}

	// if(isset($_POST['txtloginEmail'])){
	// 	$query = "SELECT * FROM tbl_suppliers WHERE supplierEmail = '". $_POST['txtloginEmail'] ."' and supplier_Password = '". $_POST['txtloginPassword'] ."' ORDER BY supplierID DESC LIMIT 1";
	// 	$result = mysqli_query($conProcedural,$query);

	// 	while ($row = mysqli_fetch_assoc($result)) {
	// 		$supplierID = $row['supplierID'];
	// 		$supplierEmail = $row['supplierEmail'];
	// 		$supplierName = $row['supplierName'];
	// 		$supplier_Password = $row['supplier_Password'];
	// 		$supplier_Status = $row['supplier_Status'];
	// 	}

	// 	// var_dump($_POST['txtloginPassword']);
	// 	// die();

	// 	if(sha1($supplier_Password) == sha1($_POST['txtloginPassword'])){
	// 		$stmt = $con->prepare("INSERT INTO tbl_logs (logName) VALUES (:logName)");

	// 		$stmt->bindParam(':logName', $FullName);

	// 		if($stmt->execute()){
	// 			$query4 = "SELECT logID FROM tbl_logs ORDER BY logID DESC LIMIT 1";
	// 			$result4 = mysqli_query($conProcedural,$query4);

	// 			while ($row4 = mysqli_fetch_assoc($result4)) {
	// 				$_SESSION['supplogID'] = $row4['logID'];
					
	// 			}

	// 			$_SESSION['suppID'] = $supplierID;
	// 			$_SESSION['suppEmail'] = $supplierEmail;
	// 			$_SESSION['suppName'] = $supplierName;

	// 			echo '<script type="text/javascript">alert("Bonjour '.$_SESSION['suppName'].'!"); window.location.href = "../supplier/dashboard.php";</script>';
	// 		}
	// 		else{
	// 			echo '<script type="text/javascript">alert("ERROR! Logs failed");</script>';
	// 		}
	// 	}
	// 	else{
	// 		echo '<script type="text/javascript">alert("Incorrect Username or Password");</script>';
	// 	}
	// }
	// else{
	// 	//do nothing
	// 	echo '<script type="text/javascript">alert("Please input your Username/Password!");</script>';
	// }

?>