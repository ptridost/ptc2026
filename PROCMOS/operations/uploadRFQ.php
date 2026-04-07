<?php
  require_once("../DatabaseConnection/databaseconnection.php");

  $target_dir = "../RFQs/";


  $target_file = $target_dir . basename($_FILES["inputFile_RFQ"]["name"]);
  



  $uploadOk = 1;
  $uploaded_FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));



  // die($target_file);
  
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

  if($uploaded_FileType != "pdf") {
    
    echo "Sorry, only PDF files are allowed.";
    echo '<script type="text/javascript">alert("Sorry, only PDF files are allowed.");</script>';
    $uploadOk = 0;
    // $_SESSION['uploadAPP'] = "true";
    echo '<script type="text/javascript">window.location.href = "../pages/attach_rfq.php";</script>';
  }
  else{
    //okay file type
    $uploadOk = 1;
  }

  $uploadFilename = $_FILES["inputFile_RFQ"]["name"];





  if ($uploadFilename == trim($uploadFilename) && strpos($uploadFilename, ' ')) {
    // echo "true";
    // error, blank spaces in the filename
    echo "Sorry, blank spaces in the filename is not allowed. Please rename your file (use an underscore) you wish to upload.";
    echo '<script type="text/javascript">alert("Sorry, blank spaces in the filename is not allowed. Please rename your file you wish to upload.");</script>';
    $uploadOk = 0;
    echo '<script type="text/javascript">window.location.href = "../pages/attach_rfq.php";</script>';
  }
  else{
    //okay, no blank spaces
    // echo "false";
    $uploadOk = 1;
  }

  // Check if $uploadOk is set to 0 by an error

  // var_dump($target_file);
  // die();

  // $rfqStatus = "APPROVED";
  // $uploadFilename = "test";

  // var_dump($uploadFilename);
  //   die();

  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } 
  else {
    if (move_uploaded_file($_FILES["inputFile_RFQ"]["tmp_name"], $target_file)) {

      // var_dump("SUCCESS");
      // die();

    //   var_dump($uploadFilename);
    // die();

      $stmt = $con->prepare("INSERT INTO tbl_rfq (rfq_Date, rfq_filename, rfq_No, pr_No, rfq_Company, rfq_Address, rfq_Email, rfq_telNum, rfq_enduser, rfq_uploadedby) VALUES (:rfq_Date, :rfq_filename, :rfq_No, :pr_No, :rfq_Company, :rfq_Address, :rfq_Email, :rfq_TelNum, :rfq_enduser, :rfq_uploadedBy)");

      $stmt->bindParam(':rfq_filename', $uploadFilename);
      
      $stmt->bindParam(':rfq_Date', $_POST['txtRFQdateSubmit']);
      $stmt->bindParam(':rfq_No', $_POST['txtRFQnoSubmit']);
      $stmt->bindParam(':pr_No', $_POST['txtPRnoSubmit']);
      $stmt->bindParam(':rfq_Company', $_POST['txtRFQcompanySubmit']);
      $stmt->bindParam(':rfq_Address', $_POST['txtRFQaddressSubmit']);
      $stmt->bindParam(':rfq_Email', $_POST['txtRFQcompanyemail']);
      $stmt->bindParam(':rfq_TelNum', $_POST['txtRFQcompanytel']);
      $stmt->bindParam(':rfq_enduser', $_POST['txtRFQenduserSubmit']);
      $stmt->bindParam(':rfq_uploadedBy', $_SESSION['Name']);
      

      // var_dump($stmt);
      // die();


      if($stmt->execute()){
        // echo "The file ". htmlspecialchars( basename( $_FILES["inputFile_RFQ"]["name"])). " has been uploaded.";
        // echo '<script type="text/javascript">alert("The file '. htmlspecialchars( basename( $_FILES["inputFile_RFQ"]["name"])) . ' has been uploaded.");</script>';
        echo '<script type="text/javascript">alert("'.$_POST['txtRFQcompanySubmit'].' is successfully submitted in the database"); history.back();</script>';
      }
      else{
        echo '<script type="text/javascript">alert("ERROR! Database failed"); history.back();</script>';
        // echo '<script type="text/javascript">window.location.href = "../pages/attach_rfq.php";</script>';
      }
    } 
    else {

      // var_dump("FAILED");
      // die();

      echo "Sorry, there was an error uploading your file.";
      echo '<script type="text/javascript">alert("Sorry, there was an error uploading your file.");</script>';
      echo '<script type="text/javascript">window.location.href = "../pages/attach_rfq.php";</script>';
    }
  }
?>