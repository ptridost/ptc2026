<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register</title>
	
	<!--CDN here-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="registration.css"/>
	
	<script src="registration.js"></script>
	
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:wght@700;800&family=Roboto:wght@700;900&display=swap" rel="stylesheet">
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.20.0/js/mdb.min.js" integrity="sha512-XFd1m0eHgU1F05yOmuzEklFHtiacLVbtdBufAyZwFR0zfcq7vc6iJuxerGPyVFOXlPGgM8Uhem9gwzMI8SJ5uw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="stylesheet" type="text/css" href="/PTC/header&footer/footers.css">
    <link rel="stylesheet" type="text/css" href="/PTC/header&footer/Navbarstyle.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  	<link rel="stylesheet" href="/resources/demos/style.css">
  	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  	<script src="~/Scripts/jquery-1.10.2.js"></script>
  
  <script>
  $( function() {
    $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true,
       yearRange : '-100:+0'
    });
  } );
  </script>

	<!--End of CDN-->

	<script type="text/javascript">
                    (function ($, window, document) {
                        $(function () {
                            $("form input").on({
                                "keyup": function () {
                                    var pass = $('#password1').val();
                                    var confirmPass = $('#password2').val();
                                    var saveButton = $("#save-btn");
                                    if (pass == confirmPass) {
                                        saveButton.removeAttr('disabled');
                                        $('#message').html('Password Matched').css('color', 'green').css('margin-left', '20px').css('margin-top', '30px');
                                    } else {
                                        saveButton.attr('disabled', 'disabled');
                                        $('#message').html('Password Not Matched').css('color', 'red').css('margin-left', '20px').css('margin-top', '30px');
                                    }
                                }
                            });
                        });

                       

                    }(window.jQuery, window, document));
                  </script>

        <script type="text/javascript">
        
        $('.datepicker').datepicker({
  		weekdaysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
  		showMonthsShort: true
		}) 

	</script>


		<script type="text/javascript">
			function txtonly(){
				var str=document.getElementById("txtfield").value;
				var txtalert= document.getElementById("txt-alert");
				if(!((/^[a-zA-Z]+$/.test(str) )|| str.length==0)){
					txtalert.style.display="block";
					txtalert.innerHTML="Alphabets only";
					alertflag=false;
				}
				else{
					txtalert.style.display="none";
					alertflag=true;
				}
			}
		</script>

		<script type="text/javascript">
			function txtonlymi(){
				var str=document.getElementById("txtfieldmi").value;
				var txtalert= document.getElementById("txt-alertmi");
				if(!((/^[a-zA-Z]+$/.test(str) )|| str.length==0)){
					txtalert.style.display="block";
					txtalert.innerHTML="Alphabets only";
					alertflag=false;
				}
				else{
					txtalert.style.display="none";
					alertflag=true;
				}
			}
		</script>

		<script type="text/javascript">
			function txtonlyln(){
				var str=document.getElementById("txtfieldln").value;
				var txtalert= document.getElementById("txt-alertln");
				if(!((/^[a-zA-Z]+$/.test(str) )|| str.length==0)){
					txtalert.style.display="block";
					txtalert.innerHTML="Alphabets only";
					alertflag=false;
				}
				else{
					txtalert.style.display="none";
					alertflag=true;
				}
			}
		</script>


</head>
<body>
	<?php

	

   	


   // if(isset($_FILES['fileToUpload'])){


   // 		var_dump("MERON");
   // 		die();

   //    $errors= array();
   //    $file_name = $_FILES['fileToUpload']['name'];
   //    $file_size =$_FILES['fileToUpload']['size'];
   //    $file_tmp =$_FILES['fileToUpload']['tmp_name'];
   //    $file_type=$_FILES['fileToUpload']['type'];
   //    $file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));
      
   //    $extensions= array("jpeg","jpg","png");
      
   //    if(in_array($file_ext,$extensions)=== false){
   //       $errors[]="extension not allowed, please choose a JPEG or PNG file.";
   //    }
      
   //    if($file_size > 2097152){
   //       $errors[]='File size must be excately 2 MB';
   //    }
      
   //    if(empty($errors)==true){
   //       move_uploaded_file($file_tmp,"user_profile/".$file_name);
   //       echo "Success";
   //    }else{
   //       print_r($errors);
   //    }
   // }
   // else{
   // 		// var_dump("WALA");
   // 		// die();
   // }
