<?php

  session_start();

  $dateValid = '2024-12-25';
  $dateNow = date('Y-m-d');

  

  if(!empty($_SESSION['participant_id']) && $dateNow == $dateValid){
  }
  else{
    header("Location: ../dashboard.php"); 
  }



require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

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

function returnStringToPrint($session){
  $digitPattern = "/[0-9]/"; //Pattern for finding digits
  $alphaPattern = "/[a-zA-z]/"; //Pattern for finding alphacharacters (both lowercase and uppercase)

  $isNumber = preg_match($digitPattern, $session); //Check if session header has number, returns true or false

  if ($isNumber){
    return preg_replace($alphaPattern, "", $session); //Replace all alpha characters with empty string
  }
  else{
    return preg_replace($digitPattern, "", $session); //Replace all digit characters with empty string
  }
}

// Remove dashes and numbers in-between a sequence
function formatColloqiumSession($stringToFormat){
  $sets = explode(",", $stringToFormat);
  $newString = "";

  foreach ($sets as $set){
    if (preg_match("/-/", $set)){
      $newString .= $set[0]."-".$set[-1] . ", ";
    }
    else{
      $newString .= $set .", ";
    }
  }
  return $newString;
}

function checkSession(){
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


  $conn = new mysqli($servername, $username, $password, $dbname);
  $sql = "SELECT participant_id,GROUP_CONCAT(session_head) as session_head FROM tblattendance WHERE participant_id = '".$_SESSION['participant_id']."' GROUP BY participant_id ";
// ----------------------------------------
    $colloqiumSession = "";
    $attendedSessionsToPrint = "";

    $digitPattern = "/[0-9]/";
    $previousNumber = 0;
// ----------------------------------------
    $all_session = $conn->query($sql);

    while($row = mysqli_fetch_array($all_session)){
      $sessionHeaders = explode(",",$row['session_head']);
      
}

      foreach ($sessionHeaders as $session){
        $attendedSessionToAdd = returnStringToPrint($session);

        // Concatenating Preliminary and Closing session
        if (!preg_match($digitPattern, $attendedSessionToAdd)){
          $attendedSessionsToPrint .= $attendedSessionToAdd .", ";
        }
        // Concatenating colloqium session numbers;
        else{
          $attendedSessionToAdd = (int)$attendedSessionToAdd; // Typecast session number to int

          // First colloqium session to add
          if ($previousNumber == 0){
            $colloqiumSession .= $attendedSessionToAdd;
          }

          // Other colloqium sessions to add
          // Check if current selected session follows the number the sequence
          else{
            // If Yes, add a dash
            if ($attendedSessionToAdd - 1 == $previousNumber){
              $colloqiumSession .= "-".$attendedSessionToAdd;
            }

            // Else, print a comma
            else{
              $colloqiumSession .= ",".$attendedSessionToAdd; 
            }
            
          }

          // Set previous number to last number in sequence
          $previousNumber = $attendedSessionToAdd;
        }
      }

      return rtrim($attendedSessionsToPrint ."<br>Session ".formatColloqiumSession($colloqiumSession), ', ');
       
  
}
$servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT t2.participant_id, t2.Fname, session_head, profession, t1.session_id FROM tblattendance t1 INNER JOIN tblfeedback t2 ON t1.participant_id = t2.participant_id AND t2.session_id = t1.session_id INNER JOIN participant t3 ON t3.id = t1.participant_id WHERE t1.participant_id = '".$_SESSION['participant_id']."' ORDER BY t2.participant_id ASC";


$all_session = $dbcon->query($sql);
$count = mysqli_num_rows($all_session);
        echo ($count);
