<?php $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error()); ?>  		
<?php
  session_start();
  if(!empty($_SESSION['participant_id'])){

  }
  else{
    header("Location: ../Loginn.php"); 
  }

?>

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
	
	 <link rel="stylesheet" type="text/css" href="/header-footer/footers.css">
     <link rel="stylesheet" type="text/css" href="/header-footer/NavbarStyle.css">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.20.0/js/mdb.min.js" integrity="sha512-XFd1m0eHgU1F05yOmuzEklFHtiacLVbtdBufAyZwFR0zfcq7vc6iJuxerGPyVFOXlPGgM8Uhem9gwzMI8SJ5uw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="stylesheet" type="text/css" href="/header-footer/footers.css">
    <link rel="stylesheet" type="text/css" href="/header-footer/NavbarStyle.css">
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


?>

	<?php

	if(isset($_POST['submit'])){
    

		$firstname 			  = $_POST['firstname'];
		$middlename 		  = $_POST['middlename'];
		$lastname 			  = $_POST['lastname'];
    $gender           = $_POST['gender'];
    $citizenship      = $_POST['citizenship'];
    $country          = $_POST['country'];
    $region           = $_POST['region'];
    $cityprov         = $_POST['cityprovince'];

		$username			    = $_POST['username'];
		$birthdate 			  = $_POST['birthdate'];
		$password1 			  = $_POST['password1'];
		$profession 		  = $_POST['profession'];
		$others			 	    = $_POST['others'];
		$prclicense 		  = $_POST['prclicense'];
		$organization 		= $_POST['organization'];
		$position 			  = $_POST['position'];
		$hashed_password 	= md5($password1);

    $filename         = $_FILES["fileToUpload"]["name"];
    $tempname         = $_FILES["fileToUpload"]["tmp_name"];
    $folder           = "./images/" . $filename;

     mysqli_query ($dbcon, "INSERT INTO `participant`(`FirstName`, `MiddleName`, `LastName`,`Gender_ID`,`Gender`,`Citizenship`,`Country`, `Region`, `CityProvince`, `username`, `BirthDate`, `Password`, `Profession`, `Other_profession`, `PRCLicense`, `Organization`, `Position`, `image`) VALUES ('$firstname', '$middlename','$lastname','1','$gender','$citizenship','$country','$region','$cityprov','$username','$birthdate', '$hashed_password','$profession', '$others','$prclicense','$organization','$position','$filename')");
  
    if (move_uploaded_file($tempname, $folder)) {
        echo "<h3>  Image uploaded successfully!</h3>";
    } else {
        echo "<h3>  Failed to upload image!</h3>";
    }

   
   

/* 
if (isset($_FILES['pp']['name']) AND !empty($_FILES['pp']['name'])) {
         
         
         $img_name = $_FILES['pp']['name'];
         $tmp_name = $_FILES['pp']['tmp_name'];
         $error = $_FILES['pp']['error'];
         
         if($error === 0){
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_to_lc = strtolower($img_ex);

            $allowed_exs = array('jpg', 'jpeg', 'png');
            if(in_array($img_ex_to_lc, $allowed_exs)){
               $new_img_name = uniqid($uname, true).'.'.$img_ex_to_lc;
               $img_upload_path = '../user_profile/'.$new_img_name;
               move_uploaded_file($tmp_name, $img_upload_path);

               // Insert into Database
               $sql = "INSERT INTO users(fname, username, password, pp) 
                 VALUES(?,?,?,?)";
               $stmt = $conn->prepare($sql);
               $stmt->execute([$fname, $uname, $pass, $new_img_name]);

               header("Location: ../index.php?success=Your account has been created successfully");
                exit;
            }else {
               $em = "You can't upload files of this type";
               header("Location: ../index.php?error=$em&$data");
               exit;
            }
         }else {
            $em = "unknown error occurred!";
            header("Location: ../index.php?error=$em&$data");
            exit;
         }*/


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

	
<form action="editprofile.php"  method="POST" enctype="multiplepart/form-data">


		<div class="container" id="cont1">
		<div class="row">

			<div class="col-md-6">
				<div class="container">
					<div class="row">

						<h1 class="header mb-3">Registration Form</h1>
						<form action="editprofile.php" method="POST" enctype="multipart/form-data">

				    	
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

						<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="input-container">
				  		<select class="input-field" name="gender" id="profession">
				  		<option selected hidden="hidden" default="true">Sex</option>
				  	 	 <option value="Male">Male</option>
				  	 	 <option value="Female">Female</option>
				  		 <option value="Not Specify">Prefer not to mention</option>
				  		</select>
						</div>
						</div>

            <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="input-container datepicker">
              <input type="text" class="input-field" name="birthdate" id="datepicker" placeholder="Birth Date" autocomplete="on" required>
              <label for="input-field" class="input-label">Birth Date</label>
              <span class="input-highlight"></span>
            </div>
            </div>

						<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="input-container">
						<select class="input-field" name="citizenship" id="citizenship" required onchange="if (this.value=='other') {display();this.form['other'].style.display='block'} else {displaynot();this.form['other']
						.style.display='none'};">
				  		<option selected hidden="hidden" default="true">Nationality</option>
  <option value="afghan">Afghan</option>
  <option value="albanian">Albanian</option>
  <option value="algerian">Algerian</option>
  <option value="american">American</option>
  <option value="andorran">Andorran</option>
  <option value="angolan">Angolan</option>
  <option value="antiguans">Antiguans</option>
  <option value="argentinean">Argentinean</option>
  <option value="armenian">Armenian</option>
  <option value="australian">Australian</option>
  <option value="austrian">Austrian</option>
  <option value="azerbaijani">Azerbaijani</option>
  <option value="bahamian">Bahamian</option>
  <option value="bahraini">Bahraini</option>
  <option value="bangladeshi">Bangladeshi</option>
  <option value="barbadian">Barbadian</option>
  <option value="barbudans">Barbudans</option>
  <option value="batswana">Batswana</option>
  <option value="belarusian">Belarusian</option>
  <option value="belgian">Belgian</option>
  <option value="belizean">Belizean</option>
  <option value="beninese">Beninese</option>
  <option value="bhutanese">Bhutanese</option>
  <option value="bolivian">Bolivian</option>
  <option value="bosnian">Bosnian</option>
  <option value="brazilian">Brazilian</option>
  <option value="british">British</option>
  <option value="bruneian">Bruneian</option>
  <option value="bulgarian">Bulgarian</option>
  <option value="burkinabe">Burkinabe</option>
  <option value="burmese">Burmese</option>
  <option value="burundian">Burundian</option>
  <option value="cambodian">Cambodian</option>
  <option value="cameroonian">Cameroonian</option>
  <option value="canadian">Canadian</option>
  <option value="cape verdean">Cape Verdean</option>
  <option value="central african">Central African</option>
  <option value="chadian">Chadian</option>
  <option value="chilean">Chilean</option>
  <option value="chinese">Chinese</option>
  <option value="colombian">Colombian</option>
  <option value="comoran">Comoran</option>
  <option value="congolese">Congolese</option>
  <option value="costa rican">Costa Rican</option>
  <option value="croatian">Croatian</option>
  <option value="cuban">Cuban</option>
  <option value="cypriot">Cypriot</option>
  <option value="czech">Czech</option>
  <option value="danish">Danish</option>
  <option value="djibouti">Djibouti</option>
  <option value="dominican">Dominican</option>
  <option value="dutch">Dutch</option>
  <option value="east timorese">East Timorese</option>
  <option value="ecuadorean">Ecuadorean</option>
  <option value="egyptian">Egyptian</option>
  <option value="emirian">Emirian</option>
  <option value="equatorial guinean">Equatorial Guinean</option>
  <option value="eritrean">Eritrean</option>
  <option value="estonian">Estonian</option>
  <option value="ethiopian">Ethiopian</option>
  <option value="fijian">Fijian</option>
  <option value="filipino">Filipino</option>
  <option value="finnish">Finnish</option>
  <option value="french">French</option>
  <option value="gabonese">Gabonese</option>
  <option value="gambian">Gambian</option>
  <option value="georgian">Georgian</option>
  <option value="german">German</option>
  <option value="ghanaian">Ghanaian</option>
  <option value="greek">Greek</option>
  <option value="grenadian">Grenadian</option>
  <option value="guatemalan">Guatemalan</option>
  <option value="guinea-bissauan">Guinea-Bissauan</option>
  <option value="guinean">Guinean</option>
  <option value="guyanese">Guyanese</option>
  <option value="haitian">Haitian</option>
  <option value="herzegovinian">Herzegovinian</option>
  <option value="honduran">Honduran</option>
  <option value="hungarian">Hungarian</option>
  <option value="icelander">Icelander</option>
  <option value="indian">Indian</option>
  <option value="indonesian">Indonesian</option>
  <option value="iranian">Iranian</option>
  <option value="iraqi">Iraqi</option>
  <option value="irish">Irish</option>
  <option value="israeli">Israeli</option>
  <option value="italian">Italian</option>
  <option value="ivorian">Ivorian</option>
  <option value="jamaican">Jamaican</option>
  <option value="japanese">Japanese</option>
  <option value="jordanian">Jordanian</option>
  <option value="kazakhstani">Kazakhstani</option>
  <option value="kenyan">Kenyan</option>
  <option value="kittian and nevisian">Kittian and Nevisian</option>
  <option value="kuwaiti">Kuwaiti</option>
  <option value="kyrgyz">Kyrgyz</option>
  <option value="laotian">Laotian</option>
  <option value="latvian">Latvian</option>
  <option value="lebanese">Lebanese</option>
  <option value="liberian">Liberian</option>
  <option value="libyan">Libyan</option>
  <option value="liechtensteiner">Liechtensteiner</option>
  <option value="lithuanian">Lithuanian</option>
  <option value="luxembourger">Luxembourger</option>
  <option value="macedonian">Macedonian</option>
  <option value="malagasy">Malagasy</option>
  <option value="malawian">Malawian</option>
  <option value="malaysian">Malaysian</option>
  <option value="maldivan">Maldivan</option>
  <option value="malian">Malian</option>
  <option value="maltese">Maltese</option>
  <option value="marshallese">Marshallese</option>
  <option value="mauritanian">Mauritanian</option>
  <option value="mauritian">Mauritian</option>
  <option value="mexican">Mexican</option>
  <option value="micronesian">Micronesian</option>
  <option value="moldovan">Moldovan</option>
  <option value="monacan">Monacan</option>
  <option value="mongolian">Mongolian</option>
  <option value="moroccan">Moroccan</option>
  <option value="mosotho">Mosotho</option>
  <option value="motswana">Motswana</option>
  <option value="mozambican">Mozambican</option>
  <option value="namibian">Namibian</option>
  <option value="nauruan">Nauruan</option>
  <option value="nepalese">Nepalese</option>
  <option value="new zealander">New Zealander</option>
  <option value="ni-vanuatu">Ni-Vanuatu</option>
  <option value="nicaraguan">Nicaraguan</option>
  <option value="nigerien">Nigerien</option>
  <option value="north korean">North Korean</option>
  <option value="northern irish">Northern Irish</option>
  <option value="norwegian">Norwegian</option>
  <option value="omani">Omani</option>
  <option value="pakistani">Pakistani</option>
  <option value="palauan">Palauan</option>
  <option value="panamanian">Panamanian</option>
  <option value="papua new guinean">Papua New Guinean</option>
  <option value="paraguayan">Paraguayan</option>
  <option value="peruvian">Peruvian</option>
  <option value="polish">Polish</option>
  <option value="portuguese">Portuguese</option>
  <option value="qatari">Qatari</option>
  <option value="romanian">Romanian</option>
  <option value="russian">Russian</option>
  <option value="rwandan">Rwandan</option>
  <option value="saint lucian">Saint Lucian</option>
  <option value="salvadoran">Salvadoran</option>
  <option value="samoan">Samoan</option>
  <option value="san marinese">San Marinese</option>
  <option value="sao tomean">Sao Tomean</option>
  <option value="saudi">Saudi</option>
  <option value="scottish">Scottish</option>
  <option value="senegalese">Senegalese</option>
  <option value="serbian">Serbian</option>
  <option value="seychellois">Seychellois</option>
  <option value="sierra leonean">Sierra Leonean</option>
  <option value="singaporean">Singaporean</option>
  <option value="slovakian">Slovakian</option>
  <option value="slovenian">Slovenian</option>
  <option value="solomon islander">Solomon Islander</option>
  <option value="somali">Somali</option>
  <option value="south african">South African</option>
  <option value="south korean">South Korean</option>
  <option value="spanish">Spanish</option>
  <option value="sri lankan">Sri Lankan</option>
  <option value="sudanese">Sudanese</option>
  <option value="surinamer">Surinamer</option>
  <option value="swazi">Swazi</option>
  <option value="swedish">Swedish</option>
  <option value="swiss">Swiss</option>
  <option value="syrian">Syrian</option>
  <option value="taiwanese">Taiwanese</option>
  <option value="tajik">Tajik</option>
  <option value="tanzanian">Tanzanian</option>
  <option value="thai">Thai</option>
  <option value="togolese">Togolese</option>
  <option value="tongan">Tongan</option>
  <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
  <option value="tunisian">Tunisian</option>
  <option value="turkish">Turkish</option>
  <option value="tuvaluan">Tuvaluan</option>
  <option value="ugandan">Ugandan</option>
  <option value="ukrainian">Ukrainian</option>
  <option value="uruguayan">Uruguayan</option>
  <option value="uzbekistani">Uzbekistani</option>
  <option value="venezuelan">Venezuelan</option>
  <option value="vietnamese">Vietnamese</option>
  <option value="welsh">Welsh</option>
  <option value="yemenite">Yemenite</option>
  <option value="zambian">Zambian</option>
  <option value="zimbabwean">Zimbabwean</option>
