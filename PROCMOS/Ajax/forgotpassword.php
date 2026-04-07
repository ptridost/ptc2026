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


if(isset($_POST['email'])){
	
	
	$email = trim($_POST['email']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $output = "ERROR: Invalid email address!";
		echo $output;

		exit;
    }

    // Check if the email exists in the database
    $stmt = $con->prepare("SELECT supplierName, supplierContactPerson FROM tbl_suppliers WHERE supplierEmail = :email");
    $stmt->execute(['email' => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
	    $supplierName = $row['supplierName'];
	    $supplierContactPerson = $row['supplierContactPerson'];
	}
    // $user = $stmt->fetch();

    // echo $supplierContactPerson;
    // exit;

    if ($supplierName) {
        // Generate secure token
        $token = bin2hex(random_bytes(32));
        $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Store token in the database
        $stmt = $con->prepare("INSERT INTO tbl_passwordresets (pwreset_email, pwreset_token, pwreset_expiresat) VALUES (:email, :token, :expires_at)");
        $stmt->execute([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expires_at
        ]);

        // Send reset email
        $reset_link = "https://procmis.dost-ptri.com/operations/reset_password.php?token=$token";
        $subject = "ProcMIS - Password Reset Request";
        $message = "Click the link to reset your password: $reset_link";
        // mail($email, $subject, $message);

        require_once("../accessory/mailConfig.php");

        $mail->addAddress($email, $supplierContactPerson);		 

		if ($mail->addReplyTo("ptri.procurement@ptri.dost.gov.ph", "ProcMIS Admin")) {
			$mail->Subject = $subject;
        	$mail->isHTML(true);

        	$str = "Good Day <b>".strtoupper($supplierName)."</b>!

	        Please be advised that you have requested to reset your password in ProcMIS. Please click the link below to reset your password

	        <b>$reset_link</b>

	        The link above is only valid up until an hour from the moment your requested to reset your password.

	        Thank you so much.";

	        $msg = nl2br($str);

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

	        	echo "Password <strong>reset link</strong> has been sent to your email.";
			}
		}
    } 
    else {
        $output = "ERROR: No account found with that email!";
		echo $output;
    }

		
}

?>