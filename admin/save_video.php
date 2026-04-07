<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
<?php
	date_default_timezone_set('Asia/Manila');
	require_once 'conn.php';
	//print_r($_GET['id'])
	if(ISSET($_POST['save'])){
		$session_number = $_POST['hidden'];
		$file_name = $_FILES['video']['name'];
		$file_temp = $_FILES['video']['tmp_name'];
		$file_size = $_FILES['video']['size'];
		//$ID        = $_GET['id'];
		$vidid      = $_POST['videoid'];
		
		if($file_size < 50000000){
			$file = explode('.', $file_name);
			$end = end($file);
			$allowed_ext = array('avi', 'flv', 'wmv', 'mov', 'mp4');
			if(in_array($end, $allowed_ext)){
				$name = $file_name;
				$location = 'video/'.$name;
				if(move_uploaded_file($file_temp, $location)){
					mysqli_query($conn, "INSERT INTO `video` VALUES('', '$name', '$location','$session_number')") or die(mysqli_error());
					echo "<script>alert('Video Uploaded')</script>";
					echo "<script>window.location = 'admindashboard.php'</script>";
				}
			}else{
				echo "<script>alert('Wrong video format')</script>";
				echo "<script>window.location = 'recordedsession.php'</script>";
			}
		}else{
			echo "<script>alert('File too large to upload')</script>";
			echo "<script>window.location = 'recordedsession.php'</script>";
		}
	}
?>