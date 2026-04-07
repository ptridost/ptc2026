  
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
 </head>
<body>
  <?php $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error()); ?>
  <div class="container con_part">
<div class="heading" id="title_part">
<img src="Picture1.png" style = "margin-top: 100px; margin-bottom: -55px; width: 60px; height: 60px;">

<h1  style="margin-left: 70px;">List of participants who submitted feedback forms /</h1>
</div>
<br>

<?php
$sql = "SELECT COUNT(DISTINCT participant_id) AS 'total3' FROM tblfeedback WHERE session_id = ".$_GET['id']."";
$all_session = $dbcon->query($sql);

while($row = mysqli_fetch_assoc($all_session)){
        
        $count2 = $row['total3'];

?>
        <div class="col-md-4 graph" style="text-align: right;">
            <div class="graph_inner">
              <h1>Number of Feedbacks Received</h1>
              <div>
              <div class="text-center align-middle" id="chart-container " >
                <h2 class="timer count-title count-number" data-to="<?php echo $count2; ?>" data-speed="1500" style="font-size: 120px; font-family: melody; display: inline-block; text-align: center;"><?php echo $count2; ?></h2>
              </div>
            </div>
          </div>
       
      </div>
  </form>
<?php
}
?>
  
<div class="table-responsive">
<center>
<table class="table caption-top" style="width: 100%;">
  <thead>
    <tr class="tbl_name">
      <th scope="col">Date and Time</th>
      <th scope="col">Fullname</th>
      <th scope="col">Email Address</th>
      <th scope="col">Organization</th>
      <th scope="col">Position</th>
      <th scope="col">Comment</th>
      <th scope="col">Information</th>
    </tr>
  </thead>
  <tbody>
    
    <?php 
  
    $sql_rc = mysqli_query($dbcon, "SELECT * FROM tblfeedback WHERE session_id =".$_GET['id']."");

        while($sql_rc_row = mysqli_fetch_array($sql_rc)){
          $str = $sql_rc_row['fDate'];?>
      <tr>
      <th scope="row"><?php echo date('g:i A, l - d M Y', strtotime($str)); ?></th>
      <td><?php echo $sql_rc_row['Fname']; ?> <?php echo $sql_rc_row['Mname']; ?> <?php echo $sql_rc_row['Lname']; ?></td>
      <td><?php echo $sql_rc_row['email']; ?></td>
      <td><?php echo $sql_rc_row['organization']; ?></td>
      <td><?php echo $sql_rc_row['position']; ?></td>
      <td><?php echo $sql_rc_row['comment']; ?></td>
      <td><?php echo $sql_rc_row['information']; ?></td>
      <td></td>
    </tr>
    <input type= "hidden" name= "hidden" value="<?php echo $_GET['id'];?>">


    <?php 
        }  
  ?>
  </tbody>
</table>
</center>
</div>
</div>
<?php  include("nav&footer/recordnav.php" );?>
    <?php  include("nav&footer/footer.php" );?>
  
  </body>
  </html>