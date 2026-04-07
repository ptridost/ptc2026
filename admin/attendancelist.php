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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="admindashboard.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/usernav.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    


   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <?php  include("nav&footer/recordnav.php" );?>
    <?php  include("nav&footer/footer.php" );?>
    <title>Admin Dashboard</title>
  </head>



 <body>


  <?php $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error()); ?>
  <div class="container con_part">
<div class="heading" id="title_part">
<img src="Picture1.png" style = "margin-top: 100px; margin-bottom: -55px; width: 60px; height: 60px;">

<h1  style="margin-left: 70px;">List of Participants Attended the Session /</h1>
</div>
<br>
<form class="search-container" action="attendancelist.php" method="GET">
  <input id="search-box" type="text" class="search-box" name="q" value="<?php if(isset($_GET['q'])){echo $_GET['q']; } ?>" />
  <label for="search-box"><span class="fa-solid fa-magnifying-glass"></span></label>
  <input type="submit" id="search-submit" />
</form>
 
<style>
body {
  margin: 5%;
}

.search-box {
  transition: width 0.6s, border-radius 0.6s, background 0.6s, box-shadow 0.6s;
  width: 40px;
  height: 40px;
  border-radius: 20px;
  border: none;
  cursor: pointer;
  background: #ebebeb;
}
.search-box + label .search-icon {
  color: black;
}
.search-box:hover {
  color: white;
  background: #c8c8c8;
  box-shadow: 0 0 0 5px #3d4752;
}
.search-box:hover + label .search-icon {
  color: white;
}
.search-box:focus {
  transition: width 0.6s cubic-bezier(0, 1.22, 0.66, 1.39), border-radius 0.6s, background 0.6s;
  outline: none;
  box-shadow: none;
  padding-left: 15px;
  cursor: text;
  width: 300px;
  border-radius: auto;
  background: #ebebeb;
  color: black;
}
.search-box:focus + label .search-icon {
  color: black;
}
.search-box:not(:focus) {
  text-indent: -5000px;
}

#search-submit {
  position: relative;
  left: -5000px;
}

.search-icon {
  position: relative;
  left: -30px;
  color: white;
  cursor: pointer;
}
</style>

  <script>
  window.console = window.console || function(t) {};
</script>
<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js'></script>
      <script id="rendered-js" >
document.addEventListener("touchstart", function () {}, true);
    </script>


<?php
 $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT COUNT(DISTINCT participant_id) AS 'total3' FROM tblattendance WHERE session_id = ".$_GET['id']."";
$all_session = $dbcon->query($sql);

while($row = mysqli_fetch_assoc($all_session)){
        
        $count2 = $row['total3'];

?>
        <div class="col-md-4 graph" style="text-align: right;">
            <div class="graph_inner">
              <h1>Total Number of Participants</h1>
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
      <th scope="col">Fullname</th>
      <th scope="col">Gender</th>
      <th scope="col">Country</th>
      <th scope="col">Institution</th>
      <th scope="col">Position</th>
      <th scope="col">Date and Time</th>
    </tr>
  </thead>
  <tbody>
    <?php 
 $con = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb");

 if(isset($_GET['q']))
 {
 $filtervalues = $_GET['q'];
    $query = "SELECT * FROM tblattendance WHERE CONCAT(fname,mname,lname,Gender,Country,Institution,Position,date_submitted) LIKE '%$filtervalues%' ";
     $query_run = mysqli_query($con, $query);

       if(mysqli_num_rows($query_run) > 0)
                                        {
         foreach($query_run as $items)
          $str = $items['date_submitted'];
          {
  ?>

    <tr class="tbl_name">
      <th scope="row"><?= $items['fname']; ?> <?= $items['mname']; ?> <?= $items['lname']; ?></td>
      <th scope="row"><?= $items['Gender']; ?></td>
      <th scope="row"><?= $items['Country']; ?></td>
      <th scope="row"><?= $items['Institution']; ?></td>
      <th scope="row"><?= $items['Position']; ?></td>
      <th scope="row"><?= date('g:i A, l - d M Y', strtotime($str)); ?></td>
    </tr>
    <?php
      }
      }
      else
       {
        ?>
        <tr>
        <td colspan="4">No Record Found</td>
        </tr>
        <?php
        }
        }
         else{
  $sql_rc = mysqli_query($dbcon, "SELECT * FROM tblattendance WHERE session_id =".$_GET['id']."");

        while($sql_rc_row = mysqli_fetch_array($sql_rc)){
          $str = $sql_rc_row['date_submitted'];

  ?>
  <tbody>
    <tr>
      <td><?php echo $sql_rc_row['fname']; ?> <?php echo $sql_rc_row['mname']; ?> <?php echo $sql_rc_row['lname']; ?></td>
      <td><?php echo $sql_rc_row['Gender']; ?></td>
      <td><?php echo $sql_rc_row['Country']; ?></td>
      <td><?php echo $sql_rc_row['Institution']; ?></td>
      <td><?php echo $sql_rc_row['Position']; ?></td>
      <th scope="row"><?php echo date('g:i A, l - d M Y', strtotime($str)); ?></th>
      <td></td>
    </tr>
    <input type= "hidden" name= "hidden" value="<?php echo $_GET['id'];?>">
    <?php 
  }
          }
  ?>

   </tbody>
   
  </tbody>
</table>
</center>
</div>
</div>
  
  </body>
  </html>