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
    <link rel="stylesheet" type="text/css" href="feedbackform.css">
    <title>Feedback Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <link rel="stylesheet" type="text/css" href="user_head&footer/footer.css">
  <link rel="stylesheet" type="text/css" href="rating.css">
   <link rel="stylesheet" type="text/css" href="user_head&footer/user_nav.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    
  <div class="container atten_con">
    <div class="row">
      
      <div class="col-md-12 atten_title">
        <img src="Picture1.png">
        <h1>PTC Feedback Form</h1>
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

$sql = "SELECT * FROM tblspeakerses WHERE session_id = '".$_GET['id']."'";
$all_session = $dbcon->query($sql);


$countrow[] = array();
while($row = mysqli_fetch_assoc($all_session)){
  $countrow[] = $row;
} 
      for($i = 1; $i < count($countrow); $i++){

?>
       <img src="<?php echo $countrow[$i]['speaker_Img'];?>">
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
    <p> <span>Data Privacy Notice:</span> In line with the Data Privacy Act of 2012, DOST-PTRI is committed to protect and secure your personal information obtained in the evaluation of the conduct of <span>2023 Philippine Textile Congress</span>. The personal information you provided herein will be processed and utilized solely for this event. Collected personal information will be kept/stored and accessed only by authorized DOST-PTRI personnel and shall not be shared with any outside parties unless written consent is secured. Information shall be stored within one (1) year after which physical records shall be disposed of or destroyed following the disposition process provided by the National Archives of the Philippines.</p>
    <p> Note: We would like to hear your feedback on the Special Session. Your inputs will help us evaluate this event.  Deadline for the submission of the Evaluation Form is only until 8:00PM.</p>
  </div>
  </div>
  <br>
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
        $parid           = $_POST['parid'];
        $radio          = $_POST['info'];
        $radioatt       = $_POST['infoatt'];
        // $radioage          = $_POST['infoage'];
        $email          = $_POST['email_address'];
        $firstname      = $_POST['fname'];
        $middlename     = $_POST['mname'];
        $lastname       = $_POST['lname'];
        $org            = $_POST['organization'];
        $pos            = $_POST['position'];
        $comm           = $_POST['comment'];


        $cq1            = $_POST['ratingRadio'];
        $cq2            = $_POST['ratingRadio1'];
        $cq3            = $_POST['ratingRadio2'];
        $cq4            = $_POST['ratingRadio3'];

        $cbq1            = $_POST['incheck'];
        $cbq2            = $_POST['incheck1'];
        $cbq3            = $_POST['incheck2'];
        $cbq4            = $_POST['incheck3'];

        


        $sql = "SELECT COUNT(*) AS 'total_count' FROM tblspeakerses WHERE session_id = ".$_POST['hidden'];
        $all_session = $dbcon->query($sql);
      
        while($row = mysqli_fetch_assoc($all_session)){
        
        $numbeOfItems = $row['total_count'];
         
        
        for ($i=0; $i <= $numbeOfItems-1; $i++) {
  
            $speakerID      = $_POST['hiddenspeakerID' . $i];
            $sq1            = $_POST['aratingRadio' . $i];
            $sq2            = $_POST['bratingRadio' . $i];
            $sq3            = $_POST['cratingRadio' . $i];
            $sq4            = $_POST['dratingRadio' . $i];

            

          
        

        

        mysqli_query($dbcon, "INSERT INTO `tblspeaker_rating`(`session_id`, `participant_id`, `speaker_id`, `q1`, `q2`, `q3`, `q4`) VALUES ('$session_number', '$parid', '$speakerID', '$sq1', '$sq2', '$sq3', '$sq4')");

        
      }
}
      mysqli_query($dbcon, "INSERT INTO `tblfeedback`(`participant_id`, `information`, `purpose_attend`, `email`, `Fname`, `Mname`, `Lname`, `organization`,`position`,`comment`,`session_id`,`checkbox_q1`, `checkbox_q2`, `checkbox_q3`, `checkbox_q4`) VALUES ('$parid', '$radio', '$radioatt',  '$email', '$firstname', '$middlename', '$lastname', '$org', '$pos', '$comm', '$session_number', '$cbq1', '$cbq2', '$cbq3', '$cbq4')");

      mysqli_query($dbcon, "INSERT INTO `tblconrating`(`session_id`, `participant_id`, `q1`, `q2`, `q3`, `q4`) VALUES ('$session_number', '$parid', '$cq1', '$cq2', '$cq3', '$cq4')");
    ?>

            <script>
             alert("Your feedback has been sent successfully!");
   

            </script>

            <?php
            
          }
            ?> 

  <form action="manualfeedback.php"  method="POST" enctype="multiplepart/form-data">
    <input type= "hidden" name= "hidden" value="<?php echo $_GET['id'];?>">
  <div class="row radiobtn">
    <h4>How did you learn about this event?</h4>
      <div class="col-md-3 radiobtnbtn">
         <input type="radio" id="html" name="info" value="By invitations" required>
      <label>By invitations</label>
      </div>
      <div class="col-md-3 radiobtnbtn">
         <input type="radio" id="html" name="info" value="Social media" required checked>
      <label>Social media</label>
      </div>
      <div class="col-md-4 radiobtnbtn">
         <input type="radio" id="html" name="info" value="Colleagues/Friends/Acquaintances" required>
      <label>Colleagues/Friends/Acquaintances</label>
      </div>
      <div class="col-md-2 radiobtnbtn">
         <input type="radio" id="html" name="info" value="Others" required>
      <label>Others</label>
      </div>
  </div>
  <br>
