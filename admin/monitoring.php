
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
</head>

<body class="">

<head>
  <meta charset="UTF-8">
  

    <link rel="apple-touch-icon"type="image/png" href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png"/>

    <meta name="apple-mobile-web-app-title" content="CodePen">

    <link rel="shortcut icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

    <link rel="mask-icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111" />

    <link rel="stylesheet" type="text/css" href="admindashboard.css">



  
    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-2c7831bb44f98c1391d6a4ffda0e1fd302503391ca806e7fcc7b9b87197aec26.js"></script>


  <title>Participant Monitoring</title>

    <link rel="canonical" href="https://codepen.io/davizp/pen/xVGEwM">
  <!-- Head is on the body -->
  
  
  
<style>
/* No CSS */
</style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
</head>

<body translate="no">
  <!-- 
* Working with dynamic table
* WebSite: http://www.dynatable.com/
-->

<!--  Bootstrap v2.3.2 -->
<link rel="stylesheet" media="all" href="https://s3.amazonaws.com/dynatable-docs-assets/css/bootstrap-2.3.2.min.css" />
<!-- Plugin styles -->
<link rel="stylesheet" media="all" href="css/jquery.dynatable.css" />

<!--  jQuery v3.0.0-beta1 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.js"></script>

<!-- JS Pluging -->
<script type='text/javascript' src='js/jquery.dynatable.js'></script>
    

<div class = "container" style="margin-left: 200px; margin-top: 40px;">
  <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Profession</th>
                <th>Position</th>
                <th>Institution</th>
                <th>Country</th>
<?php
    $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error());
    $sql = "SELECT * FROM tblptcsession";
    $all_session = $dbcon->query($sql);
    while($row = mysqli_fetch_assoc($all_session)){
      $sesid = $row['session_number'];
?>
                <th><?php echo $row['session_head']; ?></th>
<?php
}
?>
                <th>Add Attendance</th>
                <th>Add Feedback</th>
               


            </tr>
        </thead>
 
        
 
        <tbody>
            
<?php 
$sql1 = "SELECT t1.id, t1.firstName, t1.middleName, t1.lastName, t1.Gender, t1.Country, t1.profession, t1.position, t1.organization FROM participant t1 INNER JOIN tblfeedback t2 ON t1.id = t2.participant_id GROUP BY t1.id HAVING COUNT(t1.id) > 0;";
$all_session1 = $dbcon->query($sql1);
while($row1 = mysqli_fetch_assoc($all_session1)){
$countrycode = $row1['Country']; 
$idpart = $row1['id'];  
?>              
                <tr>
                <td><?php echo $row1['firstName'];?> <?php echo $row1['middleName'];?>. <?php echo $row1['lastName'];?></td>
                <td><?php echo $row1['Gender'];?></td>
                <td><?php echo $row1['profession'];?></td>
                <td><?php echo $row1['position'];?></td>
                <td><?php echo $row1['organization'];?></td>
                <?php
                $dbcon1 = mysqli_connect("localhost","root","ptc@ptri@picts","world") or die(mysqli_error());
                $sql = mysqli_query($dbcon1, "SELECT name FROM countries WHERE iso2 = '$countrycode'");
                while($row = mysqli_fetch_array($sql)){
                $Country= $row['name'];
                ?>
                <td><?php echo $Country;?></td>
<?php
}

?>
<?php
     $sql3 = "SELECT * FROM tblptcsession";
     $all_session3 = $dbcon->query($sql3);
     while($row3 = mysqli_fetch_assoc($all_session3)){
         $sesid = $row3['session_number'];
?>
<?php
  $sql2 = "SELECT * FROM tblattendance WHERE session_id = '$sesid' AND participant_id = '$idpart'";
  $all_session2 = $dbcon->query($sql2); 
  if(mysqli_num_rows($all_session2) == 1){
    $check = $all_session2;
    echo'
    <td><img src="https://th.bing.com/th/id/R.7575f3456d9cbda84e8a5de8036b97bf?rik=l5DuCJrvkbBe%2fA&riu=http%3a%2f%2fclipart-library.com%2fimages_k%2fgreen-check-mark-transparent%2fgreen-check-mark-transparent-21.png&ehk=A8Zh2l3lStq%2bS2gkM%2fou4yF0TzDfwOgSS6mE%2f2zG2Ns%3d&risl=&pid=ImgRaw&r=0" style="width:30px; height: 30px;"></td>';

  }
  else{
    echo'
    <td><img src="https://logodix.com/logo/277196.png" style="width:30px; height: 30px;"></td>';
  } 

 
?>

<?php
}
?>
<td><button onclick ="" type="button" id="attendance1"><ion-icon name="eye-outline"></ion-icon>Add Attendance</button></td>
<td><button onclick =""  type="button" id="feedback1"><ion-icon name="eye"></ion-icon>Add Feedback</button></td>
<?php
}
?>

</tr>
           
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
 </div>
  
      <script id="rendered-js" >
$(document).ready(function () {
  $('#example').dynatable();
});

    </script>

  
</body>

</html>

  </div>

</body>

</html>
