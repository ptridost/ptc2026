<?php
	$conn = mysqli_connect('localhost', 'root', 'ptc@ptri@picts', 'sampledb');
	
	if(!$conn){
		die("Error: Failed to connect to database!");
	}
?>