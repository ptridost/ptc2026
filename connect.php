<?php
$server = "localhost";
$username = "root";
$password = "ptc@ptri@picts";
$database = "sampledb";
$connection = mysqli_connect("$server","$username","$password");
$select_db = mysqli_select_db($connection, $database);
if(!$select_db)
{
	echo("connection terminated");
}

$conProcedural = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error());
?>