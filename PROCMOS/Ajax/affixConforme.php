<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
require_once("../DatabaseConnection/databaseconnection.php");




if(isset($_POST['id'])){

	// // CSRF protection
	// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
	//     die("Invalid CSRF token.");

	// }

	// // Unset token to prevent reuse (optional but good practice)
	// unset($_SESSION['csrf_token']);

	// Use prepared statements
    $stmt = mysqli_prepare($conProcedural, "SELECT po_Number FROM tbl_po WHERE po_ID = ?");
    mysqli_stmt_bind_param($stmt, "s", $_POST['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

    	$poNumber = $row['po_Number'];
    }

	$target_dir = "../supplier/esigs/";

	$target_file = $target_dir . basename($_FILES["file"]["name"]);

	// if ( 0 < $_FILES['file']['error'] ) {
 //        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
 //    }
 //    else {
 //        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
 //    }

	$uploadOk = 1;
	$uploaded_FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	// Allow certain file formats

	if($uploaded_FileType != "png") 
	{

		$output = "ERROR: Sorry, only PNG files are allowed.";
		echo $output;

		$uploadOk = 0;
		
	}
	else
	{
		//okay file type
		$uploadOk = 1;
	}

	$uploadFilename = $_FILES["file"]["name"];

	if ($uploadOk == 0) {
		$output = "ERROR: Sorry, your file was not uploaded.";
		echo $output;
		// if everything is ok, try to upload file
	} 
	else {
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {


			$stmt = $con->prepare("UPDATE tbl_po SET po_conformedate=NOW(), po_conforme=:conforme_esig WHERE po_Number=:po_Number");

			$stmt->bindParam(':conforme_esig', $uploadFilename);
			$stmt->bindParam(':po_Number', $poNumber);

			// var_dump($stmt);
			// die();


			if($stmt->execute()){
				echo "PO # <strong>". $poNumber ."</strong> has been signed.";
				// echo "The file ". htmlspecialchars( basename( $_FILES["inputFile_RFQ"]["name"])). " has been uploaded.";
				// echo '<script type="text/javascript">alert("The file '. htmlspecialchars( basename( $_FILES["inputFile_RFQ"]["name"])) . ' has been uploaded.");</script>';
			}
			else{
				$output = "ERROR: Sorry, Uploading in Database failed";
				echo $output;
			}
		}
		else{
			$output = "ERROR: Sorry, Uploading file failed";
			echo $output;
		}
	}
}
else{
	// echo '<script type="text/javascript">alert("ERROR: Incomplete details");</script>';
}
?>