<?php
	require_once("../DatabaseConnection/databaseconnection.php");
	require_once("../operations/insertVisitors.php");
	require_once("../operations/insertVisitors.php");

	if(isset($_SESSION['Username'])){
		// $appointID = $_SESSION['ProceedID'];
		// require_once("../operations/verifyAddress.php");
		// require_once("../operations/getClient.php");
		// require_once("../operations/getCtourdetails.php");
		// // echo '<script type="text/javascript">alert("TEST");</script>';

		// if($appointmentService == "TIPS Plant Tour Services"){
		// 	//PROCEED
		// 	$serviceCRN = $appointmentService;
		// 	require_once("../operations/generateCRN.php");			
		// }
		// else{
		// 	echo '<script type="text/javascript">alert("FAILED");</script>';		
		// 	header("Location: index.php");
		// }
		// var_dump($_SESSION['Division']);
		// var_dump($_SESSION['Section']);
		// die();
		require_once("../operations/getRequestors.php");
		require_once("../operations/submit_purchaserequest.php");

	}
	else{
		echo '<script type="text/javascript">alert("ERROR! You need to log in first");</script>';
		// echo '<script type="text/javascript">alert('. $_SESSION['ProceedID']) .');</script>';
		// die();
		echo '<script type="text/javascript">window.location.href = "index.php";</script>';
	}	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Request Form</title>
	<link rel="icon" href="../photos/procurement.png" type="image/png" sizes="16x16">
	<link href="../vendor/css/style.css" rel="stylesheet">
	<link href="../vendor/css/bootstrap.min.css" rel="stylesheet">
	<link href="../vendor/css/jquery-ui.min.css" rel="stylesheet">
	<link href="../vendor/fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
	<link href="../vendor/css/animate.css" rel="stylesheet">
	<link href="../vendor/css/loading.css" rel="stylesheet">
	<link rel="stylesheet" href="../vendor/ckeditor5/ckeditor5.css">
	<script src="../vendor/js/jquery-3.4.1.min.js"></script>
	<script src="../vendor/js/bootstrap.min.js"></script>
	<script src="../vendor/js/jquery-ui.min.js"></script>

</head>

