<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
require_once("../DatabaseConnection/databaseconnection.php");

if(isset($_POST['txtAccountID'])){
	try {

		// $name = "";
		// $position = "";
		// $division = "";
		// $section = "";

		$query2 = "SELECT * FROM tbl_acctdetails WHERE account_ID = '". $_POST['txtAccountID'] ."'";
		$result2 = mysqli_query($conProcedural,$query2);

		while ($row2 = mysqli_fetch_assoc($result2)) {
			$name = $row2['acct_Name'];
			$division = $row2['acct_Division'];
			$section = $row2['acct_Section'];
			$position = $row2['acct_Designation'];	
		}
		
		// $name = $_POST['txtName'];
		// $position = $_POST['txtPosition'];
		// $division = $_POST['txtDivision'];
		// $section = $_POST['txtSection'];
		$email = "marlon.casin@ptri.dost.gov.ph";
		$easy = $_POST['rad_Easy'];
		$manage = $_POST['rad_manage'];
		$confident = $_POST['rad_confi'];
		$support = $_POST['rad_support'];
		$inconsistent = $_POST['rad_inconsist'];
		$need = $_POST['rad_need'];
		$createPR = $_POST['rad_createPR'];
		$printPR = $_POST['rad_printPR'];
		$editPR = $_POST['rad_editPR'];
		$eSig = $_POST['rad_esig'];
		$routing = $_POST['rad_routing'];
		$improve = $_POST['txtImprove'];
		$suggest = $_POST['txtSuggest'];

		

		$stmt = $con->prepare("INSERT INTO tbl_feedbacks (feedback_Name, feedback_Position, feedback_Division, feedback_Section, feedback_Email, feed_easy, feed_manage, feed_confident, feed_support, feed_inconsistent, feed_need, feed_createPR, feed_printPR, feed_editPR, feed_eSig, feed_routing, feed_improvement, feed_suggestion) VALUES (:feedback_Name, :feedback_Position, :feedback_Division, :feedback_Section, :feedback_Email, :feed_easy, :feed_manage, :feed_confident, :feed_support, :feed_inconsistent, :feed_need, :feed_createPR, :feed_printPR, :feed_editPR, :feed_eSig, :feed_routing, :feed_improvement, :feed_suggestion)");



		$stmt->bindParam(':feedback_Name', $name);
		$stmt->bindParam(':feedback_Position', $position);
		$stmt->bindParam(':feedback_Division', $division);
		$stmt->bindParam(':feedback_Section', $section);
		$stmt->bindParam(':feedback_Email', $email);
		$stmt->bindParam(':feed_easy', $easy);
		$stmt->bindParam(':feed_manage', $manage);
		$stmt->bindParam(':feed_confident', $confident);
		$stmt->bindParam(':feed_support', $support);
		$stmt->bindParam(':feed_inconsistent', $inconsistent);
		$stmt->bindParam(':feed_need', $need);
		$stmt->bindParam(':feed_createPR', $createPR);
		$stmt->bindParam(':feed_printPR', $printPR);
		$stmt->bindParam(':feed_editPR', $editPR);
		$stmt->bindParam(':feed_eSig', $eSig);
		$stmt->bindParam(':feed_routing', $routing);
		$stmt->bindParam(':feed_improvement', $improve);
		$stmt->bindParam(':feed_suggestion', $suggest);
		

		if($stmt->execute()){
			$mail = new PHPMailer;
		    $mail->isSMTP();
		    $mail->SMTPDebug = 0;
		    $mail->Host = 'smtp.gmail.com';
		    $mail->Port = 587;
		    $mail->SMTPAuth = true;
		    $mail->Username = 'marlon.casin@ptri.dost.gov.ph';
		    $mail->Password = 'uaylqexqporvevww';
		    $mail->setFrom("ptridost47@gmail.com", "ProcMIS Email");
		    $mail->addAddress($email, $name);


		    if ($mail->addReplyTo("ptridost47@gmail.com", "ProcMIS Email")) {
		     	$mail->Subject = 'ProcMIS: Feedback Receipt';
	          	$mail->isHTML(true);
	          	// Email: <b>". $email . "</b>
	          	$str = "Good Day!

	          		Thanks for filling out <a href='../pages/feedback.php'>ProcMIS Feedback form</a>.
		          
	          		Here's what was received: 

	          		Name: <b>". $name . "</b>
	          		Position: <b>". $position . "</b>
	          		Division: <b>". $division . "</b>
	          		Section: <b>". $section . "</b>

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
		    // $_SESSION['successEmail'] = true;
			// echo '<script type="text/javascript">history.back();</script>';
		}
		else{
			echo '<script type="text/javascript">alert("'.$stmt->errorInfo().'"); history.back();</script>';	
		}
	} catch (PDOException $e) {
		echo '<script type="text/javascript">alert("Error:'.$e->getMessage().'"); history.back();</script>';
	}		
}
else{
	echo '<script type="text/javascript">alert("ERROR: Incomplete details"); history.back();</script>';
}
?>