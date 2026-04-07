<?php
  session_start();

  // var_dump("test");
  // die();
  if(!empty($_SESSION['participant_id'])){
    // header("Location: admin/admindashboard.php");
  }
  else{
    header("Location: ../Loginn.php"); 
  }

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="generatecert.css">
    <title>Certificate of Attendance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="user_head&footer/footer.css">
   <link rel="stylesheet" type="text/css" href="user_head&footer/user_nav.css">
  </head>
  <body>
    <div class="container concert">
      <div class="row gencert_row">
        <div class="col-md-12 gencert_title">
          
          <img src="Picture1.png"><h1>PTC Generated Certificate of Attendance</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4 gencertcol1">
          <div class="gencert_button">
        <a href="../User_Dashboard/PTC Certificate_page-0001.jpg" class="download" download="PTC Certificate_page-0001.jpg" type="button"><i class="fa-solid fa-download"></i>Download</a>
        <a class="print" type="button" target="_blank" href="../User_Dashboard/PTC Certificate_page-0001.jpg"><i class="fa-solid fa-print"></i>Print</a>
      </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-1"></div>
      <div class="col-md-12 certimg">
      <img src="PTC Certificate_page-0001.jpg">
      </div>
      <div class="col-md-1"></div>
    </div>
  </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

          <?php  include("user_head&footer/footer.php" );?>
    <?php  include("user_head&footer/user_nav.php" );?>

   <script type="text/javascript">
     function printImg(url) {
     var win = window.open('');
     win.document.write('<img src="' + url + '" onload="window.print();window.close()" />');
     win.focus();
    }
   </script>

  </body>
</html>