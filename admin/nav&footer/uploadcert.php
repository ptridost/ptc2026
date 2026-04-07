  
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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="admindashboard.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/usernav.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>


   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 
    <title>Admin Dashboard</title>
  </head>
  <body>
    <body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <a style="margin-top: 100px; margin-left: 550px; font-family: Melody; font-size: 60px;"class="navbar-brand">RECORDED SESSION</a>
    </div>
  </nav>

    <div class = "col-md-10" style = "margin-left: 20px;">


     <form action="save_video.php" method="POST" enctype="multipart/form-data">
                <input style = "font-family: Melody; font-size: 20px;"type="file" name="video" class="form-control-file"/>
                <input type= "hidden" name= "hidden" value="<?php echo $_GET['id'];?>">
       <button style ="font-family: Melody; font-size: 20px; margin-top: 70px;" name="save" class="btn btn-primary"> Save</button>
                
                 
    <?php

      require 'conn.php';

      
      $query = mysqli_query($conn, "SELECT * FROM video WHERE video_id =".$_GET['id']."") or die(mysqli_error());
      while($fetch = mysqli_fetch_array($query)){
    ?>
    <div class="col-md-12">
      <div class="col-md-4" style="word-wrap:break-word;">
        <br />
    
        <h4><?php echo $fetch['video_name']?></h4>
   
      </div>
      <div >
        <video style= "margin-left: 120px; width: 100%; height: 400px;" controls>
          <source src="<?php echo $fetch['location']?>">
        </video>
      </div>
      <br style="clear:both;"/>
      <hr style="border-top:1px groovy #000;"/>
    </div>
    <?php
      }
    ?>
  </div>     
</form>
    
    <?php  include("nav&footer/usernav.php" );?>
    <?php  include("nav&footer/footer.php" );?>
  </body>
  </html>