</select>
						</div>
						</div>

						<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="input-container">
						<select class="input-field" name="country" placeholder="Country" maxlength="1" selected="selected" autocomplete="on" id="country" on onkeyup="txtonlymi();">
				  		<option selected hidden="hidden" default="true">Country</option>
              <?php
                        require_once "conn.php";
 
                        $result = mysqli_query($conn,"SELECT * FROM countries");
 
                        while($row = mysqli_fetch_array($result)) {
                        ?>
                            <option value="<?php echo $row['iso2'];?>"><?php echo $row["name"];?></option>
                        <?php
                        }
                        ?>

						</select>
						</div>
						</div>			
				
						<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="input-container">
				  		<select type="text" class="input-field" name="region" placeholder="Region" id="region" autocomplete="on" required on onkeyup="txtonlyln();">
				  		<option selected hidden="hidden" selected="selected" default="true">Region</option>
				  		<span class="input-highlight"></span>
				  		<p id="txt-alertln">&nbsp;</p>
				  		</select>
						</div>
						</div>

						<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="input-container">
				  		<select type="text" class="input-field" name="cityprovince" placeholder="City/Province" id="city" autocomplete="on" required on onkeyup="txtonlyln();">
				  		<option selected hidden="hidden" selected="selected" default="true">City/Province</option>
				  		<span class="input-highlight"></span>
				  		<p id="txt-alertln">&nbsp;</p>
				  		</select>
						</div>
						</div>
            <script>
