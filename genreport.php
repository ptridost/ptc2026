<?php

  session_start();


require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

function convertImage($path){
  $type = pathinfo($path, PATHINFO_EXTENSION);
  $data = file_get_contents($path);
  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
  return $base64;
}
$servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


  $dbcon = new mysqli($servername, $username, $password, $dbname);

  $sql = "SELECT * FROM tblptcsession ORDER BY session_date";
  $all_session = $dbcon->query($sql);

      while($row = mysqli_fetch_assoc($all_session)){
        
$dompdf = new Dompdf();
$dompdf->loadHtml('
  <!DOCTYPE html>
<html>
<head>
<style>
table {
  margin-top: 100px;
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: center;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>
<img src="'.convertImage('ptr1.png').'" style="width: 100px;height: 100px; float: left; margin-top: -10px; margin-right: 20px; opacity: 0.6;">
<h2 style="font-size: 30px;" >Philippine Textile Summary report</h2>

<table>
  <tr>
    <th rowspan="2">SESSION NAME</th>
    <th rowspan="2">TOTAL ATTENDIES</th>
    <th colspan="3">GENDER</th>
    <th colspan="2">PROFESSION</th>
    <th rowspan="2">SESSION TOTAL SPEAKERS</th>
 
  </tr>
  <tr>
    <td>MALE</td>
    <td>FEMALE</td>
    <td>NOT SPECIFY</td>
    <td>CHEMIST</td>
    <td>CHEMICAL TECHNICIAN</td>
  </tr>
  <tr>
    <td>'.$row['session_head'].'</td>
    <td>200</td>
    <td>24</td>
    <td>10</td>
    <td>200</td>
    <td>201</td>
    <td>201</td>
    <td>201</td>
  </tr>
 
</table>

</body>
</html>




');
$dompdf->set_paper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('CERTIFICATE.pdf', array("Attachment" => false));
}
?>

