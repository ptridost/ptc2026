<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
session_start();

  $dateValid = '2024-02-08';
  $dateNow = date('Y-m-d');
  // && $dateNow == $dateValid
 
  if(!empty($_SESSION['participant_id'] && $dateNow >= $dateValid)){
  // $_SESSION['participant_id'] = '483';
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";

$conn = new mysqli($servername, $username, $password, $dbname);
$stmt = $conn->prepare("SELECT Gender, firstName, middleName, lastName FROM participant WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['participant_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a record was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Set session variables for gender and name
        $_SESSION['participant_gender'] = $row['Gender'];
        $_SESSION['participant_fname'] = $row['firstName'];
        $_SESSION['participant_mName'] = $row['middleName'];
        $_SESSION['participant_lName'] = $row['lastName'];
  }
  else{
    header("Location: ../dashboard.php"); 
  }
  

function convertImage($path){
  $type = pathinfo($path, PATHINFO_EXTENSION);
  $data = file_get_contents($path);
  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
  return $base64;
}

function makeDot($middlename){
  return $_SESSION['participant_mName'] == null ? " " : $middlename.". ";

}
function makeGen($genderName){
 if ($_SESSION['participant_gender'] == "Male"){
    return "his attendance";
  }
 elseif ($_SESSION['participant_gender'] == "Female"){
  return "her attendance";
}
 else{
  return " attending";
}


}
function getSession(){
$servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";

$dbcon1 = new mysqli($servername, $username, $password, $dbname);

$sql_attendance_feedback = "
SELECT 
    tblattendance.session_head, 
    tblptcsession.session_title, 
    tblattendance.session_id,
    tblattendance.date_submitted
FROM tblattendance 
INNER JOIN tblptcsession 
    ON tblattendance.session_id = tblptcsession.session_number 
    where participant_id = ".$_SESSION['participant_id']." AND tblattendance.session_id IN (
    SELECT session_id FROM tblfeedback where participant_id = ".$_SESSION['participant_id']."
    ) AND tblattendance.date_submitted LIKE '2023%' ORDER BY tblattendance.session_id ASC";



$sessiontitle = "";
$sessionclose = "Closing Session";
$sessionopen = "Opening/ Plenary Session, <br>";
$all_session1 = $dbcon1->query($sql_attendance_feedback);

while($row = mysqli_fetch_array($all_session1)){

  $sessiontitle .= $row['session_head'] . ": " . $row['session_title'].";<br>";

}   

  return substr($sessiontitle, 0, -5);
}

$servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT t3.id, t1.session_head, t3.profession, t1.session_id, t1.session_id, t3.cpd FROM tblattendance t1 INNER JOIN participant t3 ON t3.id = t1.participant_id WHERE t1.participant_id = '".$_SESSION['participant_id']."' ORDER BY t3.id ASC";

$all_session = $dbcon->query($sql);
$count = mysqli_num_rows($all_session);
        echo ($count);
while($row = mysqli_fetch_array($all_session)){ 
  foreach ($all_session as $row) {
    $cpd = $row['cpd']; 
    $chems = $row['profession'];
    $chem = explode(",", $chems); 
    $attend = $row['session_id'];

if ($cpd > 0) {
$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'Portrait');
$dompdf->loadHtml(('
  <html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <style>
 
      html{margin: 0px 0px}
      @font-face {
        font-family: "BLMelody-Bold";
        src: url("../../fonts/BLMelody-Bold.ttf") format("truetype");
      }
      @font-face {
        font-family: "BLMelody-Light";
        src: url("../../fonts/BLMelody-Light.ttf") format("truetype");
      }
      h1, h2, h3, {
      font-family: "BLMelody-Bold", sans-serif;
    }
    p{
      font-family: "BLMelody-Light", sans-serif;
    }
    .ses{
      font-family: Helvetica;
      font-style: italic;
    }
      body { background-image: url("'.convertimage('1.png').'"); 
      background-size: 827px 1169px; 
      background-repeat: no-repeat; 
      font-family: "BL Melody", sans-serif;

      }
    </style>
  </head>
  <body translate="no">
  <div style="height: 800px; margin-top: 45px;">
    <center><page size="A4">
    
      <div id="makepdf" style="margin-top: 20px;">
    <center>
    
    <img src="'.convertImage('logo2.png').'" style="height:65px; margin-top:-30px; float: left; margin-left: 100px;"></img>
    <img src="'.convertImage('logo1.png').'" style="height:70px; margin-top:-40px; margin-right: 100px; float: right;"></img>
    <p style="font-size: 15px;">Department of Science and Technology</p>
    <h3 style="font-size: 19px; margin-top: -15px; width:100%;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</h3>
    <p style="font-size: 12px; margin-top: -20px; margin-bottom: -20px">presents this</p>
    <h1 style="font-size: 30px;">CERTIFICATE OF ATTENDANCE</h1>
    <p style=" font-size: 12px; margin-top: -10px; margin-bottom: 10px;">to</p>
    <h1 style=" color: #187bcd; font-size:50px; margin-top: -1px;">'.ucwords($_SESSION['participant_fname']).' '.makeDot($_SESSION['participant_mName']).' '.ucwords($_SESSION['participant_lName']).'</h1>
    <p style="font-size: 13px; margin-top: -40px;">for '.makeGen($_SESSION['participant_gender']).' in the</p>
    
    <center><div style="width: 792px;">
    <p class="ses" style="margin-left: 80px; margin-right: 80px;"><b><em>'.getSession().'</em><b></p>
    </div></center>
    <h3 style="color: #187bcd; font-size: 21px; margin-top:-10px; width:100%;">Accreditation No. CHM-2023-032-345 for '.$cpd.' CPD units</h3>
    <p style="font-size: 13px; margin-top: 30px; margin-bottom: 5px;">during the</p>
    <div><img src="'.convertImage('logo3.png').'" style="height:100px; margin:0;"></img></div>

    
    <p style="font-size: 12px; margin-top: 40px;">Given this 24th Day of November 2023 at the <br>
                                          National Museum Western Visayas <br>Iloilo City,Philippines
                                          </p>
    <div><img src="'.convertImage('esig.jpg').'" style="height:90px; margin-bottom: -70px;"></img>
    <h4 style="font-size: 14px; margin-top: 50px;">JULIUS L. LEAÑO JR., Ph.D.</h4></div>
    <p style=" font-size: 12px; margin-top: -15px; ">Director</p>
    
       
      <div style="display: inline;">
      
      </center>
  </div> 
  </div>
  </div>
  </body>
</html>


'));

$dompdf->render();
$dompdf->stream('CERTIFICATE.pdf', array("Attachment" => false));

}
else{

$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'Portrait');
$dompdf->loadHtml(('
  <html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <style>
 
      html{margin: 0px 0px}
      @font-face {
        font-family: "BLMelody-Bold";
        src: url("../../fonts/BLMelody-Bold.ttf") format("truetype");
      }
      @font-face {
        font-family: "BLMelody-Light";
        src: url("../../fonts/BLMelody-Light.ttf") format("truetype");
      }
      h1, h2, h3, {
      font-family: "BLMelody-Bold", sans-serif;
    }
    p{
      font-family: "BLMelody-Light", sans-serif;
    }
    .ses{
      font-family: Helvetica;
      font-style: italic;
    }
      body { background-image: url("'.convertimage('1.png').'"); 
      background-size: 827px 1169px; 
      background-repeat: no-repeat; 
      font-family: "BL Melody", sans-serif;

      }
    </style>
  </head>
  <body translate="no">
  <div style="height: 800px; margin-top: 45px;">
    <center><page size="A4">
    
      <div id="makepdf" style="margin-top: 20px;">
    <center>
    
    <img src="'.convertImage('logo2.png').'" style="height:65px; margin-top:-30px; float: left; margin-left: 100px;"></img>
    <img src="'.convertImage('logo1.png').'" style="height:70px; margin-top:-40px; margin-right: 100px; float: right;"></img>
    <p style="font-size: 15px;">Department of Science and Technology</p>
    <h3 style="font-size: 19px; margin-top: -15px; width:100%;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</h3>
    <p style="font-size: 12px; margin-top: -20px; margin-bottom: -20px">presents this</p>
    <h1 style="font-size: 30px;">CERTIFICATE OF PARTICIPATION</h1>
    <p style=" font-size: 12px; margin-top: -10px; margin-bottom: 10px;">to</p>
    <h1 style=" color: #187bcd; font-size:50px; margin-top: -1px;">'.ucwords($_SESSION['participant_fname']).' '.makeDot($_SESSION['participant_mName']).' '.ucwords($_SESSION['participant_lName']).'</h1>
    <p style="font-size: 13px; margin-top: -40px;">for '.makeGen($_SESSION['participant_gender']).' in the</p>
    
    <center><div style="width: 792px;">
    <p class="ses" style=" margin-left: 80px; margin-right: 80px;"><b><em>'.getSession().'</em><b></p>
    </div></center>
    
    <p style="font-size: 13px; margin-top: 30px; margin-bottom: 5px;">during the</p>
    <div><img src="'.convertImage('logo3.png').'" style="height:100px; margin:0;"></img></div>

    
    <p style="font-size: 12px; margin-top: 40px;">Given this 24th Day of November 2023 at the <br>
                                          National Museum Western Visayas <br>Iloilo City,Philippines
                                          </p>
    <div><img src="'.convertImage('esig.jpg').'" style="height:90px; margin-bottom: -70px;"></img>
    <h4 style="font-size: 14px; margin-top: 50px; ">JULIUS L. LEAÑO JR., Ph.D.</h4></div>
    <p style=" font-size: 12px; margin-top: -15px; ">Director</p>
    
       
      <div style="display: inline;">
      
      </center>
  </div> 
  </div>
  </div>
  </body>
</html>


'));
$dompdf->render();
$dompdf->stream('CERTIFICATE.pdf', array("Attachment" => false));
}
 }
    }
  }
?>