<!-- --------------------------------------------------------------------------------------------------- -->
  <div class="row radiobtn">
    <h4>What made you attend this event? </h4>
      <div class="col-md-2 radiobtnbtn">
         <input type="radio" id="html" name="infoatt" value="Interest" required checked>
      <label>Interest</label>
      </div>
      <div class="col-md-3 radiobtnbtn" >
         <input type="radio" id="html" name="infoatt" value="Required by school/office" required>
      <label>Required by school/office</label>
      </div>
      <div class="col-md-3 radiobtnbtn" >
         <input type="radio" id="html" name="infoatt" value="Eagerness to learn" required>
      <label>Eagerness to learn</label>
      </div>
      <div class="col-md-4 radiobtnbtn" >
         <input type="radio" id="html" name="infoatt" value="Requirements for renewal of license" required>
      <label>Requirements for renewal of license</label>
      </div>

      <div class="col-md-2 radiobtnbtn" >
         <input type="radio" id="html" name="infoatt" value="Other" required>
      <label>Other</label>
      </div>
<!-- --------------------------------------------------------------------------------------------------------------       -->
  </div>
  <?php
  $sql = "SELECT * FROM participant WHERE id = 217";
        $all_session = $dbcon->query($sql);
      
        while($row = mysqli_fetch_assoc($all_session)){

  ?>
  <br>
  <div class="row">
    <div class="col-md-12 col-sm-12 inputBox">
      <i class="fa-solid fa-envelope"></i><input type="text" required="required"  id="parid" name="parid" value = "<?php echo $row['id']; ?>" ><span></span>
        <i class="fa-solid fa-envelope"></i><input type="email" required="required"  id="email" name="email_address" value = "<?php echo $row['username']; ?>" ><span>Email Address</span>
    </div>
  </div>
  <br>
  
  <div class="row inputBox1row">
    <div class="col-md-4 col-sm-12 inputBox1">
      <i class="fa-solid fa-user"></i><input type="text" required="required"  id="fname" name="fname" value="<?php echo $row['firstName']; ?>"><span>First Name</span>
    </div>
    <div class="col-md-4 col-sm-12 inputBox1">
      <i class="fa-solid fa-user"></i><input type="text" id="mname" name="mname" value="<?php echo $row['middleName']; ?>"><span>Middle Initial</span>
    </div>
    <div class="col-md-4 col-sm-12 inputBox1">
      <i class="fa-solid fa-user"></i><input type="text" required="required"  id="lname" name="lname" value="<?php echo $row['lastName']; ?>"><span>Last Name</span>
    </div>
  </div>
  <br>
  <!-- -------------------------------------------------------------------------------------------------------- -->
  
  <!-- <div class="row radiobtn">
    <h4>Age: </h4>
      <div class="col-md-3 radiobtnbtn">
         <input type="radio" id="html" name="infoage" value="61 and above" required>
      <label>61 and above</label>
      </div>
      <div class="col-md-3 radiobtnbtn" style="margin-left: -120px;">
         <input type="radio" id="html" name="infoage" value="51-60" required>
      <label>51-60 years old</label>
      </div>
      <div class="col-md-3 radiobtnbtn" style="margin-left: -90px;">
         <input type="radio" id="html" name="infoage" value="41-50" required>
      <label>41-50 years old</label>
      </div>
      <div class="col-md-4 radiobtnbtn" style="margin-left: -90px;">
         <input type="radio" id="html" name="infoage" value="31-40" required>
      <label>31-40 years old</label>
      </div>
      <div class="col-md-2 radiobtnbtn" style="margin-left: -180px;">
         <input type="radio" id="html" name="infoage" value="21-30" required>
      <label>21-30 years old</label>
      </div>
      <div class="col-md-2 radiobtnbtn" style="margin-left: 0px;">
         <input type="radio" id="html" name="infoage" value="Less than 21" required>
      <label>Less than 21</label>
      </div> -->


<!-- --------------------------------------------------------------------------------------------------------------       -->
 <!--  </div> -->
  <div class="row">
    <div class="col-md-7">
      <div class="inputBox2">
      <i class="fa-solid fa-sitemap"></i><input type="text" required="required"  id="organization" name="organization" value="<?php echo $row['organization']; ?>"><span>Organization</span>
    </div>
    </div>
    <div class="col-md-5 ">
      <div class="inputBox2">
      <i class="fa-solid fa-briefcase"></i><input type="text" required="required"  id="position" name="position" value="<?php echo $row['position']; ?>"><span>Position</span>
    </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <?php
  }
  ?>
