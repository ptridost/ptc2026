<?php
  require_once("../DatabaseConnection/databaseconnection.php");
  require_once("../operations/insertVisitors.php");

  if(isset($_SESSION['Username'])){
    // $appointID = $_SESSION['ProceedID'];
    // require_once("../operations/verifyAddress.php");
    // require_once("../operations/getClient.php");
    // require_once("../operations/getCtourdetails.php");
    // // echo '<script type="text/javascript">alert("TEST");</script>';

    // if($appointmentService == "TIPS Plant Tour Services"){
    //  //PROCEED
    //  $serviceCRN = $appointmentService;
    //  require_once("../operations/generateCRN.php");      
    // }
    // else{
    //  echo '<script type="text/javascript">alert("FAILED");</script>';    
    //  header("Location: index.php");
    // }
    // var_dump($_SESSION['Division']);
    // var_dump($_SESSION['Section']);
    // die();
    // require_once("../operations/getPRequests_toedit.php");
    require_once("../operations/getRequestors.php");
    require_once("../operations/update_purchaserequest.php");

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
  <title>Edit PR</title>
  <link rel="icon" href="../photos/procurement.png" type="image/png" sizes="16x16">
  <link href="../vendor/css/style.css" rel="stylesheet">
  <link href="../vendor/css/bootstrap.min.css" rel="stylesheet">
  <link href="../vendor/css/jquery-ui.min.css" rel="stylesheet">
  <link href="../vendor/fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
  <link href="../vendor/css/animate.css" rel="stylesheet">
  <link href="../vendor/css/loading.css" rel="stylesheet">
  <script src="../vendor/js/jquery-3.4.1.min.js"></script>
  <script src="../vendor/js/bootstrap.min.js"></script>
  <script src="../vendor/js/jquery-ui.min.js"></script>
</head>

<body class="body_style">

  <?php require_once("../accessory/navbar.php");?>

    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample08" aria-controls="   navbarsExample08" aria-expanded="false" aria-label="Toggle navigation">
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
          <center><h2>Purchase Request <br><br> <muted><h3>Edit</h3></muted></h2></center>
        </div>
      </div>  
    </div>

    <br>

 

    <div class="container-fluid">
      <div class="row" style="padding-top: 1%; padding-right: 1%;">
        <div class="col-lg-3">
          <?php require_once("../accessory/sidebar_actions.php");?>
        </div>
        <div class="col-lg-9">
          <div class="card">
            <div class="card-header" style="background-color: #AC92E4; color: #ffffff;">Purchase Requests (Notification)</div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 col-xl-3">
                  <div class="form-group">
                    <select class="form-control" id="select_SearchColumn" name="select_SearchColumn">
                      <option value="0">SEARCH BY ID:</option>
                      <option value="1">SEARCH BY PR NUMBER:</option>
                      <option value="8">SEARCH BY STATUS:</option>
                      <option value="4">SEARCH BY LOCATION:</option>
                      <option value="7">SEARCH BY PROJECT:</option>
                    </select>
                  </div>  
                </div>
                <div class="col-lg-9 col-md-12 col-xl-9">
                  <input type="text" class="form-control forms_textbox" name="txtSearchPR" id="txtSearchPR" placeholder="TYPE HERE..." onkeyup="search_tblPR()" autocomplete="off" style="border: none; border-bottom: 2px double #009aee;">
                </div>
              </div>
              <ul class="list-group" style="height: 650px; overflow-y: auto;">
                <!-- <li class="list-group-item">First item</li>
                <li class="list-group-item">Second item</li>
                <li class="list-group-item">Third item</li> --> 
                <!-- <a href="print_pr_pdf.php?ID=insertnum" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">(DATE TIME) Purchase Request ID: by OD-PICTS<span class="badge badge-secondary mr-5">✔ Approve</span> <span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>   -->
                <table id="tblPR" class="table table-hover table-bordered table-sm">
                  <thead class="thead-white bg-primary text-white tableheader">
                    <td>ID</td>
                    <td>P.R. No.</td>

                    <td>Date & Time Requested</td>
                    <td>Location</td>
                    <td>Date & Time of Last Action</td>
                    <td>Last Processed By</td>
                    <td>Project</td>
                    <td>Status</td>
                    <td>Action</td>
                  </thead>
                  <tbody>
                    <?php
                      // for ($i=0; $i < count($purchrequest_ID); $i++) { 
                      //   echo '<tr id="row'.$i.'">
                      //       <td>'.$purchrequest_ID[$i].'</td>
                      //       <td>'.$purchrequest_No[$i].'</td>
                      //       <td>'.$purchrequest_DateTime[$i].'</td>
                            




                      //       <td>'.$purchrequest_location[$i].'</td>
                      //       <td>'.$pr_datetimelastaction[$i].'</td>
                      //       <td>'.$pr_lastprocessedby[$i].'</td>
                      //       <td>'.$acct_Project[$i].'</td>
                      //       <td>'.$purchrequest_status[$i].'</td>
                      //       <td><a id="lnkEditPR" href="#lblPRFormNo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Edit This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
                      //   </tr>';
                      // }
                    ?>
                  </tbody>
                </table>


                <?php
                  // for ($i=0; $i < count($purchrequest_ID); $i++) { 
                  //   echo '<a href="print_pr_pdf.php?ID='.$purchrequest_ID[$i].'&acctID='.$account_ID[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">'.$purchrequest_DateTime[$i].' Purchase Request ID: '.$purchrequest_ID[$i].' by '.$acct_Division[$i].' '.$acct_Section[$i].'<span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>';
                  // }

                ?>
                <!-- <a href="print_pr_pdf.php?ID=insertnum" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">(DATE TIME) Purchase Request ID: by OD-PICTS <span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>
                <a href="#" class="list-group-item list-group-item-action">Second item</a>
                <a href="#" class="list-group-item list-group-item-action">Third item</a>
                <a href="#" class="list-group-item list-group-item-action">See All</a>
 -->
              </ul>          
            </div>
          </div>
        </div>
        
      </div>

      <div class="container-fluid mt-5">
      <div class="row">
        <div class="col-lg-3">
          
        </div>
        <div id="lblPRFormNo" class="col-lg-9">
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

      <div class="row" style="padding-top: 1%; padding-right: 1%;">
        <div class="col-lg-3">
          

        </div>
        <div class="col-lg-9">
          <form method="POST" action="edit_pr.php" id="" style="width: 100%">
            <div class="form-row">
              <div class="col-lg-6">



                <input type="text"  class="forms_textbox" name="txtNumberOfRows" 
                id="txtNumberOfRows" placeholder="ID" autocomplete="off" 
                style="display: none">


                <input type="text"  class="forms_textbox" name="txtPR_ID" 
                id="txtPR_ID" placeholder="ID" autocomplete="off" required
                style="display: none">









                <b><label for="txtDivision">PR NUMBER</label></b>
                <?php echo '<input type="text" class="form-control prtextboxes" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" value="(SYSTEM-GENERATED)" autocomplete="off" required readonly>';?>
                <!-- <input type="text" class="form-control prtextboxes" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              <div class="col-sm-6">
                <b><label for="txtSection">SAI NUMBER</label></b>
                <?php echo '<input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" value="(SYSTEM-GENERATED)" readonly>';?>
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
                <?php echo '<input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" value="'.$_SESSION['Section'].'" required readonly>';?>
                <!-- <input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" required readonly> -->
              </div>
            </div>

            <br>

            <table id="tblPR_form" class="table table-responsive table-bordered" style="margin-top: 1%;">
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
                  id="txtstock1" autocomplete="off" required pattern="\S(.*\S)?" title="This field is required" style="width: 100%;"></td>
                  <td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty1" 
                  id="txtUnitQty1" autocomplete="off" required style="width: 100%;"></td>
                  <td><textarea rows="5" class="form-control forms_textbox" id="txtItem1" name="txtItem1" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>
                  <td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity1" 
                  id="txtQuantity1" onkeyup="computeTotalFee(this); computeFinalTotalFee();" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>
                  <td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee1" 
                  id="txtUnitFee1" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this); computeFinalTotalFee()" style="width: 100%;"></td>
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

            <!-- <div class="form-row">
              <div class="col-sm-2 offset-sm-8">
                <center><label for="txtFinalFee" class="" style="color: #757575;text-align: center; font-size: 1.25rem; margin-bottom: .5rem; font-weight: 500; line-height: 1.2; margin-top: 5%; margin-left: 15%">Total Fee: ₱</label></center>
              </div>
              <div class="col-sm-2">
                <input type="text" class="form-control forms_textbox prtextboxes" name="txtFinalFee" 
                id="txtFinalFee" autocomplete="off" placeholder="0.00" readonly required style="width: 100%; text-align: center;">
              </div>
            </div> -->

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
                <input type="text" class="form-control prtextboxes" id="txtCharging" name="txtCharging" placeholder="Eg. (Charge to GAA-PICTS.)" autocomplete="off" required>
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
                                  //  echo '<option value="'.$signID[$i].'">'. $signTitle[$i] .'. '. $signFName[$i] .' '. $signMName[$i] .'. '. $signLName[$i] .'</option>';  
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

