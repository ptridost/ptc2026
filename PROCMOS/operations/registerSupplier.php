<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
require_once("../DatabaseConnection/databaseconnection.php");




if(isset($_POST['txtSuppEmail'])){

	// CSRF protection
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
	    // die("Invalid CSRF token.");

	}

	// Unset token to prevent reuse (optional but good practice)
	unset($_SESSION['csrf_token']);

	$target_dir = "../supplier/docs/";
	$allowedType = ['pdf'];

	if($_POST['select_BusinessType'] == "Sole"){
		// Mapping: form input name => DB column name
		$fileMap = [
		    'inputFile_tin' => 'supplier_TIN',
		    'inputFile_itr' => 'supplier_ITR',
		    'inputFile_dti' => 'supplier_DTI',
		    'inputFile_philgeps' => 'supplier_PhilGEPS',
		    'inputFile_permit' => 'supplier_Permit'
		];
	}
	else{
		// Mapping: form input name => DB column name
		$fileMap = [
		    'inputFile_tin' => 'supplier_TIN',
		    'inputFile_itr' => 'supplier_ITR',
		    'inputFile_sec' => 'supplier_SEC',
		    'inputFile_philgeps' => 'supplier_PhilGEPS',
		    'inputFile_permit' => 'supplier_Permit'
		];
	}

	$uploadedData = [];

	// // List of your actual input names
	// $fileInputs = ['inputFile_tin', 'inputFile_dti', 'inputFile_sec', 'inputFile_philgeps', 'inputFile_permit'];	




	// Sanitize and validate inputs
	function sanitize($data) {
	    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
	}

	$supplierName         = sanitize($_POST['txtSuppName']);
	$supplierEmail        = filter_var($_POST['txtSuppEmail'], FILTER_VALIDATE_EMAIL);
	$supplierAddress      = sanitize($_POST['txtSuppAddress']);
	$supplierContactPerson = sanitize($_POST['txtContactPerson']);
	$supplierContactNumber = filter_var($_POST['txtSuppNumber'], FILTER_SANITIZE_NUMBER_INT);
	$dateEstablished      = sanitize($_POST['txtDateEstablished']);
	$vatNumber        	  = filter_var($_POST['txtVatNumber'], FILTER_SANITIZE_NUMBER_INT);
	$tinNumber        	  = filter_var($_POST['txtTIN'], FILTER_SANITIZE_NUMBER_INT);
	$cellphoneNumber      = filter_var($_POST['txtcellphoneNumber'], FILTER_SANITIZE_NUMBER_INT);
	$creditAccommodation  = sanitize($_POST['select_CreditAccommodation'] ?? '');
	$businessType  		  = sanitize($_POST['select_BusinessType'] ?? '');
	$password             = $_POST['txtConfirmPassword'];

	// Validate required fields
	if (!$supplierEmail) {
	    die("Invalid email address.");
	}

	if (!$vatNumber) {
	    die("Invalid VAT number");
	}

	if (!$tinNumber) {
	    die("Invalid TIN Number");
	}

	if (!$cellphoneNumber) {
	    die("Invalid Cellphone number");
	}

	if (!$supplierContactNumber) {
	    die("Invalid Telephone number");
	}

	// $totalchkFacilities = max(count($_POST['chkLabfaci']), count($_POST['chkPilotfaci']), count($_POST['chkOthersfaci']));

	$arrayNatureOfBusiness = [];
				
	for ($i=0; $i<count($_POST['chk_NatureOfBusiness']); $i++) {

		array_unshift($arrayNatureOfBusiness, sanitize($_POST['chk_NatureOfBusiness'][$i] ?? ''));
	}

	// var_dump($arrayNatureOfBusiness);
	// die();

	try {				

		$stmt = $con->prepare("INSERT INTO tbl_suppliers (supplierName, supplierEmail, supplierAddress, supplierContactPerson, supplierContactNumber, suppdate_established, suppdate_businessType, supp_vatnumber, supp_tinnumber, supp_cellphonenumber, supp_creditAccomm, supplier_Password) VALUES (:supplierName, :supplierEmail, :supplierAddress, :supplierContactPerson, :supplierContactNumber, :suppdate_established, :suppdate_businessType, :supp_vatnumber, :supp_tinnumber, :supp_cellphonenumber, :supp_creditAccomm, :supplier_Password)");

		// $stmt->bindParam(':supplierName', $_POST['txtSuppName']);
		// $stmt->bindParam(':supplierEmail', $_POST['txtSuppEmail']);
		// $stmt->bindParam(':supplierAddress', $_POST['txtSuppAddress']);
		// $stmt->bindParam(':supplierContactPerson', $_POST['txtContactPerson']);
		// $stmt->bindParam(':supplierContactNumber', $_POST['txtSuppNumber']);

		// $stmt->bindParam(':suppdate_established', $_POST['txtDateEstablished']);
		// $stmt->bindParam(':supp_vatnumber', $_POST['txtVatNumber']);
		// $stmt->bindParam(':supp_tinnumber', $_POST['txtTIN']);
		// $stmt->bindParam(':supp_cellphonenumber', $_POST['txtcellphoneNumber']);

		// $stmt->bindParam(':supplier_Password', $_POST['txtConfirmPassword']);

		 // Bind values

		$stmt->bindValue(':supplierName', $supplierName);
		$stmt->bindValue(':supplierEmail', $supplierEmail);
		$stmt->bindValue(':supplierAddress', $supplierAddress);
		$stmt->bindValue(':supplierContactPerson', $supplierContactPerson);
		$stmt->bindValue(':supplierContactNumber', $supplierContactNumber);

		$stmt->bindValue(':suppdate_established', $dateEstablished);
		$stmt->bindValue(':suppdate_businessType', $businessType);
		$stmt->bindValue(':supp_creditAccomm', $creditAccommodation);

		$stmt->bindValue(':supp_vatnumber', $vatNumber);
		$stmt->bindValue(':supp_tinnumber', $tinNumber);
		$stmt->bindValue(':supp_cellphonenumber', $cellphoneNumber);

		$stmt->bindValue(':supplier_Password', $password);
		
		// var_dump($stmt);
		// die();

		if($stmt->execute()){

			$query = "SELECT supplierID FROM tbl_suppliers ORDER BY supplierID DESC LIMIT 1";
			$stmt = $con->prepare($query);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($row) {
			    $ID = $row['supplierID'];
			}

			// var_dump($ID);
			// die();

			if(isset($ID)){

				// var_dump($stmt);
				// die();

				for ($j=0; $j<count($arrayNatureOfBusiness); $j++) { 
							
					$stmt2 = $con->prepare("INSERT INTO tbl_suppdetails (supplierID, supplier_NatureOfBusiness) VALUES (:supplierID, :supplier_NatureOfBusiness)");

					$stmt2->bindValue(':supplierID', $ID);
					$stmt2->bindValue(':supplier_NatureOfBusiness', $arrayNatureOfBusiness[$j]);

					if($stmt2->execute()){
						//do nothing

						if($j == count($arrayNatureOfBusiness) - 1){

							foreach ($fileMap as $inputName => $columnName) {
								if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
									$target_file = $target_dir . basename($_FILES[$inputName]['name']);
						            $tmpPath = $_FILES[$inputName]['tmp_name'];

						            $uploadFilename = basename($_FILES[$inputName]['name']);

						            $fileExt = strtolower(pathinfo($uploadFilename, PATHINFO_EXTENSION));

						            if (in_array($fileExt, $allowedType)) {
						                // $newFileName = uniqid($inputName . "_", true) . "." . $fileExt;
						                // $destination = $target_dir . $newFileName;

						                if (move_uploaded_file($tmpPath, $target_file)) {
						                    // Save to database
						                    // $stmt = $pdo->prepare("INSERT INTO uploaded_files (file_input_name, file_name) VALUES (:input, :filename)");
						                    // $stmt->execute([
						                    //     ':input' => $inputName,
						                    //     ':filename' => $newFileName
						                    // ]);
						                	$uploadedData[$columnName] = $uploadFilename;
						                    // echo "Uploaded {$inputName} successfully.<br>";
						                } 
						                else {
						                    $error = "ERROR: Failed to upload {$inputName}.<br> Please try again";
						                    $_SESSION['registrationSuccess'] = $error;

						                    echo $error;

						                    die();
						                }
						            } 
						            else {
						                $error = "ERROR: Invalid file type for {$inputName}.<br> Please try again";
						                $_SESSION['registrationSuccess'] = $error;

						                echo $error;

						                die();
						            }
						        } 
						        else {
						        	switch ($_FILES[$inputName]['error']) {
								        case UPLOAD_ERR_INI_SIZE:
								            $errmessage = "File exceeds the upload_max_filesize limit.";
								            break;
								        case UPLOAD_ERR_FORM_SIZE:
								            $errmessage = "File exceeds the form's MAX_FILE_SIZE limit.";
								            break;
								        case UPLOAD_ERR_PARTIAL:
								            $errmessage = "File only partially uploaded.";
								            break;
								        case UPLOAD_ERR_NO_FILE:
								            $errmessage = "No file uploaded.";
								            break;
								        case UPLOAD_ERR_NO_TMP_DIR:
								            $errmessage = "Missing temporary folder.";
								            break;
								        case UPLOAD_ERR_CANT_WRITE:
								            $errmessage = "Failed to write file to disk.";
								            break;
								        case UPLOAD_ERR_EXTENSION:
								            $errmessage = "File upload stopped by extension.";
								            break;
								        default:
								            $errmessage = "Unknown error.";
								            break;
								    }

						            $error = "ERROR: " . $errmessage;
						            $_SESSION['registrationSuccess'] = $error;

						            echo $error;

						            // echo '<script type="text/javascript">history.back();</script>';

						            die();
						        }
							}

							// var_dump($uploadFilename);
							// die();

							if (!empty($uploadedData)) {
							    try {
							        // Build dynamic SQL
							        $setParts = [];
									foreach (array_keys($uploadedData) as $col) {
									    $setParts[] = "$col = :$col";
									}
									$columns = implode(", ", $setParts);

							        $uploadedData['supplierID'] = $ID;

							        $stmt = $con->prepare("UPDATE tbl_suppliers SET $columns WHERE supplierID = :supplierID");
							        $stmt->execute($uploadedData);

							        // echo "Files are uploaded and saved successfully";
							        echo '<script type="text/javascript">alert("Files are uploaded and saved successfully. Your account is now registered");</script>';
							    }
							    catch (PDOException $e) {
							        echo "Database error: " . $e->getMessage();

							        die();
							    }
							} 
							else {
							    echo "No valid files uploaded.";

							    die();
							}

							
					        $subject = "ProcMIS - Supplier Registration";
					        // mail($email, $subject, $message);

					        require_once("../accessory/mailConfig.php");

					        $mail->addAddress($supplierEmail, $supplierContactPerson);		 
					        $mail->addCC("marlondanielcasin.ptri@gmail.com");

							if ($mail->addReplyTo("ptri.procurement@ptri.dost.gov.ph", "ProcMIS Admin")) {
								$mail->Subject = $subject;
					        	$mail->isHTML(true);
					        	$mail->addEmbeddedImage('../photos/procurement.png', 'logoimg');

					        	$str = '<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport"
      content="width=device-width, initial-scale=1.0">

    <title>Event Gate Pass</title>

    <link href="https://fonts.googleapis.com"
      rel="preconnect">
    <link href="https://fonts.gstatic.com"
      rel="preconnect"
      crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap"
      rel="stylesheet">

    <style type="text/css">
      body {
        margin: 0;
        padding: 0;
        height: 100vh;
        font-family: "Arial", sans-serif;
        background-color: #B6F0E2;
        overflow-x: hidden;
      }

      #header {
        padding: 15px;
        background-color: #AEDFF7;
        color: #000;
      }

      #org-name {
        margin-top: 10px;
      }

      #gate-pass-body {
        margin: auto;
        width: 40%;
        height: auto;
        padding: 15px;
        background-color: #B6F0E2;
      }

      #banner {
        width: 100%;
        height: auto;
      }

      #qr-code {
        text-align: center;
        padding: 0 20px;
      }

      #ref-num {
        color: #0F2C59;
      }

      .centered-text {
        text-align: center;
      }

      @media only screen and (max-width: 768px) {
        #gate-pass-body {
          width: auto;
          height: auto;
          padding: 15px;
          text-align: justify;
        }

        #header {
	        width: auto;
	      	height: auto;
	    }
      }
    </style>
  </head>

  <body>
    <div class="centered-text"
      id="header">
      <img src="https://res.cloudinary.com/dc33tvyxp/image/upload/v1697509490/dost-ptri/el4eykotaozy5i3jhogl.png"
        width="100"
        height="100" />

      <div id="org-name">
        <small>Department of Science and Technology</small> <br />
        <strong>PHILIPPINE TEXTILE RESEARCH INSTITUTE</strong>
      </div>
    </div>

    <div id="gate-pass-body">
      <p class="centered-text"
        id="email-title"><strong>PROCUREMENT MANAGEMENT INFORMATION SYSTEM</strong></p>

              <div class="centered-text">
          <img id="logo"
            src="cid:logoimg"
            alt="ProcMIS-logo"
            width="120"
            height="120" />
        </div>
        <br>
        <br>
        <p class="centered-text"
        id="email-title"><strong><h3 class="centered-text">REGISTERED SUCCESSFULLY</h3></strong></p>
     <div class="centered-text"
     	<p>Good Day <strong>'.strtoupper($supplierName).'</strong>!</p>

	      <p>You have successfully registered your account in Procurement Management Information System (ProcMIS).</p>

	      <p>You can now login your account using the credentials you inputted at <a href="https://bit.ly/ProcMIS-SupplierLogin" target="_blank">ProcMIS Supplier Login</a></p>   

	      <p>Thank you for your registration, and we look forward to an efficient process.</p>
     </div> 
    </div>

  </body>

