<?php
$servername2 = "localhost";
$username2 = "root";
$password2 = "ptc@ptri@picts";
$dbname2 = "world";

// Create connection
$dbcon_world = new mysqli($servername2, $username2, $password2, $dbname2);
// Check connection
if ($dbcon_world->connect_error) {
    die("Connection failed: " . $dbcon_world->connect_error);
}

?>