<!--  <div class="container-fluid">
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
  <div id="loadingDIV" class="loader text-center" style="display: none;">
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

<script>

  $(document).ready(function(){
      $("#linav_Modules").addClass("active");
      $("#pill_editPR").addClass("active");

      getPRs_foredit();
  });

  $(".navLinks").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

    $(".actionPills").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

  function getPRs_foredit(){
        $.ajax({
            method: "POST",
            url: "../Ajax/getPRs_foredit.php",
            dataType: "html",
            success: function(data){
                // alert("Hello");
                // console.log(data);
                $("#tblPR tbody").html(data);
            }
        });
    }


  var numberOfRows = 0;
  $('#tblPR').on('click','#lnkEditPR', function(){
    $('#tblPR_form tbody').empty();

    //get the current row
    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    // alert (col_ID);
    var col_prNumber = currentRow.find("td:eq(1)").text();
    var col_PRno = currentRow.find("td:eq(1)").text();
    var colStatus = currentRow.find("td:eq(7)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);

    // if(col_prNumber != "Not Yet Validated"){
    //   alert("Error! PR number already exist, PR cannot be edited anymore");
    // }
    


    $("#txtPR_ID").val(col_ID);



    // if(colStatus == "For Signature (Requestor/Project Leader)"){
      $.ajax({
        method : "POST",
        url : "../Ajax/retrievePR_toedit.php",
        data : {id : col_ID},
        dataType : "JSON",
        success : function(data){
          // $("#txtRDDID").prop("value", data);
          // if(data == "ERROR"){
          //   alert(data);
          // }
          // else{
          //   console.log(data);
          //   window.open('../pages/print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
          // }
          console.log(data);
          // alert(data.prUnits);
          $('#txtCharging').val(data.cpr[0]);
          $('#txtPurpose').val(data.cpr[1]);
          $('#txtFinalFee').val(data.cpr[3]);

          console.log($("#row1"));
          // $("#tblPR_form #row1").css("border", "10px solid blue");

          $("#tblPR_form #row1").remove();

          for (let i = 0; i < data.desc.length; i++) {
            // console.log("test");

            numberOfRows += 1;

            // alert(i);

            if(i == 0){
              // alert(data.unit[i]);

              $("#tblPR_form tbody").append('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtstock'+numberOfRows+'" '+
              'id="txtstock'+numberOfRows+'" value="'+data.stock[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
              'id="txtUnitQty'+numberOfRows+'" value="'+data.unit[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;">'+data.desc[i]+'</textarea></td>'+
              '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity'+numberOfRows+'" '+
              'id="txtQuantity'+numberOfRows+'" onkeyup="computeTotalFee(this); computeFinalTotalFee();" value="'+data.qty[i]+'" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>'+
              '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee'+numberOfRows+'" '+
              'id="txtUnitFee'+numberOfRows+'" value="'+data.unitCost[i]+'" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this); computeFinalTotalFee()" style="width: 100%;"></td>'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes textboxTotal" name="txtTotal'+numberOfRows+'" min="0"'+
              'id="txtTotal'+numberOfRows+'" value="'+data.totalCost[i]+'" autocomplete="off" value=0 required readonly style="width: 100%;"></td>'+
            '</tr>');
            }
            else{
              $("#tblPR_form tbody").append('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtstock'+numberOfRows+'" '+
              'id="txtstock'+numberOfRows+'" value="'+data.stock[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
              'id="txtUnitQty'+numberOfRows+'" value="'+data.unit[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;">'+data.desc[i]+'</textarea></td>'+
              '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity'+numberOfRows+'" '+
              'id="txtQuantity'+numberOfRows+'" onkeyup="computeTotalFee(this); computeFinalTotalFee();" value="'+data.qty[i]+'" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>'+
              '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee'+numberOfRows+'" '+
              'id="txtUnitFee'+numberOfRows+'" value="'+data.unitCost[i]+'" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this); computeFinalTotalFee()" style="width: 100%;"></td>'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes textboxTotal" name="txtTotal'+numberOfRows+'" min="0"'+
              'id="txtTotal'+numberOfRows+'" value="'+data.totalCost[i]+'" autocomplete="off" value=0 required readonly style="width: 100%;"></td>'+
                      '<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
                    '</tr>');
            }
            


            $(".newAddedRow").fadeIn();
          }

          $("#select_requestor").val(data.cpr[2])

          // console.log($("#select_requestor option"));

          // if($("#select_requestor").val() === data.cpr[2]){
          //   $("#select_requestor").attr("selected", "true");
          // }
          // else{

          // }
          

        }
      });
    // }
    // else if(colStatus == "For Clearance (PPGSS)"){
    //   alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
    //   // $("#modalPPGSS").modal();
    // }
    // else{
    //   //DO NOTHING
    // }

    // $.ajax({
    //   method : "POST",
    //   url : "../Ajax/retrieveICTclearance_toupdate.php",
    //   data : {id : col_PRno},
    //   dataType : "JSON",
    //   success : function(data){
    //     // $("#txtRDDID").prop("value", data);
    //     // if(data == "ERROR"){
    //       // alert(data);
    //     // }
    //     // else{
    //     //   console.log(data);
    //     //   window.open('../pages/print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
    //     // }
    //     console.log(data);
    //     // alert(data.prUnits);

    //     $('#txtPRno').val(data.pNiN[0]);
    //     $('#txtICTClearanceNo').val(data.pNiN[1]);


    //     $('#txtRemarks').val(data.cds[0]);
    //     $('#txtDivision').val(data.cds[1]);
    //     $('#txtSection').val(data.cds[2]);

    //     $("#row1").remove();


    //     for (let i = 0; i < data.desc.length; i++) {
    //       // console.log("test");

    //       numberOfRows += 1;
    //       if(i == 0){
    //         $("#tblICTClearanceform tbody").append('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
    //                 '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
    //                 'id="txtUnitQty'+numberOfRows+'" value="'+data.qty[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
    //                 '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;">'+data.desc[i]+'</textarea></td>'+
    //               '</tr>');
    //       }
    //       else{
    //         $("#tblICTClearanceform tbody").append('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
    //                 '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
    //                 'id="txtUnitQty'+numberOfRows+'" value="'+data.qty[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
    //                 '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;">'+data.desc[i]+'</textarea></td>'+
    //                 '<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
    //               '</tr>');
    //       }
          

    //       $(".newAddedRow").fadeIn();
    //     }

    // //     lastRow.after('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
    // //           '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtstock'+numberOfRows+'" '+
    // //           'id="txtstock'+numberOfRows+'" autocomplete="off" style="width: 100%;"></td>'+
    // //           '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
    // //           'id="txtUnitQty'+numberOfRows+'" autocomplete="off" required style="width: 100%;"></td>'+
    // //           '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>'+
    // //           '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity'+numberOfRows+'" '+
    // //           'id="txtQuantity'+numberOfRows+'" onkeyup="computeTotalFee(this); computeFinalTotalFee(); computeFinalTotalFee();" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>'+
    // //           '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee'+numberOfRows+'" '+
    // //           'id="txtUnitFee'+numberOfRows+'" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this); computeFinalTotalFee() computeFinalTotalFee();" style="width: 100%;"></td>'+
    // //           '<td><input type="text"  class="form-control forms_textbox prtextboxes textboxTotal" name="txtTotal'+numberOfRows+'" min="0"'+
    // //           'id="txtTotal'+numberOfRows+'" autocomplete="off" value=0 required readonly style="width: 100%;"></td>'+
    // //           '<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
    // //         '</tr>');

    // // $(".newAddedRow").fadeIn();

    //   }
    // });

  });


  // $('#tblPR').on('click','#lnkEditPR', function(){
  //   //get the current row
  //   var currentRow = $(this).closest("tr");

  //   var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
  //   // alert (col_ID);
  //   // var colServiceName = currentRow.find("td:eq(3)").text();
  //   var colStatus = currentRow.find("td:eq(6)").text();
  //   // var colDateAppoint = currentRow.find("td:eq(2)").text();
  //   // var colTimeAppoint = currentRow.find("td:eq(3)").text();
  //   // var colClientName = currentRow.find("td:eq(4)").text();
  //   // var colClientEmail = currentRow.find("td:eq(5)").text();
  //   // var colMobile = currentRow.find("td:eq(6)").text();
  //   // alert(colStatus);

  //   if(colStatus == "FOR SIGNATURE (REQUESTOR/PROJECT LEADER)"){
  //     $.ajax({
  //       method : "POST",
  //       url : "../Ajax/retrievePR_toedit.php",
  //       data : {id : col_ID},
  //       dataType : "JSON",
  //       success : function(data){
  //         // $("#txtRDDID").prop("value", data);
  //         // if(data == "ERROR"){
  //         //   alert(data);
  //         // }
  //         // else{
  //         //   console.log(data);
  //         //   window.open('../pages/print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
  //         // }
  //         console.log(data.prUnits);
  //         alert(data.prUnits);

  //         $('#txtCharging').val(data.cpr[0])
  //         $('#txtPurpose').val(data.cpr[1])
  //       }
  //     });
  //   }
  //   else if(colStatus == "For Clearance (PPGSS)"){
  //     alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
  //     // $("#modalPPGSS").modal();
  //   }
  //   else{
  //     //DO NOTHING
  //   }
  //   // else if(colStatus == "For Clearance (PPGSS)"){
  //   //   alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
  //   //   $("#modalPPGSS").modal();
  //   // }
    

  // });

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
    
   var numberOfTotaltextbox = $("#tblPR_form td input.textboxTotal").length;
   // console.log(numberOfTotaltextbox);
   if(numberOfTotaltextbox > 1){
     // console.log($("td input.textboxTotal"));
     $("#tblPR_form td input.textboxTotal").each(function(){
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

  $("#btnAddRow").click(function() {
    // var lastTR = $("#row1").clone();
    // var finalTR = lastTR
    // console.log(lastTR);
    // $("#row1").after(finalTR);
    // console.log($("tr tbody").last());
    // $("#thActions").css("display", "block");
    // $("#txtFinalFee").val("EXPERIMENT");

    var numberOfNewRows = $("#tblPR_form tbody").children().length;

    numberOfNewRows += 1;
    var lastRow = $("#tblPR_form tbody tr").last();
    lastRow.after('<tr id="row'+numberOfNewRows+'" class="newAddedRow">'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtstock'+numberOfNewRows+'" '+
              'id="txtstock'+numberOfNewRows+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfNewRows+'" '+
              'id="txtUnitQty'+numberOfNewRows+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfNewRows+'" name="txtItem'+numberOfNewRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>'+
              '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity'+numberOfNewRows+'" '+
              'id="txtQuantity'+numberOfNewRows+'" onkeyup="computeTotalFee(this); computeFinalTotalFee();" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>'+
              '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee'+numberOfNewRows+'" '+
              'id="txtUnitFee'+numberOfNewRows+'" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this); computeFinalTotalFee()" style="width: 100%;"></td>'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes textboxTotal" name="txtTotal'+numberOfNewRows+'" min="0"'+
              'id="txtTotal'+numberOfNewRows+'" autocomplete="off" value=0 required readonly style="width: 100%;"></td>'+
              '<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
            '</tr>');

    $(".newAddedRow").fadeIn();
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
    // alert($("#tblPR_form tbody tr").length);
    $("#txtNumberOfRows").val($("#tblPR_form tbody tr").length);


    $("#loadingDIV").fadeIn();
    // var newFinalFee = $("#txtFinalFee").val();
    // $("#txtNewFinalFee").val(newFinalFee);
    // var numberOfTotaltextbox = $("td input.prtextboxes").length;
    // // console.log(numberOfTotaltextbox);
    // if(numberOfTotaltextbox > 1){
    //  if($("#txtDiscount").val()){
    //    var discountpercentage = $("#txtDiscount").val();
    //    // alert(discountpercentage/100);
    //    var discount = discountpercentage / 100;

    //    var subtotal = $("#txtFinalFee").val();
    //    var discountamount = subtotal * discount;
    //    // alert($("td input.textboxTotal").val() - ($("td input.textboxTotal").val() * discount));

    //    // $("#txtDiscount").val(discountamount);
    //    $("#txtDiscount").val(discountamount.toFixed(2));
    //    // alert($("#txtDiscount").val());
    //    // e.preventDefault();
    //    $("#txtDiscount").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
    //      $(this).removeClass('animated flash');
    //    });
    //  }               
    // }
    // else{
    //  // console.log($("td input.textboxTotal").val());
    //  if($("#txtDiscount").val()){
    //    // console.log("with discount");
    //    var discountpercentage = $("#txtDiscount").val();
    //    // alert(discountpercentage/100);
    //    var discount = discountpercentage / 100;

    //    var subtotal = $("#txtFinalFee").val();
    //    var discountamount = subtotal * discount;
    //    // alert($("td input.textboxTotal").val() - ($("td input.textboxTotal").val() * discount));

    //    $("#txtDiscount").val(discountamount.toFixed(2));
    //    // alert($("#txtDiscount").val());
    //    // e.preventDefault();
    //    $("#txtDiscount").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
    //      $(this).removeClass('animated flash');
    //    });
    //  }
    // }
  });

  function search_tblPR(){
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("txtSearchPR");
    filter = input.value.toLowerCase();
    table = document.getElementById("tblPR");
    tr = table.getElementsByTagName("tr");

    var columnToBeSearch = $("#select_SearchColumn").val();

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 1; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[columnToBeSearch];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toLowerCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } 
        else {
          tr[i].style.display = "none";
        }
      }
      else{
          td = tr[i].getElementsByTagName("td")[1];
          if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
            } 
            else {
              tr[i].style.display = "none";
            }
          }
      }
    }
  }

</script>

</html>