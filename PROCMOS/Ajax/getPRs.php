<?php
require_once("../DatabaseConnection/databaseconnection.php");

$query = "SELECT purchrequest_ID, purchrequest_No, prNo_dtd, purchrequest_DateTime, purchrequest_charging, purchrequest_location, pr_datetimelastaction, pr_lastprocessedby, purchrequest_status, tbl_accounts.account_ID, acct_Division, acct_Section, acct_Project FROM ((`tbl_purchaserequest` INNER JOIN tbl_accounts ON tbl_purchaserequest.account_ID = tbl_accounts.account_ID) INNER JOIN tbl_acctdetails ON tbl_accounts.account_ID = tbl_acctdetails.account_ID) ORDER BY purchrequest_ID DESC"; //WHERE purchrequest_requestor = ".$_SESSION['account_ID']." OR purchrequest_location = 'Office of the Director'
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
			echo '<tr id="row'.$i.'" class="overdue">
			<td>'.$purchrequest_DateTime.'</td>
                     <td>'.$purchrequest_No.'</td>




                     <td>Not Yet Assigned</td>
                     <td>Not Yet Assigned</td>
                     <td>'.$purchrequest_location.'</td>
                     <td>'.$acct_Project.'</td>
                     <td>'.$purchrequest_status.'</td>
	                        </tr>';
		}
		else if(($numberOfDaysDiff < 8) && ($numberOfDaysDiff > 5)){
			echo '<tr id="row'.$i.'" class="due">
			<td>'.$purchrequest_DateTime.'</td>
                     <td>'.$purchrequest_No.'</td>




                     <td>Not Yet Assigned</td>
                     <td>Not Yet Assigned</td>
                     <td>'.$purchrequest_location.'</td>
                     <td>'.$acct_Project.'</td>
                     <td>'.$purchrequest_status.'</td>
	                        </tr>';
		}
		else if(($numberOfDaysDiff < 6) && ($numberOfDaysDiff > 4)){
			echo '<tr id="row'.$i.'" class="neardue">
			<td>'.$purchrequest_DateTime.'</td>
                     <td>'.$purchrequest_No.'</td>




                     <td>Not Yet Assigned</td>
                     <td>Not Yet Assigned</td>
                     <td>'.$purchrequest_location.'</td>
                     <td>'.$acct_Project.'</td>
                     <td>'.$purchrequest_status.'</td>
	                        </tr>';
		}
		else{
			echo '<tr id="row'.$i.'" class="healthy">
			<td>'.$purchrequest_DateTime.'</td>
                     <td>'.$purchrequest_No.'</td>




                     <td>Not Yet Assigned</td>
                     <td>Not Yet Assigned</td>
                     <td>'.$purchrequest_location.'</td>
                     <td>'.$acct_Project.'</td>
                     <td>'.$purchrequest_status.'</td>
	                        </tr>';	
		}


	    					

		$i++;
	}

?>