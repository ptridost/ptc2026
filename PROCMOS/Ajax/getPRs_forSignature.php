<?php
require_once("../DatabaseConnection/databaseconnection.php");

if($_SESSION['AccountType'] == "End-User"){
	
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE tbl_accounts.account_ID = ".$_SESSION['account_ID']." OR acct_Project LIKE '%".$_SESSION['Project']."%' ORDER BY purchrequest_ID DESC";
	$result = mysqli_query($conProcedural,$query);	

    $i = 1;
    
    while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_ID = $row['purchrequest_ID'];
		$purchrequest_No = $row['purchrequest_No'];
		$prNo_dtd = $row['prNo_dtd'];
		$purchrequest_DateTime = $row['purchrequest_DateTime'];
		$purchrequest_location = $row['purchrequest_location'];
		$pr_datetimelastaction = $row['pr_datetimelastaction'];
		$pr_lastprocessedby = $row['pr_lastprocessedby'];
		$purchrequest_status = $row['purchrequest_status'];
		$account_ID = $row['account_ID'];
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
		$acct_Project = $row['purchrequest_charging'];

        echo '<tr id="row'.$i.'">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
	}

}
else if($_SESSION['AccountType'] == "PPGSS"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, date_prdivisionheadesig, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'FAD-PPGSS') AND (purchrequest_status = 'For Clearance (PPGSS)' OR purchrequest_status = 'For Signature (Requestor/Project Leader)' OR purchrequest_status = 'For PR number') ORDER BY purchrequest_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	$i = 1;
    
    while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_ID = $row['purchrequest_ID'];
		$purchrequest_No = $row['purchrequest_No'];
		$prNo_dtd = $row['prNo_dtd'];
		$purchrequest_DateTime = $row['purchrequest_DateTime'];
		$purchrequest_location = $row['purchrequest_location'];
		$pr_datetimelastaction = $row['pr_datetimelastaction'];
		$pr_lastprocessedby = $row['pr_lastprocessedby'];
		$purchrequest_status = $row['purchrequest_status'];
		$account_ID = $row['account_ID'];
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
		$acct_Project = $row['purchrequest_charging'];

		$date_prdivisionheadesig = $row['date_prdivisionheadesig'];
		$date_prdivisionheadesigNew = date('Y-m-d', strtotime($date_prdivisionheadesig));
		// $closing_dateNew = date('Y-m-d', strtotime($closing_date));

		// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
		$date1=date_create($date_prdivisionheadesigNew);
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

		if($numberOfDaysDiff == 0){
			echo '<tr id="row'.$i.'" class="due">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';

		}
		else if(($numberOfDaysDiff < 3) && ($numberOfDaysDiff > 0)){
			echo '<tr id="row'.$i.'" class="neardue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else if($numberOfDaysDiff > 5){
			echo '<tr id="row'.$i.'" class="overdue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else{
			echo '<tr id="row'.$i.'" class="healthy">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}

        // echo '<tr id="row'.$i.'">
        //                      <td>'.$purchrequest_ID.'</td>
        //                      <td>'.$purchrequest_No.'</td>
        //                      <td>'.$purchrequest_DateTime.'</td>
        //                      <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




        //                      <td>'.$purchrequest_location.'</td>
        //                      <td>'.$pr_datetimelastaction.'</td>
        //                      <td>'.$pr_lastprocessedby.'</td>
        //                      <td>'.$acct_Project.'</td>
        //                      <td>'.$purchrequest_status.'</td>
        //                      <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
        //                  </tr>';
	}
}

