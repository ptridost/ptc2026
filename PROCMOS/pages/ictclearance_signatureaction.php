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

    // var_dump($_SESSION['account_ID']);
    // die();

    // require_once("../operations/getPRs_forict.php");
    require_once("../operations/getRequestors.php");
    // require_once("../operations/generateCRN.php");
    require_once("../operations/update_ictclearance.php");

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
  <title>For ICT Clearance</title>
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
          <center><h2>ICT Clearance <br><br> <muted><h3>For Signature</h3></muted></h2></center>
        </div>
      </div>  
    </div>

    <br>

 

    <div class="container-fluid">
      <div class="row" style="padding-top: 1%; padding-right: 1%;">
        <div class="col-lg-3">
          <?php require_once("../accessory/sidebar_actions_ictclearance.php");?>
        </div>
        <div class="col-lg-9">
          <div class="card" style="height: 650px; overflow-y: auto;">
            <div class="card-header" style="background-color: #AC92E4; color: #ffffff;">Purchase Requests (Notification)</div>
            <div class="card-body">
              <ul class="list-group">
                <!-- <li class="list-group-item">First item</li>
                <li class="list-group-item">Second item</li>
                <li class="list-group-item">Third item</li> --> 
                <!-- <a href="print_pr_pdf.php?ID=insertnum" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">(DATE TIME) Purchase Request ID: by OD-PICTS<span class="badge badge-secondary mr-5">✔ Approve</span> <span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>   -->
                <table id="tblPR" class="table table-hover table-bordered table-sm">
                  <thead class="thead-dark">
                    <td>ID</td>
                    <td>P.R. No.</td>
                    <td>Date & Time Requested</td>
                    <td>View PR</td>
                    <td>Location</td>
                    <td>Date & Time of Last Action</td>
                    <td>Last Processed By</td>
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
                      //       <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID[$i].'&acctID='.$account_ID[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                      //       <td>'.$purchrequest_location[$i].'</td>
                      //       <td>'.$pr_datetimelastaction[$i].'</td>
                      //       <td>'.$pr_lastprocessedby[$i].'</td>
                      //       <td>'.$purchrequest_status[$i].'</td>
                      //       <td><a href="#txtPRno" title="Edit ICT Clearance" id="lnkUpdateICTclearance" class="btn formsbutton"><i class="fas fa-edit"></i></a></a></td>
                      //       <td><a id="lnkAttachEsigICTclearance" class="btn formsbutton">✔</a></a></td>
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
              <label style="font-size: 0.8rem; text-align: right;">PICTS Form No. 002 <br>Rev. 1/ 12-05-17</label>
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
          <form id="formUpdateICT" method="POST" action="ictclearance_signatureaction.php" id="" style="width: 100%">
            <div class="form-row">
              <div class="col-lg-6">



                <input type="text"  class="forms_textbox" name="txtNumberOfRows" 
                id="txtNumberOfRows" placeholder="ID" autocomplete="off" 
                style="display: none">


                <input type="text"  class="forms_textbox" name="txtPRID" 
                id="txtPRID" placeholder="ID" autocomplete="off" 
                style="display: none">









                <b><label for="txtPRno">PR NUMBER</label></b>
                <?php echo '<input type="text" class="form-control prtextboxes readonly" id="txtPRno" name="txtPRno" placeholder="(SYSTEM-GENERATED)" value="" autocomplete="off" required>';?>
                <!-- <input type="text" class="form-control prtextboxes" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              <div class="col-sm-6">
                <b><label for="txtSAI">ICT CLEARANCE NUMBER</label></b>
                <?php echo '<input type="text" class="form-control prtextboxes readonly" id="txtICTClearanceNo" name="txtICTClearanceNo" placeholder="(SYSTEM-GENERATED)" autocomplete="off" value="'. $ICTClearanceno_crn .'">';

                  // echo '<input type="text"  class="form-control forms_textbox" name="txtPRNo_crn" id="txtPRNo_crn" placeholder="PR No. CRN" value="'. $ictclearance_no .'" autocomplete="off" readonly required>';
                ?>
                <!-- <input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" required readonly> -->

              </div>
            </div>
            <div class="form-row mt-3">
              <div class="col-sm-6">















                <b><label for="txtDivision">DIVISION</label></b>
                <?php echo '<input type="text" class="form-control prtextboxes readonly" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" value="" autocomplete="off" required>';?>
                <!-- <input type="text" class="form-control prtextboxes" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              <div class="col-sm-6">
                <b><label for="txtSection">SECTION</label></b>
                <?php echo '<input type="text" class="form-control prtextboxes readonly" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" value="" required>';?>
                <!-- <input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" required readonly> -->
              </div>
            </div>

            <br>

            <table id="tblICTClearanceform" class="table table-responsive table-bordered" style="margin-top: 1%;">
              <thead>
                <tr class="">
                  <!-- <th scope="col-sm-2">STOCK NO.</th>
                  <th scope="col-sm-2">UNIT</th>
                  <th scope="col-sm-6">ITEM DESCRIPTION</th>
                  <th scope="col-sm-1">QTY</th>
                  <th scope="col-sm-1">UNIT COST</th>
                  <th scope="col-sm-1">TOTAL COST</th>
                  <th id="thActions" scope="col-sm-1" style="">ACTION</th> -->
                          <th class="col-sm-1">Quantity</th>
                          <th class="col-sm-5">Particular/Description</th>
                          <th class="col-sm-1">ACTION</th>
                </tr>
              </thead>
              <tbody>
                <tr id="row1">
                  <td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty1" 
                  id="txtUnitQty1" autocomplete="off" required style="width: 100%;"></td>
                  <td><textarea rows="5" class="form-control forms_textbox" id="txtItem1" name="txtItem1" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
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

            <br>

            <div class="form-row">
              <div class="col-sm-12">
                <b><label for="txtCharging">REMARKS</label></b>
                <input type="text" class="form-control prtextboxes" id="txtRemarks" name="txtRemarks" placeholder="Eg. (Charge to GAA-PICTS.)" autocomplete="off" required>
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

  <!--MODAL-->

