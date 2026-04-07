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
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/Chart.min.js"></script>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="feedbackform.css">
    <title>Summary Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <link rel="stylesheet" type="text/css" href="user_head&footer/footer.css">
  <link rel="stylesheet" type="text/css" href="rating.css">
   <link rel="stylesheet" type="text/css" href="user_head&footer/user_nav.css">
   <!-- <link rel="stylesheet" type="text/css" href="dashboard.css"> -->
    <link rel="stylesheet" type="text/css" href="./admindashboard.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/usernav.css">
    <link rel="stylesheet" type="text/css" href="nav&footer/footer.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <style type="text/css">     
                
                #chart-container {
                    
                   
                }
        </style>
  </head>
  <body>
    
  <div class="container atten_con">
    <div class="row">
      
      <div class="col-md-12 atten_title">
       
        <h1>Attendance and feedback monitoring sheet</h1>
    </div></div>
    <br>
    <br>
<?php $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error()); ?>
<?php
$sql = "SELECT * FROM tblattendance WHERE session_id = ".$_GET['id']."";
$all_session = $dbcon->query($sql);

if($row = mysqli_fetch_assoc($all_session)){
        
?>

<input type= "hidden" name= "hidden" value="<?php echo $_GET['id'];?>">
<table style="border: 2px solid black; border-collapse: collapse;
  width: 100%;">
  <tr>
      <th style="border: 2px solid black; text-align: center;" colspan="5"><p style="font-size: 25px; margin-bottom: -20px; color: #2ba3d9;">SESSION: </p><br><p style="font-size: 15px; margin-bottom: -10px; color: #3cbcf6; font-family: arial normal"><i></i></p></th>
      <th style=" border: 2px solid black;text-align: center;" colspan="12"><?php echo $row['session_head']; ?></th>
  </tr>
  <tr  style="border: 2px solid black;">
    
    <th style=" border: 2px solid black;text-align: center;" rowspan="2">TOTAL ATTENDEES</th>
    <th style=" border: 2px solid black;text-align: center;" colspan="3">GENDER</th>
    <th style=" border: 2px solid black;text-align: center;" colspan="5">PROFESSION</th>
    <th style=" border: 2px solid black;text-align: center;" rowspan="2">SESSION TOTAL SPEAKERS</th>
  </tr>
   <tr style="border: 2px solid black;">
    <td style=" border: 2px solid black;text-align: center;">MALE</td>
    <td style=" border: 2px solid black;text-align: center;">FEMALE</td>
    <td style=" border: 2px solid black;text-align: center;">NOT SPECIFY</td>
    <td style=" border: 2px solid black;text-align: center;">CHEMICAL ENGINEER</td>
    <td style=" border: 2px solid black;text-align: center;">CHEMICAL TECHNICIAN</td>
    <td style=" border: 2px solid black;text-align: center;">CHEMIST</td>
    <td style=" border: 2px solid black;text-align: center;">OTHERS</td>
    <td style=" border: 2px solid black;text-align: center;">NONE</td>
    
  </tr>
  <tr style="border: 2px solid black;">
    
<?php
}
?>
<?php
$sql0 = "SELECT COUNT(DISTINCT participant_id) AS 'total' FROM tblattendance WHERE session_id = ".$_GET['id']."";
$all_session0 = $dbcon->query($sql0);

while($row = mysqli_fetch_assoc($all_session0)){
        
        $count2 = $row['total'];
?>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $count2; ?></td>
<?php
}
?>
<?php
$sql1 = "SELECT COUNT(DISTINCT participant_id) AS 'totalgen' FROM tblattendance WHERE GENDER = 'Male' AND session_id = ".$_GET['id']."";
$all_session1 = $dbcon->query($sql1);

while($row = mysqli_fetch_assoc($all_session1)){
        
        $countgen = $row['totalgen'];
?>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $countgen ?></td>
<?php
}
?>
<?php
$sql2 = "SELECT COUNT(DISTINCT participant_id) AS 'totalgenf' FROM tblattendance WHERE GENDER = 'Female' AND session_id = ".$_GET['id']."";
$all_session2 = $dbcon->query($sql2);

while($row = mysqli_fetch_assoc($all_session2)){
        
        $countgenf = $row['totalgenf'];
?>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $countgenf ?></td>
<?php
}
?>
<?php
$sql3 = "SELECT COUNT(DISTINCT participant_id) AS 'totalgenN' FROM tblattendance WHERE GENDER = 'Not Specify' AND session_id = ".$_GET['id']."";
$all_session3 = $dbcon->query($sql3);

