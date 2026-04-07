<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
require_once("../DatabaseConnection/databaseconnection.php");

	$mail = new PHPMailer;
		    $mail->isSMTP();
		    $mail->SMTPDebug = 0;
		    $mail->Host = 'smtp.gmail.com';
		    $mail->Port = 587;
		    $mail->SMTPAuth = true;
		    $mail->setFrom("ptridost47@gmail.com", "ProcMIS Email");
		    $mail->addAddress($email, $name);


		    if ($mail->addReplyTo("ptridost47@gmail.com", "ProcMIS Email")) {
		     	$mail->Subject = 'ProcMIS: Feedback Receipt';
	          	$mail->isHTML(true);
	          	$str = "Good Day!

	          		Thanks for filling out <a href='../pages/feedback.php'>ProcMIS Feedback form</a>.
		          
	          		Here's what was received: 

	          		Name: <b>". $name . "</b>
	          		Position: <b>". $position . "</b>
	          		Division: <b>". $division . "</b>
	          		Section: <b>". $section . "</b>
	          		Email: <b>". $email . "</b>

	          		The system is easy to use: <b>". $easy . "</b>	
					
					The system is manageable to use: <b>". $manage . "</b>	

					I felt very confident using the system: <b>". $confident . "</b>	

					I think I would need the support of a technical person to be a able to use this system: <b>". $support . "</b>	

					There are inconsistencies in the system: <b>". $inconsistent . "</b>	

					Need a lot of things before going with the system: <b>". $need . "</b>	

					Functionalities that are properly working:
					Creation of PR: <b>". $createPR . "</b>	

					Print of PR: <b>". $printPR . "</b>	

					Revision of PR: <b>". $editPR . "</b>	

					Attachment of E-signature: <b>". $eSig . "</b>	

					Routing of the PR to the appropriate staff: <b>". $routing . "</b>

					AREAS FOR IMPROVEMENT: <b>". $improve . "</b>

					OTHER COMMENTS/SUGGESTIONS: <b>". $suggest . "</b>

					Thank you so much for the time taking part of this feedback form.

					Sincerely yours,
					ProcMIS Email";

				$msg = nl2br($str);

		    	// die($msg);


	        	$mail->Body = $msg;



				if (!$mail->send()) {
					$msg = 'Sorry, something went wrong in the Server. Please try again later.';
					echo '<script type="text/javascript">alert('. $msg .'); alert("ERROR! EMAIL RECEIPT FAILED"); history.back();</script>';  
				}
				else{
					$_SESSION['successEmail'] = true;
					echo '<script type="text/javascript">history.back();</script>';
				}
			}

	    	else {
		        $msg = 'Invalid email address, message ignored.';
		        echo '<script type="text/javascript">alert('. $msg .'); alert("ERROR! EMAIL ADDRESS FAILED"); history.back();</script>';
		    }
?>