<div class="modal fade" id="modalAffix" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">AFFIX E-SIGNATURE</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formAffix" action="#" method="POST">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtICT_ID" id="txtICT_ID" placeholder="ICT Clearance ID"
           autocomplete="off" required>
           <input type="text"  class="form-control forms_textbox d-none" name="txtPR_No" id="txtPR_No" placeholder="PR No" autocomplete="off" required>
          

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <!-- <center><h5>REMARKS</h5></center> -->

          <!-- <textarea class="form-control" rows="5" id="txtAffixRemarks" placeholder="(INPUT IF ANY)"></textarea>      
          <br> -->
          <center><button type="submit" class="btn formsbutton">CONFIRM E-SIGNATURE</button></center>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Error Modal -->
  <div class="modal fade" id="modalDanger">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div id="modalHeader" class="modal-header modal-danger d-block">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 id="h3ConfirmTitle" class="modal-title text-center" style="">ERROR</h3>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <center><h4 id="modalh4dMessage"></h4></center>
        </div>        
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div class="modal fade" id="ModalConfirmation">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div id="modalHeader" class="modal-header modal-confirm d-block">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 id="h3ConfirmTitle" class="modal-title text-center" style="">SUCCESS</h3>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <center><h4 id="modalh4cMessage"></h4></center>
        </div>        
      </div>
    </div>
  </div>




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