<body class="body_style">

	<?php require_once("../accessory/navbar.php");?>

		<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample08" aria-controls="		navbarsExample08" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample08">
				<ul class="navbar-nav">
					<li class="nav-item active">
						<a class="nav-link" href="#">Centered nav only <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Link</a>
					</li>
					<li class="nav-item">
						<a class="nav-link disabled" href="#">Disabled</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown08" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
					<div class="dropdown-menu" aria-labelledby="dropdown08">
						<a class="dropdown-item" href="#">Action</a>
						<a class="dropdown-item" href="#">Another action</a>
						<a class="dropdown-item" href="#">Something else here</a>
					</div>
					</li>
				</ul>
			</div>
		</nav> -->

		<!-- example 2 - using auto margins -->


		<br>

		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<center><h2>Purchase Request</h2></center>
				</div>
			</div>	
		</div>

		<br>

		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3">
					
				</div>
				<div class="col-lg-9">
					<div class="col-lg-3 offset-lg-10" style="">
						<label style="font-size: 0.8rem; text-align: right;">FAD-PPGSS Form No. 15 <br>Rev. 1/15-05-17</label>
					</div>	
				</div>
				<!-- <div class="col-sm-3 offset-sm-11" style="margin-top: -2%">
					<label style="font-size: 0.8rem; text-align: right;">FAD-PPGSS Form No. 15 <br>Rev. 1/15-05-17</label>
				</div> -->
			</div>
			<div class="row">
				<div class="col-lg-3">
					
				</div>
				<div class="col-lg-9">
					<div id="lblAsterisk" class="col-sm-12 animated flash proLong">
						<i class="fas fa-asterisk" style="color: #8d0101;"></i> <label style="color: #8d0101">All fields are required</label>
					</div>
				</div>
				<!-- <div id="lblAsterisk" class="col-sm-12 animated flash proLong">
					<i class="fas fa-asterisk" style="color: #8d0101;"></i> <label style="color: #8d0101">All fields are required except: Stock No.</label>
				</div> -->
			</div>
		</div>

		<div class="container-fluid">
			<div class="row" style="padding-top: 1%; padding-right: 1%;">
				<div class="col-lg-3">
					<?php require_once("../accessory/sidebar_actions.php");?>

				</div>
				<div class="col-lg-9">
					<form method="POST" action="purchase_request.php" id="" style="width: 100%">
						<div class="form-row">
							<div class="col-lg-6">



								<input type="text"  class="forms_textbox" name="txtNumberOfRows" 
								id="txtNumberOfRows" placeholder="ID" autocomplete="off" 
								style="display: none">


								<?php echo '<input type="text" class="form-control prtextboxes d-none" id="txtUsername" name="txtUsername" placeholder="Username" value="'.$_SESSION['Username'].'" autocomplete="off" required readonly>';?>









								<b><label for="txtDivision">PR NUMBER</label></b>
								<?php echo '<input type="text" class="form-control prtextboxes" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" value="(SYSTEM-GENERATED)" autocomplete="off" required readonly>';?>
								<!-- <input type="text" class="form-control prtextboxes" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
							</div>	
							<div class="col-sm-6">
								<b><label for="txtSAI">SAI NUMBER</label></b>
								<?php echo '<input type="text" class="form-control prtextboxes" id="txtSAI" name="txtSAI" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" value="(SYSTEM-GENERATED)" readonly>';?>
								<!-- <input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" required readonly> -->
							</div>
						</div>
						<div class="form-row mt-3">
							<div class="col-sm-6">















								<b><label for="txtDivision">DIVISION</label></b>
								<?php echo '<input type="text" class="form-control prtextboxes" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" value="'.$_SESSION['Division'].'" autocomplete="off" required readonly>';?>
								<!-- <input type="text" class="form-control prtextboxes" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
							</div>	
							<div class="col-sm-6">
								<b><label for="txtSection">SECTION</label></b>
								<?php 

										if($_SESSION['Username'] == "184"){
											echo '<div class="input-group mb-3">
  <input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" value="'.$_SESSION['Section'].'" required readonly>
  <div class="input-group-append">
    <a id="lnkChange" class="btn btn-warning" style="color: white;"><i class="fas fa-exchange-alt"></i></a>
  </div>
</div>
		  						</div>
							</div>';



										}
										else{
											echo '<input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" value="'.$_SESSION['Section'].'" required readonly>';
										}
										?>
								<!-- <input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" required readonly> -->
							</div>
						</div>

						<br>

						<table class="table table-responsive table-bordered" style="margin-top: 1%;">
							<thead>
								<tr class="">
									<!-- <th scope="col-sm-2">STOCK NO.</th>
									<th scope="col-sm-2">UNIT</th>
									<th scope="col-sm-6">ITEM DESCRIPTION</th>
									<th scope="col-sm-1">QTY</th>
									<th scope="col-sm-1">UNIT COST</th>
									<th scope="col-sm-1">TOTAL COST</th>
									<th id="thActions" scope="col-sm-1" style="">ACTION</th> -->
					                <th class="col-sm-1">STOCK NO.</th>
					                <th class="col-sm-1">UNIT</th>
					                <th class="col-sm-5">ITEM DESCRIPTION</th>
					                <th class="col-sm-1">QTY</th>
					                <th class="col-sm-2">UNIT COST</th>
					                <th class="col-sm-2">TOTAL COST</th>
					                <th class="col-sm-1">ACTION</th>
								</tr>
							</thead>
							<tbody>
								<tr id="row1">
									<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtstock1" 
									id="txtstock1" autocomplete="off" required style="width: 100%;"></td>
									<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty1" 
									id="txtUnitQty1" autocomplete="off" required style="width: 100%;"></td>
									<td><textarea rows="5" class="form-control forms_textbox txtCHKeditor" id="txtItem1" name="txtItem1" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>
									<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity1" 
									id="txtQuantity1" onkeyup="computeTotalFee(this); computeFinalTotalFee();" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>
									<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee1" 
									id="txtUnitFee1" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this); computeFinalTotalFee();" style="width: 100%;"></td>
									<td><input type="text"  class="form-control forms_textbox prtextboxes textboxTotal" name="txtTotal1" min="0"
									id="txtTotal1" autocomplete="off" value=0 required readonly style="width: 100%;"></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td style="visibility: hidden;"></td>
									<td style="visibility: hidden;"></td>
									<td style="visibility: hidden;"></td>
									<td style="visibility: hidden;"></td>
									<!-- <td><i id="btnAddRow" class="fas fa-plus-circle fa-2x" style="cursor: pointer;"></td> -->
									<td style="visibility: hidden;"></td>
									<td style="visibility: hidden;"></td>
									<td><i id="btnAddRow" class="fas fa-plus-circle fa-2x" style="cursor: pointer;"></td>						
								</tr>    						
							</tfoot>
						</table>

						<div class="form-row">
							<div class="col-sm-2 offset-sm-8">
								<center><label for="txtFinalFee" class="" style="color: #757575;text-align: center; font-size: 1.25rem; margin-bottom: .5rem; font-weight: 500; line-height: 1.2; margin-top: 5%; margin-left: 15%">Total Fee: ₱</label></center>
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control forms_textbox prtextboxes" name="txtFinalFee" 
								id="txtFinalFee" autocomplete="off" placeholder="0.00" readonly required style="width: 100%; text-align: center;">
							</div>
						</div>

						<br>

						<div class="form-row">
							<div class="col-sm-12">
								<b><label for="txtCharging">CHARGING</label></b>
								<input type="text" class="form-control prtextboxes" id="txtCharging" name="txtCharging" placeholder="Eg. (Charge to GAA-PICTS.)" onkeyup="checkGAA_lastQuarter(this);" autocomplete="on" required>
							</div>	
						</div>

						<br>

						<div class="form-row">
							<div class="col-sm-12">
								<b><label for="txtPurpose">PURPOSE</label></b>
								<input type="text" class="form-control prtextboxes" id="txtPurpose" name="txtPurpose" placeholder="Eg. (For PICTS use.)" autocomplete="off" required>
							</div>	
						</div>				

						<br>

						<div class="form-row">
							<div class="col-sm-12">
								<b><label for="select_requestor">REQUESTOR</label></b>
								<!-- <input type="text" class="form-control prtextboxes" id="txtPurpose" name="txtPurpose" placeholder="Eg. (MARNIE B. DONES)" autocomplete="off" required> -->

								<select class="form-control forms_textbox" id="select_requestor" name="select_requestor" required>
	                                <?php
	                                // for ($i=0; $i < count($signID); $i++){
	                                // 	echo '<option value="'.$signID[$i].'">'. $signTitle[$i] .'. '. $signFName[$i] .' '. $signMName[$i] .'. '. $signLName[$i] .'</option>';	
	                                // }

	                                for ($i=0; $i < count($landingaccount_ID); $i++){
	                            		echo '<option value="'.$landingaccount_ID[$i].'">'. $landingacct_Name[$i] .'</option>';
	                                }
	                                ?>
	                            </select>
							</div>	
						</div>				
						
						

						<br>

						<!-- <h5 style="margin-left: 41%; margin-bottom: 0%; margin-top: 1%; color: #757575">Due Date</h5> -->

						

						
						

						<!-- <div class="form-row">
							<div class="col-sm-2 offset-sm-8">
								<h5 style="" class="align-middle">FINAL TOTAL FEE</h5>
								<p class="align-middle">FINAL TOTAL FEE:</p>
								<center><label for="txtFinalFee" class="" style="color: #757575;text-align: center; font-size: 1.25rem; margin-bottom: .5rem; font-weight: 500; line-height: 1.2; margin-top: 5%; margin-left: 15%">Final Total Fee: ₱</label></center>
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control forms_textbox" name="txtFinalFee" 
								id="txtFinalFee" autocomplete="off" placeholder="0.00" readonly required style="width: 100%; text-align: center;">
							</div>
						</div> -->

						<br>

						<div class="formFooter" style="display: block;">
							<div class="form-row">
								<div class="col">
									<input type="checkbox" id="chkAgree" name="chkAgree" required>
									<label for="chkAgree">I have reviewed all the details inputted</label>
								</div>
							</div>

							<!-- <div class="form-row">
								<div class="col">
									<input type="checkbox" id="chkPreview" name="chkPreview" required>
									<label for="chkPreview">I have checked the preview of the form in small format & large format</label>
								</div>
							</div> -->

							<br>

							<div class="form-row">
								<div class="col">
									<center><button type="submit" class="btn formsbutton" id="btnAddAccount">SUBMIT</button></center>
								</div>
							</div>
						</div>
					</form>
				</div>
				
			</div>
		</div>

