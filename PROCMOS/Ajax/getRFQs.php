<?php
require_once("../DatabaseConnection/databaseconnection.php");


if(isset($_POST['qNumber'])){
	$query = "SELECT * FROM `tbl_rfq` WHERE rfq_No = '". $_POST['qNumber'] ."' ORDER BY rfq_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	

	while ($row = mysqli_fetch_assoc($result)) {
		$rfq_ID = $row['rfq_ID'];
		$rfq_Date = $row['rfq_Date'];
		$rfq_filename = $row['rfq_filename'];
		$rfq_No = $row['rfq_No'];
		$pr_No = $row['pr_No'];
		$rfq_Company = $row['rfq_Company'];
		$rfq_Address = $row['rfq_Address'];
		$rfq_enduser = $row['rfq_enduser'];
		$rfq_UploadDate = $row['rfq_UploadDate'];

		echo '<tr id="row'.$rfq_ID.'">
		        <td>'.$rfq_ID.'</td>
		        <td>'.$rfq_Date.'</td>
		        <td>'.$rfq_No.'</td>


		        <td><a id="lnkViewPR" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




		        <td>'.$pr_No.'</td>
		        <td>'.$rfq_Company.'</td>
		        <td>'.$rfq_Address.'</td>
		        <td>'.$rfq_enduser.'</td>
		        <td>'.$rfq_UploadDate.'</td>
		        <td><a id="lnkOpenQuotation" href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This RFQ</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
		        
		    </tr>';
	}

	// <td><a href="print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$pr_No.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This RFQ</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>

	// <td><a href="../RFQs/'.$rfq_filename.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This RFQ</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No Quotation number parameter";
	echo $output . " " . $_POST['qNumber'];
}

	

	

?>