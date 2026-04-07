<?php
$servername = "localhost";
$username = "root";
$password = "ptc@ptri@picts";
$dbname = "world";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conProcedural = mysqli_connect("localhost","root","ptc@ptri@picts","world") or die(mysqli_error());
?>