</html>

									    ';

					        // 	$str = "<p>Good Day <b>".strtoupper($supplierName)."</b>!</p>

						       //  <p>You have successfully registered your account in Procurement Management Information System (ProcMIS).</p>



						       //  <div style='padding: 20px;'>
							      //   <div style='margin-top: 10px;'>
							      //     <small style='opacity: 0.7'><strong><u>NAME OF COMPANY</u></strong></small>
							      //     <div style='margin: 5px 0;'>
							      //       <span>NAME</span><br/>
							      //       <span style='padding-left: 20px; color: #3d68bc;'><strong>".strtoupper($supplierName)."</strong></span>
							      //     </div>

							      //     <div style='margin: 5px 0;'>
							      //       <span>Email</span><br/>
							      //       <span style='padding-left: 20px; color: #3d68bc;'><strong>".$supplierEmail."</strong></span>
							      //     </div>

							      //     <div style='margin: 5px 0;'>
							      //       <span>CONTACT PERSON</span><br/>
							      //       <span style='padding-left: 20px; color: #3d68bc;'><strong>".strtoupper($supplierContactPerson)."</strong></span>
							      //     </div>
							      //   </div>



							      //   <div style='margin-top: 10px;'>
							      //     <small style='opacity: 0.7'><strong><u>BUSINESS ADDRESS</u></strong></small>
							      //     <div style='margin: 5px 0;'>
							      //       <span style='padding-right: 10px; color: #3d68bc;'><strong>". $supplierAddress ."</strong></span>
							      //       <span>Address</span>
							      //     </div>

							      //     <div style='margin: 5px 0;'>
							      //       <span style='padding-right: 10px; color: #3d68bc;'><strong>". $dateEstablished ."</strong></span>
							      //       <span>Date Established</span>
							      //     </div>

							      //     <div style='margin: 5px 0;'>
							      //       <span style='padding-right: 10px; color: #3d68bc;'><strong>". $creditAccommodation ."</strong></span>
							      //       <span>Credit Accommodation</span>
							      //     </div>
							      //   </div>
							        
							        
							      
							      // <p>
							      //   Thank you so much for the time in registering.<br/><br/>
							      //   Best regards, <br/>
							      //   PTRI
							      // </p>";

						        // $msg = nl2br($str);
								$msg = $str;

						        $mail->Body = $msg;

						        // var_dump($mail->send());
						        // die();

						        if (!$mail->send()) {

						        	// var_dump($mail);
						        	// die();

						        	echo '<script type="text/javascript">alert("ERROR: Sorry, something went wrong in the Email Server. Please try again later.");</script>';
						        }
						        else{

						        	echo '<script type="text/javascript">alert("Thank you! You have successfully registered your account '. $supplierName .'" You may check your email for confirmation of your registration.);</script>';
								}
							}	
						}
					}
					else{
						echo '<script type="text/javascript">alert("ERROR! Supplier Details Failed");</script>';
					}
				}
			}
			else{
				echo '<script type="text/javascript">alert("ERROR! No Supplier ID");</script>';
			}


			
			// $mail = new PHPMailer;
		 //    $mail->isSMTP();
		 //    $mail->SMTPDebug = 0;
		 //    $mail->Host = 'smtp.gmail.com';
		 //    $mail->Port = 587;
		 //    $mail->SMTPAuth = true;
		 //    $mail->Username = 'marlon.casin@ptri.dost.gov.ph';
		 //    $mail->Password = 'uaylqexqporvevww';
		 //    $mail->setFrom("ptridost47@gmail.com", "ProcMIS Email");
		 //    $mail->addAddress($email, $name);


		 //    if ($mail->addReplyTo("ptridost47@gmail.com", "ProcMIS Email")) {
		 //     	$mail->Subject = 'ProcMIS: Feedback Receipt';
	  //         	$mail->isHTML(true);
	  //         	// Email: <b>". $email . "</b>
	  //         	$str = "Good Day!

	  //         		Thanks for filling out <a href='../pages/feedback.php'>ProcMIS Feedback form</a>.
		          
	  //         		Here's what was received: 

	  //         		Name: <b>". $name . "</b>
	  //         		Position: <b>". $position . "</b>
	  //         		Division: <b>". $division . "</b>
	  //         		Section: <b>". $section . "</b>

	  //         		The system is easy to use: <b>". $easy . "</b>	
					
			// 		The system is manageable to use: <b>". $manage . "</b>	

			// 		I felt very confident using the system: <b>". $confident . "</b>	

			// 		I think I would need the support of a technical person to be a able to use this system: <b>". $support . "</b>	

			// 		There are inconsistencies in the system: <b>". $inconsistent . "</b>	

			// 		Need a lot of things before going with the system: <b>". $need . "</b>	

			// 		Functionalities that are properly working:
			// 		Creation of PR: <b>". $createPR . "</b>	

			// 		Print of PR: <b>". $printPR . "</b>	

			// 		Revision of PR: <b>". $editPR . "</b>	

			// 		Attachment of E-signature: <b>". $eSig . "</b>	

			// 		Routing of the PR to the appropriate staff: <b>". $routing . "</b>

			// 		AREAS FOR IMPROVEMENT: <b>". $improve . "</b>

			// 		OTHER COMMENTS/SUGGESTIONS: <b>". $suggest . "</b>

			// 		Thank you so much for the time taking part of this feedback form.

			// 		Sincerely yours,
			// 		ProcMIS Email";

			// 	$msg = nl2br($str);

		 //    	// die($msg);


	  //       	$mail->Body = $msg;

			// 	if (!$mail->send()) {
			// 		$msg = 'Sorry, something went wrong in the Server. Please try again later.';
			// 		echo '<script type="text/javascript">alert('. $msg .'); alert("ERROR! EMAIL RECEIPT FAILED"); history.back();</script>';  
			// 	}
			// 	else{
			// 		$_SESSION['successEmail'] = true;
			// 		echo '<script type="text/javascript">history.back();</script>';
			// 	}
			// }

	  //   	else {
		 //        $msg = 'Invalid email address, message ignored.';
		 //        echo '<script type="text/javascript">alert('. $msg .'); alert("ERROR! EMAIL ADDRESS FAILED"); history.back();</script>';
		 //    }
		    // $_SESSION['successEmail'] = true;
			// echo '<script type="text/javascript">history.back();</script>';
		}
		else{
			echo '<script type="text/javascript">alert("Error during registration!");</script>';
			// echo '<script type="text/javascript">alert("'.$stmt->errorInfo().'");</script>';	
		}
	} catch (PDOException $e) {
		error_log("Database error: " . $e->getMessage());
		// echo '<script type="text/javascript">alert("'.$e->getMessage().'");</script>';	
		echo '<script type="text/javascript">alert("Internal Error! Please try again later");</script>';

		die();
	}		
}
else{
	// echo '<script type="text/javascript">alert("ERROR: Incomplete details");</script>';
}
?>