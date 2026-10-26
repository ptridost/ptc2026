<?php
$servername2 = "localhost";
$username2 = "ptc_user";
$password2 = '$_ptri@Ptc#2026';
$dbname2 = "ptc_world";

// Create connection
$dbcon_world = new mysqli($servername2, $username2, $password2, $dbname2);
// Check connection
if ($dbcon_world->connect_error) {
    die("Connection failed: " . $dbcon_world->connect_error);
}

?>