while($row = mysqli_fetch_assoc($all_session3)){
        
        $countgenN = $row['totalgenN'];
?>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $countgenN ?></td>
<?php
}
?>
<?php
$sql4 = "SELECT COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemical Engineer', profession) OR LOWER(other_profession) LIKE '%chemical engineer%' THEN participant_id END) AS ChemicalEngineer, COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemical Technician', profession) THEN participant_id END) AS ChemicalTechnician, COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemist', profession) THEN participant_id END) AS Chemist, COUNT(DISTINCT CASE WHEN profession = 'Others' THEN participant_id END) AS Others, COUNT(DISTINCT CASE WHEN profession = 'None' THEN participant_id END) AS None FROM sampledb.participant INNER JOIN tblattendance ON participant_id = participant.id
    WHERE 
        session_id = ".$_GET['id']." ";
$all_session4 = $dbcon->query($sql4);
$row = mysqli_fetch_array($all_session4);
?>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $row['ChemicalEngineer'] ?></td>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $row['ChemicalTechnician'] ?></td>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $row['Chemist'] ?></td>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $row['Others'] ?></td>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $row['None'] ?></td>



<?php
$sql6 = "SELECT COUNT(*) AS 'totalspeak' FROM tblspeakerses WHERE session_id = ".$_GET['id']."";
$all_session6 = $dbcon->query($sql6);

while($row = mysqli_fetch_assoc($all_session6)){
        
        $count6 = $row['totalspeak'];
?>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $count6; ?></td>
<?php
}
 $sql7 = "SELECT Country,COUNT(Country) AS count FROM tblattendance WHERE session_id = ".$_GET['id']." GROUP BY Country ORDER BY count DESC";
$all_session7 = $dbcon->query($sql7);
?>
  </tr>
