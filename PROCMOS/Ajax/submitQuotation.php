<?php
require_once("../DatabaseConnection/databaseconnection.php");

	// var_dump([$_POST]);
	// die();
	// $training = [];

	// foreach ($_POST['Training'] as $key => $value) {
	// 	$training[$key] = $value;
	// 	//var_dump($training[$key]);

	// }


	// echo json_encode($training);
	// die();


if(isset($_POST['numRows'])){
	
	try {
	

		$query2= "SELECT supplierName, supplierEmail, supplierAddress, supplierContactPerson, supplierContactNumber FROM tbl_suppliers WHERE supplierID ='".$_SESSION['suppID']."'";
		$result2 = mysqli_query($conProcedural,$query2);

		while ($row2 = mysqli_fetch_assoc($result2)) {
			$supplierName = $row2['supplierName'];
			$supplierEmail = $row2['supplierEmail'];
			$supplierAddress = $row2['supplierAddress'];
			$supplierContactPerson = $row2['supplierContactPerson'];
			$supplierContactNumber = $row2['supplierContactNumber'];
		}



		if(!empty($supplierName)){


			$query = "SELECT ictclearance_requisitioner, ictdetails_description, ictdetails_qty FROM `tbl_ictclearance` INNER JOIN `tbl_ictclearancedetails` on tbl_ictclearance.ictclearance_ID = tbl_ictclearancedetails.ictclearance_ID WHERE tbl_ictclearance.purchrequest_No = '".$_POST['prNo']."'";
			$result = mysqli_query($conProcedural,$query);

			// var_dump($query);
			// die();

			while ($row = mysqli_fetch_assoc($result)) {
				$RFQ_EndUser = $row['ictclearance_requisitioner'];		
			}

			

			$query = "SELECT date_quotationno, account_ID FROM `tbl_purchaserequest` WHERE purchrequest_No = '".$_POST['prNo']."'";
			$result = mysqli_query($conProcedural,$query);

			while ($row = mysqli_fetch_assoc($result)) {
				$RFQ_Date = $row['date_quotationno'];	
				$end_user = $row['account_ID'];		
			}



			if(empty($RFQ_EndUser)){
				$query2= "SELECT acct_Division, acct_Section FROM tbl_acctdetails WHERE account_ID ='".$end_user."'";
				$result2 = mysqli_query($conProcedural,$query2);

				while ($row2 = mysqli_fetch_assoc($result2)) {
					$acct_Division = $row2['acct_Division'];	
					$acct_Section = $row2['acct_Section'];
				}

				if($acct_Division == "Office of the Director"){
					$ptridivision = "OD";
				}
				else if($acct_Division == "Research and Development Division"){
					$ptridivision = "RDD";
				}
				else if($acct_Division == "Finance and Administrative Division"){
					$ptridivision = "FAD";
				}
				else if($acct_Division == "Technical Services Division"){
					$ptridivision = "TSD";
				}
				// else{
				// 	$ptridivision = $_POST['txtDivision'];
				// }

				if($acct_Section == "Planning and Information & Communication Technology Staff"){
					$ptrisection = "PICTS";
				}
				
				else if($acct_Section == "Technology Transfer, Information, and Promotion Staff"){
					$ptrisection = "TIPS";
				}
				else if($acct_Section == "Natural Fiber Utilization Section"){
					$ptrisection = "NFUS";
				}
				else if($acct_Section == "Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section"){
					$ptrisection = "CDABUS";
				}
				else if($acct_Section == "Property, Procurement and General Services Section"){
					$ptrisection = "PPGSS";
				}
				else if($acct_Section == "Human Resources and Records Management Section"){
					$ptrisection = "HRRMS";
				}
				else if($acct_Section == "Budget and Treasury Section"){
					$ptrisection = "BTS";
				}
				else if($acct_Section == "Accounting Section"){
					$ptrisection = "ACCTG";
				}
				else if($acct_Section == "Physical and Chemical Testing Section"){
					$ptrisection = "PCTS";
				}
				else if($acct_Section == "Mechanical and Chemical Processing Section"){
					$ptrisection = "MCPS";
				}
				else{
					$ptrisection = $acct_Section;
				}
				// else{
				// 	$ptrisection = $_POST['txtSection'];
				// }
				$RFQ_EndUser = $ptridivision . "-" . $ptrisection;
			}
			else{
				//do nothing
			}

			$prNo = $_POST['prNo'];

			$stmt = $con->prepare("INSERT INTO tbl_rfq (rfq_Date, rfq_No, pr_No, rfq_Company, rfq_Address, rfq_Email, rfq_telNum, rfq_enduser, rfq_uploadedby, rfq_BrandModel, rfq_DeliveryPlace, rfq_DeliveryPeriod, rfq_Warranty, rfq_PriceValidity, rfq_Remarks) VALUES (:rfq_Date, :rfq_No, :pr_No, :rfq_Company, :rfq_Address, :rfq_Email, :rfq_TelNum, :rfq_enduser, :rfq_uploadedBy, :rfq_BrandModel, :rfq_DeliveryPlace, :rfq_DeliveryPeriod, :rfq_Warranty, :rfq_PriceValidity, :rfq_Remarks)");

			$stmt->bindParam(':rfq_Date', $RFQ_Date);
			$stmt->bindParam(':rfq_No', $_POST['rfqNo']);
			$stmt->bindParam(':pr_No', $_POST['prNo']);
			$stmt->bindParam(':rfq_Company', $supplierName);
			$stmt->bindParam(':rfq_Address', $supplierAddress);
			$stmt->bindParam(':rfq_Email', $supplierEmail);
			$stmt->bindParam(':rfq_TelNum', $supplierContactNumber);
			$stmt->bindParam(':rfq_enduser', $RFQ_EndUser);
			$stmt->bindParam(':rfq_uploadedBy', $supplierContactPerson);

			$stmt->bindParam(':rfq_BrandModel', $_POST['brandModel']);
			$stmt->bindParam(':rfq_DeliveryPeriod', $_POST['deliveryPeriod']);
			$stmt->bindParam(':rfq_DeliveryPlace', $_POST['deliveryPlace']);
			$stmt->bindParam(':rfq_Warranty', $_POST['warranty']);
			$stmt->bindParam(':rfq_PriceValidity', $_POST['priceValidity']);
			$stmt->bindParam(':rfq_Remarks', $_POST['remarks']);

			if($stmt->execute()){

				$query = "SELECT rfq_ID from tbl_rfq ORDER BY rfq_ID DESC LIMIT 1";
				$result = mysqli_query($conProcedural,$query);
				
				while ($row = mysqli_fetch_assoc($result)) {
					$rfq_ID = $row['rfq_ID'];
				}

				// echo $_POST['itemParticulars'][0];
				// die();

				

				if(isset($rfq_ID)){
					for ($i=$_POST['numRows']-1; $i >= 0; $i--) { 
						$itemNo = $_POST['itemNo'][$i];
						$itemParticulars = $_POST['itemParticulars'][$i];
						$qty = $_POST['qty'][$i];
						$itemPrice = $_POST['itemPrice'][$i];
						$itemDesc = $_POST['itemDesc'][$i];
						$supp_Price = $_POST['supp_Price'][$i];

						$stmt2 = $con->prepare("INSERT INTO tbl_rfqdetails (rfq_ID, rfq_itemNo, rfq_particular, rfq_qty, rfq_UnitPrice, rfq_suppDesc, rfq_suppPrice) VALUES (:rfq_ID, :rfq_itemNo, :rfq_particular, :rfq_qty, :rfq_UnitPrice, :rfq_suppDesc, :rfq_suppPrice)");

						$stmt2->bindParam(':rfq_ID', $rfq_ID);
						$stmt2->bindParam(':rfq_itemNo', $itemNo);
						$stmt2->bindParam(':rfq_particular', nl2br(htmlentities($itemParticulars)));
						$stmt2->bindParam(':rfq_qty', $qty);
						$stmt2->bindParam(':rfq_UnitPrice', $itemPrice);
						$stmt2->bindParam(':rfq_suppDesc', nl2br(htmlentities($itemDesc)));
						$stmt2->bindParam(':rfq_suppPrice', $supp_Price);

						if($stmt2->execute()){

							// echo "Quotation Submitted for Quotation No. <strong>". $_POST['rfqNo'] . "</strong> <i>item no. </i>" . $itemNo;
							
							if($i == count($_POST['numRows']) - 1){
								// var_dump($i == count($_POST['numRows']) - 1);
								// die();

								// Check if files were uploaded
							    if (isset($_FILES['files'])) {
							        $target_dir = "../supplier/rfq_attachments/";

							        foreach ($_FILES['files']['name'] as $key => $name) {
							        	$target_file = $target_dir . basename($name);
							            $fileTmpPath = $_FILES['files']['tmp_name'][$key];

							            $uploadOk = 1;
										$uploaded_FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

										// Allow certain file formats

										if($uploaded_FileType != "pdf") 
										{

											$output = "ERROR: Sorry, only PDF files are allowed.";
											echo $output;

											$uploadOk = 0;
											
										}
										else
										{
											//okay file type
											$uploadOk = 1;
										}

										$uploadFilename = $name;

										if ($uploadOk == 0) {
											$output = "ERROR: Sorry, your file was not uploaded.";
											echo $output;
											// if everything is ok, try to upload file
										} 
										else {
								            if (move_uploaded_file($fileTmpPath, $target_file)) {
								                // Save file info in the database
								                $stmt = $con->prepare("INSERT INTO tbl_rfqattachments (rfq_ID, rfqattach_filename) VALUES (:rfq_ID, :rfqattach_filename)");
								                $stmt->execute([':rfq_ID' => $rfq_ID, ':rfqattach_filename' => $uploadFilename]);


								                $subject = "ProcMIS - Supplier's Quotation";
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
														        id="email-title"><strong><h3 class="centered-text">QUOTATION SUBMITTED SUCCESSFULLY</h3></strong></p>
														     <div class="centered-text"
														     	<p>Good Day <strong>'.strtoupper($supplierName).'</strong>!</p>

															      <p>You have successfully submitted an offer for PR No. <strong>$prNo</strong>.</p>

															      <p>Please view your quotation <a href="https://procmis.dost-ptri.com/pages/print_quotation_pdf.php?ID=$rfq_ID&prNo=$prNo" target="_blank">here</a>.</p>   

															      <p>Thank you for your submitting an offer, we\'ll get back to you once the quotations are opened.</p>
														     </div> 
														    </div>

														  </body>

														</html>

									    				';

											        $msg = $str;

											        $mail->Body = $msg;

											        // var_dump($mail->send());
											        // die();

											        if (!$mail->send()) {

											        	// var_dump($mail);
											        	// die();

											        	$output = "ERROR: Sorry, something went wrong in the Email Server. Please try again later.";
														echo $output;
											        }
											        else{

											        	echo "File uploaded and saved in DB: <strong>$uploadFilename</strong><br><br>";
								                		echo "Quotation Submitted for Quotation No. <strong>". $_POST['rfqNo'] . "</strong>";
													}
												}
								            } else {
								                echo "ERROR: uploading file: <strong>$uploadFilename</strong><br>";
								            }
								        }
							        }
							    } 
							    else {
							        $subject = "ProcMIS - Supplier's Quotation";
										        // mail($email, $subject, $message);

										        require_once("../accessory/mailConfig.php");

										        // $mail->addAddress($supplierEmail, $supplierContactPerson);		 
										        $mail->addAddress("casinmarlondaniel@gmail.com", $supplierContactPerson);
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
														        id="email-title"><strong><h3 class="centered-text">QUOTATION SUBMITTED SUCCESSFULLY</h3></strong></p>
														     <div class="centered-text"
														     	<p>Good Day <strong>'.strtoupper($supplierName).'</strong>!</p>

															      <p>You have successfully submitted an offer for PR No. <strong>'.$prNo.'</strong>.</p>

															      <p>Please view your quotation <a href="https://procmis.dost-ptri.com/pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$prNo.'" target="_blank">here</a>.</p>   

															      <p>Thank you for your submitting an offer, we\'ll get back to you once the quotations are opened.</p>
														     </div> 
														    </div>

														  </body>

														</html>

									    				';

											        $msg = $str;

											        $mail->Body = $msg;

											        // var_dump($mail->send());
											        // die();

											        if (!$mail->send()) {

											        	// var_dump($mail);
											        	// die();

											        	$output = "ERROR: Sorry, something went wrong in the Email Server. Please try again later.";
														echo $output;
											        }
											        else{
								                		echo "Quotation Submitted for Quotation No. <strong>". $_POST['rfqNo'] . "</strong>";
													}
												}
							    }
								
							}
							else{
								//do nothing
							}
						}
						else{
							$output = "ERROR: Submission of Quotation details Failed!";
							echo $output;
						}
					}
				}
				else{
					$output = "ERROR: No RFQ ID!";
					echo $output;
				}
			}
			else{
				$output = "ERROR: Submission of RFQ Failed!";
				echo $output;
			}
		}
		else{
			$output = "ERROR: No Supplier Name!";
			echo $output;
		}

		
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
		// echo '<script type="text/javascript">window.location.href = "../pages/training.php";</script>';
	}
	$con = null;	
}

?>