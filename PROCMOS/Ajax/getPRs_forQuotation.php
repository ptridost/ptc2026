<?php
require_once("../DatabaseConnection/databaseconnection.php");

// var_dump($_SESSION['AccountType']);
// die();

		// $query = "SELECT supplier_NatureOfBusiness FROM tbl_suppdetails WHERE supplierID=:supplierID";
		// $stmt = $con->prepare($query);
		// $stmt->bindValue(':supplierID', $_SESSION['suppID'], PDO::PARAM_INT);
		// $stmt->execute();
		// $row = $stmt->fetch(PDO::FETCH_ASSOC);

		$stmt = $con->prepare("SELECT supplier_NatureOfBusiness FROM tbl_suppdetails WHERE supplierID = :suppID");
	    $stmt->execute(['suppID' => $_SESSION['suppID']]);

	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	        $supplier_Categories[] = $row['supplier_NatureOfBusiness'];
	    }

		// $query = "SELECT supplier_NatureOfBusiness FROM tbl_suppdetails WHERE supplierID = '". $_SESSION['suppID'] ."'";
		// $result = mysqli_query($conProcedural,$query);

		// while ($row = mysqli_fetch_assoc($result)) {
		// 	$supplier_Categories[] = $row['supplier_NatureOfBusiness'];
		// }

		// var_dump($row);
		// die();

		// if ($row) {
		//     $supplier_Categories[] = $row['supplier_NatureOfBusiness'];
		// }

		// echo $_SESSION['suppID'];

// $query = "SELECT purchrequest_ID, quotation_no, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_location = 'FAD-PPGSS' AND purchrequest_status = 'For RFQ' AND quotation_no != 'Not Yet Assigned' ORDER BY pr_datetimelastaction DESC";
$query = "SELECT purchrequest_ID, quotation_no, date_quotationno, pr_rfqDuration, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, DATE_ADD(date_quotationno, INTERVAL pr_rfqDuration DAY) AS closing_date FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_location = 'FAD-PPGSS' AND purchrequest_status = 'For RFQ' AND quotation_no != 'Not Yet Assigned' AND pr_Category IN ('".implode("','", $supplier_Categories)."') AND DATE_ADD(date_quotationno, INTERVAL pr_rfqDuration DAY) >= CURDATE() ORDER BY closing_date DESC";
$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// 	die();	
	if (mysqli_num_rows($result)==0){ 
		echo '<tr><td colspan="9" style="text-align: center;"><b><h3>THERE ARE NO ACTIVE PRs FOR QUOTATION YET</h3><b></td></tr>';
	}
	else{
		$i = 1;

		while ($row = mysqli_fetch_assoc($result)) {
			$purchrequest_ID = $row['purchrequest_ID'];
			$purchrequest_No = $row['purchrequest_No'];
			$prquotation_no = $row['quotation_no'];
			$date_quotationno = $row['date_quotationno'];
			$pr_rfqDuration = $row['pr_rfqDuration'];
			$closing_date = $row['closing_date'];
			$prNo_dtd = $row['prNo_dtd'];
			$purchrequest_DateTime = $row['purchrequest_DateTime'];
			$purchrequest_location = $row['purchrequest_location'];
			$pr_datetimelastaction = $row['pr_datetimelastaction'];
			$pr_lastprocessedby = $row['pr_lastprocessedby'];
			$purchrequest_status = $row['purchrequest_status'];
			$account_ID = $row['account_ID'];
			$acct_Division = $row['acct_Division'];
			$acct_Section = $row['acct_Section'];

			$pr_datetimelastactionNew = date('Y-m-d', strtotime($pr_datetimelastaction));
			$closing_dateNew = date('Y-m-d', strtotime($closing_date));

			// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
			$date1=date_create($closing_dateNew);
			$date2=date_create($nowDateOnly);
			$diff=date_diff($date1,$date2);

			// var_dump($pr_datetimelastactionNew);
			// var_dump($nowDateOnly);

			// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
			// var_dump($diff);
			// var_dump($diff->format("%R%a days"));
			// die();

			$numberOfDaysDiff = $diff->format("%R%a days");

			// var_dump($numberOfDaysDiff);
			// die();

			if($pr_rfqDuration == 10){
				if($numberOfDaysDiff == 0){
					echo '<tr id="row'.$i.'" class="due">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else if(($numberOfDaysDiff < 4) && ($numberOfDaysDiff > 0)){
					echo '<tr id="row'.$i.'" class="neardue">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else{
					echo '<tr id="row'.$i.'" class="healthy">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';	
				}
			}
			else if($pr_rfqDuration == 7){
				if($numberOfDaysDiff == 0){
					echo '<tr id="row'.$i.'" class="due">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else if(($numberOfDaysDiff < 4) && ($numberOfDaysDiff > 0)){
					echo '<tr id="row'.$i.'" class="neardue">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else{
					echo '<tr id="row'.$i.'" class="healthy">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';	
				}
			}
			else if($pr_rfqDuration == 5){
				if($numberOfDaysDiff == 0){
					echo '<tr id="row'.$i.'" class="due">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else if(($numberOfDaysDiff < 3) && ($numberOfDaysDiff > 0)){
					echo '<tr id="row'.$i.'" class="neardue">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else{
					echo '<tr id="row'.$i.'" class="healthy">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';	
				}
			}
			else if($pr_rfqDuration == 3){
				if($numberOfDaysDiff == 0){
					echo '<tr id="row'.$i.'" class="due">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else if(($numberOfDaysDiff < 2) && ($numberOfDaysDiff > 0)){
					echo '<tr id="row'.$i.'" class="neardue">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else{
					echo '<tr id="row'.$i.'" class="healthy">
					<td>'.$purchrequest_ID.'</td>
			                            <td>'.$purchrequest_No.'</td>
			                            <td>'.$prquotation_no.'</td>
			                            <td><a href="../pages/print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a id="lnkCreateRFQ" href="#tblRFQform" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Offer</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';	
				}
			}

			


		    					

			$i++;
		}
	}

?>