<?php
require_once("../DatabaseConnection/databaseconnection.php");

// var_dump($_SESSION['AccountType']);
// die();

$query = "SELECT rfq_ID, rfq_UploadDate, purchrequest_ID, quotation_no, purchrequest_No, purchrequest_location, pr_datetimelastaction, purchrequest_status, tbl_accounts.account_ID FROM ((tbl_rfq INNER JOIN `tbl_purchaserequest` ON tbl_rfq.pr_No = tbl_purchaserequest.purchrequest_No) INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE rfq_Company = '".$_SESSION['suppName']."' ORDER BY pr_datetimelastaction ASC";
$result = mysqli_query($conProcedural,$query);

	

	

	if (mysqli_num_rows($result)==0){ 
		echo '<tr><td colspan="9" style="text-align: center;"><b><h3>YOU HAVE NOT SUBMITTED ANY QUOTATIONS YET</h3><b></td></tr>';
	}
	else{
		$i = 1;

		while ($row = mysqli_fetch_assoc($result)) {
			$rfq_ID = $row['rfq_ID'];
			$purchrequest_ID = $row['purchrequest_ID'];
			$purchrequest_No = $row['purchrequest_No'];
			$prquotation_no = $row['quotation_no'];
			$rfq_UploadDate = $row['rfq_UploadDate'];
			$purchrequest_location = $row['purchrequest_location'];
			$pr_datetimelastaction = $row['pr_datetimelastaction'];
			$purchrequest_status = $row['purchrequest_status'];
			$account_ID = $row['account_ID'];

			$pr_datetimelastactionNew = date('Y-m-d', strtotime($pr_datetimelastaction));

			// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
			$date1=date_create($pr_datetimelastactionNew);
			$date2=date_create($nowDateOnly);
			$diff=date_diff($date1,$date2);


			// var_dump($nowDateOnly);

			// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
			// var_dump($diff);
			// var_dump($diff->format("%R%a days"));
			// die();

			$numberOfDaysDiff = $diff->format("%R%a days");

			// var_dump($numberOfDaysDiff);
			// die();

			if($numberOfDaysDiff > 7){
				echo '<tr id="row'.$i.'" class="overdue">
				<td>'.$purchrequest_ID.'</td>
		                            <td>'.$purchrequest_No.'</td>
		                            <td>'.$prquotation_no.'</td>
		                            <td>'.$rfq_UploadDate.'</td>
		                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
		                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$purchrequest_No.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View Quotation</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
		                        </tr>';
			}
			else if(($numberOfDaysDiff < 8) && ($numberOfDaysDiff > 5)){
				echo '<tr id="row'.$i.'" class="due">
				<td>'.$purchrequest_ID.'</td>
		                           <td>'.$purchrequest_No.'</td>
		                            <td>'.$prquotation_no.'</td>
		                            <td>'.$rfq_UploadDate.'</td>
		                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
		                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$purchrequest_No.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View Quotation</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
		                        </tr>';
			}
			else if(($numberOfDaysDiff < 6) && ($numberOfDaysDiff > 4)){
				echo '<tr id="row'.$i.'" class="neardue">
				<td>'.$purchrequest_ID.'</td>
		                           <td>'.$purchrequest_No.'</td>
		                            <td>'.$prquotation_no.'</td>
		                            <td>'.$rfq_UploadDate.'</td>
		                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
		                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$purchrequest_No.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View Quotation</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
		                        </tr>';
			}
			else{
				echo '<tr id="row'.$i.'" class="healthy">
				<td>'.$purchrequest_ID.'</td>
		                           <td>'.$purchrequest_No.'</td>
		                            <td>'.$prquotation_no.'</td>
		                            <td>'.$rfq_UploadDate.'</td>
		                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
		                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$purchrequest_No.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View Quotation</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
		                        </tr>';	
			}


		    					

			$i++;
		}
	}

	

?>