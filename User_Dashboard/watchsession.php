<?php
  session_start();
  if(!empty($_SESSION['participant_id'])){

  }
  else{
    header("Location: ../Loginn.php"); 
  }

?>

<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Dash Board</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
   
   <link rel="stylesheet" type="text/css" href="./user_head&footer/footer.css">
   <link rel="stylesheet" type="text/css" href="./user_head&footer/user_nav.css">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>

    <!-- Bootstrap CSS -->
    


   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 
    <title>Admin Dashboard</title>
  </head>
  <body>
   
    <?php
    require 'conn.php';
      
      $query = mysqli_query($conn, "SELECT * FROM video WHERE session_id =".$_GET['id']."") or die(mysqli_error());
      while($fetch = mysqli_fetch_array($query)){
    ?>
    <div class="col-md-12">
      <div style="margin-top:70px;"class="col-md-4" style="word-wrap:break-word;">
        <br/>
      </div>
      <div >
        <video width="100%" height="550px" controls>
          <source src="../admin/<?php echo $fetch['location']?>">
        </video>
      </div>
      <br style="clear:both;"/>
    </div>
    <input type= "hidden" name= "hidden" value="<?php echo $_GET['id'];?>">
    <?php
      }
    ?>
  </div>     

    
   <?php  include("./user_head&footer/footer.php" );?>
    <?php  include("./user_head&footer/user_nav.php" );?>
  </body>
  </html>