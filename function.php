<?php

	require_once 'database.php';
	function display_data(){
	global $dbcon;
	$query = "SELECT * FROM participants";
	$result = mysqli_query($dbcon,$query);
	return $result;
	}

?>