<!-- 	<div class="container-fluid">
		<h1>My First Bootstrap Page</h1>
		<p>This is some text.</p>
	</div>

	The quick brown fox jumps 

	<div class="container p-3 my-3 bg-dark text-white">
		<h1>My First Bootstrap Page</h1>
		<p>This container has a dark background color and a white text, and some extra padding and margins.</p>
	</div>
	<div class="container p-3 my-3 bg-primary text-white">
		<h1>My First Bootstrap Page</h1>
		<p>This container has a blue background color and a white text, and some extra padding and margins.</p>
	</div>
	<div class="container p-3 my-3 border">
		<h1>My First Bootstrap Page</h1>
		<p>This container has a border and some extra padding and margins.</p>
	</div> -->

	<!-- Loading Spinner Wrapper-->
<div id="loadingDIV" class="loader text-center d-none" style="">
    <div class="loader-inner">

        <!-- Animated Spinner -->
        
        <div class="lds-roller mb-3">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        
        <!-- Spinner Description Text [For Demo Purpose]-->
        <h4 class="text-uppercase font-weight-bold" style="color: #00aeef">LOADING</h4>
    </div>
</div>

</body>

<!--Banana, Abaca, Pineapple, Bamboo-->

<script type="importmap">
			{
				"imports": {
					"ckeditor5": "../vendor/ckeditor5/ckeditor5.js",
					"ckeditor5/": "../vendor/ckeditor5/"
				}
			}
		</script>
        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Paragraph,
                Bold,
                Italic,
                Font
            } from 'ckeditor5';

            var allEditors = document.querySelectorAll('.txtCHKeditor');
			for (var i = 0; i < allEditors.length; ++i) {
			  	// ClassicEditor.create(allEditors[i]);
			  	ClassicEditor
                .create( allEditors[i], {
                    plugins: [ Essentials, Paragraph, Bold, Italic, Font ],
                    toolbar: [
						'undo', 'redo', '|', 'bold', 'italic', '|',
						'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
					]
                } )
                .then( editor => {
                    window.editor = editor;
                } )
                .catch( error => {
                    console.error( error );
                } );
			}

            
        </script>
        <!-- A friendly reminder to run on a server, remove this during the integration. -->
        <script>
		        window.onload = function() {
		            if ( window.location.protocol === "file:" ) {
		                alert( "This sample requires an HTTP server. Please serve this file with a web server." );
		            }
		        };
		</script>

