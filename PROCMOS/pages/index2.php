<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Service Requests Registration - Appointment</title>
	<link href="../vendor/css/style.css" rel="stylesheet">
	<link href="../vendor/css/bootstrap.min.css" rel="stylesheet">
	<link href="../vendor/css/jquery-ui.min.css" rel="stylesheet">
	<link href="../vendor/fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
	<link href="../vendor/css/bootstrap.min.css" rel="stylesheet">
	<link href="../vendor/css/animate.css" rel="stylesheet">
	<script src="../vendor/js/jquery-3.4.1.min.js"></script>
	<script src="../vendor/js/bootstrap.min.js"></script>
	<script src="../vendor/js/jquery-ui.min.js"></script>
</head>

<body class="body_style">

	<nav class="navbar fixed-top navbar-expand-md nav-bg navbar-dark animated slideInDown">
		<!-- Brand -->
		<!-- <a class="navbar-brand" href="#" style=""><img width="50px" src="../photos/ptrilogo.jpg">  Philippine Textile Research Institure - Department of Science and Technology</a> -->
		<a class="navbar-brand navBar-logo" href="#" style="padding-top: 0; padding-bottom: 0; margin-right: 0.4rem"><img width="50px" src="../photos/ptrilogo.jpg">
		</a>

		<a class="navbar-brand navBar-GovName" href="#" style="font-size: 14px; padding-top: 0; padding-bottom: 0; line-height: 1.2">
			<span>Republic of the Philippines</span>
			<br>
			<span>Department of Science and Technology</span>
			<br>
			<span><b>PHILIPPINE TEXTILE RESEARCH INSTITUTE</b></span>
		</a>

		<div class="collapse navbar-collapse" id="navbarText">
		    <ul class="navbar-nav mr-auto">
		    </ul>
			<span class="navbar-text" style="color: white; font-size: 24px;">
			  Service Requests Registration Information System
			</span>
  		</div>

		<!-- Toggler/collapsibe Button -->
		<!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
			<span calss="navbar-toggler-icon"></span>
		</button>	 -->
		<!-- <div class="collapse navbar-collapse" id="collapsibleNavbar"> -->
			<!-- 			<ul class="navbar-nav"> -->

				<!-- Navbar links -->
<!-- 				<li class="nav-item">
					<a class="nav-link active" href="#">Home</a>
				</li>	
				<li class="nav-item">
					<a class="nav-link" href="#">About Us</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Contact Us</a>	
				</li>

			</ul> -->
		<!-- </div> -->
	</nav>

	<br>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6 animated slideInLeft">
				<img class="img-fluid" src="../photos/Appointment_Guide.jpg">
			</div>
			<div class="col-sm-6 animated slideInRight">
				<center><h2>Book an Appointment</h2></center>
				<form method="POST" action="../operations/proceedAppointment.php" id="formProceedAppoint" style="width: 100%">
					<div class="form-row">
						<div class="col">
							<input type="text"  class="form-control form-control forms_textbox" name="txtService" id="txtService" placeholder="Service Chosen" style="display: none">
							<input type="text"  class="forms_textbox" name="txtNumberOfRows" 
							id="txtNumberOfRows" placeholder="ID" autocomplete="off" 
							style="display: none">
							<select class="form-control forms_textbox" id="select_TypeOfServices" name="select_TypeOfServices" required>
                                <option disabled selected="">Type of Services</option>
                                <option value="RDD Services">RDD Services</option>
                                <option value="TSD Lab Services">TSD Lab Services</option>
                                <option value="ICYT Processing Services">ICYT Processing Services</option>
                                <option value="TIPS Training Services">TIPS Training Services</option>
                                <option value="PICTS Library Registration">PICTS Library Registration</option>
                                <!-- <option disabled value="TPDC Service Requests">TPDC Service Requests</option> -->
                            </select>
						</div>	
					</div>

					<br>

					<div class="form-row">
						<div class="col">
							<input type="text"  class="form-control forms_textbox" placeholder="Select desired Date of Appointment below" autocomplete="off" value="Select Desired Date and Time of Appointment below" readonly>
							<input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
							<!-- <input type="text"  class="form-control forms_textbox" placeholder="Select desired Date of Appointment below" autocomplete="off" value="1pm is equals to 13:00" readonly> -->
							<input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required>
						</div>
					</div>

					<br>

					<div class="form-row">
						<div class="col">
							<center><button type="submit" class="btn formsbutton" id="btnAddAccount">PROCEED</button></center>
						</div>	
					</div>


				</form>
			</div>
		</div>
	</div>

	<br>
	<br>

<!-- Confirmation Modal -->


<!-- Confirmation Modal -->
	<div class="modal fade" id="ModalConfirmation">
		<div class="modal-dialog modal-lg">
		  <div class="modal-content">
		  
		    <!-- Modal Header -->
		    <div class="modal-header d-block">
		    	<button type="button" class="close" data-dismiss="modal">&times;</button>
		      	<h3 class="modal-title text-center" style="">ERROR</h3>
		    </div>
		    
		    <!-- Modal body -->
		    <div class="modal-body">
		      	<center><h4 id="modalh4Message">Please Select a Type of Service</h4></center>
		    </div>		    
		  </div>
		</div>
	</div>

</body>

<script>
	$(document).ready(function(){
		$("#ModalConfirmation").on('shown.bs.modal', function () {
			setTimeout(function(){ 
				$("#ModalConfirmation").modal("hide");
			}, 1800);		
		});

		var truemessage = "Email Sent!";
	    var falsemessage = "Error!";
	    var incmessage = "Please complete the Form";

	    let searchParams = new URLSearchParams(window.location.search);
	    if(searchParams.has('signal')){
	        let param = searchParams.get('signal');
	        if(param == "wahr"){
	            // $(".modal-title").html(truemessage);
	            // $("#ModalConfirmation").modal(); 
	        }
	        else if(param == "inc"){
	            $("#modalh4Message").html(incmessage);
	            $("#ModalConfirmation").modal();   
	        }
	        else{
	            window.location.href = "../pages/index.php";
	        }
	    }
	    else{
	        //do nothing
	    }		
	});

	$("form").submit(function(e){
		// alert($("#select_TypeOfServices").val());
		if(!$("#select_TypeOfServices").val()){
			$("#ModalConfirmation").modal();
			e.preventDefault(e);
		}
		else{
			// alert("MAY VALUE");
		}	
	});
</script>
 
</html>
