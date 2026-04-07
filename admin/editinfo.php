  
<?php

  session_start();

  
  // var_dump($_SESSION['admin_id']);
  // die();
  if(!empty($_SESSION['admin_id'])){
    // header("Location: admin/admindashboard.php");
  }
  else{
    header("Location: ../Loginn.php"); 
  }

?>

<!DOCTYPE html>
<html lang="en">
<script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/Chart.min.js"></script>
  <head>
    <style type="text/css">     
                
                }
        </style>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="admindashboard.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/usernav.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    


   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <?php  include("nav&footer/usernav.php" );?>
    <?php  include("nav&footer/footer.php" );?>
    <title>Admin Dashboard</title>
  </head>
  <body>
   <input type= "hidden" name= "hidden" value="<?php echo $_GET['id'];?>">
  
  </body>
  </html>