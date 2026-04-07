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
   <link rel="stylesheet" type="text/css" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </head>
  <body>
    
  <div class="container atten_con">
    <div class="row">
      
      <div class="col-md-12 atten_title">
        <img src="Picture1.png">
        <h1>Feedback Details</h1>

    </div></div>
    <br>
    <br>
    <!-- <button style="width: 200px; height: 50px; margin-bottom: 20px;" type="button" id="edit_prof" onclick="window.open('updatefeedback.php?id=<?php echo $_GET['id']; ?>')"><ion-icon name="person-circle-outline" ></ion-icon>Update my Feedback</button> -->
<?php 
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM tblfeedback WHERE session_id = ".$_GET['id']." AND participant_id = ".$_SESSION['participant_id']; 
$all_session = $dbcon->query($sql);
 

    $countrow = array();
      while($row = mysqli_fetch_assoc($all_session)){



?>
  <form action="feedbackform.php"  method="POST" enctype="multiplepart/form-data">
    <input type= "hidden" name= "hidden" value="<?php echo $_GET['id'];?>">
  <div class="row radiobtn">
    <h4 class="text-secondary fst-italic">How did you learn about this event?</h4>
      <h5><?php echo $row['information']; ?></h5>
  </div>
  <br>
<!-- --------------------------------------------------------------------------------------------------- -->
  <div class="row radiobtn">
    <h4 class="text-secondary fst-italic">What made you attend this event? </h4>
      <h5><?php echo $row['purpose_attend']; ?></h5>
<!-- --------------------------------------------------------------------------------------------------------------       -->
  </div>

  <br>
  <div class="row">
    <h5>EMAIL :</h5>
    <div class="col-md-12 col-sm-12 inputBox">
        <i class="fa-solid fa-envelope"></i><input type="email" required="required"  id="email" name="email_address" value="<?php echo $row['email'];?>" disabled><span></span>
    </div>
  </div>
  <br>
 

  <div class="row inputBox1row">
    <h5 style="margin-bottom: -10px;">NAME :</h5>
    <div class="col-md-4 col-sm-12 inputBox1">
      <i class="fa-solid fa-user"></i><input type="text" required="required"  id="fname" name="fname" value="<?php echo ucfirst ($_SESSION['participant_fname']); ?> <?php echo ucfirst ($_SESSION['participant_mName']); ?>. <?php echo ucfirst ($_SESSION['participant_lName']); ?>" disabled><span></span>
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
  
    <div class="col-md-7">
      <h5 style="margin-bottom: -10px;">ORGANIZATION :</h5>
      <div class="inputBox2">
      <i class="fa-solid fa-sitemap"></i><input type="text" required="required"  id="organization" name="organization" value="<?php echo ucfirst ($_SESSION['participant_Institution']); ?>" disabled>
    </div>
    </div>
    <div class="col-md-7 ">
      <h5 style="margin-bottom: -10px; margin-top: 30PX;">POSITION :</h5>
      <div class="inputBox2">
      <i class="fa-solid fa-briefcase"></i><input type="text" required="required"  id="position" name="position" value="<?php echo ucfirst ($_SESSION['participant_Position']); ?>" disabled>
    </div>
    </div>
    <hr>
    <div class="py-3 d-flex align-items-center align-middle">
      <h5 class="fst-italic text-secondary">CATEGORY :</h5>
      <h6 class="ps-3 fw-bold"><?php 
      if($row['category'] == null){
        echo "N/A";
      }
      echo $row['category'] ;
    ?></h6>
    </div>
      
       <?php
}
?>
<hr>
<div class="pt-5">

  <?php
  $sql1 = "SELECT * FROM tblconrating WHERE session_id = ".$_GET['id']." AND participant_id = ".$_SESSION['participant_id']; 
  $all_session1 = $dbcon->query($sql1);

  if($row1 = mysqli_fetch_assoc($all_session1)){
  ?>
    <center><h2>CONTENTS</h2></center>
    <br>
    <h5></h5>
  </div>
  <div class="col-md-12" >
    

<table style="border: 2px solid #dddddd; border-collapse: collapse;
  width: 100%;">
  <tr>
    <th style=" text-align: center;"><i>Questions</i></th>
    <th style=" text-align: center;"><i>Rating</i></th>
  </tr>
  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">1. Quality of the congress / convention contents</h6></th>
    <th style="border: 2px solid black;"><div class="wrapper col-md-5" id="rating">
      <span class="fa-stack fa-2x">
    <i class="fa fa-star fa-stack-2x" style="color: orange;"></i>
      <span class="fa fa-stack-1x" style="color:white;">
          <span style="font-size:20px;">
              <?php echo $row1['q1']; ?>
          </span>
    </span>
</span>
    
    </div></th>
  </tr>

  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">2. Relevance of the congress / convention to your job</h6></th>
    <th style="border: 2px solid black;"><div class="wrapper col-md-5" id="rating">
      <span class="fa-stack fa-2x">
    <i class="fa fa-star fa-stack-2x" style="color: orange;"></i>
      <span class="fa fa-stack-1x" style="color:white;">
          <span style="font-size:20px;">
              <?php echo $row1['q2']; ?>
          </span>
    </span>
</span>
    </div></th>
  </tr>
  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">3. Time allotted for the discussion and questions are adequate</h6></th>
    <th style="border: 2px solid black;"><div class="wrapper col-md-5" id="rating">
      <span class="fa-stack fa-2x">
    <i class="fa fa-star fa-stack-2x" style="color: orange;"></i>
      <span class="fa fa-stack-1x" style="color:white;">
          <span style="font-size:20px;">
              <?php echo $row1['q3']; ?>
          </span>
    </span>
</span>
    </div></th>
  </tr>
  <tr>
    <th style="border: 2px solid black;"><h6 style="margin-top: 10px; margin-left: 15px;">4. Program is well organized</h6></th>
    <th style="border: 2px solid black;"><div class="wrapper col-md-5" id="rating">
      <span class="fa-stack fa-2x">
    <i class="fa fa-star fa-stack-2x" style="color: orange;"></i>
      <span class="fa fa-stack-1x" style="color:white;">
          <span style="font-size:20px;">
              <?php echo $row1['q4']; ?>
          </span>
    </span>
</span>
    </div></th>
  </tr>
  
</table>
<br>
<br>
<br>
<?php
}
?>

