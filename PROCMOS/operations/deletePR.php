<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['txtPR_IDdelete'])){

	

	$query = "SELECT account_ID FROM tbl_purchaserequest WHERE purchrequest_ID = '". $_POST['txtPR_IDdelete'] ."'";
	$result = mysqli_query($conProcedural,$query);

	while ($row = mysqli_fetch_assoc($result)) {
		$account_ID = $row['account_ID'];
	}

	if($account_ID == $_SESSION['account_ID']){
		$query = "DELETE FROM tbl_purchaserequest WHERE purchrequest_ID = '". $_POST['txtPR_IDdelete'] ."'";
		$result = mysqli_query($conProcedural,$query);

		while ($row = mysqli_fetch_assoc($result)) {
			$deleteResult = $row['deleteResult'];
		}

		echo '<script type="text/javascript">alert("Success! PR has been deleted forever"); history.back()</script>';
	}
	else{
		echo '<script type="text/javascript">alert("ERROR! You cannot delete a PR that you did not create"); history.back()</script>';
	}

	



	
	
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No ID parameter";
	echo '<script type="text/javascript">alert("'.$output.' '.$_POST['txtPR_IDdelete'].'"); history.back()</script>';
}
	


?>