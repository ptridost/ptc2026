<?php $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error()); ?>		

<!DOCTYPE html>
<html>
<head>



	<?php

	if(isset($_POST['submit'])){
    print "helooerdkf0";
    print_r($_FILES['file']['name']);
    if(isset($_FILES["anyfile"]) && $_FILES["anyfile"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["anyfile"]["name"];
        $filetype = $_FILES["anyfile"]["type"];
        $filesize = $_FILES["anyfile"]["size"]

		
        // Validate file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
     
        // Validate file size - 10MB maximum
        $maxsize = 10 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
     
        // Validate type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("upload/" . $filename)){
                echo $filename . " is already exists.";
            } else{
                if(move_uploaded_file($_FILES["anyfile"]["tmp_name"], "upload/" . $filename)){
 
                     $sql="INSERT INTO images(file,type,size) VALUES('$filename','$filetype','$filesize')";
                     
                    mysqli_query($conn,$sql);

 
                    echo "Your file was uploaded successfully.";
                }else{
 
                   echo "File is not uploaded";
                }
                 
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } else{
     
        echo "Error: " . $_FILES["anyfile"]["name"];
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

	
<!-- <form action="registration.php"  method="POST" enctype="multiplepart/form-data"> -->
  <div class="col-md-6 regpic">
              
      


		<div class="container" id="cont1">
		<div class="row">

			<div class="col-md-6">
				<div class="container">
					<div class="row">

						<h1 class="header mb-3">Registration Form</h1>
						<form action="registration.php" method="POST" enctype="multipart/form-data">
                <img class="rounded-circle"  id="anyfile" name="file" src="user1.jpg" alt="your image" style= "margin-bottom: 20px; border-style: solid; border-width: 10px; border-color: lightblue; margin-left: 40px; margin-top: 60px; width: 500px; height: 500px;" />
        <input type="file" name="file" onchange="readURL(this);" style="bottom: 300px; margin-left: 200px; font-family: MelodyBold; color: gray;"/>

              <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
              <input class="btn btn btn-lg" type="submit" name="submit" id="save-btn" value="Register">
              </div>
				    	
				    	
        </form>
		

   <?php  include("header-footer/footer.php" );?>
</body>
</html>