</div>
<div>
    <center><h2>RESOURCE SPEAKERS</h2></center>
    <br>
    <h5></h5>
  </div>



<?php 
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM tblspeakerses WHERE session_id = ".$_GET['id']." AND year = '2024' AND speaker_Img NOT IN ('placeholder.jpg') AND speaker_Name 

NOT IN(
  'Dr. Julius L. Leaño, Jr.',
  'Dr. Renato Solidum, Jr.',
  'Hon. Win Gatchalian',
  'Acd. Emil Q. Javier, Ph.D.',
  'Dr. Gladys J. Completo',
  'Dr. Eden May B. Dela Peña',
  'Engr. Dennis Pagud',
  'Acd. Fabian M. Dayrit, Ph.D.',
  'Dr. Leah J. Buendia',
  'Hon. Francis “Chiz” Escudero',
  'Dr. Lourdes J. Cruz',
  'Hon. Mark A. Villar'

) ;";
$all_session = $dbcon->query($sql);
 

    $countrow = array();
      while($row = mysqli_fetch_assoc($all_session)){

        $countrow[] = $row;

      }
    
      for($i = 0; $i < count($countrow); $i++){
?>

<div class="col-md-12" >
<form action="viewfeedback.php" method="POST" enctype="multipart/form-data">
 <input type= "hidden" name="hiddenspeakerID<?php echo $i; ?>" value="<?php echo $countrow[$i]['speaker_id']; ?>">
</form>
<table style="border: 2px solid #dddddd; border-collapse: collapse;
  width: 100%; ">
  <tr>
    <th style=" text-align: center;"><p style="font-size: 25px; margin-bottom: -20px; color: #2ba3d9;"><?php echo $countrow[$i]['speaker_Name']; ?></p><br><p style="font-size: 15px; margin-bottom: -10px; color: #3cbcf6; font-family: arial normal"><i><?php echo $countrow[$i]['speaker_Info']; ?></i></p></th>
    <th style=" text-align: center;"></th>
    <th style=" text-align: center;">Rating</th>
  </tr>
  <tr>
    <th rowspan="4" style="width: 40%;">
      <img src="./2024/<?php echo $countrow[$i]['speaker_Img'];?>" style="width: 100%;
  display: block; border: 5px solid #0e6cc5;">
    </th>
    <?php

    $sql2 = "SELECT * FROM tblspeaker_rating WHERE session_id = ".$_GET['id']." AND participant_id = ".$_SESSION['participant_id']." AND speaker_id = ".$countrow[$i]['speaker_id']; 
    $all_session2 = $dbcon->query($sql2);

  if($row2 = mysqli_fetch_assoc($all_session2)){
  ?>
    <th style="border: 2px solid white; width: 35%;"><h6 style="margin-top: 10px; margin-left: 15px; ">1. Knowledgeable of the topics and able to provide insights</h6></th>
    <th style="border: 2px solid white; text-align: center;">
    <div class="wrapper col-md-12" id="aspeakerRate<?php echo $i; ?>">
      <span class="fa-stack fa-3x">
    <i class="fa fa-star fa-stack-2x" style="color: orange;"></i>
      <span class="fa fa-stack-1x" style="color:white;">
          <span style="font-size:30px;">
              <?php echo $row2['q1']; ?>
          </span>
    </span>
</span>
    
   
    </th>
  </tr>
  <tr>
    <th style="border: 2px solid white; "><h6 style="margin-top: 10px; margin-left: 15px;">2. Topic/content consistent with objectives</h6></th>
    <th style="border: 2px solid white;"><div class="wrapper col-md-8" id="bspeakerRate<?php echo $i; ?>">
    <div class="wrapper col-md-5" id="aspeakerRate<?php echo $i; ?>">
    <span class="fa-stack fa-3x">
    <i class="fa fa-star fa-stack-2x" style="color: orange;"></i>
      <span class="fa fa-stack-1x" style="color:white;">
          <span style="font-size:30px;">
              <?php echo $row2['q2']; ?>
          </span>
    </span>
</span>
    
  </th>
  </tr>
  <tr>   
    <th style="border: 2px solid white;"><h6 style="margin-top: 10px; margin-left: 15px;">3. Able to manage time well</h6></th>
    <th style="border: 2px solid white;"><div class="wrapper col-md-8" id="cspeakerRate<?php echo $i; ?>">
    <div class="wrapper col-md-5" id="aspeakerRate<?php echo $i; ?>">
    <span class="fa-stack fa-3x">
    <i class="fa fa-star fa-stack-2x" style="color: orange;"></i>
      <span class="fa fa-stack-1x" style="color:white;">
          <span style="font-size:30px;">
              <?php echo $row2['q3']; ?>
          </span>
    </span>
</span>
    </th>
  </tr>
  <tr>   
    <th style="border: 2px solid white;"><h6 style="margin-top: 10px; margin-left: 15px;">4. Clarified content in response to questions</h6></th>
    <th style="border: 2px solid white;"><div class="wrapper col-md-8" id="dspeakerRate<?php echo $i; ?>">
    <div class="wrapper col-md-5" id="aspeakerRate<?php echo $i; ?>">
    <span class="fa-stack fa-3x">
    <i class="fa fa-star fa-stack-2x" style="color: orange;"></i>
      <span class="fa fa-stack-1x" style="color:white;">
          <span style="font-size:30px;">
              <?php echo $row2['q4']; ?>
          </span>
    </span>
</span>
    </div></th>
  </tr> 
</table>

<?php
}
?>
<br>
</div>

<!-- comments -->
 <div class="row Commentfield">
      <div class="col-md-12">
        <p>What were your learnings/insights after the 
presentation of the speaker? </p>
        <textarea id="textareacom<?php echo $i;?>" name="comment_speaker<?php echo $i;?>" style="background-color:#f8f8f8;width:100%;padding: 12px 20px;resize:none;height:150px;box-sizing: border-box;border: 1px solid rgba(130,130,130);border-radius:4px;" placeholder="N/A" disabled><?php 
       echo ($row2['comment']);
        
        ?>
</textarea>
      </div>
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



<?php 
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM tblfeedback WHERE session_id = ".$_GET['id']." AND participant_id = ".$_SESSION['participant_id']; 
$all_session = $dbcon->query($sql);
 

    $countrow = array();
      if($row = mysqli_fetch_assoc($all_session)){


?>


</table>
<br>
<hr>
<br>

<table style="border: 2px solid #dddddd; border-collapse: collapse;
  width: 100%;">
  <tr>
    <div class="wrapper col-md-12" id="learning1">
      <td colspan = "2" style="border: 2px solid black;">
        <h6 style="font-style: italic; color:gray; margin-left: 15px; margin-top: 10px;">I gained ideas that could be implemented in my area of practice (what?)</h6>
        <p style="margin-left: 15px; display: inline-block; font-weight: 1;  ">
          <?php 
            if($row['checkbox_q1'] == ""){
              echo 'N/A';
            }
            echo $row['checkbox_q1'];
          ?></p>
      </td>
    </div>
  </tr>
  <tr>
    <div class="wrapper col-md-12" id="learning2">
      <td colspan = "2" style="border: 2px solid black;">
          <h6 style="font-style: italic; color:gray; margin-left: 15px; margin-top: 10px;">I learned something new (what?)</h6>
        <p style="margin-left: 15px; display: inline-block; font-weight: 1;  ">
          <?php 
            if($row['checkbox_q2'] == ""){
              echo 'N/A';
            }
            echo $row['checkbox_q2'];
          ?></p>
      </td>
    </div>
  </tr>

  <tr>
    <div class="wrapper col-md-12" id="learning3">
      <td colspan = "2" style="border: 2px solid black;">
        <h6 style="font-style: italic; color:gray; margin-left: 15px; margin-top: 10px;">I can see possibilities for Collaboration</h6>
        <p style="margin-left: 15px; display: inline-block; font-weight: 1;  ">
          <?php 
            if($row['checkbox_q3'] == ""){
              echo 'N/A';
            }
            echo $row['checkbox_q3'];
          ?></p>
        </td>
    </div>
  </tr>
    <div class="wrapper col-md-12" id="learning4">
     <td colspan = "2" style="border: 2px solid black;">
        <h6 style="font-style: italic; color:gray; margin-left: 15px; margin-top: 10px;">Others</h6>
        <p style="margin-left: 15px; display: inline-block; font-weight: 1;  ">
          <?php 
            if($row['checkbox_q4'] == ""){
              echo 'N/A';
            }
            echo $row['checkbox_q4'];
          ?></p>
      </td>
    </div>
  </tr>
  
</table>

</div>

    <div class="container pb-5">
          <div class="row Commentfield">
            <div class="col-md-12">
              <p>Your Comment :</p>
              <textarea id="textareacom" name="comment" style="background-color:#f8f8f8;width:100%;padding: 12px 20px;resize:none;height:150px;box-sizing: border-box;border: 1px solid rgba(130,130,130);border-radius:4px;" placeholder="Your feedback" disabled><?php echo $row['comment']; ?>
      </textarea>
            </div>
          </div>
    </div>
    
 <?php
 }
 ?>   
  </div>
  </div>
  <br>
  <div class="row pb-5 mb-5">

    <div class="col-md-12 doteddot">
      <center>
      <input class="dot1" type="" name="">
      <input class="dot2" type="" name="">
      <input class="dot3" type="" name="">
    </center>
    </div>
  </div>
</form>

</div>
</div>



   

        <?php include("user_head&footer/footer.php" );?>
    <?php  include("user_head&footer/user_nav.php" );?>
  
  </body>
</html>