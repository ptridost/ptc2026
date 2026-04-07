<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

  $dateValid = '2024-02-08';
  $dateNow = date('Y-m-d');
 
if(!empty($_GET['id']) && $dateNow >= $dateValid){
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";

  $conn = new mysqli($servername, $username, $password, $dbname);
  $stmt = $conn->prepare("SELECT Gender, firstName, middleName, lastName FROM participant WHERE id = ?");
  $stmt->bind_param("i", $_GET['id']);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
  } else {
    header("Location: ../dashboard.php"); 
    exit;
  }

function convertImage($path){
  $type = pathinfo($path, PATHINFO_EXTENSION);
  $data = file_get_contents($path);
  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
  return $base64;
}

function makeDot($middlename){
  return $middlename == null || trim($middlename) == '' ? " " : rtrim(ucfirst($middlename), '.') . ". ";
}

function makeGen($genderName){
 if ($genderName == "Male"){
    return "his attendance";
  }
 elseif ($genderName == "Female"){
  return "her attendance";
}
 else{
  return " attending";
}
}

$arrID=array(5340,6588);

$blankPage='';
$secondPage='<div style="page-break-before: always;">
    <div style=" margin-top: 80px;">
    <center>
    
    <img src="'.convertImage('logo2.png').'" style="height:75px; margin-top:-20px; float: left; margin-left: 230px;"></img>
    <img src="'.convertImage('logo1.png').'" style="height:75px; margin-top:-20px; margin-right: 230px; float: right;"></img>
    <p style="font-size: 15px; margin-top:-20px; margin-bottom:0;">Republic of the Philippines</p>
    <p style="font-size: 15px; margin-top: -15px;">Department of Science and Technology</p>
    <h3 style="font-size: 19px; margin-top: -20px; width:100%;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</h3>
    <p style="font-size: 12px; margin-bottom:-15px;">awards this</p>
    <h1 style="font-size: 30px;">CERTIFICATE OF PARTICIPATION</h1>
    <p style=" font-size: 12px; margin-top: -10px; margin-bottom: -10px;">to</p>
    <h1 style=" font-size:52px;padding-right:50px;padding-left:50px; margin-top: 25px; line-height:30px;">'.ucwords($row['firstName']).' '.makeDot($row['middleName']).' '.ucwords($row['lastName']).'</h1>
    <p style="font-size: 13px; margin-top: -35px;">in acknowledgement of '.makeGen($row['Gender']).' in the</p>
   
    <div><img src="'.convertImage('./assets/2024/ptcbanner.png').'" style="height:110px; margin:0;"></img></div>

    <p style="font-size: 13px; margin-top:-10px; margin-bottom:-5px">during the</p>

    <div><img src="'.convertImage('./assets/2024/ptclogowithrc.png').'" style="height:100px; margin:0;"></img></div>
    
    <p style="font-size: 12px; margin-top:20px; margin-bottom:-10px">Given this 25th Day of November 2024 via Zoom Conference</p>
    <div><img src="'.convertImage('./assets/2024/esigdirector.png').'" style="height:90px; margin-bottom: -50px;"></img>
    <h4 style="font-size: 14px; margin-top: 30px; ">JULIUS L. LEAÑO JR., Ph.D.</h4></div>
    <p style=" font-size: 12px; margin-top: -15px; margin-bottom:0;">Director</p>
      
      </center>
  </div> 
  </div>
  </div>
  </div>';

  if(in_array($_GET['id'],$arrID)){
    $nextPage = $secondPage;
  }else{
    $nextPage = $blankPage;
  }

$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'Landscape');
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
      body { background-image: url("'.convertimage('./assets/2024/certificate2024bg.jpg').'"); 
      background-size: cover; 
      background-repeat: no-repeat; 
      font-family: "BL Melody", sans-serif;
      }
    </style>
  </head>
  <body translate="no">
  <div style=" margin-top: 80px;">
    <center>
    
    <img src="'.convertImage('logo2.png').'" style="height:75px; margin-top:-20px; float: left; margin-left: 230px;"></img>
    <img src="'.convertImage('logo1.png').'" style="height:75px; margin-top:-20px; margin-right: 230px; float: right;"></img>
    <p style="font-size: 15px; margin-top:-20px; margin-bottom:0;">Republic of the Philippines</p>
    <p style="font-size: 15px; margin-top: -15px;">Department of Science and Technology</p>
    <h3 style="font-size: 19px; margin-top: -20px; width:100%;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</h3>
    <p style="font-size: 12px; margin-bottom:-15px;">awards this</p>
    <h1 style="font-size: 30px;">CERTIFICATE OF ATTENDANCE</h1>
    <p style=" font-size: 12px; margin-top: -10px; margin-bottom: -10px;">to</p>
    <h1 style=" font-size:52px;padding-right:50px;padding-left:50px; margin-top: 25px; line-height:30px;">'.ucwords($row['firstName']).' '.makeDot($row['middleName']).' '.ucwords($row['lastName']).'</h1>
    <p style="font-size: 13px; margin-top: -35px;">in acknowledgement of '.makeGen($row['Gender']).' in the</p>
   
    <div><img src="'.convertImage('./assets/2024/ptcbanner.png').'" style="height:110px; margin:0;"></img></div>

    <p style="font-size: 13px; margin-top:-10px; margin-bottom:-5px">during the</p>

    <div><img src="'.convertImage('./assets/2024/ptclogowithrc.png').'" style="height:100px; margin:0;"></img></div>
    
    <p style="font-size: 12px; margin-top:20px; margin-bottom:-10px">Given this 25th Day of November 2024 via Zoom Conference</p>
    <div><img src="'.convertImage('./assets/2024/esigdirector.png').'" style="height:90px; margin-bottom: -50px;"></img>
    <h4 style="font-size: 14px; margin-top: 30px; ">JULIUS L. LEAÑO JR., Ph.D.</h4></div>
    <p style=" font-size: 12px; margin-top: -15px; margin-bottom:0;">Director</p>
      
      </center>
  </div> 
  </div>
  </div>
  
  '.$nextPage.'
  </body>
</html>
'));
$dompdf->render();
$dompdf->stream('CERTIFICATE.pdf', array("Attachment" => false));
}
?>
