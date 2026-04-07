<?php
require_once("../DatabaseConnection/databaseconnection.php");

if($_SESSION['AccountType'] == "PPGSS-AOC"){
	$query = "SELECT purchrequest_ID, aoc_no, quotation_no, ictclearance_No, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (purchrequest_location = 'FAD-PPGSS' AND purchrequest_status = 'For PO') ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	

	
}
else{
	$query = "SELECT purchrequest_ID, quotation_no, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_status != 'For AOC' ORDER BY purchrequest_ID ASC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();
}

$i = 1;

while ($row = mysqli_fetch_assoc($result)) {
	$purchrequest_ID = $row['purchrequest_ID'];
	$purchrequest_No = $row['purchrequest_No'];
	$aoc_no = $row['aoc_no'];
	$prquotation_no = $row['quotation_no'];
	$prictclearance_no = $row['ictclearance_No'];
	$prNo_dtd = $row['prNo_dtd'];
	$purchrequest_DateTime = $row['purchrequest_DateTime'];
	$purchrequest_location = $row['purchrequest_location'];
	$pr_datetimelastaction = $row['pr_datetimelastaction'];
	$pr_lastprocessedby = $row['pr_lastprocessedby'];
	$purchrequest_status = $row['purchrequest_status'];
	$account_ID = $row['account_ID'];
	$acct_Division = $row['acct_Division'];
	$acct_Section = $row['acct_Section'];

	echo '<tr id="rowlist'.$i.'">
                            <td>'.$purchrequest_ID.'</td>
                            <td>'.$purchrequest_No.'</td>
                            <td>'.$prictclearance_no.'</td>
                            <td>'.$prquotation_no.'</td>
                            <td>'.$aoc_no.'</td>
                            <td>'.$purchrequest_DateTime.'</td>
                            <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>

                            <td>'.$purchrequest_location.'</td>
                            <td>'.$pr_datetimelastaction.'</td>
                            <td>'.$pr_lastprocessedby.'</td>
                            <td>'.$purchrequest_status.'</td>
                            <td><a id="lnkAttachPO" href="#lblPRFormNo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Purchase Order</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
                        </tr>';

    $i++;
}

?>