<div>
    <center><h2>CONTENTS</h2></center>
    <br>
    <h5>On a scale of 1 to 5, 5 being the highest, how do you find each element.</h5>
  </div>
  <div class="col-md-12" >
    

<table style="border: 2px solid #dddddd; border-collapse: collapse;
  width: 100%;">
  <tr>
    <th style=" text-align: center;"><i>Please rate from 1 star - 5 star</i></th>
    <th style=" text-align: center;"><i>Rating</i></th>
  </tr>
  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">1. Quality of the congress / convention contents</h6></th>
    <th style="border: 2px solid black;"><div class="wrapper col-md-8" id="rating">
    <input name="ratingRadio" type="radio" id="1st1" value="5" required/>
    <label for="1st1"></label>
    <input name="ratingRadio" type="radio" id="1st2" value="4" required checked/>
    <label for="1st2"></label>
    <input name="ratingRadio" type="radio" id="1st3" value="3" required/>
    <label for="1st3"></label>
    <input name="ratingRadio" type="radio" id="1st4" value="2" required/>
    <label for="1st4"></label>
    <input name="ratingRadio" type="radio" id="1st5" value="1" required/>
    <label for="1st5"></label>
    </div></th>
  </tr>

  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">2. Relevance of the congress / convention to your job</h6></th>
    <th style="border: 2px solid black;"><div class="wrapper col-md-8" id="rating1">
    <input name="ratingRadio1" type="radio" id="2st1" value="5" required/>
    <label for="2st1"></label>
    <input name="ratingRadio1" type="radio" id="2st2" value="4" required checked/>
    <label for="2st2"></label>
    <input name="ratingRadio1" type="radio" id="2st3" value="3" required/>
    <label for="2st3"></label>
    <input name="ratingRadio1" type="radio" id="2st4" value="2" required/>
    <label for="2st4"></label>
    <input name="ratingRadio1" type="radio" id="2st5" value="1" required/>
    <label for="2st5"></label>
    </div></th>
  </tr>
  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">3. Time allotted for the discussion and questions are adequate</h6></th>
    <th style="border: 2px solid black;"><div class="wrapper col-md-8" id="rating2">
    <input name="ratingRadio2" type="radio" id="3st1" value="5" required/>
    <label for="3st1"></label>
    <input name="ratingRadio2" type="radio" id="3st2" value="4" required checked/>
    <label for="3st2"></label>
    <input name="ratingRadio2" type="radio" id="3st3" value="3" required/>
    <label for="3st3"></label>
    <input name="ratingRadio2" type="radio" id="3st4" value="2" required/>
    <label for="3st4"></label>
    <input name="ratingRadio2" type="radio" id="3st5" value="1" required/>
    <label for="3st5"></label>
    </div></th>
  </tr>
  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">4. Program is well organized</h6></th>
    <th style="border: 2px solid black;"><div class="wrapper col-md-8" id="rating3">
    <input name="ratingRadio3" type="radio" id="4st1" value="5" required/>
    <label for="4st1"></label>
    <input name="ratingRadio3" type="radio" id="4st2" value="4" required checked/>
    <label for="4st2"></label>
    <input name="ratingRadio3" type="radio" id="4st3" value="3" required/>
    <label for="4st3"></label>
    <input name="ratingRadio3" type="radio" id="4st4" value="2" required/>
    <label for="4st4"></label>
    <input name="ratingRadio3" type="radio" id="4st5" value="1" required/>
    <label for="4st5"></label>
    </div></th>
  </tr>
  