<script>

  $(document).ready(function(){
      $("#linav_Modules").addClass("active");
      $("#pill_actionPR").addClass("active");

      getPRs_forICT();
  });

  function getPRs_forICT(){
        $.ajax({
            method: "POST",
            url: "../Ajax/getPRs_forICT.php",
            dataType: "html",
            success: function(data){
                // alert("Hello");
                // console.log(data);
                $("#tblPR tbody").html(data);
            }
        });
    }

  $("#modalConfirmation").on('hidden.bs.modal', function(){
      window.location.reload();
  });

  $(".readonly").on('keydown paste focus mousedown', function(e){
    if(e.keyCode != 9){ // ignore tab
      e.preventDefault();
    }
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

  var numberOfRows = 0;
  $('#tblPR').on('click','#lnkUpdateICTclearance', function(){
    $('#tblICTClearanceform tbody').empty();
    //get the current row
    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    // alert (col_ID);
    // var colServiceName = currentRow.find("td:eq(3)").text();
    var col_PRno = currentRow.find("td:eq(1)").text();
    var colStatus = currentRow.find("td:eq(7)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);

    $("#txtPRID").val(col_ID);

    $.ajax({
      method : "POST",
      url : "../Ajax/retrieveICTclearance_toupdate.php",
      data : {id : col_PRno},
      dataType : "JSON",
      success : function(data){
        // $("#txtRDDID").prop("value", data);
        // if(data == "ERROR"){
          // alert(data);
        // }
        // else{
        //   console.log(data);
        //   window.open('../pages/print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
        // }
        console.log(data);
        // alert(data.prUnits);

        $('#txtPRno').val(data.pNiN[0]);
        $('#txtICTClearanceNo').val(data.pNiN[1]);


        $('#txtRemarks').val(data.cds[0]);
        $('#txtDivision').val(data.cds[1]);
        $('#txtSection').val(data.cds[2]);

        $("#tblICTClearanceform #row1").remove();



        for (let i = 0; i < data.desc.length; i++) {
          // console.log("test");

          numberOfRows += 1;
          if(i == 0){
            $("#tblICTClearanceform tbody").append('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
                    '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
                    'id="txtUnitQty'+numberOfRows+'" value="'+data.qty[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
                    '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;">'+data.desc[i]+'</textarea></td>'+
                  '</tr>');
          }
          else{
            $("#tblICTClearanceform tbody").append('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
                    '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
                    'id="txtUnitQty'+numberOfRows+'" value="'+data.qty[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
                    '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;">'+data.desc[i]+'</textarea></td>'+
                    '<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
                  '</tr>');
          }
          

          $(".newAddedRow").fadeIn();


        }

    //     lastRow.after('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
    //           '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtstock'+numberOfRows+'" '+
    //           'id="txtstock'+numberOfRows+'" autocomplete="off" style="width: 100%;"></td>'+
    //           '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
    //           'id="txtUnitQty'+numberOfRows+'" autocomplete="off" required style="width: 100%;"></td>'+
    //           '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>'+
    //           '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity'+numberOfRows+'" '+
    //           'id="txtQuantity'+numberOfRows+'" onkeyup="computeTotalFee(this); computeFinalTotalFee();" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>'+
    //           '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee'+numberOfRows+'" '+
    //           'id="txtUnitFee'+numberOfRows+'" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this); computeFinalTotalFee();" style="width: 100%;"></td>'+
    //           '<td><input type="text"  class="form-control forms_textbox prtextboxes textboxTotal" name="txtTotal'+numberOfRows+'" min="0"'+
    //           'id="txtTotal'+numberOfRows+'" autocomplete="off" value=0 required readonly style="width: 100%;"></td>'+
    //           '<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
    //         '</tr>');

    // $(".newAddedRow").fadeIn();

      }
    });

  });


  $("#btnAddRow").click(function() {
    console.log($("#tblICTClearanceform tbody").children().length);
    // alert($("tbody").children().length);
    // var lastTR = $("#row1").clone();
    // var finalTR = lastTR
    // console.log(lastTR);
    // $("#row1").after(finalTR);
    // console.log($("tr tbody").last());
    // $("#thActions").css("display", "block");
    // $("#txtFinalFee").val("EXPERIMENT");
    var numberOfNewRows = $("#tblICTClearanceform tbody").children().length;



    numberOfNewRows += 1;
    // alert(numberOfNewRows);
    var lastRow = $("tbody tr").last();
    lastRow.after('<tr id="row'+numberOfNewRows+'" class="newAddedRow">'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfNewRows+'" '+
              'id="txtUnitQty'+numberOfNewRows+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfNewRows+'" name="txtItem'+numberOfNewRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>'+
              '<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
            '</tr>');

    $(".newAddedRow").fadeIn();
  });


  $('#tblPR').on('click','#lnkAttachEsigICTclearance', function(){
    //get the current row
    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    // alert (col_ID);
    // var colServiceName = currentRow.find("td:eq(3)").text();
    var col_PRno = currentRow.find("td:eq(1)").text();
    var colStatus = currentRow.find("td:eq(6)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);

    $("#txtICT_ID").val(col_ID);
    $("#txtPR_No").val(col_PRno);
    

    $("#modalAffix").modal();

  });

  $("#formUpdateICT").submit(function(e){
    // console.log($("table tbody tr"));
    // console.log($("table tbody tr").length);
    // alert($("#tblICTClearanceform tbody tr").length);
    $("#txtNumberOfRows").val($("#tblICTClearanceform tbody tr").length);
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



  $("#formAffix").submit(function(e){
    e.preventDefault();

    let text = "Are you sure you have already reviewed this document and want to attach your e-signature?\n\nChoose OK to proceed or Cancel.";
      
      
      var col_ID = $("#txtICT_ID").val();
      var col_PRno = $("#txtPR_No").val();
      
      


      if (confirm(text) == true) {
        text = "You pressed OK!";  

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();      

        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_ICTclearance.php",
          data : {id : col_ID, pr_No : col_PRno},
          dataType : "html",
          success : function(data){
            // $("#txtRDDID").prop("value", data);

            // if(data.includes("ERROR")){
            //   alert(data);
            //   window.location.reload();
            // }
            // else{
            //   // console.log(data);
            //   alert(data);
            //   if(data == 1){
            //       $("#modalConfirmation").modal("show");
            //   }
            //   else{
            //       alert(data);
            //       console.log(data);
            //   }
            //   // reload();
            //   // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
            // }



                $("#modalAffix").modal("hide");
                if(data.includes("ERROR")){
                  // alert(data);
                  console.log(data);
                  $("#modalh4dMessage").html(data);
                  $("#modalDanger").modal();
                  $("#loadingDIV").fadeOut();
                }
                else{
                  console.log(data);
                  $("#txtRemarks").val("");
                  $("#modalh4cMessage").html(data);
                  $("#ModalConfirmation").modal();
                  $("#loadingDIV").fadeOut();

                  getPRs_forICT();
                  // alert(data);
                  // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
                }
          }
        });
      } 
      else {
        // text = "You canceled!";
        //do nothing
        e.preventDefault();
      }
  });


      
   


</script>

</html>