else if($_SESSION['AccountType'] == "PPGSS-PPMP"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, date_prdivisionheadesig, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE ((purchrequest_requestor = ".$_SESSION['account_ID']." 
		OR purchrequest_location = 'FAD-PPGSS'
		)
		AND (`purchrequest_status` = 'For Clearance (PPMP)'))
		ORDER BY purchrequest_ID DESC";
	$result = mysqli_query($conProcedural,$query);


	// var_dump($query);
	// die();

	$i = 1;
    
    while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_ID = $row['purchrequest_ID'];
		$purchrequest_No = $row['purchrequest_No'];
		$prNo_dtd = $row['prNo_dtd'];
		$purchrequest_DateTime = $row['purchrequest_DateTime'];
		$purchrequest_location = $row['purchrequest_location'];
		$pr_datetimelastaction = $row['pr_datetimelastaction'];
		$pr_lastprocessedby = $row['pr_lastprocessedby'];
		$purchrequest_status = $row['purchrequest_status'];
		$account_ID = $row['account_ID'];
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
		$acct_Project = $row['purchrequest_charging'];

		$date_prdivisionheadesig = $row['date_prdivisionheadesig'];
		$date_prdivisionheadesigNew = date('Y-m-d', strtotime($date_prdivisionheadesig));
		// $closing_dateNew = date('Y-m-d', strtotime($closing_date));

		// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
		$date1=date_create($date_prdivisionheadesigNew);
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

		if($numberOfDaysDiff == 0){
			echo '<tr id="row'.$i.'" class="due">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';

		}
		else if(($numberOfDaysDiff < 3) && ($numberOfDaysDiff > 0)){
			echo '<tr id="row'.$i.'" class="neardue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else if($numberOfDaysDiff > 5){
			echo '<tr id="row'.$i.'" class="overdue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else{
			echo '<tr id="row'.$i.'" class="healthy">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
	}
}

// else if($_SESSION['AccountType'] == "PPGSS-GAA"){
// 	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'FAD-PPGSS' AND `purchrequest_charging` LIKE '%GAA%' ORDER BY purchrequest_ID DESC";
// 	$result = mysqli_query($conProcedural,$query);

// 	$i = 1;
    
//     while ($row = mysqli_fetch_assoc($result)) {
// 		$purchrequest_ID = $row['purchrequest_ID'];
// 		$purchrequest_No = $row['purchrequest_No'];
// 		$prNo_dtd = $row['prNo_dtd'];
// 		$purchrequest_DateTime = $row['purchrequest_DateTime'];
// 		$purchrequest_location = $row['purchrequest_location'];
// 		$pr_datetimelastaction = $row['pr_datetimelastaction'];
// 		$pr_lastprocessedby = $row['pr_lastprocessedby'];
// 		$purchrequest_status = $row['purchrequest_status'];
// 		$account_ID = $row['account_ID'];
// 		$acct_Division = $row['acct_Division'];
// 		$acct_Section = $row['acct_Section'];
// 		$acct_Project = $row['purchrequest_charging'];

//         echo '<tr id="row'.$i.'">
//                              <td>'.$purchrequest_ID.'</td>
//                              <td>'.$purchrequest_No.'</td>
//                              <td>'.$purchrequest_DateTime.'</td>
//                              <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




//                              <td>'.$purchrequest_location.'</td>
//                              <td>'.$pr_datetimelastaction.'</td>
//                              <td>'.$pr_lastprocessedby.'</td>
//                              <td>'.$acct_Project.'</td>
//                              <td>'.$purchrequest_status.'</td>
//                              <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
//                          </tr>';
// 	}
// }

// else if($_SESSION['AccountType'] == "PPGSS-GIA"){
// 	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'FAD-PPGSS' AND `purchrequest_charging` LIKE '%GIA%' ORDER BY purchrequest_ID DESC";
// 	$result = mysqli_query($conProcedural,$query);

// 	$i = 1;
    
//     while ($row = mysqli_fetch_assoc($result)) {
// 		$purchrequest_ID = $row['purchrequest_ID'];
// 		$purchrequest_No = $row['purchrequest_No'];
// 		$prNo_dtd = $row['prNo_dtd'];
// 		$purchrequest_DateTime = $row['purchrequest_DateTime'];
// 		$purchrequest_location = $row['purchrequest_location'];
// 		$pr_datetimelastaction = $row['pr_datetimelastaction'];
// 		$pr_lastprocessedby = $row['pr_lastprocessedby'];
// 		$purchrequest_status = $row['purchrequest_status'];
// 		$account_ID = $row['account_ID'];
// 		$acct_Division = $row['acct_Division'];
// 		$acct_Section = $row['acct_Section'];
// 		$acct_Project = $row['purchrequest_charging'];

//         echo '<tr id="row'.$i.'">
//                              <td>'.$purchrequest_ID.'</td>
//                              <td>'.$purchrequest_No.'</td>
//                              <td>'.$purchrequest_DateTime.'</td>
//                              <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