</table>
<br>
<br>
<br>


</div>
<div>
    <center><h2>RESOURCE SPEAKERS</h2></center>
    <br>
    <h5>On a scale of 1 to 5, 5 being the highest, how do you find each element.</h5>
  </div>



<?php 
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM tblspeakerses WHERE session_id = ".$_GET['id'];
$all_session = $dbcon->query($sql);
 

    $countrow = array();
      while($row = mysqli_fetch_assoc($all_session)){

        $countrow[] = $row;

      }
    
      for($i = 0; $i < count($countrow); $i++){
?>

<div class="col-md-12" >
 <input type= "hidden" name= "hiddenspeakerID<?php echo $i; ?>" value="<?php echo $countrow[$i]['speaker_id']; ?>">
<table style="border: 2px solid #dddddd; border-collapse: collapse;
  width: 100%; ">
  <tr>
    <th style=" text-align: center;"><p style="font-size: 25px; margin-bottom: -20px; color: #2ba3d9;"><?php echo $countrow[$i]['speaker_Name']; ?></p><br><p style="font-size: 15px; margin-bottom: -10px; color: #3cbcf6; font-family: arial normal"><i><?php echo $countrow[$i]['speaker_Info']; ?></i></p></th>
    <th style=" text-align: center;">Please rate from 1 star - 5 star</th>
    <th style=" text-align: center;">Rating</th>
  </tr>
  <tr>
    <th rowspan="4" style="width: 40%;">
      <img src="<?php echo $countrow[$i]['speaker_Img'];?>" style="width: 100%;
  display: block; border: 5px solid #0e6cc5;">
    </th>
    <th style="border: 2px solid white; width: 35%;"><h6 style="margin-top: 10px; margin-left: 15px; ">1. Knowledgeable of the topics and able to provide insights</h6></th>
    <th style="border: 2px solid white;">
    <div class="wrapper col-md-8" id="aspeakerRate<?php echo $i; ?>">
      <input name="aratingRadio<?php echo $i; ?>" type="radio" id="asr<?php echo $i; ?>" value="5" required/>
      <label for="asr<?php echo $i; ?>"></label>

      <input name="aratingRadio<?php echo $i; ?>" type="radio" id="bsr<?php echo $i; ?>" value="4" required checked/>
      <label for="bsr<?php echo $i; ?>"></label>

      <input name="aratingRadio<?php echo $i; ?>" type="radio" id="csr<?php echo $i; ?>" value="3" required/>
      <label for="csr<?php echo $i; ?>"></label>

      <input name="aratingRadio<?php echo $i; ?>" type="radio" id="dsr<?php echo $i; ?>" value="2" required/>
      <label for="dsr<?php echo $i; ?>"></label>

      <input name="aratingRadio<?php echo $i; ?>" type="radio" id="esr<?php echo $i; ?>" value="1" required/>
      <label for="esr<?php echo $i; ?>"></label>
    </div>
    </th>
  </tr>
  <tr>
    <th style="border: 2px solid white;"><h6 style="margin-top: 10px; margin-left: 15px;">2. Topic/content consistent with objectives</h6></th>
    <th style="border: 2px solid white;"><div class="wrapper col-md-8" id="bspeakerRate<?php echo $i; ?>">
    <input name="bratingRadio<?php echo $i; ?>" type="radio" id="fsr<?php echo $i; ?>" value="5" required/>
    <label for="fsr<?php echo $i; ?>"></label>

    <input name="bratingRadio<?php echo $i; ?>" type="radio" id="gsr<?php echo $i; ?>" value="4" required checked/>
    <label for="gsr<?php echo $i; ?>"></label>

    <input name="bratingRadio<?php echo $i; ?>" type="radio" id="hsr<?php echo $i; ?>" value="3" required/>
    <label for="hsr<?php echo $i; ?>"></label>

    <input name="bratingRadio<?php echo $i; ?>" type="radio" id="isr<?php echo $i; ?>" value="2" required/>
    <label for="isr<?php echo $i; ?>"></label>

    <input name="bratingRadio<?php echo $i; ?>" type="radio" id="jsr<?php echo $i; ?>" value="1" required/>
    <label for="jsr<?php echo $i; ?>"></label>
    </div></th>
  </tr>
  <tr>   
    <th style="border: 2px solid white;"><h6 style="margin-top: 10px; margin-left: 15px;">3. Able to manage time well</h6></th>
    <th style="border: 2px solid white;"><div class="wrapper col-md-8" id="cspeakerRate<?php echo $i; ?>">
    <input name="cratingRadio<?php echo $i; ?>" type="radio" id="ksr<?php echo $i; ?>" value="5" required/>
    <label for="ksr<?php echo $i; ?>"></label>

    <input name="cratingRadio<?php echo $i; ?>" type="radio" id="lsr<?php echo $i; ?>" value="4" required checked/>
    <label for="lsr<?php echo $i; ?>"></label>

    <input name="cratingRadio<?php echo $i; ?>" type="radio" id="msr<?php echo $i; ?>" value="3" required/>
    <label for="msr<?php echo $i; ?>"></label>

    <input name="cratingRadio<?php echo $i; ?>" type="radio" id="nsr<?php echo $i; ?>" value="2" required/>
    <label for="nsr<?php echo $i; ?>"></label>

    <input name="cratingRadio<?php echo $i; ?>" type="radio" id="osr<?php echo $i; ?>" value="1" required/>
    <label for="osr<?php echo $i; ?>"></label>
    </div></th>
  </tr>
  <tr>   
    <th style="border: 2px solid white;"><h6 style="margin-top: 10px; margin-left: 15px;">4. Clarified content in response to questions</h6></th>
    <th style="border: 2px solid white;"><div class="wrapper col-md-8" id="dspeakerRate<?php echo $i; ?>">
    <input name="dratingRadio<?php echo $i; ?>" type="radio" id="psr<?php echo $i; ?>" value="5" required/>
    <label for="psr<?php echo $i; ?>"></label>

    <input name="dratingRadio<?php echo $i; ?>" type="radio" id="qsr<?php echo $i; ?>" value="4" required checked/>
    <label for="qsr<?php echo $i; ?>"></label>

    <input name="dratingRadio<?php echo $i; ?>" type="radio" id="rsr<?php echo $i; ?>" value="3" required/>
    <label for="rsr<?php echo $i; ?>"></label>

    <input name="dratingRadio<?php echo $i; ?>" type="radio" id="ssr<?php echo $i; ?>" value="2" required/>
    <label for="ssr<?php echo $i; ?>"></label>

    <input name="dratingRadio<?php echo $i; ?>" type="radio" id="tsr<?php echo $i; ?>" value="1" required/>
    <label for="tsr<?php echo $i; ?>"></label>
    </div></th>
  </tr> 
</table>
<br>
</div>
<style>
#aspeakerRate<?php echo $i; ?> {
  position: relative;
  display: inline-block;
  border: none;
  font-size: 14px;
  left: 50%;
  transform: translateX(-50%);
}

