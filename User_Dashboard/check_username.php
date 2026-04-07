<?php

$host = "localhost";
$user = "root"; 
$password = "ptc@ptri@picts"; 
$dbname = "sampledb"; 

// Create connection
$con = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($con->connect_error) {
   die("Connection failed: " . $con->connect_error);
}

$data = json_decode(file_get_contents("php://input"));

if(isset($data->username)){
   $username = mysqli_real_escape_string($con,$data->username);

   $query = "select count(*) as cntUser from participant where username='".$username."'";

   $result = mysqli_query($con,$query);
   $response = "<span style='color: green;'>Username is Available.</span>";
   if(mysqli_num_rows($result)){
      $row = mysqli_fetch_array($result);

      $count = $row['cntUser'];

      if($count > 0){
         $response = "<span style='color: red;'>Username is taken.</span>";
      }

   }
   echo $response;
}








?>