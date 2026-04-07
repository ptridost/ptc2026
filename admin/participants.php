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
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<link rel="stylesheet" href="participants.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/usernav.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/footer.css">
<style type="text/css">
  p{
    font-size: 10px;
    text-align: center;
  }
</style>
</head>
<body>
  <?php $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error()); ?>
  <div class="container con_part">
<div class="heading" id="title_part">
<img class="part_logo" src="picture1.png">
<h2>List of Participants/</h2>
</div>
<br>

<div class="table-responsive">
<center>
<table class="table caption-top" style="width: 100%;">
  <thead>
    <tr class="tbl_name">
      <th scope="col">Registration ID</th>
      <th scope="col">Fullname</th>
      <th scope="col">Date of Birth</th>
      <th scope="col">Email Address</th>
      <th scope="col">Profession / PRC License</th>
      <th scope="col">Organization</th>
      <th scope="col">Position</th>
    </tr>
  </thead>
  <tbody>
    <?php
  $sql_rc = mysqli_query($dbcon, "SELECT * FROM participant WHERE type = 'Participant'");
        while($sql_rc_row = mysqli_fetch_array($sql_rc)){
  ?>
    <tr>
      <th scope="row"><?php echo $sql_rc_row['id']; ?></th>
      <td><?php echo $sql_rc_row['firstName']; ?> <?php echo $sql_rc_row['middleName']; ?> <?php echo $sql_rc_row['lastName']; ?></td>
      <td><?php echo $sql_rc_row['birthdate']; ?></td>
      <td><?php echo $sql_rc_row['email']; ?></td>
      <td><?php echo $sql_rc_row['prclicense']; ?></td>
      <td><?php echo $sql_rc_row['organization']; ?></td>
      <td><?php echo $sql_rc_row['position']; ?></td>
      <td></td>
    </tr>
    <?php 
          }
  ?>
   
   
  </tbody>
</table>
</center>
</div>
</div>
    <?php  include("nav&footer/usernav.php" );?>
    <?php  include("nav&footer/footer.php" );?>
</body>
</html>