#aspeakerRate<?php echo $i; ?> input {
  border: 0;
  width: 1px;
  height: 1px;
  overflow: hidden;
  position: absolute !important;
  clip: rect(1px 1px 1px 1px);
  clip: rect(1px, 1px, 1px, 1px);
  opacity: 0;
}

#aspeakerRate<?php echo $i; ?> label {
  position: relative;
  float: right;
  color: #C8C8C8;
}

#aspeakerRate<?php echo $i; ?> label:before {
  margin: 5px;
  content: "\f005";
  font-family: FontAwesome;
  display: inline-block;
  font-size: 1.5em;
  color: #ccc;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
      user-select: none;
}

#aspeakerRate<?php echo $i; ?> input:checked ~ label:before {
  color: #FFC107;
}

#aspeakerRate<?php echo $i; ?> label:hover ~ label:before {
  color: #ffdb70;
}

#aspeakerRate<?php echo $i; ?> label:hover:before {
  color: #FFC107;
}
/*---------------------------------------*/

#bspeakerRate<?php echo $i; ?> {
  position: relative;
  display: inline-block;
  border: none;
  font-size: 14px;
  left: 50%;
  transform: translateX(-50%);
}

#bspeakerRate<?php echo $i; ?> input {
  border: 0;
  width: 1px;
  height: 1px;
  overflow: hidden;
  position: absolute !important;
  clip: rect(1px 1px 1px 1px);
  clip: rect(1px, 1px, 1px, 1px);
  opacity: 0;
}