//                              <td>'.$purchrequest_location.'</td>
//                              <td>'.$pr_datetimelastaction.'</td>
//                              <td>'.$pr_lastprocessedby.'</td>
//                              <td>'.$acct_Project.'</td>
//                              <td>'.$purchrequest_status.'</td>
//                              <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
//                          </tr>';
// 	}
// }

else if($_SESSION['AccountType'] == "Chief, FAD"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, date_prdivisionheadesig, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (
		(purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Chief, FAD')
		) ORDER BY purchrequest_ID DESC";
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	$i = 1;
    
    while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_ID = $row['purchrequest_ID'];
		$purchrequest_No = $row['purchrequest_No'];
		$prNo_dtd = $row['prNo_dtd'];
		$purchrequest_DateTime = $row['purchrequest_DateTime'];
		$purchrequest_location = $row['purchrequest_location'];
		$pr_datetimelastaction = $row['pr_datetimelastaction'];
		$pr_lastprocessedby = $row['pr_lastprocessedby'];
		$purchrequest_status = $row['purchrequest_status'];
		$account_ID = $row['account_ID'];
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
		$acct_Project = $row['purchrequest_charging'];

		$date_prdivisionheadesig = $row['date_prdivisionheadesig'];
		$date_prdivisionheadesigNew = date('Y-m-d', strtotime($date_prdivisionheadesig));
		// $closing_dateNew = date('Y-m-d', strtotime($closing_date));

		// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
		$date1=date_create($date_prdivisionheadesigNew);
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

		if($numberOfDaysDiff == 0){
			echo '<tr id="row'.$i.'" class="due">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';

		}
		else if(($numberOfDaysDiff < 3) && ($numberOfDaysDiff > 0)){
			echo '<tr id="row'.$i.'" class="neardue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else if($numberOfDaysDiff > 5){
			echo '<tr id="row'.$i.'" class="overdue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else{
			echo '<tr id="row'.$i.'" class="healthy">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}

        // echo '<tr id="row'.$i.'">
        //                      <td>'.$purchrequest_ID.'</td>
        //                      <td>'.$purchrequest_No.'</td>
        //                      <td>'.$purchrequest_DateTime.'</td>
        //                      <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




        //                      <td>'.$purchrequest_location.'</td>
        //                      <td>'.$pr_datetimelastaction.'</td>
        //                      <td>'.$pr_lastprocessedby.'</td>
        //                      <td>'.$acct_Project.'</td>
        //                      <td>'.$purchrequest_status.'</td>
        //                      <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
        //                  </tr>';
	}
}
else if($_SESSION['AccountType'] == "Director"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, date_prdivisionheadesig, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE (purchrequest_status = 'For Signature (Director)') ORDER BY purchrequest_ID DESC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
	$result = mysqli_query($conProcedural,$query);

	// var_dump($query);
	// die();

	$i = 1;
    
    while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_ID = $row['purchrequest_ID'];
		$purchrequest_No = $row['purchrequest_No'];
		$prNo_dtd = $row['prNo_dtd'];
		$purchrequest_DateTime = $row['purchrequest_DateTime'];
		$purchrequest_location = $row['purchrequest_location'];
		$pr_datetimelastaction = $row['pr_datetimelastaction'];
		$pr_lastprocessedby = $row['pr_lastprocessedby'];
		$purchrequest_status = $row['purchrequest_status'];
		$account_ID = $row['account_ID'];
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
		$acct_Project = $row['purchrequest_charging'];

		$date_prdivisionheadesig = $row['date_prdivisionheadesig'];
		$date_prdivisionheadesigNew = date('Y-m-d', strtotime($date_prdivisionheadesig));
		// $closing_dateNew = date('Y-m-d', strtotime($closing_date));

		// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
		$date1=date_create($date_prdivisionheadesigNew);
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

		if($numberOfDaysDiff == 0){
			echo '<tr id="row'.$i.'" class="due">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';

		}
		else if(($numberOfDaysDiff < 3) && ($numberOfDaysDiff > 0)){
			echo '<tr id="row'.$i.'" class="neardue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else if($numberOfDaysDiff > 5){
			echo '<tr id="row'.$i.'" class="overdue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else{
			echo '<tr id="row'.$i.'" class="healthy">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}

        // echo '<tr id="row'.$i.'">
        //                      <td>'.$purchrequest_ID.'</td>
        //                      <td>'.$purchrequest_No.'</td>
        //                      <td>'.$purchrequest_DateTime.'</td>
        //                      <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




        //                      <td>'.$purchrequest_location.'</td>
        //                      <td>'.$pr_datetimelastaction.'</td>
        //                      <td>'.$pr_lastprocessedby.'</td>
        //                      <td>'.$acct_Project.'</td>
        //                      <td>'.$purchrequest_status.'</td>
        //                      <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
        //                  </tr>';
	}
}
else if($_SESSION['AccountType'] == "Sec, Director"){
	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, date_prdivisionheadesig, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE 
		(purchrequest_status = 'For Signature Sec (Director)') ORDER BY purchrequest_ID DESC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
	$result = mysqli_query($conProcedural,$query);

	$i = 1;

	// var_dump($query);
	// die();
    
    while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_ID = $row['purchrequest_ID'];
		$purchrequest_No = $row['purchrequest_No'];
		$prNo_dtd = $row['prNo_dtd'];
		$purchrequest_DateTime = $row['purchrequest_DateTime'];
		$purchrequest_location = $row['purchrequest_location'];
		$pr_datetimelastaction = $row['pr_datetimelastaction'];
		$pr_lastprocessedby = $row['pr_lastprocessedby'];
		$purchrequest_status = $row['purchrequest_status'];
		$account_ID = $row['account_ID'];
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
		$acct_Project = $row['purchrequest_charging'];

		$date_prdivisionheadesig = $row['date_prdivisionheadesig'];
		$date_prdivisionheadesigNew = date('Y-m-d', strtotime($date_prdivisionheadesig));
		// $closing_dateNew = date('Y-m-d', strtotime($closing_date));

		// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
		$date1=date_create($date_prdivisionheadesigNew);
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

		if($numberOfDaysDiff == 0){
			echo '<tr id="row'.$i.'" class="due">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';

		}
		else if(($numberOfDaysDiff < 3) && ($numberOfDaysDiff > 0)){
			echo '<tr id="row'.$i.'" class="neardue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else if($numberOfDaysDiff > 5){
			echo '<tr id="row'.$i.'" class="overdue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else{
			echo '<tr id="row'.$i.'" class="healthy">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}

        // echo '<tr id="row'.$i.'">
        //                      <td>'.$purchrequest_ID.'</td>
        //                      <td>'.$purchrequest_No.'</td>
        //                      <td>'.$purchrequest_DateTime.'</td>
        //                      <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




        //                      <td>'.$purchrequest_location.'</td>
        //                      <td>'.$pr_datetimelastaction.'</td>
        //                      <td>'.$pr_lastprocessedby.'</td>
        //                      <td>'.$acct_Project.'</td>
        //                      <td>'.$purchrequest_status.'</td>
        //                      <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
        //                  </tr>';
	}
}
else if($_SESSION['AccountType'] == "Chief, RDD"){

	require_once("../accessory/validate_DivisionSection.php");

	$location = $ptridivision . "-" . $ptrisection;


	// var_dump($location);
	// die();

	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE ((purchrequest_requestor = ".$_SESSION['account_ID']." AND purchrequest_status = 'For Signature (Requestor/Project Leader)') OR (purchrequest_location = '".$location."' AND purchrequest_status = 'For Signature (Division Chief)')) ORDER BY purchrequest_ID DESC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
	$result = mysqli_query($conProcedural,$query);

	$i = 1;
    
    while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_ID = $row['purchrequest_ID'];
		$purchrequest_No = $row['purchrequest_No'];
		$prNo_dtd = $row['prNo_dtd'];
		$purchrequest_DateTime = $row['purchrequest_DateTime'];
		$purchrequest_location = $row['purchrequest_location'];
		$pr_datetimelastaction = $row['pr_datetimelastaction'];
		$pr_lastprocessedby = $row['pr_lastprocessedby'];
		$purchrequest_status = $row['purchrequest_status'];
		$account_ID = $row['account_ID'];
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
		$acct_Project = $row['purchrequest_charging'];

        echo '<tr id="row'.$i.'">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
	}
}
else if($_SESSION['AccountType'] == "Chief, TSD"){

	require_once("../accessory/validate_DivisionSection.php");

	$location = $ptridivision . "-" . $ptrisection;

	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE ((purchrequest_requestor = ".$_SESSION['account_ID']." AND purchrequest_status = 'For Signature (Requestor/Project Leader)') OR (purchrequest_location = '".$location."' AND purchrequest_status = 'For Clearance (Division Chief)')) ORDER BY purchrequest_ID DESC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
	$result = mysqli_query($conProcedural,$query);

	$i = 1;
    
    while ($row = mysqli_fetch_assoc($result)) {
		$purchrequest_ID = $row['purchrequest_ID'];
		$purchrequest_No = $row['purchrequest_No'];
		$prNo_dtd = $row['prNo_dtd'];
		$purchrequest_DateTime = $row['purchrequest_DateTime'];
		$purchrequest_location = $row['purchrequest_location'];
		$pr_datetimelastaction = $row['pr_datetimelastaction'];
		$pr_lastprocessedby = $row['pr_lastprocessedby'];
		$purchrequest_status = $row['purchrequest_status'];
		$account_ID = $row['account_ID'];
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
		$acct_Project = $row['purchrequest_charging'];

        echo '<tr id="row'.$i.'">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
	}
}
else{

	require_once("../accessory/validate_DivisionSection.php");

	$location = $ptridivision . "-" . $ptrisection;

	// var_dump($location);
	// die();

	$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, date_prdivisionheadesig, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = '". $location ."' ORDER BY purchrequest_ID DESC";

	

	$result = mysqli_query($conProcedural,$query);

	


	$i = 1;
    
    while ($row = mysqli_fetch_assoc($result)) {
	
		$purchrequest_ID = $row['purchrequest_ID'];
		$purchrequest_No = $row['purchrequest_No'];
		$prNo_dtd = $row['prNo_dtd'];
		$purchrequest_DateTime = $row['purchrequest_DateTime'];
		$purchrequest_location = $row['purchrequest_location'];
		$pr_datetimelastaction = $row['pr_datetimelastaction'];
		$pr_lastprocessedby = $row['pr_lastprocessedby'];
		$purchrequest_status = $row['purchrequest_status'];
		$account_ID = $row['account_ID'];
		$acct_Division = $row['acct_Division'];
		$acct_Section = $row['acct_Section'];
		$acct_Project = $row['purchrequest_charging'];


		$date_prdivisionheadesig = $row['date_prdivisionheadesig'];
		$date_prdivisionheadesigNew = date('Y-m-d', strtotime($date_prdivisionheadesig));
		// $closing_dateNew = date('Y-m-d', strtotime($closing_date));

		// $diff=date_diff($nowDateOnly,$pr_datetimelastactionNew);
		$date1=date_create($date_prdivisionheadesigNew);
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

		if($numberOfDaysDiff == 0){
			echo '<tr id="row'.$i.'" class="due">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';

		}
		else if(($numberOfDaysDiff < 3) && ($numberOfDaysDiff > 0)){
			echo '<tr id="row'.$i.'" class="neardue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else if($numberOfDaysDiff > 5){
			echo '<tr id="row'.$i.'" class="overdue">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
		else{
			echo '<tr id="row'.$i.'" class="healthy">
                             <td>'.$purchrequest_ID.'</td>
                             <td>'.$purchrequest_No.'</td>
                             <td>'.$purchrequest_DateTime.'</td>
                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                             <td>'.$purchrequest_location.'</td>
                             <td>'.$pr_datetimelastaction.'</td>
                             <td>'.$pr_lastprocessedby.'</td>
                             <td>'.$acct_Project.'</td>
                             <td>'.$purchrequest_status.'</td>
                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
                         </tr>';
		}
 //        echo '<tr id="row'.$i.'">
 //                             <td>'.$purchrequest_ID.'</td>
 //                             <td>'.$purchrequest_No.'</td>
 //                             <td>'.$purchrequest_DateTime.'</td>
 //                             <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID.'&acctID='.$account_ID.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




 //                             <td>'.$purchrequest_location.'</td>
 //                             <td>'.$pr_datetimelastaction.'</td>
 //                             <td>'.$pr_lastprocessedby.'</td>
 //                             <td>'.$acct_Project.'</td>
 //                             <td>'.$purchrequest_status.'</td>
 //                             <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a><a id="lnkView" data-toggle="modal" href="#modalView" class="btn formsbutton" title="View Remarks"><i class="fas fa-eye"></i> </a></td>
 //                         </tr>';
	}
}


?>