?>


	<?php

	if(isset($_POST['submit'])){

		

		$errors = array();
		$file_name = $_FILES['fileToUpload']['name'];
		$file_size = $_FILES['fileToUpload']['size'];
		$file_tmp = $_FILES['fileToUpload']['tmp_name'];
		$file_type= $_FILES['fileToUpload']['type'];
		$file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));

		

		$extensions= array("jpeg","jpg","png");

		if(in_array($file_ext,$extensions)=== false){
		 $errors[]="extension not allowed, please choose a JPEG or PNG file.";
		}

		if($file_size > 2097152){
		 $errors[]='File size must be excately 2 MB';
		}

		// var_dump($errors);
		// die();

		if(empty($errors)==true){
		 move_uploaded_file($file_tmp,"user_profile/".$file_name);
		 echo "Success";
		}else{
		 print_r($errors);
		}

		


	?>	
			
				
			<script>

			 Swal.fire(
  				'Success!',
  				'Registration Successful!',
  				'success'
			)
			
			</script>

			
			<?php
		}

	?> 

	
		

		<div class="container" id="cont1">
		<div class="row">

			<!--start of form-->
			<div class="col-md-6">
				<div class="container">
					<div class="row">

						<h1 class="header mb-3">Registration Form</h1>
						<form action="DP.php" method="POST" enctype="multipart/form-data">

				    	
				    	<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="input-container">
				  		<input type="text" class="input-field" name="firstname" placeholder="First Name" autocomplete="on" id="txtfield" required on onkeyup="txtonly();">
				  		<label for="input-field" class="input-label">First Name</label>
				  		<span class="input-highlight"></span>
				  		<p id="txt-alert">&nbsp;</p>
						</div>
						</div>
						
						
						<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="input-container">
				  		<input type="text" class="input-field" name="middlename" placeholder="Middle Initial" maxlength="1" autocomplete="on" id="txtfieldmi" on onkeyup="txtonlymi();">
				  		<label for="input-field" class="input-label">Middle Initial</label>
				  		<span class="input-highlight"></span>
				  		<p id="txt-alertmi">&nbsp;</p>
						</div>
						</div>
						
				
						<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="input-container">
				  		<input type="text" class="input-field" name="lastname" placeholder="Last Name" id="txtfieldln" autocomplete="on" required on onkeyup="txtonlyln();">
				  		<label for="input-field" class="input-label">Last Name</label>
				  		<span class="input-highlight"></span>
				  		<p id="txt-alertln">&nbsp;</p>
						</div>
						</div>


						<div class="col-lg-6 col-md-12 col-sm-12">
						<div class="input-container datepicker">
				  		<input type="text" class="input-field" name="birthdate" id="datepicker" placeholder="Birth Date" autocomplete="on" required>
				  		<label for="input-field" class="input-label">Birth Date</label>
				  		<span class="input-highlight"></span>
						</div>
						</div>


						<div class="col-lg-6 col-md-12 col-sm-12 email">
						<div class="input-container">
				  		<input type="email" class="input-field" name="email" id="email" placeholder="Your Email" autocomplete="on" required>
				  		<label for="input-field" class="input-label">Email</label>
				  		<span class="input-highlight"></span>
				  		<p>ex. sample@gmail.com</p>	
						</div>
						</div>
						


						<div class="col-lg-6 col-md-12 col-sm-12 passwordcol">
						<div class="input-container">
				  		<input type="password" class="input-field" name="password1" id="password1" placeholder="Password" autocomplete="on"  minlength="8" required>	
				  		<label for="input-field" class="input-label">Password</label>
				  		<span class="input-highlight"></span>
				  		<p>At least 8 characters</p>
						</div>
						</div>
						

						<div class="col-lg-6 col-md-12 col-sm-12">
						<div class="input-container">
				  		<input type="password" class="input-field" name="password2" id="password2" placeholder="Confirm Password" autocomplete="on" required="true">
				  		<label for="input-field" class="input-label">Confirm Password</label>
				  		<span class="input-highlight"></span>
						<span id='message'></span>
						</div>
						</div>

						
						<div class="col-lg-6 col-md-12 col-sm-12">
						<div class="input-container">
						<select class="input-field" name="profession" id="profession" required onchange="if (this.value=='other') {display();this.form['other'].style.display='block'} else {displaynot();this.form['other']
						.style.display='none'};">
				  		<option selected hidden="hidden" default="true">Profession</option>
				  	 	 <option value="Chemical Engineer">Chemical Engineer</option>
				  		 <option value="Chemist">Chemist</option>
				  		 <option value="Chemical Technician">Chemical Technician</option>
				  		 <option value="other">Others</option>
				  		</select>			  		
				  		</div>
				  		</div>


				  		<div class="col-lg-6 col-md-12 col-sm-12">
				  	  	<div class="input-container">
				  		<input type="number" class="input-field" name="prclicense" id="prclicense" placeholder="PRC License" autocomplete="on" required>
				  		<label for="input-field" class="input-label">PRC License</label>
				  		<p id="prc-txt-alert">&nbsp;</p>
				  		<span class="input-highlight"></span>
						</div>
						</div>


						<div class="input col-lg-12 col-md-12 col-sm-12">
				  		<input type="textbox" name="other" class="input-field" placeholder="Type other profession here" style="display: none;"/>
				  		<label for="input-field" class="input-label">Other</label>
				  		<span class="input-highlight"></span>
				  		</div>


						<div class="col-lg-6 col-md-12 col-sm-12">
						<div class="input-container">			  		
				  		<input type="text" class="input-field" name="organization" id="organization" placeholder="Organization" autocomplete="on" required>
				  		<label for="input-field" class="input-label">Organization</label>
				  		<span class="input-highlight"></span>
						</div>
						</div>
					
						
						<div class="col-lg-6 col-md-12 col-sm-12">
						<div class="input-container">
				  		<input type="text" class="input-field" name="position" id="position" placeholder="Position" autocomplete="on" required>
				  		<label for="input-field" class="input-label">Position</label>
				  		<span class="input-highlight"></span>
						</div>
						</div>

						
						<div class="col-lg-6 col-md-12 col-sm-12">
						<div class="input-container">
							<p class="uploadprof">Upload Profile Picture</p>
						<input type="file" class="input-field" name="fileToUpload" id="fileToUpload" value="" required>
						<label for="input-field" class="input-label">Upload Image</label>
						<span class="input-highlight"></span>												
						</div>
						</div>

						
						<div class="col-lg-12 col-md-12 col-sm-12 mb-4">
						<input type="checkbox" id="checkbox" required>
				  		<label for="terms of service" id="link1">I agree all statements in <a target="_blank" href="https://ptri.dost.gov.ph/">Terms of service</a></label>
				  		</div>
				  	

				  		<div class="col-lg-12 col-md-12 col-sm-12 mb-4">
				  		<input class="btn btn btn-lg" type="submit" name="submit" id="save-btn" value="Register">
				  		</div>

						</form>

						

					</div>
				</div>
			</div>
		

			<script type="text/javascript">

				function display(){
					document.getElementById('prclicense').required = false;
					var prctxtalert= document.getElementById("prc-txt-alert");
					prctxtalert.style.display="block";
					prctxtalert.innerHTML="Not required";
					alertflag=false;
				}

				function displaynot(){
					document.getElementById('prclicense').required = true;
					var prctxtalert= document.getElementById("prc-txt-alert");
					prctxtalert.style.display="block";
					prctxtalert.innerHTML="";
					alertflag=true;
					
				}


			</script>

			<!--document.getElementById('prclicense').required = false;-->


			<!--start for the image-->
			<div class="col-md-6 regpic">
						<img src="regpic.png"> 
						</div>
					</div>
				</div>
			<!--end for the image-->
				
			


</body>
</html>











