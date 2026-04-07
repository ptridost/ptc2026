<?php
	require_once("../DatabaseConnection/databaseconnection.php");
	require_once("../operations/insertVisitors.php");

if(isset($_SESSION['account_ID'])){
	//do nothing
}
else{
  echo '<script type="text/javascript">alert("ERROR! You need to log in first"); window.location.href = "index.php";</script>';
  // echo '<script type="text/javascript">alert('. $_SESSION['ProceedID']) .');</script>';
  // die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Feedback Form</title>
	<link rel="icon" href="../photos/procurement.png" type="image/png" sizes="16x16">
	<link href="../vendor/css/style.css" rel="stylesheet">
	<link href="../vendor/css/bootstrap.min.css" rel="stylesheet">
	<link href="../vendor/css/jquery-ui.min.css" rel="stylesheet">
	<link href="../vendor/fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
	<link href="../vendor/css/animate.css" rel="stylesheet">
	<link href="../vendor/css/loading.css" rel="stylesheet">
	<script src="../vendor/js/jquery-3.4.1.min.js"></script>
	<script src="../vendor/js/bootstrap.min.js"></script>
	<script src="../vendor/js/jquery-ui.min.js"></script>
</head>

<body class="body_style">

	<?php require_once("../accessory/navbar.php");?>

<!-- 	<nav class="navbar navbar-expand-md nav-bg navbar-dark animated slideInDown" style="background-color: #00aeef;">
  
  <a class="navbar-brand navBar-logo" href="services.dost-ptri.com" style="padding-top: 0; padding-bottom: 0; margin-right: 0.4rem"><img width="50px" src="../photos/ptrionlywhite.png">
    </a>

    <a class="navbar-brand navBar-GovName" href="#" style="font-size: 14px; padding-top: 0; padding-bottom: 0; line-height: 1.2">
      <span>Department of Science and Technology</span>
      <br>
      <span><b>PHILIPPINE TEXTILE RESEARCH INSTITUTE</b></span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar7">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse justify-content-stretch" id="navbar7">
        <ul class="navbar-nav ml-auto">
            <li id="linav_Dashboard" class="nav-item">
              <a class="nav-link active" href="../pages/index.php">Procurement Management Information System</a>
          </li>
          
      
        </ul>
    </div>
</nav> -->

		<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample08" aria-controls="		navbarsExample08" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample08">
				<ul class="navbar-nav">
					<li class="nav-item active">
						<a class="nav-link" href="#">Centered nav only <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Link</a>
					</li>
					<li class="nav-item">
						<a class="nav-link disabled" href="#">Disabled</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown08" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
					<div class="dropdown-menu" aria-labelledby="dropdown08">
						<a class="dropdown-item" href="#">Action</a>
						<a class="dropdown-item" href="#">Another action</a>
						<a class="dropdown-item" href="#">Something else here</a>
					</div>
					</li>
				</ul>
			</div>
		</nav> -->

		<!-- example 2 - using auto margins -->


		<br>
		<br>

		<div class="container">
			<div class="row">
				<div class="col">
					<center><h2>Feedback form</h2></center>
				</div>
			</div>	
		</div>

		<br>

		

		<div class="container">
			<div class="row" style="padding-top: 1%; padding-right: 1%;">
				<div class="col offset-md-1 offset-lg-0">
					<form id="formFeedback" method="POST" action="../operations/submitFeedback.php" id="" style="width: 100%">
							<?php echo '<input type="text" class="form-control prtextboxes d-none" id="txtAccountID" name="txtAccountID" placeholder="Account ID" value="'.$_SESSION['account_ID'].'" autocomplete="off" required readonly>';?>
						<!-- <div class="form-row">
							<div class="col-lg-6">


								

								<b><label for="txtName">NAME</label></b>
								

								<input type="text" class="form-control prtextboxes" id="txtName" name="txtName" placeholder="Eg. Juan Dela Cruz" required>
							</div>	
							<div class="col-sm-6">
								<b><label for="txtPosition">POSITION</label></b>
								<input type="text" class="form-control prtextboxes" id="txtPosition" name="txtPosition" placeholder="Eg. Science Research Assistant" required>
							</div>
						</div>
						<div class="form-row mt-3">
							<div class="col-sm-6">


								<b><label for="txtDivision">DIVISION</label></b>
								
								<input type="text" class="form-control prtextboxes" id="txtDivision" name="txtDivision" placeholder="Eg. Office of the Director" required>
							</div>	
							<div class="col-sm-6">
								<b><label for="txtSection">SECTION</label></b>
								
								<input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. Planning Information and Communication Technology Staff" required>
							</div>
						</div>
						<div class="form-row mt-3">
							<div class="col">

								<b><label for="txtEmail">Email</label></b>
								
								<input type="email" class="form-control prtextboxes" id="txtEmail" name="txtEmail" placeholder="Eg. juandelacruz@gmail.com" required>
							</div>	
						</div>

						<br> -->

						<div class="container-fluid">
							<div class="row">
						<div class="col">
							<table class="table table-responsive table-bordered" style="margin-left: -2.5%; width: 110%;">
								<thead class="thead-white bg-primary text-white tableheader">
									<tr>
										<th>Description</th>
		                                <th>Strongly Disagree</th>
		                                <th>Disagree</th>
		                                <th>Satisfactory</th>
										<th>Agree</th>
										<th>Strongly Agree</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>The system is easy to use</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_Easy" value="1" required><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_Easy" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_Easy" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_Easy" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_Easy" value="5" required checked><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>				    									    
									</tr>
									<tr>
										<td>The system is manageable to use</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_manage" value="1" required><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_manage" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_manage" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_manage" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_manage" value="5" required checked><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>
									<tr>
										<td>I felt very confident using the system</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_confi" value="1" required><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_confi" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_confi" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_confi" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_confi" value="5" required checked><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>













									<tr>
										<td>I think I would need the support of a technical person to be a able to use this system</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_support" value="1" required checked><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_support" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_support" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_support" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_support" value="5" required><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>
									<tr>
										<td>There are inconsistencies in the system</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_inconsist" value="1" required checked><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_inconsist" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_inconsist" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_inconsist" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_inconsist" value="5" required><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>




















									<tr>
										<td>Need a lot of things before going with the system</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_need" value="1" required checked><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_need" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_need" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_need" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_need" value="5" required><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>
									<tr>
										<td>Functionalities that are properly working: <br><br>Creation of PR</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_createPR" value="1" required><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_createPR" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_createPR" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_createPR" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_createPR" value="5" required checked><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>


									<tr>
										<td>Print of PR</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_printPR" value="1" required><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_printPR" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_printPR" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_printPR" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_printPR" value="5" required checked><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>












									<tr>
										<td>Revision of PR</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_editPR" value="1" required><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_editPR" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_editPR" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_editPR" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_editPR" value="5" required checked><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>
									<tr>
										<td>Attachment of E-signature</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_esig" value="1" required><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_esig" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_esig" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_esig" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_esig" value="5" required checked><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>
									<tr>
										<td>Routing of the PR to the appropriate staff</td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_routing" value="1" required><span class="starSpan">★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_routing" value="2" required><span class="starSpan">★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_routing" value="3" required><span class="starSpan">★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_routing" value="4" required><span class="starSpan">★★★★</span>
												</label>
											</div>
									    </td>
									    <td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input newStars" name="rad_routing" value="5" required checked><span class="starSpan">★★★★★</span>
												</label>
											</div>
									    </td>
									    
									</tr>
									

								</tbody>
						</table>
						</div>
					</div>
				</div>
						<div class="form-row">
						<div class="col-sm-12">
							<textarea type="text" rows="5" class="form-control forms_textbox" name="txtImprove"
							id="txtImprove" placeholder="Areas for improvement" autocomplete="off" required style="border-bottom: 2px double #AC92E4"></textarea>
						</div>
					</div>

					<div class="form-row">
						<div class="col-sm-12">
							<textarea type="text" rows="5" class="form-control forms_textbox" name="txtSuggest"
							id="txtSuggest" placeholder="Other comments/suggestions" autocomplete="off" required style="border-bottom: 2px double #AC92E4"></textarea>
						</div>

						<!-- <div class="col-sm-12">
							<textarea type="text" rows="5" class="form-control forms_textbox" name="txtSuggest"
							id="txtSuggest" placeholder="Other comments/suggestions" autocomplete="off" required style="border: 2px solid #3d68b1;"></textarea>
						</div> -->
					</div>

						<br>

						<div class="formFooter" style="display: block;">


							<div class="form-row">
								<div class="col">
									<input type="checkbox" id="chkAgree" name="chkAgree" required>
									<label for="chkAgree">I have reviewed all the details inputted</label>
								</div>
							</div>

							<!-- <div class="form-row">
								<div class="col">
									<input type="checkbox" id="chkPreview" name="chkPreview" required>
									<label for="chkPreview">I have checked the preview of the form in small format & large format</label>
								</div>
							</div> -->

							<br>

							<div class="form-row">
								<div class="col">
									<center><button type="submit" class="btn formsbutton" id="btnAddAccount">SUBMIT</button></center>
								</div>
							</div>
						</div>
					</form>
				</div>

				
				
			</div>
		</div>

<!-- 	<div class="container-fluid">
		<h1>My First Bootstrap Page</h1>
		<p>This is some text.</p>
	</div>

	The quick brown fox jumps 

	<div class="container p-3 my-3 bg-dark text-white">
		<h1>My First Bootstrap Page</h1>
		<p>This container has a dark background color and a white text, and some extra padding and margins.</p>
	</div>
	<div class="container p-3 my-3 bg-primary text-white">
		<h1>My First Bootstrap Page</h1>
		<p>This container has a blue background color and a white text, and some extra padding and margins.</p>
	</div>
	<div class="container p-3 my-3 border">
		<h1>My First Bootstrap Page</h1>
		<p>This container has a border and some extra padding and margins.</p>
	</div> -->
	<!-- Confirmation Modal -->

	<div class="modal fade" id="modalConfirmation">
		<div class="modal-dialog modal-lg">
		  <div class="modal-content">
		  
		    <!-- Modal Header -->
		    <div id="modalHeader" class="modal-header d-block" style="background-color: #018d01;">
		    	<button type="button" class="close" data-dismiss="modal">&times;</button>
		      	<h3 id="h3ConfirmTitle" class="modal-title text-center" style="">SUCCESS</h3>
		    </div>
		    
		    <!-- Modal body -->
		    <div class="modal-body">
		      	<center><h4 id="modalh4Message">Your feedback is already submitted and sent in your provided email</h4></center>
		    </div>		    
		  </div>
		</div>
	</div>


<!-- Loading Spinner Wrapper-->
<div id="loadingDIV" class="loader text-center" style="display: none;">
    <div class="loader-inner">

        <!-- Animated Spinner -->
        
        <div class="lds-roller mb-3">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        
        <!-- Spinner Description Text [For Demo Purpose]-->
        <h4 class="text-uppercase font-weight-bold" style="color: #00aeef">PROCESSING</h4>
    </div>
</div>

</body>

<!--Banana, Abaca, Pineapple, Bamboo-->

<?php
	if($_SESSION['successEmail'] == true){
		echo '<script type="text/javascript">$("#modalConfirmation").modal("show");</script>';
		unset($_SESSION['successEmail']);
	}
	else{
		//do nothing
	}
?>

<script>

	$(document).ready(function(){
        $("#linav_Feedback").addClass("active");
        // $("#pill_createPR").addClass("active");
        // $("#modalConfirmation").modal("show");

       
    });

    $(".navLinks").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

    $("#formFeedback").submit(function(e){
    	$("#loadingDIV").fadeIn();

    	
	});

    function computeTotalFee(x){
		// console.log($("table :focus"));
		// console.log($(x).val());
		// console.log($(x).parent().next().children());
		// console.log($(x).parent().next().next().children());
		var qty = $(x).val();
		var unitFee = $(x).parent().next().children().val();
		var totalFee = unitFee * qty;
		$(x).parent().next().next().children().val(totalFee.toFixed(2));
	}

	function computeTotalFee_fromUnit(x){
		// console.log($("table :focus"));
		// console.log($(x).val());
		// console.log($(x).parent().next().children());
		// console.log($(x).parent().next().next().children());
		var unitFee = $(x).val();
		var qty = $(x).parent().prev().children().val();
		var totalFee = unitFee * qty;
		$(x).parent().next().children().val(totalFee.toFixed(2));		
	}

	function computeFinalTotalFee(){
		// console.log($("td input.textboxTotal"));
		// console.log($("td input.textboxTotal").length);
		var txtTotalValues = 0;
		var arrayTxtTotal = [];
		
		var numberOfTotaltextbox = $("td input.textboxTotal").length;
		// console.log(numberOfTotaltextbox);
		if(numberOfTotaltextbox > 1){
			// console.log($("td input.textboxTotal"));
			$("td input.textboxTotal").each(function(){
				// arrayTxtTotal += $(this).val();
				arrayTxtTotal.push(parseFloat($(this).val())); 
				//
			});

			txtTotalValues = arrayTxtTotal.reduce(function(a, b){
				return a + b;
			}, 0);

			$("#txtFinalFee").val(txtTotalValues.toFixed(2));

			$("#txtFinalFee").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
				$(this).removeClass('animated flash');
			});				
		}
		else{
			// console.log($("td input.textboxTotal").val());
			var finalfee = parseFloat($("td input.textboxTotal").val());
			$("#txtFinalFee").val(finalfee.toFixed(2));

			$("#txtFinalFee").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
				$(this).removeClass('animated flash');
			});	
		}
		// console.log(txtTotalValues);
		// console.log(arrayTxtTotal);
	}

	var numberOfRows = 1;
	$("#btnAddRow").click(function() {
		// var lastTR = $("#row1").clone();
		// var finalTR = lastTR
		// console.log(lastTR);
		// $("#row1").after(finalTR);
		// console.log($("tr tbody").last());
		// $("#thActions").css("display", "block");
		// $("#txtFinalFee").val("EXPERIMENT");
		numberOfRows += 1;
		var lastRow = $("tbody tr").last();
		lastRow.after('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
							'<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtstock'+numberOfRows+'" '+
							'id="txtstock'+numberOfRows+'" autocomplete="off" required style="width: 100%;"></td>'+
							'<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
							'id="txtUnitQty'+numberOfRows+'" autocomplete="off" required style="width: 100%;"></td>'+
							'<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>'+
							'<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity'+numberOfRows+'" '+
							'id="txtQuantity'+numberOfRows+'" onkeyup="computeTotalFee(this); computeFinalTotalFee();" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>'+
							'<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee'+numberOfRows+'" '+
							'id="txtUnitFee'+numberOfRows+'" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this); computeFinalTotalFee();" style="width: 100%;"></td>'+
							'<td><input type="text"  class="form-control forms_textbox prtextboxes textboxTotal" name="txtTotal'+numberOfRows+'" min="0"'+
							'id="txtTotal'+numberOfRows+'" autocomplete="off" value=0 required readonly style="width: 100%;"></td>'+
							'<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
						'</tr>');

		$(".newAddedRow").fadeIn();
	});

	function deleteRow(x){
		var allInputs = [];
		var allInputData = [];
		var withData = 0;
		$(x).prevAll().each(function(){
			// console.log($(this).children());
			allInputs.push($(this).children());
		});

		// console.log(allInputs[4]);

		for(var i=0; i<allInputs.length; i++){
			/*allInputData.push(allInputs[i].val());*/
			// console.log(allInputs[i].val() == 0);
			if(allInputs[i].val() != 0){
				// console.log("Eto ay may laman: " + i);
				// withData = withData + 1;
				withData++;
			}			
			else{
				// console.log("Eto ay walang laman: " + i);
			}
			// console.log(withData);
		}

		if(withData > 0){
			var deleteAnswer = confirm("Are you sure you want to delete this?");

			if(deleteAnswer == true){
				// console.log("YES");
				// console.log(x);
				$(x).parent().fadeOut();

				setTimeout(function(){
					$(x).parent().remove();
				}, 400);
				// console.log($(this))
			}
			else{
				// console.log("NO");
			}
		}
		else{
			// $(x).parent().fadeOut();
			// $(x).parent().remove();

			$(x).parent().fadeOut();

			setTimeout(function(){
				$(x).parent().remove();
			}, 400);

		}

		// var allInputs = $(x).prevAll();
		// var selected = allInputs[0];
		// console.log(selected.children());
	}

	$("form").submit(function(e){
		// console.log($("table tbody tr"));
		// console.log($("table tbody tr").length);
		// alert($("table tbody tr").length);
		$("#txtNumberOfRows").val($("table tbody tr").length);
		// var newFinalFee = $("#txtFinalFee").val();
		// $("#txtNewFinalFee").val(newFinalFee);
		// var numberOfTotaltextbox = $("td input.prtextboxes").length;
		// // console.log(numberOfTotaltextbox);
		// if(numberOfTotaltextbox > 1){
		// 	if($("#txtDiscount").val()){
		// 		var discountpercentage = $("#txtDiscount").val();
		// 		// alert(discountpercentage/100);
		// 		var discount = discountpercentage / 100;

		// 		var subtotal = $("#txtFinalFee").val();
		// 		var discountamount = subtotal * discount;
		// 		// alert($("td input.textboxTotal").val() - ($("td input.textboxTotal").val() * discount));

		// 		// $("#txtDiscount").val(discountamount);
		// 		$("#txtDiscount").val(discountamount.toFixed(2));
		// 		// alert($("#txtDiscount").val());
		// 		// e.preventDefault();
		// 		$("#txtDiscount").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
		// 			$(this).removeClass('animated flash');
		// 		});
		// 	}								
		// }
		// else{
		// 	// console.log($("td input.textboxTotal").val());
		// 	if($("#txtDiscount").val()){
		// 		// console.log("with discount");
		// 		var discountpercentage = $("#txtDiscount").val();
		// 		// alert(discountpercentage/100);
		// 		var discount = discountpercentage / 100;

		// 		var subtotal = $("#txtFinalFee").val();
		// 		var discountamount = subtotal * discount;
		// 		// alert($("td input.textboxTotal").val() - ($("td input.textboxTotal").val() * discount));

		// 		$("#txtDiscount").val(discountamount.toFixed(2));
		// 		// alert($("#txtDiscount").val());
		// 		// e.preventDefault();
		// 		$("#txtDiscount").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
		// 			$(this).removeClass('animated flash');
		// 		});
		// 	}
		// }
	});

</script>

</html>