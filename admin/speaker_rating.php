<?php
  session_start();

  // var_dump("test");
  // die();
  if(!empty($_SESSION['admin_id'])){
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
    <title>Speaker Rating</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <link rel="stylesheet" type="text/css" href="user_head&footer/footer.css">
  <link rel="stylesheet" type="text/css" href="rating.css">
   <link rel="stylesheet" type="text/css" href="user_head&footer/user_nav.css">
   <link rel="stylesheet" type="text/css" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    
  <div class="container atten_con">
    <div class="row">
      
      <div class="col-md-12 atten_title">
       
        <h1>RESOURCE SPEAKER RATING</h1>
    </div></div>
    <br>
    <br>
<?php $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error()); ?>
<?php
$sql = "SELECT * FROM tblptcsession";
$all_session01 = $dbcon->query($sql);

while($row = mysqli_fetch_assoc($all_session01)){
   $sesid = $row['session_number'];
?>

<table style="border: 2px solid black; border-collapse: collapse;
  width: 100%;">
  <tr  style="border: 2px solid black;">
    <th style="border: 2px solid black; text-align: center;" colspan="5"><p style="font-size: 25px; margin-bottom: -20px; color: #2ba3d9;"><?php echo $row['session_head']; ?></p><br><p style="font-size: 15px; margin-bottom: -10px; color: #3cbcf6; font-family: arial normal"><i></i></p></th>
  
  </tr>
   <tr style="border: 2px solid black;">
    <td style=" border: 2px solid black;text-align: center;"colspan="5"><?php echo $row['session_title']; ?></td>
  </tr>
  <tr>
    <td style=" border: 2px solid black;text-align: center;">NAME</td>
    <td style=" border: 2px solid black;text-align: center;">1. Knowledgeable of the topics and able to provide insights</td>
    <td style=" border: 2px solid black;text-align: center;">2. Topic/content consistent with objectives</td>
    <td style=" border: 2px solid black;text-align: center;">3. Able to manage time well</td>
    <td style=" border: 2px solid black;text-align: center;">4. Clarified content in response to questions</td>
    <td style=" border: 2px solid black;text-align: center;">TOTAL</td>
  </tr>
  <tr>
<?php
$sql00 = "SELECT COUNT(*) as 'totfeed' FROM tblfeedback WHERE session_id = ".$sesid;
$all_session00 = $dbcon->query($sql00);

while($row = mysqli_fetch_assoc($all_session00)){
   $totalfeed = $row['totfeed'];
?>
<?php
$sql0 = "SELECT * FROM tblspeakerses WHERE session_id = ".$sesid;
$all_session0 = $dbcon->query($sql0);

while($row = mysqli_fetch_assoc($all_session0)){
   $speakid = $row['speaker_id'] ;

?>
<tr>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $row['speaker_Name']; ?></td>

<?php
$sql02 = "SELECT SUM(q1) AS 'sum' FROM `tblspeaker_rating` WHERE session_id = ".$sesid." AND speaker_id = ".$speakid;
$all_session02 = $dbcon->query($sql02);
while($row2 = mysqli_fetch_assoc($all_session02)){
$sum = $row2['sum'];

if($totalfeed == 0){
  $total1 = 0;
}
else{
  $total1 = $sum / $totalfeed;
}
?>
<td style=" border: 2px solid black;text-align: center;"><?php echo round($total1,2); ?></td>
<?php
}

?>
<?php
$sql02 = "SELECT SUM(q2) AS 'sum' FROM `tblspeaker_rating` WHERE session_id = ".$sesid." AND speaker_id = ".$speakid;
$all_session02 = $dbcon->query($sql02);
while($row2 = mysqli_fetch_assoc($all_session02)){
$sum = $row2['sum'];
if($totalfeed == 0){
  $total2 = 0;
}
else{
  $total2 = $sum / $totalfeed;
}
?>
<td style=" border: 2px solid black;text-align: center;"><?php echo round($total2,2); ?></td>
<?php
}
?>
<?php
$sql02 = "SELECT SUM(q3) AS 'sum' FROM `tblspeaker_rating` WHERE session_id = ".$sesid." AND speaker_id = ".$speakid;
$all_session02 = $dbcon->query($sql02);
while($row2 = mysqli_fetch_assoc($all_session02)){
$sum = $row2['sum'];
if($totalfeed == 0){
  $total3 = 0;
}
else{
  $total3 = $sum / $totalfeed;
}
?>
<td style=" border: 2px solid black;text-align: center;"><?php echo round($total3,2); ?></td>
<?php
}
?>
<?php
$sql02 = "SELECT SUM(q4) AS 'sum' FROM `tblspeaker_rating` WHERE session_id = ".$sesid." AND speaker_id = ".$speakid;
$all_session02 = $dbcon->query($sql02);
while($row2 = mysqli_fetch_assoc($all_session02)){
$sum = $row2['sum'];
if($totalfeed == 0){
  $total4 = 0;
}
else{
  $total4 = $sum / $totalfeed;
}
?>
<td style="border: 2px solid black;text-align: center;"><?php echo round($total4,2); ?></td>

<?php
}
?>



<!-- -------------------------------------------------------------------- -->
<?php
 $totalall = ($total1 + $total2 + $total3 + $total4) / 4;
 ?>
<td style="border: 2px solid black;text-align: center;"><?php echo round($totalall,2); ?></td>

<!-- ------------------------------------------------------------- -->



<?php
}
}
}
?>
</tr>

</table>

    <br>
    <br>
     </div>
    </div>
  </div>
