while($row = mysqli_fetch_assoc($all_session)){ 
  $chem = $row['profession'];
  $attend = $row['session_id'];
  echo $row['profession'];     
        
if($count >= 6 && ($chem == 'Chemist' || $chem == 'Chemist,Others' || $chem == 'Chemist,Chemical Technician') && $attend = 1 && $attend = 14){
$dompdf = new Dompdf();
$dompdf->loadHtml('
  <html>
  <head>
  <style>
      html{margin: 0px 0px}
      body { background-image: url("'.convertimage('1.png').'"); background-size: 815px 1100px; background-repeat: no-repeat; }
    </style>
  </head>
  <body translate="no">
  <div style="width: 700px; height: 800px; margin-left: 60px; margin-top: 45px;">
    <center><page size="A4">
    
      <div id="makepdf">
    <center>
    <h5 style="font-family: sans-serif; margin-bottom: -20px; margin-top: 10px">Republic of the Philippines</h5>
    <h4 style="font-family: sans-serif; margin-bottom: -15px;">Department of Science and Technology</h4>
    <h3 style="font-family: sans-serif;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</h3>
    <h5 style="font-family: sans-serif;">awards this</h5>
    <h1 style="font-family: sans-serif;">CERTIFICATE OF ATTENDANCE</h1>
    <h5 style="font-family: sans-serif;">to</h5>
    <h1 style="font-family: sans-serif; color: #187bcd; font-size:40px;">'.strtoupper($_SESSION['participant_fname']).' '.makeDot($_SESSION['participant_mName']).' '.strtoupper($_SESSION['participant_lName']).'</h1>
    <h5 style="font-family: sans-serif;">for '.makeGen($_SESSION['participant_gender']).' in the</h5>
    <div><img src="'.convertImage('../../landingpageimg/rc.png').'" style="height:70px;"></img></div>
    <h3 style="font-family: sans-serif;">'.checkSession().'</h3>
    <h3 style="font-family: sans-serif; color: #187bcd;">Accreditation No. CHM-2023-032 for 12 CPD units</h3>
    <h5 style="font-family: sans-serif; margin-top: 20px; margin-bottom: -10px;">during the</h5>
    <h1 style="font-family: sans-serif;">PHILIPPINE TEXTILE CONGRESS 2023</h1>
    <h5 style="font-family: sans-serif;">with the theme</h5>
    <h5 style="font-family: sans-serif;"><i>"Futures Thinking for Philippine Textiles"</i></h5>
    <h5 style="font-family: sans-serif;">Given this 24th Day of November 2023 at the <br>
                                          Philippine Textile Research Institute, Department of Science and Technology,<br>
                                           in Bicutan, Taguig City, Philippines
                                          </h5>
    <h3 style="font-family: sans-serif;margin-top: 60px; color: gray;">JULIUS L. LEAÑO JR., Ph.D.</h3>
    <h5 style="font-family: sans-serif;margin-top: -20px; color: gray;">Director IV</h5>
    
       <img src="'.convertImage('https://iconape.com/wp-content/files/ys/177932/png/dost-logo.png').'" style="width: 60px;height: 60px; float: right; margin-top: 10px; margin-right: 10px; opacity: 0.6;">
      <img src="'.convertImage('ptr1.png').'" style="width: 60px;height: 60px; float: right; margin-top: 10px; margin-right: 20px; opacity: 0.6;">
      <img src="'.convertimage('PTC.png').'" style="width: auto;height: 90px; float: left; margin-top: -10px; opacity: 0.6;">
      <div style="display: inline;">
      
      </center>
  </div> 
  </div>
  </div>
  </body>
</html>


');
$dompdf->render();
$dompdf->stream('CERTIFICATE.pdf', array("Attachment" => false));
}
else{
$dompdf = new Dompdf();
$dompdf->loadHtml('
  <html>
  <head>
  <style>
      html{margin: 0px 0px}
      body { background-image: url("'.convertimage('1.png').'"); background-size: 815px 1100px; background-repeat: no-repeat; }
    </style>
  </head>
  <body translate="no">
  <div style="width: 700px; height: 800px; margin-left: 60px; margin-top: 45px;">
    <center><page size="A4">
    
      <div id="makepdf">
    <center>
    <h5 style="font-family: sans-serif; margin-bottom: -20px; margin-top: 10px">Republic of the Philippines</h5>
    <h4 style="font-family: sans-serif; margin-bottom: -15px;">Department of Science and Technology</h4>
    <h3 style="font-family: sans-serif;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</h3>
    <h5 style="font-family: sans-serif;">awards this</h5>
    <h1 style="font-family: sans-serif;">CERTIFICATE OF ATTENDANCE</h1>
    <h5 style="font-family: sans-serif;">to</h5>
    <h1 style="font-family: sans-serif; color: #187bcd; font-size:40px;">'.strtoupper($_SESSION['participant_fname']).' '.makeDot($_SESSION['participant_mName']).' '.strtoupper($_SESSION['participant_lName']).'</h1>
    <h5 style="font-family: sans-serif;">for '.makeGen($_SESSION['participant_gender']).' in the</h5>
    <div><img src="'.convertImage('../../landingpageimg/rc.png').'" style="height:70px;"></img></div>
    <h3 style="font-family: sans-serif;">'.checkSession().'</h3>
    <h5 style="font-family: sans-serif; margin-top: 20px; margin-bottom: -10px;">during the</h5>
    <h1 style="font-family: sans-serif;">PHILIPPINE TEXTILE CONGRESS 2023</h1>
    <h5 style="font-family: sans-serif;">with the theme</h5>
    <h5 style="font-family: sans-serif;"><i>"Futures Thinking for Philippine Textiles"</i></h5>
    <h5 style="font-family: sans-serif;">Given this 24th Day of November 2023 at the <br>
                                          Philippine Textile Research Institute, Department of Science and Technology,<br>
                                           in Bicutan, Taguig City, Philippines
                                          </h5>
    <h3 style="font-family: sans-serif;margin-top: 60px; color: gray;">JULIUS L. LEAÑO JR., Ph.D.</h3>
    <h5 style="font-family: sans-serif;margin-top: -20px; color: gray;">Director IV</h5>
    
       <img src="'.convertImage('https://iconape.com/wp-content/files/ys/177932/png/dost-logo.png').'" style="width: 60px;height: 60px; float: right; margin-top: 10px; margin-right: 10px; opacity: 0.6;">
      <img src="'.convertImage('ptr1.png').'" style="width: 60px;height: 60px; float: right; margin-top: 10px; margin-right: 20px; opacity: 0.6;">
      <img src="'.convertimage('PTC.png').'" style="width: auto;height: 90px; float: left; margin-top: -10px; opacity: 0.6;">
      <div style="display: inline;">
      
      </center>
  </div> 
  </div>
  </div>
  </body>
</html>


');
$dompdf->render();
$dompdf->stream('CERTIFICATE.pdf', array("Attachment" => false));
}
}

?>
<!-- <h3 style="font-family: sans-serif; color: #187bcd;">Accreditation No. CHM-2022-032 for '.$_SESSION["participant_cpd"].' CPD units</h3> -->