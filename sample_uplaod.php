<!doctype html>
<html lang="en">
  <head>
   
  </head>
  <body>

    <?php
  

  $target_dir = "/user_profile/";


  $target_file = $target_dir . basename($_FILES["inputFile_APP"]["name"]);

  var_dump($target_file);
  die();
  
  
  $uploadOk = 1;
  $uploaded_FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


  // var_dump($uploaded_FileType);
  // die();
  // // Check if image file is a actual image or fake image
  // if(isset($_POST["submit"])) {
  //   $check = getimagesize($_FILES["inputFile_APP"]["tmp_name"]);
  //   if($check !== false) {
  //     echo "File is an image - " . $check["mime"] . ".";
  //     $uploadOk = 1;
  //   } else {
  //     echo "File is not an image.";
  //     $uploadOk = 0;
  //   }
  // }

  // // Check if file already exists (pag same file name, mag override ito sa OG file, kaya dapat wag same name)
  // if (file_exists($target_file)) {
  //   echo "Sorry, file already exists.";
  //   $uploadOk = 0;
  // }

  // // Check file size
  // if ($_FILES["inputFile_APP"]["size"] > 500000) {
  //   echo "Sorry, your file is too large.";
  //   $uploadOk = 0;
  // }

  // Allow certain file formats



  if($uploaded_FileType != "jpg" && $uploaded_FileType != "png") {
    
    echo "Sorry, only .XLS & .XLSX files are allowed.";
    echo '<script type="text/javascript">alert("Sorry, only .XLS & .XLSX files are allowed.");</script>';
    $uploadOk = 0;
    // $_SESSION['uploadAPP'] = "true";
    // echo '<script type="text/javascript">window.location.href = "../pages/app.php";</script>';
  }
  else{
    //okay file type
    $uploadOk = 1;
  }

  $uploadFilename = $_FILES["inputFile_APP"]["name"];

  if ($uploadFilename == trim($uploadFilename) && strpos($uploadFilename, ' ')) {
    // echo "true";
    // error, blank spaces in the filename
    echo "Sorry, blank spaces in the filename is not allowed. Please rename your file (use an underscore) you wish to upload.";
    echo '<script type="text/javascript">alert("Sorry, blank spaces in the filename is not allowed. Please rename your file you wish to upload.");</script>';
    $uploadOk = 0;
    // echo '<script type="text/javascript">window.location.href = "../pages/app.php";</script>';
  }
  else{
    //okay, no blank spaces
    echo "false";
    $uploadOk = 1;
  }

  // Check if $uploadOk is set to 0 by an error

  

  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } 
  else {
    if (move_uploaded_file($_FILES["inputFile_APP"]["tmp_name"], $target_file)) {

      

      
        echo "The file ". htmlspecialchars( basename( $_FILES["inputFile_APP"]["name"])). " has been uploaded.";
        echo '<script type="text/javascript">alert("The file '. htmlspecialchars( basename( $_FILES["inputFile_APP"]["name"])) . ' has been uploaded.");</script>';
      
    } 
    else {
      echo "Sorry, there was an error uploading your file.";
      echo '<script type="text/javascript">alert("Sorry, there was an error uploading your file.");</script>';
    }
  }
?>


      <form action="sample_uplaod.php"  method="post" enctype="multipart/form-data">
      <div class="input-container">
              <p class="uploadprof">Upload Profile Picture</p>
            <input type="file" class="input-field" name="inputFile_APP" id="inputFile_APP" value="" required>
            <label for="input-field" class="input-label">Upload Image</label>
            <span class="input-highlight"></span>                       
            </div>
            <br>
            <input class="btn btn btn-lg" type="submit" name="submit" id="save-btn" value="Register">
          </form>
  </body>
</html>