</table>
<br>
  <table style="border: 2px solid black; border-collapse: collapse;
  width: 100%;">
  <tr>
    <th style=" border: 2px solid black;text-align: center;" colspan="7">Age</th>
    <th style=" border: 2px solid black;text-align: center;" colspan="<?php echo mysqli_num_rows($all_session7);?>">Location</th>
  </tr>
  <tr>
      <td style=" border: 2px solid black;text-align: center;">61 and Above</td>
      <td style=" border: 2px solid black;text-align: center;">51-60</td>
      <td style=" border: 2px solid black;text-align: center;">41-50</td>
      <td style=" border: 2px solid black;text-align: center;">31-40</td>
      <td style=" border: 2px solid black;text-align: center;">21-30</td>
      <td style=" border: 2px solid black;text-align: center;">Less than 21</td>
      <td style=" border: 2px solid black;text-align: center;">None</td>
      <?php
     
      while($row = mysqli_fetch_assoc($all_session7)){
        echo "<td style=' border: 2px solid black;text-align: center;'>".$row['Country']."</td>";
      }
      ?>
     <!--  <td style=" border: 2px solid black;text-align: center;">Philippines</td>
      <td style=" border: 2px solid black;text-align: center;">Australia</td>
      <td style=" border: 2px solid black;text-align: center;">Pakistan</td>
      <td style=" border: 2px solid black;text-align: center;">China</td>
      <td style=" border: 2px solid black;text-align: center;">India</td>
      <td style=" border: 2px solid black;text-align: center;">Taiwan</td>
      <td style=" border: 2px solid black;text-align: center;">Yemen</td>
      <td style=" border: 2px solid black;text-align: center;">Malaysia</td> -->
  </tr>
  <tr>
      <?php
      $sql5 = "SELECT 
                                      COUNT(DISTINCT participant_id) as total,
                                      COUNT(DISTINCT CASE WHEN participant.age = '61 and above' THEN participant_id END) as '61 and above',
                                      COUNT(DISTINCT CASE WHEN participant.age = '51-60' THEN participant_id END) as '51-60' ,
                                      COUNT(DISTINCT CASE WHEN participant.age = '41-50' THEN participant_id END) as '41-50',
                                      COUNT(DISTINCT CASE WHEN participant.age = '31-40' THEN participant_id END) as '31-40',
                                      COUNT(DISTINCT CASE WHEN participant.age = '21-30' THEN participant_id END) as '21-30',
                                      COUNT(DISTINCT CASE WHEN participant.age = 'Less than 21' THEN participant_id END) as 'Less than 21',
                                      COUNT(DISTINCT CASE WHEN participant.age = '' THEN participant_id END) as 'None'
                                  FROM 
                                      participant 
                                  INNER JOIN 
                                      tblattendance ON participant_id = participant.id
                      WHERE
                          session_id = ".$_GET['id']."
      ";
      $all_session5 = $dbcon->query($sql5);
      $row = mysqli_fetch_array($all_session5);
      ?>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['61 and above'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['51-60'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['41-50'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['31-40'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['21-30'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['Less than 21'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['None'] ?></td>
      <?php
      $sql7 = "SELECT Country,COUNT(Country) AS count FROM tblattendance WHERE session_id = ".$_GET['id']." GROUP BY Country ORDER BY count DESC";
        $all_session7 = $dbcon->query($sql7);
            $all_session7 = $dbcon->query($sql7);
            while($row = mysqli_fetch_array($all_session7)){
                echo "<td style=' border: 2px solid black;text-align: center;'>".$row['count']."</td>";
            };
      ?>
      <!-- <td style=" border: 2px solid black;text-align: center;"><?php echo $row['Philippines'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['Australia'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['Pakistan'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['China'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['India'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['Taiwan'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['Yemen'] ?></td>
      <td style=" border: 2px solid black;text-align: center;"><?php echo $row['Malaysia'] ?></td> -->
  </tr>
</table>

    <br>
    <br>
     </div>
    </div>
  </div>
<!-- ------------------------------------------------------------------------------------------------------------------------------ -->
<?php 
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT COUNT(*) AS 'total' FROM tblptcsession";
$all_session = $dbcon->query($sql);

while($row = mysqli_fetch_assoc($all_session)){
        
        $count = $row['total'];

        

?>           

<?php
}
?>

<?php
$sql = "SELECT COUNT(DISTINCT participant_id) AS 'total1' FROM tblattendance WHERE session_id = ".$_GET['id']."";
$all_session = $dbcon->query($sql);

while($row = mysqli_fetch_assoc($all_session)){
        
        $count1 = $row['total1'];

        }


?>        
<div class="container">          
<div class="row">          
          <div class="col-md-4 graph" style="text-align: right; margin-bottom: 20px;">
              <div class="graph_inner">
                <h1>TOTAL PARTICIPANTS ATTENDED</h1>
                <div>
              
                  <div class="text-center align-middle" id="main-box">
                  <h2 class="timer count-title count-number" data-to="<?php echo $count1; ?>" data-speed="1500" style="font-size: 120px; font-family:melody;"><?php echo $count1; ?></h2>
                </div>
               
              </div>
            </div>
        </div>

        <?php

?>   
    <div class="col-md-4 graph">
    <div class="graph_inner">
    <h1>Gender</h1>
    <div id="chart-container1" style = " width: 100%;
                    height: 100%; padding-left: 1em; padding-right: 1em;">
            <canvas id="graphCanvas3" height="380" width="550"></canvas>
        </div>

</div>
</div>
    

<script>
        $(document).ready(function () {
            bieChart(<?php echo $_GET['id']?>); // Pass the session ID here
        });

        function bieChart(sessionId) {
            $.post("genderdata_individual.php", { session_id: sessionId }, function (data) {
                console.log(data);
                var marks = [];
                
                // Assuming data is a single object
                marks.push(data.male);
                marks.push(data.female);
                marks.push(data.notspecify);
                
                var chartdata = {
                    labels: ["Male", "Female", "Not Specify"],
                    datasets: [
                        {
                            label: "",
                            backgroundColor: [
                                'rgb(30,144,255)',
                                'rgb(255,105,180)',
                                'rgb(186,85,211)',
                            ],
                            data: marks,
                            borderWidth: 1
                        }
                    ]
                };

                var graphTarget = $("#graphCanvas3");
                var barGraph = new Chart(graphTarget, {
                    type: 'pie',
                    data: chartdata
                });
            });
        }
    </script>
    


    <div class="col-md-4 graph">
      <div class="graph_inner">
      <h1>Age</h1>
      <div id="chart-container2" style = "  width: 100%;
                      height: 100%; padding-left: 1em; padding-right: 1em; margin-bottom:-9em;">
              <canvas id="graphCanvas1" height="380" width="500"></canvas>
          </div>
          <script>
          $(document).ready(function () {
              lineChart(<?php echo $_GET['id']?>);
          });

          function lineChart(sessionId) {
              $.post("agedata_individual.php", { session_id: sessionId })
              .done(function (data) {
                  console.log("Received data:", data);
                  var marks = [];

                  // Assuming data is a single object
                  for (var [key, value] of Object.entries(data)) {
                      console.log(key, value);
                      if (key !== 'total') {
                          marks.push(value);
                      }
                  }

                  console.log("Marks:", marks);

                  var chartdata = {
                      labels: ["61 and above", "51-60", "41-50", "31-40", "21-30", "Less than 21", "None"],
                      datasets: [{
                          label: "",
                          backgroundColor: [
                              'rgb(255, 69, 0)',
                              'rgb(255, 140, 0)',
                              'rgb(255, 240, 0)',
                              'rgb(50, 205, 50)',
                              'rgb(30, 144, 255)',
                              'rgb(186, 85, 211)',
                              'rgb(83, 104, 120)',
                          ],
                          data: marks,
                      }]
                  };

                  var graphTarget = $("#graphCanvas1");

                  var doughnutChart = new Chart(graphTarget, {
                      type: 'doughnut',
                      data: chartdata
                  });
              })
              .fail(function (jqXHR, textStatus, errorThrown) {
                  console.error("AJAX request failed:", textStatus, errorThrown);
              });
          }
          </script>


      <div class="agebracket_graph_color">
          
        </div>
    </div>
    </div>


      <div class="col-md-12 graph pt-4">
        <div class="graph_inner">
          <h1>Profession</h1>
          <div id="chart-container" style = " position: relative; width: 100%; margin-bottom: 5em; margin-top: 2em;">
              <canvas id="graphCanvas" height="210" width="500"></canvas>
            </div>
        </div>
    </div>

      <script>
          $(document).ready(function () {
              barChart(<?php echo $_GET['id']?>);
          });

          function barChart(sessionId) {
              $.post("professiondata_individual.php", { session_id: sessionId })
              .done(function (data) {
                  console.log("Received data:", data);

                  var marks = [];
                  for (var [key, value] of Object.entries(data)) {
                      console.log(key, value);
                      if (key != 'total') {
                          marks.push(value);
                      }
                  }

                  console.log("Marks:", marks);

                  const chartdata = {
                      labels: ["Chemical Eng", "Chemical Tech", "Chemist", "Others", "None"],
                      datasets: [{
                          label: "",
                          backgroundColor: [
                              'rgb(255, 206, 86)',
                              'rgb(4, 15, 111)',
                              'rgb(153, 102, 255)',
                              'rgb(255, 159, 64)',
                              'rgb(255, 69, 0)'
                          ],
                          borderColor: 'rgb(75, 192, 192)',
                          data: marks,
                      }]
                  };

                  var graphTarget = $("#graphCanvas");
                  var barGraph = new Chart(graphTarget, {
                      type: 'bar',
                      data: chartdata
                  });
              })
              .fail(function (jqXHR, textStatus, errorThrown) {
                  console.error("AJAX request failed:", textStatus, errorThrown);
              });
          }
      </script>



       <div class="col-md-12 graph" style="margin-top: 10px;">
        <div class="graph_inner">
          <h1>Location</h1>
          <div id="chart-container" style = " position: relative; width: 100%; margin-bottom: -5em; margin-top: 2em;">
              <canvas id="graphCanvas5" height="125" width="500"></canvas>
            </div>

         <script>
                    $(document).ready(function () {
                        douChart(<?php echo $_GET['id']?>);
                    });

                    function douChart(sessionId) {
                        $.post("locdata_individual.php", { session_id: sessionId })
                        .done(function (data) {
                            console.log("Received data:", data);
                            var marks = [];

                            // If data is a single object
                            for (var [key, value] of Object.entries(data)) {
                                console.log(key, value);
                                if (key !== 'total') {
                                    marks.push(value);
                                }
                            }

                            console.log("Marks:", marks);

                            var chartdata = {
                                labels: ["Philippines", "Australia", "Pakistan", "China", "India", "Taiwan", "Yemen", "Malaysia"],
                                datasets: [{
                                    label: "",
                                    backgroundColor: [
                                        'rgb(255, 69, 0)',
                                        'rgb(255, 140, 0)',
                                        'rgb(255, 240, 0)',
                                        'rgb(50, 205, 50)',
                                        'rgb(30, 144, 255)',
                                        'rgb(186, 85, 211)',
                                        'rgb(83, 104, 120)',
                                    ],
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    data: marks,
                                }]
                            };

                            var graphTarget = $("#graphCanvas5");
                            var barGraph = new Chart(graphTarget, {
                                type: 'bar',
                                data: chartdata
                            });
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.error("AJAX request failed:", textStatus, errorThrown);
                        });
                    }
                </script>
            <div class="Profession_graph_color">
            
          </div>
      </div>
    </div>
</div>
</div>
</body>
</html>