<script>

	$(document).ready(function(){
        $("#linav_Modules").addClass("active");
        $("#pill_createPR").addClass("active");
    });

    $(".navLinks").click(function() {
	    $("#loadingDIV").removeClass("d-none");
	    $("#loadingDIV").fadeIn();
  	});

  	$(".actionPills").click(function() {
	    $("#loadingDIV").removeClass("d-none");
	    $("#loadingDIV").fadeIn();
  	});

    $("#lnkChange").click(function() {
    	// alert("CHANGE TO CDABUS");
    	var sectionPresent = $("#txtSection").val();
    	// alert(sectionPresent);
    	if(sectionPresent == "Natural Fiber Utilization Section"){
    		$("#txtSection").val("Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section");
    	}
    	else if(sectionPresent == "Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section"){
    		$("#txtSection").val("Natural Fiber Utilization Section");
    	}
    	else{
    		alert("ERROR")
    	}
    });

    function computeTotalFee(x){
		// console.log($("table :focus"));
		// console.log($(x).val());
		// console.log($(x).parent().next().children());
		// console.log($(x).parent().next().next().children());
		var qty = $(x).val();
		var unitFee = $(x).parent().next().children().val();
		var totalFee = unitFee * qty;
		$(x).parent().next().next().children().val(totalFee.toFixed(2));
	}

	function computeTotalFee_fromUnit(x){
		// console.log($("table :focus"));
		// console.log($(x).val());
		// console.log($(x).parent().next().children());
		// console.log($(x).parent().next().next().children());
		var unitFee = $(x).val();
		var qty = $(x).parent().prev().children().val();
		var totalFee = unitFee * qty;
		$(x).parent().next().children().val(totalFee.toFixed(2));		
	}

	function computeFinalTotalFee(){
		// console.log($("td input.textboxTotal"));
		// console.log($("td input.textboxTotal").length);
		var txtTotalValues = 0;
		var arrayTxtTotal = [];
		
		var numberOfTotaltextbox = $("td input.textboxTotal").length;
		// console.log(numberOfTotaltextbox);
		if(numberOfTotaltextbox > 1){
			// console.log($("td input.textboxTotal"));
			$("td input.textboxTotal").each(function(){
				// arrayTxtTotal += $(this).val();
				arrayTxtTotal.push(parseFloat($(this).val())); 
				//
			});

			txtTotalValues = arrayTxtTotal.reduce(function(a, b){
				return a + b;
			}, 0);

			$("#txtFinalFee").val(txtTotalValues.toFixed(2));

			$("#txtFinalFee").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
				$(this).removeClass('animated flash');
			});				
		}
		else{
			// console.log($("td input.textboxTotal").val());
			var finalfee = parseFloat($("td input.textboxTotal").val());
			$("#txtFinalFee").val(finalfee.toFixed(2));

			$("#txtFinalFee").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
				$(this).removeClass('animated flash');
			});	
		}
		// console.log(txtTotalValues);
		// console.log(arrayTxtTotal);
	}

	var numberOfRows = 1;
	$("#btnAddRow").click(function() {
		// var lastTR = $("#row1").clone();
		// var finalTR = lastTR
		// console.log(lastTR);
		// $("#row1").after(finalTR);
		// console.log($("tr tbody").last());
		// $("#thActions").css("display", "block");
		// $("#txtFinalFee").val("EXPERIMENT");
		numberOfRows += 1;
		var lastRow = $("tbody tr").last();
		lastRow.after('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
							'<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtstock'+numberOfRows+'" '+
							'id="txtstock'+numberOfRows+'" autocomplete="off" required style="width: 100%;"></td>'+
							'<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
							'id="txtUnitQty'+numberOfRows+'" autocomplete="off" required style="width: 100%;"></td>'+
							'<td><textarea rows="5" class="form-control forms_textbox txtCHKeditor" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>'+
							'<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity'+numberOfRows+'" '+
							'id="txtQuantity'+numberOfRows+'" onkeyup="computeTotalFee(this); computeFinalTotalFee();" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>'+
							'<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee'+numberOfRows+'" '+
							'id="txtUnitFee'+numberOfRows+'" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this); computeFinalTotalFee();" style="width: 100%;"></td>'+
							'<td><input type="text"  class="form-control forms_textbox prtextboxes textboxTotal" name="txtTotal'+numberOfRows+'" min="0"'+
							'id="txtTotal'+numberOfRows+'" autocomplete="off" value=0 required readonly style="width: 100%;"></td>'+
							'<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
						'</tr>');

		$(".newAddedRow").fadeIn();

		

		// ClassicEditor
  //               .create( document.querySelector( '.txtCHKeditor' ), {
  //                   plugins: [ Essentials, Paragraph, Bold, Italic, Font ],
  //                   toolbar: [
		// 				'undo', 'redo', '|', 'bold', 'italic', '|',
		// 				'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
		// 			]
  //               } )
  //               .then( editor => {
  //                   window.editor = editor;
  //               } )
  //               .catch( error => {
  //                   console.error( error );
  //               } );
	});

	function deleteRow(x){
		var allInputs = [];
		var allInputData = [];
		var withData = 0;
		$(x).prevAll().each(function(){
			// console.log($(this).children());
			allInputs.push($(this).children());
		});

		// console.log(allInputs[4]);

		for(var i=0; i<allInputs.length; i++){
			/*allInputData.push(allInputs[i].val());*/
			// console.log(allInputs[i].val() == 0);
			if(allInputs[i].val() != 0){
				// console.log("Eto ay may laman: " + i);
				// withData = withData + 1;
				withData++;
			}			
			else{
				// console.log("Eto ay walang laman: " + i);
			}
			// console.log(withData);
		}

		if(withData > 0){
			var deleteAnswer = confirm("Are you sure you want to delete this?");

			if(deleteAnswer == true){
				// console.log("YES");
				// console.log(x);
				$(x).parent().fadeOut();

				setTimeout(function(){
					$(x).parent().remove();
				}, 400);
				// console.log($(this))
			}
			else{
				// console.log("NO");
			}
		}
		else{
			// $(x).parent().fadeOut();
			// $(x).parent().remove();

			$(x).parent().fadeOut();

			setTimeout(function(){
				$(x).parent().remove();
			}, 400);

		}

		// var allInputs = $(x).prevAll();
		// var selected = allInputs[0];
		// console.log(selected.children());
	}

	$("form").submit(function(e){
		// console.log($("table tbody tr"));
		// console.log($("table tbody tr").length);
		// alert($("table tbody tr").length);
		$("#txtNumberOfRows").val($("table tbody tr").length);
		// var newFinalFee = $("#txtFinalFee").val();
		// $("#txtNewFinalFee").val(newFinalFee);
		// var numberOfTotaltextbox = $("td input.prtextboxes").length;
		// // console.log(numberOfTotaltextbox);
		// if(numberOfTotaltextbox > 1){
		// 	if($("#txtDiscount").val()){
		// 		var discountpercentage = $("#txtDiscount").val();
		// 		// alert(discountpercentage/100);
		// 		var discount = discountpercentage / 100;

		// 		var subtotal = $("#txtFinalFee").val();
		// 		var discountamount = subtotal * discount;
		// 		// alert($("td input.textboxTotal").val() - ($("td input.textboxTotal").val() * discount));

		// 		// $("#txtDiscount").val(discountamount);
		// 		$("#txtDiscount").val(discountamount.toFixed(2));
		// 		// alert($("#txtDiscount").val());
		// 		// e.preventDefault();
		// 		$("#txtDiscount").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
		// 			$(this).removeClass('animated flash');
		// 		});
		// 	}								
		// }
		// else{
		// 	// console.log($("td input.textboxTotal").val());
		// 	if($("#txtDiscount").val()){
		// 		// console.log("with discount");
		// 		var discountpercentage = $("#txtDiscount").val();
		// 		// alert(discountpercentage/100);
		// 		var discount = discountpercentage / 100;

		// 		var subtotal = $("#txtFinalFee").val();
		// 		var discountamount = subtotal * discount;
		// 		// alert($("td input.textboxTotal").val() - ($("td input.textboxTotal").val() * discount));

		// 		$("#txtDiscount").val(discountamount.toFixed(2));
		// 		// alert($("#txtDiscount").val());
		// 		// e.preventDefault();
		// 		$("#txtDiscount").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
		// 			$(this).removeClass('animated flash');
		// 		});
		// 	}
		// }
	});

	function checkGAA_lastQuarter(x){
		// console.log($("table :focus"));
		// console.log($(x).val());
		// console.log($(x).parent().next().children());
		// console.log($(x).parent().next().next().children());
		let text = $(x).val(); 

		let user = $("#txtUsername").val(); 
		
		let result = text.toLowerCase().includes("gaa");

		if(result){
			// if(user == "477"){ //Liezel
			// 	console.log("THIS IS GAA, YET THIS USER " + user + " IS EXEMPTED");
			// 	if(text == 'GAA - "Performance Evaluation of the Long and Short Staple Spun Natural Textile Fiber (NTF)-blended Yarns for RA 9242-Compliant Corporate Dress Apparel (Government Uniform Project)"'){
			// 		console.log("THIS IS PROJECT IS EXEMPTED");
			// 	}
			// 	else if(text == 'DOST-GAA: Creativity and Equity through Accelerated Textile Technology Engagements and Entrepreneurship for Vulnerable Sectors (CREATTEEVS)'){
			// 		console.log("THIS IS PROJECT IS EXEMPTED");
			// 	}
			// 	else if(text == 'GAA: 2024 FRONTIER: Regional Hubs for Innovative Handloom Weaving'){
			// 		console.log("THIS IS PROJECT IS EXEMPTED");
			// 	}
			// 	else{
			// 		alert("Error! You cannot use GAA as the source of fund");
			// 		$(x).val(""); 	
			// 	}
			// }
			// else if(user == "268"){ //Claire
			// 	console.log("THIS IS GAA, YET THIS USER " + user + " IS EXEMPTED");
			// 	if(text == 'TSD GAA - Quality Testing of Textiles and Allied Products'){
			// 		console.log("THIS IS PROJECT IS EXEMPTED");
			// 	}{
			// 		alert("Error! You cannot use GAA as the source of fund");
			// 		$(x).val(""); 	
			// 	}
			// }
			// else if(user == "577"){ //May-Anne
			// 	console.log("THIS IS GAA, YET THIS USER " + user + " IS EXEMPTED");
			// 	if(text == 'DOST-GAA: Creativity and Equity through Accelerated Textile Technology Engagements and Entrepreneurship for Vulnerable Sectors (CREATTEEVS)'){
			// 		console.log("THIS IS PROJECT IS EXEMPTED");
			// 	}
			// 	else{
			// 		alert("Error! You cannot use GAA as the source of fund");
			// 		$(x).val(""); 	
			// 	}
			// }
			// else{
			// 	if(text == 'GAA: 2024 FRONTIER: Regional Hubs for Innovative Handloom Weaving'){
			// 		console.log("THIS IS PROJECT IS EXEMPTED");
			// 	}
			// 	else if(text == 'DOST-GAA: Creativity and Equity through Accelerated Textile Technology Engagements and Entrepreneurship for Vulnerable Sectors (CREATTEEVS)'){
			// 		console.log("THIS IS PROJECT IS EXEMPTED");
			// 	}
			// 	else if(text == 'GAA - "Performance Evaluation of the Long and Short Staple Spun Natural Textile Fiber (NTF)-blended Yarns for RA 9242-Compliant Corporate Dress Apparel (Government Uniform Project)"'){
			// 		console.log("THIS IS PROJECT IS EXEMPTED");
			// 	}
			// 	else{
			// 		alert("Error! You cannot use GAA as the source of fund");
			// 		$(x).val(""); 
			// 	}
			// }

			// alert("Error! You cannot use GAA as the source of fund");
			// $(x).val(""); 
		}
		else{
			console.log("THIS IS NOT GAA");

			let GIAresultCRADLE = text.toLowerCase().includes("sustainable material solution");

			if(GIAresultCRADLE){
				// alert("Error! You cannot use \"GIA-CRADLE: Sustainable Material Solution from Post-Industrial Textiles\" as the source of fund");
				// $(x).val(""); 
			}
			else{
				console.log("THIS IS NOT GIA-CRADLE");

				let GIAresultONELAB = text.toLowerCase().includes("onelab");

				if(GIAresultONELAB){
					if(user == "268"){
						console.log("THIS IS GIA, YET THIS USER " + user + " IS EXEMPTED");
						if(text == 'DOST-GIA Smarter OneLab for Industry 4.0 through Testing and Calibration, Education, and Discovery (OneLab for TED)'){
							console.log("THIS IS PROJECT IS EXEMPTED");
						}
						else{
							alert("Error! You cannot use \"GIA-ONELAB: Smarter OneLab for Industry 4.0 through Testing and Calibration, Education, and Discovery\" as the source of fund");
							$(x).val(""); 	
						}
					}
				}
				else{
					console.log("THIS IS NOT GIA-OneLab");

					let GIAresultBamboo = text.toLowerCase().includes("bamboo");

					if(GIAresultBamboo){
						// alert("Error! You cannot use \"GIA-BAMBOO projects\" as the source of fund");
						// $(x).val(""); 
					}
					else{
						console.log("THIS IS NOT GIA-BAMBOO");
					}
				}
			}
		}
	}

</script>

</html>