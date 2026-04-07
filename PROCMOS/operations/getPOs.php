<?php
require_once("../DatabaseConnection/databaseconnection.php");

$query = "SELECT po_ID, po_Number, po_Date, po_mode, prNumber, po_Supplier, purchrequest_status, purchrequest_ID, account_ID FROM `tbl_po` INNER JOIN tbl_purchaserequest ON tbl_po.po_Number = tbl_purchaserequest.po_no ORDER BY po_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	

	while ($row = mysqli_fetch_assoc($result)) {
		$po_ID[] = $row['po_ID'];
		$po_Number[] = $row['po_Number'];
		$po_Date[] = $row['po_Date'];
		$po_mode[] = $row['po_mode'];
		$prNumber[] = $row['prNumber'];
		$po_Supplier[] = $row['po_Supplier'];
		$purchrequest_status[] = $row['purchrequest_status'];
		$purchrequest_ID[] = $row['purchrequest_ID'];
		$account_ID[] = $row['account_ID'];
	}

?>