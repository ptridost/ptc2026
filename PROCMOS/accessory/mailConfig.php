<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
require_once("../DatabaseConnection/databaseconnection.php");
// require_once("verifyAddress.php");

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPAuth = true;
	$mail->Username = 'ptri.procurement@ptri.dost.gov.ph';
	$mail->Password = 'spiqzasggluxawbi';
	//qyuziqvlonfrqqby
?>