$(document).ready(function() {
    $('#country').change(function() {
            var country_id = this.value;

            // alert(country_id);
            // console.log(country_id);

            if(country_id == "PH"){
              $.ajax({
                url: "ajax/validateProvince.php",
                type: "POST",
                data: {
                    provid : country_id
                },
                dataType : "html",
                success: function(data){
                   //alert("test " + data);

                    $("#region").html(data);
                }
              });
            }
            else{
              $.ajax({
                url: "../statevalidation.php",
                type: "POST",
                data: {
                    isoID : country_id
                },
                dataType : "html",
                success: function(data){
                   /* alert("test " + data);*/
                    $("#region").html(data);
                }
              });
            }
            
            
         
         
    });    
 
    $('#region').change(function() {
            var ph = $('#country').val();
            var provCode = this.value;

            // alert(ph);

            if(ph == "PH"){
              $.ajax({
                url: "ajax/validateCity.php",
                type: "POST",
                data: {
                    ctid : provCode
                },
                dataType : "html",
                success: function(data){
                  //alert("test " + data);

                    $("#city").html(data);
                }
              });
            }
            else{
              $.ajax({
                url: "ajax/citiesvalidation.php",
                type: "POST",
                data: {
                    citycode: provCode
                },
                dataType : "html",
                success: function(data){
                 
                    $("#city").html(data);
                }
              });
            }
         
         
    });
});
</script>

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
            <select class="input-field" name="profession" id="profession" required onchange="if (this.value=='others') {display();this.form['others'].style.display='block'} else {displaynot();this.form['others']
            .style.display='none'};">
              <option selected hidden="hidden" default="true">Profession</option>
               <option value="Chemical Engineer">Chemical Engineer</option>
               <option value="Chemist">Chemist</option>
               <option value="Chemical Technician">Chemical Technician</option>
               <option value="others">Others</option>
              </select>           
              </div>
              </div>

              <div class="input col-lg-6 col-md-12 col-sm-12">
              <input type="textbox" name="others" class="input-field" placeholder="Type other profession here" style="display: none;"/>
              <label for="input-field" class="input-label">Other</label>
              <span class="input-highlight"></span>
              </div>


              <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="input-container">
              <input type="number" class="input-field" name="prclicense" id="prclicense" placeholder="PRC License" autocomplete="on" required>
              <label for="input-field" class="input-label">PRC License</label>
              <p id="prc-txt-alert">&nbsp;</p>
              <span class="input-highlight"></span>
            </div>
            </div>

						<div class="col-lg-6 col-md-12 col-sm-12">
						<div class="input-container">			  		
				  		<input type="text" class="input-field" name="username" id="organization" placeholder="Username" autocomplete="on" required>
				  		<label for="input-field" class="input-label">Username</label>
				  		<span class="input-highlight"></span>
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
            


						<div class="input col-lg-12 col-md-12 col-sm-12">
				  		<input type="textbox" name="other" class="input-field" placeholder="Type other profession here" style="display: none;"/>
				  		<label for="input-field" class="input-label">Other</label>
				  		<span class="input-highlight"></span>
				  		</div>


						
						<div class="col-lg-12 col-md-12 col-sm-12 mb-4">
						<input type="checkbox" id="checkbox" required>
				  		<label for="terms of service" id="link1">I hereby agree with the <a target="_blank" href="https://ptri.dost.gov.ph/">Data Privacy Policy </a>and <a target="_blank" href="https://ptri.dost.gov.ph/">Terms of Use</a></label>
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
      <script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#fileToUpload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#fileToUpload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

			<!--document.getElementById('prclicense').required = false;-->


			<!--start for the image-->
			<div class="col-md-6 regpic">
              <form id="form1" runat="server" action="editprofile.php" method="POST" enctype="multipart/form-data" >
                <img class="rounded-circle"  id="fileToUpload" name="fileToUpload" src="user1.jpg" alt="your image" style= "margin-bottom: 20px; border-style: solid; border-width: 10px; border-color: lightblue; margin-left: 40px; margin-top: 60px; width: 500px; height: 500px;" />
        <input type="file" name="fileToUpload" onchange="readURL(this);" style="bottom: 300px; margin-left: 200px; font-family: MelodyBold; color: gray;"/>
        
    </form>
					</div>
				</div>
			<!--end for the image-->
				
			

   <?php  include("header-footer/navbar.php" );?>
   <?php  include("header-footer/footer.php" );?>
</body>
</html>












