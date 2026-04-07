<?php
	ob_start();
	session_start();
	require_once("../operations/setupTimezone.php");

	$conProcedural = mysqli_connect("localhost","root","ptc@ptri@picts","db_PROCMOS") or die(mysqli_error());

	$con = new PDO("mysql:host=localhost;dbname=db_PROCMOS", "root", "ptc@ptri@picts");
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	error_reporting(0);

	/*











	*/
?>