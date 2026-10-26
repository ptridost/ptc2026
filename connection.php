<?php
$servername = "localhost";
$username = "ptc_user";
$password = '$_ptri@Ptc#2026';
$dbname = "ptc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>