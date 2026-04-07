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
    <link rel="stylesheet" type="text/css" href="attendanceform.css">
    <title>Attendance Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 
    <link rel="stylesheet" type="text/css" href="user_head&footer/footer.css">
   <link rel="stylesheet" type="text/css" href="user_head&footer/user_nav.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    
  <div class="container atten_con">
    <div class="row">
      
      <div class="col-md-12 atten_title">
        <img src="Picture1.png">
        <h1>PTC Attendance Form</h1>
    </div></div>
    <br>
    <br>
<?php 
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM tblptcsession WHERE session_number = '".$_GET['id']."'";
$all_session = $dbcon->query($sql);
      
        while($row = mysqli_fetch_assoc($all_session)){
?>
    <div class="container atten_con1">
    <div class="row">
      <div class="col-md-12 atten_inner_title">
      <img src="../landingpageimg/<?php echo $row['logo_image']; ?>">
      <div class="h1_img">
     <h1><?php echo $row['session_title']; ?></h1>

<?php
}
?>
<?php

$dbcon = new mysqli($servername, $username, $password, $dbname);
$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : $_POST['hidden'];
$sql = "SELECT * FROM tblspeakerses WHERE session_id =".$id." AND year = '2024' AND speaker_Img NOT IN ('placeholder.jpg')";
$all_session = $dbcon->query($sql);


$countrow[] = array();
while($row = mysqli_fetch_assoc($all_session)){
  $countrow[] = $row;
} 
      for($i = 1; $i < count($countrow); $i++){

?>
       <img src="./2024/<?php echo $countrow[$i]['speaker_Img'];?>">
<?php
}
?>
     </div>
    </div>
  </div>
      <br>
    <br>
  <div class="row">
    <div class="col-md-12 inner_p">
    <p> <span>Data Privacy Notice:</span> In line with the Data Privacy Act of 2012, DOST-PTRI is committed to protect and secure your personal information obtained in the evaluation of the conduct of <span>2024 Philippine Textile Congress</span>. The personal information you provided herein will be processed and utilized solely for this event. Collected personal information will be kept/stored and accessed only by authorized DOST-PTRI personnel and shall not be shared with any outside parties unless written consent is secured. Information shall be stored within one (1) year after which physical records shall be disposed of or destroyed following the disposition process provided by the National Archives of the Philippines.</p>
  </div>
  </div>
  <br>
  <!-- <div class="row">
    <div class="col-md-12 col-sm-12 inputBox">
        <i class="fa-solid fa-envelope"></i><input type="text" required="required"  id="email_address" name="email_address" ><span>Email Address</span>
    </div>
  </div>
  
  <div class="row inputBox1row">
    <div class="col-md-4 col-sm-12 inputBox1">
      <i class="fa-solid fa-user"></i><input type="text" required="required"  id="email_address" name="email_address" ><span>First Name</span>
    </div>
    <div class="col-md-4 col-sm-12 inputBox1">
      <i class="fa-solid fa-user"></i><input type="text" required="required"  id="email_address" name="email_address" ><span>Middle Name</span>
    </div>
    <div class="col-md-4 col-sm-12 inputBox1">
      <i class="fa-solid fa-user"></i><input type="text" required="required"  id="email_address" name="email_address" ><span>Last Name</span>
    </div>
  </div>
  <div class="row">
    <div class="col-md-7">
      <div class="inputBox2">
      <i class="fa-solid fa-sitemap"></i><input type="text" required="required"  id="email_address" name="email_address" ><span>Organization</span>
    </div>
    </div>
    <div class="col-md-5 ">
      <div class="inputBox2">
      <i class="fa-solid fa-briefcase"></i><input type="text" required="required"  id="email_address" name="email_address" ><span>Position</span>
    </div>
    </div> -->


    <form action="attendanceform.php" method="POST" enctype="multipart/form-data">
<?php
$servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * FROM tblattendance WHERE session_id = '".$_GET['id']."' AND participant_id = ".$_SESSION['participant_id']."";
$all_session = $dbcon->query($sql);
 
   
if(mysqli_num_rows($all_session) == 1){

    echo '
    <center><input id="btn_submit_atten" class="button" type="submit" name="submit" value="ATTENDED"></input></center>';
}
else{
  echo'<form action="attendanceform.php" method="POST" enctype="multipart/form-data">
    <center><input id="btn_submit_atten" class="button" type="submit" name="submit" value="Submit Attendance"></input></center>';

}
?>
<input type= "hidden" name= "hidden" value="<?php echo $_GET['id'];?>">   
<input type= "hidden" name= "hidden1" value="<?php echo $_GET['sessionHeader'];?>">
</form>
  <?php
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";

// Create connection
$dbcon = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($dbcon->connect_error) {
    die("Connection failed: " . $dbcon->connect_error);
  }
    if(isset($_POST['submit'])){

        $session_number = $_POST['hidden'];
        $session_head = $_POST['hidden1'];
        $attend = mysqli_query($dbcon, "SELECT * FROM tblattendance WHERE session_id = ".$session_number." AND participant_id = ".$_SESSION['participant_id']." LIMIT 1");
        
        

        if(mysqli_num_rows($attend) == 1){
          echo '<script>alert("You already submitted attendance")</script>';
          echo "<script>window.location = 'dashboard.php'</script>";
        }
        else{

        mysqli_query($dbcon, "INSERT INTO `tblattendance`(`session_id`,`session_head`,`participant_id`,`fname`,`mName`,`lName`,`Gender`,`Country`,`Institution`,`Position`) VALUES ('$session_number','$session_head','{$_SESSION['participant_id']}','{$_SESSION['participant_fname']}','{$_SESSION['participant_mName']}','{$_SESSION['participant_lName']}','{$_SESSION['participant_gender']}','{$_SESSION['participant_Country']}','{$_SESSION['participant_Institution']}','{$_SESSION['participant_Position']}')");

         $result = mysqli_query($dbcon, "SELECT SUM(ses.cpd_units) AS 'TOTAL_CPD' FROM tblattendance att
          INNER JOIN participant par ON att.participant_id = '".$_SESSION['participant_id']."'
          INNER JOIN tblptcsession ses ON att.session_id = ses.session_number
          WHERE par.id = '".$_SESSION['participant_id']."'");
         
         
        $row = mysqli_fetch_row($result);
          $totcpd = $row[0];
          
         
        
          mysqli_query($dbcon, "UPDATE participant SET cpd = ".$totcpd." WHERE id = ".$_SESSION['participant_id']);

        }

          
    ?>
        
            <script>
              
             alert("Attendance Submitted");
              window.location = 'dashboard.php';
          


            
            </script>

            <?php
          
            }
            ?>  
      </div>
  </div>
</div>
</div>


        <?php  include("user_head&footer/footer.php" );?>
   
  
  </body>
</html>