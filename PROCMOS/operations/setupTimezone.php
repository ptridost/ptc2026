<?php
	date_default_timezone_set("Asia/Singapore");
  
	$now = date_create('now')->format('Y-m-d H:i:s');

	$nowDateOnly = date_create('now')->format('Y-m-d');

	$nowDateOnlyISO = date_create('now')->format('d-M-Y');
?>