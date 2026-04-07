<?php
$servername2 = "localhost";
$username2 = "root";
$password2 = "ptc@ptri@picts";
$dbname2 = "world";

// Create connection
$conn2 = new mysqli($servername2, $username2, $password2, $dbname2);
// Check connection
if ($conn2->connect_error) {
    die("Connection failed: " . $conn2->connect_error);
}
$conProcedural = mysqli_connect("localhost","root","ptc@ptri@picts","world") or die(mysqli_error());
?>