#bspeakerRate<?php echo $i; ?> label {
  position: relative;
  float: right;
  color: #C8C8C8;
}

#bspeakerRate<?php echo $i; ?> label:before {
  margin: 5px;
  content: "\f005";
  font-family: FontAwesome;
  display: inline-block;
  font-size: 1.5em;
  color: #ccc;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
      user-select: none;
}

#bspeakerRate<?php echo $i; ?> input:checked ~ label:before {
  color: #FFC107;
}

#bspeakerRate<?php echo $i; ?> label:hover ~ label:before {
  color: #ffdb70;
}

#bspeakerRate<?php echo $i; ?> label:hover:before {
  color: #FFC107;
}

/*--------------------------------------------*/

#cspeakerRate<?php echo $i; ?> {
  position: relative;
  display: inline-block;
  border: none;
  font-size: 14px;
  left: 50%;
  transform: translateX(-50%);
}

#cspeakerRate<?php echo $i; ?> input {
  border: 0;
  width: 1px;
  height: 1px;
  overflow: hidden;
  position: absolute !important;
  clip: rect(1px 1px 1px 1px);
  clip: rect(1px, 1px, 1px, 1px);
  opacity: 0;
}

#cspeakerRate<?php echo $i; ?> label {
  position: relative;
  float: right;
  color: #C8C8C8;
}

#cspeakerRate<?php echo $i; ?> label:before {
  margin: 5px;
  content: "\f005";
  font-family: FontAwesome;
  display: inline-block;
  font-size: 1.5em;
  color: #ccc;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
      user-select: none;
}

#cspeakerRate<?php echo $i; ?> input:checked ~ label:before {
  color: #FFC107;
}

#cspeakerRate<?php echo $i; ?> label:hover ~ label:before {
  color: #ffdb70;
}

#cspeakerRate<?php echo $i; ?> label:hover:before {
  color: #FFC107;
}
/*---------------------------------------------*/

