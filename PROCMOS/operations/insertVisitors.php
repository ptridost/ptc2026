<?php
require_once("visitorComponents.php");

$ip = $_SERVER['REMOTE_ADDR'];
$hostname = strtoupper(gethostbyaddr($ip));
$page_visit = $_SERVER['PHP_SELF'];
$browser = "";

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE){
	$browser = "Internet Explorer";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE){
	$browser = "Internet Explorer";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Edg') !== FALSE){
	$browser = "Microsoft Edge";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE){
	$browser = "Google Chrome";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
	$browser = "Mozilla Firefox";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE){
	$browser = "Safari";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE){
	$browser = "Opera";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE){
	$browser = "Opera Mini";
}
else{
	$browser = "Handheld Browser";
}

$operatingsystem = visitorComponents::get_os();
$device = visitorComponents::get_device();

// echo '<br><br>';

// echo $ip . '<br><br>';
// echo $hostname . '<br><br>';
// echo visitorComponents::get_ip() . '<br><br>';
// echo visitorComponents::get_browser() . '<br><br>';

// die();

// var_dump($page_visit); equals = string(33) "/rypic/rypicv1/pages/products.php"
// die();

// PUBLIC RYPIC PORTAL = ($ip != "202.90.141.187")&&($ip != "192.168.1.19")&&($ip != "10.10.11.19") &&($ip != "112.208.175.87")
if(($ip != "10.10.11.19")&&($ip != "10.10.11.19")&&($ip != "202.90.141.180")){
	require_once("getPage_Title.php");

	// var_dump($directory_title);
	// die();

	// die($ip);

	$query = "INSERT INTO tbl_visitors(visitorIP, visitorHostname, visitorBrowser, visitorDevice, visitorOS, visitorPage) VALUES('$ip', '$hostname', '$browser', '$device', '$operatingsystem', '$directory_title')";
	$result = mysqli_query($conProcedural,$query);	
}
else{
	// require_once("getPage_Title.php");

	// $query = "INSERT INTO tbl_visitors(visitor_IP, visitor_hostname, page_visit, visitor_date) VALUES('$ip',
	// '$hostname', '$directory_title', '$now')";
	// $result = mysqli_query($con,$query);	
	// die($ip);
}
?>