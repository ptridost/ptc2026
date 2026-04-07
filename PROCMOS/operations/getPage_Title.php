<?php
	// $directory = "";
	$ifAdmin = stripos($page_visit, "/PROCMOS/supplier");
	
	if(is_bool($ifAdmin) === false){
		// var_dump("TRUE");
		// die();
		$directory = str_replace("/PROCMOS/supplier/", "", $page_visit);
		// var_dump($page_visit);
		// die();
	}
	else{
		// var_dump("FAILED");
		// die();
		$directory = str_replace("/PROCMOS/pages/", "", $page_visit);
		// var_dump($directory);
		// die();
	}

	// die($directory);

	function page_title($url) {
		$fp = file_get_contents($url);
		if (!$fp) 
			return null;

		$res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);
		if (!$res) 
			return null; 

	    // Clean up title: remove EOL's and excessive whitespace.
		$title = preg_replace('/\s+/', ' ', $title_matches[1]);
		$title = trim($title);
		return $title;
	}

	$directory_title = page_title("$directory");
?>