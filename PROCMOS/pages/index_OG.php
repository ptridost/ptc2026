<?php
require_once("../DatabaseConnection/databaseconnection.php");
require_once("../operations/login.php");

  	if(!empty($_SESSION['Username'])){
		echo '<script type="text/javascript">alert("Bonjour '.$_SESSION['Username'].'");</script>';
		echo '<script type="text/javascript">window.location.href = "purchase_request.php";</script>';
	}
	else{
		//do nothing
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>PROCMOS - Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- <link rel="stylesheet" type="text/css" href="../vendor/css/login/util.css">
	<link rel="stylesheet" type="text/css" href="../vendor/css/login/main.css"> -->
	<!-- <link href="../vendor/css/style.css" rel="stylesheet"> -->
	<link rel="icon" href="../photos/procurement.png" type="image/png" sizes="16x16">
	<link rel="stylesheet" type="text/css" href="../vendor/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="../vendor/fonts/Linearicons-Free-v1.0.0/icon-font.min.css"> -->
	<link href="../vendor/css/loginstyle.css" rel="stylesheet">
	<!-- <link href="../vendor/css/jquery-ui.min.css" rel="stylesheet">
	<link href="../vendor/fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet"> -->
	<!-- <link href="../vendor/css/bootstrap.min.css" rel="stylesheet"> -->
	
	 <link href="../vendor/css/animate.css" rel="stylesheet">
	<script src="../vendor/js/jquery-3.4.1.min.js"></script>
	<!-- <script src="../vendor/js/bootstrap.min.js"></script>
	<script src="../vendor/js/jquery-ui.min.js"></script> -->
</head>
<body class="img js-fullheight" style="background-image: url(../photos/PPMIS-bg.png);">
		<section class="ftco-section">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-6 text-center mb-5">
						<h1 class="heading-section"><b>Procurement & Property Management Information System</b></h1>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-6 col-lg-4">
						<div class="login-wrap p-0">
			      			<h3 class="mb-4 text-center">Login</h3>
			      			<form action="index.php" class="signin-form" method="POST">
					      		<div class="form-group">
					      			<input type="text" class="form-control" name="txtUsername" placeholder="Username" required>
					      		</div>
					            <div class="form-group">
					              	<input id="password-field" type="password" class="form-control" name="txtPassword" placeholder="Password" required>
					              	<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
					            </div>
					            <div class="form-group">
					            	<button type="submit" class="form-control btn btn-primary submit px-3">Log In</button>
					            </div>
		          			</form>
			      		</div>
					</div>
				</div>
			</div>
		</section>
	
<!--===============================================================================================-->
<script src="../vendor/js/loginmain.js"></script>

</body>
</html>