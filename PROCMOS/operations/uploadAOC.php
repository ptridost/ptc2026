<?php
  require_once("../DatabaseConnection/databaseconnection.php");

  $target_dir = "../AOCs/";


  $target_file = $target_dir . basename($_FILES["inputFile_AOC"]["name"]);
  
  
  $uploadOk = 1;
  $uploaded_FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // var_dump($target_file);
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

  if($uploaded_FileType != "pdf") {
    
    echo "Sorry, only PDF files are allowed.";
    echo '<script type="text/javascript">alert("Sorry, only PDF files are allowed.");</script>';
    $uploadOk = 0;
    // $_SESSION['uploadAPP'] = "true";
    echo '<script type="text/javascript">window.location.href = "../pages/attach_aoc.php";</script>';
  }
  else{
    //okay file type
    $uploadOk = 1;
  }

  $uploadFilename = $_FILES["inputFile_AOC"]["name"];



  // if ($uploadFilename == trim($uploadFilename) && strpos($uploadFilename, ' ')) {
  //   // echo "true";
  //   // error, blank spaces in the filename
  //   echo "Sorry, blank spaces in the filename is not allowed. Please rename your file (use an underscore) you wish to upload.";
  //   echo '<script type="text/javascript">alert("Sorry, blank spaces in the filename is not allowed. Please rename your file you wish to upload.");</script>';
  //   $uploadOk = 0;
  //   echo '<script type="text/javascript">window.location.href = "../pages/attach_rfq.php";</script>';
  // }
  // else{
  //   //okay, no blank spaces
  //   // echo "false";
  //   $uploadOk = 1;
  // }

  // Check if $uploadOk is set to 0 by an error

  // var_dump($target_file);
  // die();

  // $rfqStatus = "APPROVED";
  // $uploadFilename = "test";

  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } 
  else {
    if (move_uploaded_file($_FILES["inputFile_AOC"]["tmp_name"], $target_file)) {

      // var_dump("SUCCESS");
      // die();

      $stmt = $con->prepare("UPDATE tbl_aoc SET aocpdf_date=NOW(), aoc_pdf=:aocPDF WHERE aoc_number=:aoc_number");

      $stmt->bindParam(':aocPDF', $uploadFilename);
      $stmt->bindParam(':aoc_number', $_POST['txtAOCNo']);

      // var_dump($stmt);
      // die();


      if($stmt->execute()){
        // echo "The file ". htmlspecialchars( basename( $_FILES["inputFile_RFQ"]["name"])). " has been uploaded.";
        // echo '<script type="text/javascript">alert("The file '. htmlspecialchars( basename( $_FILES["inputFile_RFQ"]["name"])) . ' has been uploaded.");</script>';
        $_SESSION['successUploadedAOC'] = $_POST['txtAOCNo'];

        echo '<script type="text/javascript">alert("AOC no. '.$_POST['txtAOCNo'].' is successfully uploaded in the database"); window.location.href = "../pages/attach_aoc.php";</script>';
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
      echo '<script type="text/javascript">window.location.href = "../pages/attach_aoc.php";</script>';
    }
  }
?>