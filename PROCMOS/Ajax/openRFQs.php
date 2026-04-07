<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['id'])){
	$prNo = $_POST['prNo'];
	$rfqID = $_POST['id'];
	// $email = $_POST['email'];
	$suppName = $_POST['name'];

	$query = "SELECT rfq_email, rfq_No FROM `tbl_rfq` WHERE rfq_ID = '$rfqID'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$rfq_email = $row['rfq_email'];
		$rfq_No = $row['rfq_No'];
	}

	$rfq_email = "casinmarlondaniel@gmail.com";

	$subject = "ProcMIS - Supplier's Quotation Number " . $rfq_No;
					        // mail($email, $subject, $message);

    require_once("../accessory/mailConfig.php");

    $mail->addAddress($rfq_email, $suppName);		 
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
        
     <div class="centered-text"
     	<p>Good Day <strong>'.strtoupper($suppName).'</strong>!</p>

        <p>Your submitted quotation has been opened</p>

	      <p>Please be advised that your <a href="https://procmis.dost-ptri.com/pages/print_quotation_pdf.php?ID='.$rfqID.'&prNo='.$prNo.'" target="_blank">offer</a> will be subject for evaluation.</p>

	      <p>Thank you for submitting an offer.</p>
     </div> 
    </div>

  </body>

</html>

									    ';

								$msg = $str;

                /*<p class="centered-text"
        id="email-title"><strong><h3 class="centered-text">YOUR SUBMITTED QUOTATION HAS BEEN OPENED</h3></strong></p>*/

						        $mail->Body = $msg;

						        // var_dump($mail->send());
						        // die();

						        if (!$mail->send()) {

						        	// var_dump($mail);
						        	// die();

						        	$output = "ERROR: Email Sending failed!";
										echo $output;
						        }
						        else{

						        	$stmt = $con->prepare("INSERT INTO tbl_activityLogs (activityUsername, activityName, activityAction) VALUES (:Username, :fName, :actConfAction)");
        								
							    	$actConfAction = "OPENED QUOTATIONS of PR NO. ".$prNo.", remarks: SUCCESS! ID: ". $rfqID ."";

							    	$stmt->bindParam(':Username', $_SESSION['Username']);
									$stmt->bindParam(':fName', $_SESSION['Name']);
									$stmt->bindParam(':actConfAction', $actConfAction);

									if($stmt->execute()){
										$newStatus = "For AOC";

										$stmt = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus WHERE purchrequest_No=:prNo");

										$stmt->bindParam(':newLastProcessedBy', $_SESSION['Name']);
										$stmt->bindParam(':newPRStatus', $newStatus);
										$stmt->bindParam(':prNo', $prNo);


										if($stmt->execute()){
											echo "Quotation No. <strong>". $rfq_No ."</strong> has been now opened! RFQ ID # ". $rfqID ."";
										}
										else{
											$output = "ERROR: Update PR AOC failed!";
										echo $output;
										}
									}
									else{
										$output = "ERROR: Activity Logs failed!";
										echo $output;
									}
								}
							}

	

	

	
}
else{
	echo "ERROR: NO ID PARAMETER";
	// echo '<script type="text/javascript">alert("ERROR!");</script>';
}




?>