#dspeakerRate<?php echo $i; ?> {
  position: relative;
  display: inline-block;
  border: none;
  font-size: 14px;
  left: 50%;
  transform: translateX(-50%);
}

#dspeakerRate<?php echo $i; ?> input {
  border: 0;
  width: 1px;
  height: 1px;
  overflow: hidden;
  position: absolute !important;
  clip: rect(1px 1px 1px 1px);
  clip: rect(1px, 1px, 1px, 1px);
  opacity: 0;
}

#dspeakerRate<?php echo $i; ?> label {
  position: relative;
  float: right;
  color: #C8C8C8;
}

#dspeakerRate<?php echo $i; ?> label:before {
  margin: 5px;
  content: "\f005";
  font-family: FontAwesome;
  display: inline-block;
  font-size: 1.5em;
  color: #ccc;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
      user-select: none;
}

#dspeakerRate<?php echo $i; ?> input:checked ~ label:before {
  color: #FFC107;
}

#dspeakerRate<?php echo $i; ?> label:hover ~ label:before {
  color: #ffdb70;
}

#dspeakerRate<?php echo $i; ?> label:hover:before {
  color: #FFC107;
}

/*------------------------------------------*/




</style>
<?php 
}
?>



<div>
    <center><h2>LEARNINGS AND INSIGHTS</h2></center>
    <br>
    <h5><i>By attending this congress / convention :</i></h5>
  </div>
  <div class="col-md-12" >
    

<table style="border: 2px solid #dddddd; border-collapse: collapse;
  width: 100%;">
  <tr>
    <th style=" text-align: center;"><i>Please check all that apply</i></th>
    <th style=" text-align: center;"><i></i></th>
  </tr>
  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 20px;">1. I gained ideas that could be implemented in my area of practice</h6></th>
    <th style="border: 2px solid black; text-align: center;"><div class="wrapper col-md-12" id="learning">
    <input style="transform: scale(2);" name="incheck" type="checkbox" id="lr" value="I gained ideas that could be implemented in my area of practice" />
    <label for="lr"></label>
    
    </div></th>
  </tr>

  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">2. I learned something new</h6></th>
    <th style="border: 2px solid black; text-align: center;"><div class="wrapper col-md-12" id="learning">
    <input style="transform: scale(2);" name="incheck1" type="checkbox" id="lr1" value="I learned something new" />
    <label for="lr1"></label>
    
    </div></th>
  </tr>
  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">3. I can see possibilities for collaboration</h6></th>
    <th style="border: 2px solid black; text-align: center;"><div class="wrapper col-md-12" id="learning">
    <input style="transform: scale(2);" name="incheck2" type="checkbox" id="lr2" value="I can see possibilities for collaboration" />
    <label for="lr2"></label>
   
    </div></th>
  </tr>
  <tr>
    <div class="wrapper col-md-12" id="learning">
    <th colspan = "2" style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px; display: inline-block;">4. Other</h6>
    <input  name="incheck3" type="input" id="lr3" style="width: 100%; max-width: 900px; outline: none; margin-top: 10px; box-sizing: border-box;
  height: 22px; float: right;" />
    </div></th>
  </tr>
  
</table>
<br>
<br>
<br>


</div>


    <div class="row Commentfield">
      <div class="col-md-12">
        <p>Is there anything else you think we should know?</p>
        <textarea id="textareacom" name="comment" style="background-color:#f8f8f8;width:100%;padding: 12px 20px;resize:none;height:150px;box-sizing: border-box;border: 1px solid rgba(130,130,130);border-radius:4px;" placeholder="Your feedback" >
</textarea>
      </div>
    </div>
    <center><input id="btn_submit_atten" class="button" type="submit" name="submit" value="Submit Feedback"></input></center>
  </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-12 doteddot">
      <center><input class="dot1" type="" name="">
      <input class="dot2" type="" name="">
      <input class="dot3" type="" name=""></center>
    </div>
  </div>
</form>

</div>
</div>



   

        <?php  include("user_head&footer/footer.php" );?>
    <?php  include("user_head&footer/user_nav.php" );?>
  
  </body>
</html>