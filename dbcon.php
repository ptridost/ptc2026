<?php
require_once 'config.php';
// Database connection
$servername = "localhost";
$username = "ptc_user";
$password = '$_ptri@Ptc#2026';
$dbname = "ptc";

$dbcon = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($dbcon->connect_error) {
    die("Connection failed: " . $dbcon->connect_error);
}

// Optional but highly recommended: Set the character set to prevent encoding issues.
$dbcon->set_charset("utf8mb4");
?>
