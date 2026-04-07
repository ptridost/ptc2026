<?php

	require_once 'database.php';
	function display_data(){
	global $dbcon;
	$query = "SELECT * FROM contact";
	$result = mysqli_query($dbcon,$query);
	return $result;
	}

?>