<?php
require_once("../DatabaseConnection/databaseconnection.php");

// var_dump($_SESSION['AccountType']);
// die();

		// $query = "SELECT supplier_NatureOfBusiness FROM tbl_suppdetails WHERE supplierID=:supplierID";
		// $stmt = $con->prepare($query);
		// $stmt->bindValue(':supplierID', $_SESSION['suppID'], PDO::PARAM_INT);
		// $stmt->execute();
		// $row = $stmt->fetch(PDO::FETCH_ASSOC);

		// if ($row) {
		//     $supplier_Categories[] = $row['supplier_NatureOfBusiness'];
		// }

		// echo $_SESSION['suppID'];

// $query = "SELECT purchrequest_ID, quotation_no, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_location = 'FAD-PPGSS' AND purchrequest_status = 'For RFQ' AND quotation_no != 'Not Yet Assigned' ORDER BY pr_datetimelastaction DESC";
// $result = mysqli_query($conProcedural,$query);

	// $query = "SELECT po_ID, po_Number, po_Date, prNumber, pr_datetimelastaction, quotation_no FROM `tbl_po` INNER JOIN tbl_purchaserequest ON tbl_po.po_Number = tbl_purchaserequest.po_no WHERE po_Supplier = '". $_SESSION['suppName'] ."' ORDER BY po_ID ASC";

	$query = "SELECT po_ID, po_Number, po_Date, po_conforme, prNumber, pr_datetimelastaction, tbl_purchaserequest.quotation_no, rfq_ID FROM ((`tbl_po` INNER JOIN tbl_purchaserequest ON tbl_po.po_Number = tbl_purchaserequest.po_no) INNER JOIN tbl_rfq ON tbl_purchaserequest.quotation_no = tbl_rfq.rfq_No) WHERE po_Supplier = '". $_SESSION['suppName'] ."' AND rfq_Company = '". $_SESSION['suppName'] ."' ORDER BY po_ID ASC";

	
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();
	if (mysqli_num_rows($result)==0){ 
		echo '<tr><td colspan="9" style="text-align: center;"><b><h3>NO PO HAS BEEN AWARDED TO YOUR COMPANY YET</h3><b></td></tr>';
	}
	else{
		$i = 1;

		while ($row = mysqli_fetch_assoc($result)) {
			$po_ID = $row['po_ID'];
			$po_Number = $row['po_Number'];
			$po_Date = $row['po_Date'];
			$po_conforme = $row['po_conforme'];
			$prNumber = $row['prNumber'];
			$quotation_no = $row['quotation_no'];
			$rfq_ID = $row['rfq_ID'];
			$pr_datetimelastaction = $row['pr_datetimelastaction'];
			
			// $purchrequest_DateTime = $row['purchrequest_DateTime'];
			// $purchrequest_location = $row['purchrequest_location'];
			// $pr_lastprocessedby = $row['pr_lastprocessedby'];
			// $purchrequest_status = $row['purchrequest_status'];
			// $account_ID = $row['account_ID'];
			// $acct_Division = $row['acct_Division'];
			// $acct_Section = $row['acct_Section'];

			$pr_datetimelastactionNew = date('Y-m-d', strtotime($pr_datetimelastaction));

			// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
			$date1=date_create($pr_datetimelastactionNew);
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

			if($numberOfDaysDiff > 7){
				if($po_conforme){
					echo '<tr id="row'.$i.'" class="overdue">
					<td>'.$po_ID.'</td>
			                            <td>'.$prNumber.'</td>
			                            <td>'.$quotation_no.'</td>
			                            <td>'.$po_Number.'</td>
			                            <td>'.$po_Date.'</td>
			                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$prNumber.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view</b></a></td>
			                            <td><a href="../pages/print_po_pdf.php?ID='.$po_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to View PO</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else{
					echo '<tr id="row'.$i.'" class="overdue">
					<td>'.$po_ID.'</td>
			                            <td>'.$prNumber.'</td>
			                            <td>'.$quotation_no.'</td>
			                            <td>'.$po_Number.'</td>
			                            <td>'.$po_Date.'</td>
			                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$prNumber.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view</b></a></td>
			                            <td><a href="../pages/print_po_pdf.php?ID='.$po_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to View PO</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center text-center" id="lnkProceed" data-toggle="modal" href="#modalConforme"><b>CONFORME <br> (click to affix e-signature)</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
			}
			else if(($numberOfDaysDiff < 8) && ($numberOfDaysDiff > 5)){
				if($po_conforme){
					echo '<tr id="row'.$i.'" class="due">
					<td>'.$po_ID.'</td>
			                            <td>'.$prNumber.'</td>
			                            <td>'.$quotation_no.'</td>
			                            <td>'.$po_Number.'</td>
			                            <td>'.$po_Date.'</td>
			                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$prNumber.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view</b></a></td>
			                            <td><a href="../pages/print_po_pdf.php?ID='.$po_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to View PO</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else{
					echo '<tr id="row'.$i.'" class="due">
					<td>'.$po_ID.'</td>
			                            <td>'.$prNumber.'</td>
			                            <td>'.$quotation_no.'</td>
			                            <td>'.$po_Number.'</td>
			                            <td>'.$po_Date.'</td>
			                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$prNumber.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view</b></a></td>
			                            <td><a href="../pages/print_po_pdf.php?ID='.$po_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to View PO</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center text-center" id="lnkProceed" data-toggle="modal" href="#modalConforme"><b>CONFORME <br> (click to affix e-signature)</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
			}
			else if(($numberOfDaysDiff < 6) && ($numberOfDaysDiff > 4)){
				if($po_conforme){
					echo '<tr id="row'.$i.'" class="neardue">
					<td>'.$po_ID.'</td>
			                            <td>'.$prNumber.'</td>
			                            <td>'.$quotation_no.'</td>
			                            <td>'.$po_Number.'</td>
			                            <td>'.$po_Date.'</td>
			                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$prNumber.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view</b></a></td>
			                            <td><a href="../pages/print_po_pdf.php?ID='.$po_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to View PO</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else{
					echo '<tr id="row'.$i.'" class="neardue">
					<td>'.$po_ID.'</td>
			                            <td>'.$prNumber.'</td>
			                            <td>'.$quotation_no.'</td>
			                            <td>'.$po_Number.'</td>
			                            <td>'.$po_Date.'</td>
			                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$prNumber.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view</b></a></td>
			                            <td><a href="../pages/print_po_pdf.php?ID='.$po_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to View PO</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center text-center" id="lnkProceed" data-toggle="modal" href="#modalConforme"><b>CONFORME <br> (click to affix e-signature)</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
			}
			else{
				if($po_conforme){
					echo '<tr id="row'.$i.'" class="healthy">
					<td>'.$po_ID.'</td>
			                            <td>'.$prNumber.'</td>
			                            <td>'.$quotation_no.'</td>
			                            <td>'.$po_Number.'</td>
			                            <td>'.$po_Date.'</td>
			                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$prNumber.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view</b></a></td>
			                            <td><a href="../pages/print_po_pdf.php?ID='.$po_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to View PO</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
				else{
					echo '<tr id="row'.$i.'" class="healthy">
					<td>'.$po_ID.'</td>
			                            <td>'.$prNumber.'</td>
			                            <td>'.$quotation_no.'</td>
			                            <td>'.$po_Number.'</td>
			                            <td>'.$po_Date.'</td>
			                            <td><a href="../pages/print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$prNumber.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to view</b></a></td>
			                            <td><a href="../pages/print_po_pdf.php?ID='.$po_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Click this to View PO</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                            <td><a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center text-center" id="lnkProceed" data-toggle="modal" href="#modalConforme"><b>CONFORME <br> (click to affix e-signature)</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
			                        </tr>';
				}
			}


		    					

			$i++;
		}
	}

?>