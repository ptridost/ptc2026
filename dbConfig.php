<?php
$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = "ptc@ptri@picts";
$dbName = "sampledb";

// Create connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
