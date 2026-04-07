<?php
require_once("../DatabaseConnection/databaseconnection.php");

// echo "string test";

if(isset($_POST['aocNo'])){

	$newStatus = "YES";
	$newaocStatus = "Chosen Supplier";
	$newPRStatus = "For PO";

	$query2 = "SELECT aoc_ID FROM tbl_aoc WHERE aoc_number = '". $_POST['aocNo'] ."'";
	$result2 = mysqli_query($conProcedural,$query2);

	while ($row2 = mysqli_fetch_assoc($result2)) {
		$aocID = $row2['aoc_ID'];
	}

	for ($i=0; $i < count($_POST['itemNum']); $i++) { 
		// $arrayweight = $_POST['txtweight'.$i];

		
		// var_dump($_POST['suppliers'][$i]);
		// var_dump($_POST['itemNum'][$i]);
		$txtSupplier = $_POST['suppliers'][$i];
		$txtItem = $_POST['itemNum'][$i];

		// $query = "UPDATE tblaoc_details SET chosenSupplier='".$newStatus."', chosenDate=NOW() WHERE supplierName='". $txtSupplier ."' AND itemNo='". $txtItem ."'";

		// // var_dump($query);

		// $result = mysqli_query($conProcedural,$query);
		if($txtSupplier == "0"){
			//skip the choosing process for this item
			//NO SUPPLIER CHOSEN FOR THIS ITEM
			// $notCompleteSupplier = "incomplete";
		}
		else{
			$stmt = $con->prepare("UPDATE tblaoc_details SET chosenSupplier=:newchosenStatus, chosenDate=NOW() WHERE supplierName=:suppName AND itemNo=:itemNumber AND aoc_ID=:aocID");

			$stmt->bindParam(':newchosenStatus', $newStatus);
			$stmt->bindParam(':suppName', $txtSupplier);
			$stmt->bindParam(':itemNumber', $txtItem);
			$stmt->bindParam(':aocID', $aocID);

			if($stmt->execute()){
				if($i == count($_POST['itemNum']) - 1){
					$updateSuccess = "SUCCESS";
				}
				else{
					//do nothing
				}
			}
			else{
				$output = "ERROR: Choosing Supplier: <strong>". $txtSupplier ."</strong> for Item No. <strong>". $txtItem ."</strong> failed in the database";
				echo $output;
			}
		}
	}

	if($updateSuccess == "SUCCESS"){
		$stmt = $con->prepare("UPDATE tbl_aoc SET aoc_status=:newaocStatus, aoc_chosensupplier=:chosenSupplier, aoc_deliveryterm=:aoc_deliveryterm, aoc_paymentterm=:aoc_paymentterm, aoc_placeofdelivery=:aoc_placeofdelivery WHERE aoc_number=:aocNum");

		$stmt->bindParam(':newaocStatus', $newaocStatus);
		$stmt->bindParam(':chosenSupplier', $txtSupplier);
		$stmt->bindParam(':aoc_deliveryterm', $_POST['deliveryTerm']);
		$stmt->bindParam(':aoc_paymentterm', $_POST['paymentTerm']);
		$stmt->bindParam(':aoc_placeofdelivery', $_POST['deliveryPlace']);
		$stmt->bindParam(':aocNum', $_POST['aocNo']);

		if($stmt->execute()){
			$stmt4 = $con->prepare("UPDATE tbl_purchaserequest SET pr_datetimelastaction=NOW(), pr_lastprocessedby=:newLastProcessedBy, purchrequest_status=:newPRStatus WHERE aoc_no=:aoc_no");

			$stmt4->bindParam(':newLastProcessedBy', $_SESSION['Name']);
			$stmt4->bindParam(':newPRStatus', $newPRStatus);
			$stmt4->bindParam(':aoc_no', $_POST['aocNo']);

			// $_SESSION['successEsig'] = true;

			if($stmt4->execute()){
				echo "AOC No. <strong>". $_POST['aocNo'] ."</strong> has successfully awarded to the " . $txtSupplier;
			}
			else{
				$output = "ERROR: Updating PR New Status and Process failed!";
				echo $output;
			}
		}
		else{
			$output = "ERROR: Updating AOC New Status and Process failed!";
			echo $output;
		}
	}
	else{
		$output = "ERROR: Updating all items to their designated Supplier failed in the database";
		echo $output;
	}

	// var_dump($txtSupplier);

	


	// if($stmt->execute()){
	// 	// $output = "SUCCESS";
	// 	echo "PR No. <strong>". $_POST['prNo'] ."</strong> has successfully been cancelled";		
	// }
	// else{
	// 	$output = "ERROR: Updating PR New Status and Process failed!";
	// 	echo $output;
	// }
	
}
else{
	//prompt error
	//no ID from the table selected
	$output = "ERROR: No AOC no. parameter";
	echo $output . " " . $_POST['aocNo'];
}
	


?>