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
    require_once("../operations/generateCRN.php");
    require_once("../operations/submit_ictclearance.php");

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
          <center><h2>ICT Clearance <br><br> <muted><h3>Create</h3></muted></h2></center>
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
                      //       <td><a id="lnkAttachICTclearance" href="#lblPRFormNo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create ICT Clearance</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
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
          <form method="POST" action="attach_ictclearance.php" id="" style="width: 100%">
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

</body>

<!--Banana, Abaca, Pineapple, Bamboo-->

<script>

  $(document).ready(function(){
      $("#linav_Modules").addClass("active");
      $("#pill_editPR").addClass("active");


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

  $(".readonly").on('keydown paste focus mousedown', function(e){
    if(e.keyCode != 9){ // ignore tab
      e.preventDefault();
    }
  });


  
  $('#tblPR').on('click','#lnkAttachICTclearance', function(){
    var numberOfRows = 0;
    $('#tblICTClearanceform tbody').empty();
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

    $("#txtPRno").val(col_PRno);
    $("#txtPRID").val(col_ID);

    $.ajax({
      method : "POST",
      url : "../Ajax/retrievePR_toattachICTclearance.php",
      data : {id : col_ID},
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
        // console.log(data);
        // alert(data.prUnits);

        $('#txtRemarks').val("<i>Charge to: " + data.cds[0] + "</i>");
        $('#txtDivision').val(data.cds[1]);
        $('#txtSection').val(data.cds[2]);

        // $("#tblICTClearanceform #row1").remove();

        for (let i = 0; i < data.particulars.length; i++) {
          // console.log("test");

          numberOfRows += 1;
          if(i == 0){
            $("#tblICTClearanceform tbody").append('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
                    '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
                    'id="txtUnitQty'+numberOfRows+'" value="'+data.qty[i]+' '+data.unit[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
                    '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;">'+data.particulars[i]+'</textarea></td>'+
                  '</tr>');
          }
          else{
            $("#tblICTClearanceform tbody").append('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
                    '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
                    'id="txtUnitQty'+numberOfRows+'" value="'+data.qty[i]+' '+data.unit[i]+'" autocomplete="off" required style="width: 100%;"></td>'+
                    '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;">'+data.particulars[i]+'</textarea></td>'+
                    '<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
                  '</tr>');
          }
          

          $(".newAddedRow").fadeIn();


        }
      }
    });

    // if(colStatus == "FOR SIGNATURE (REQUESTOR/PROJECT LEADER)"){
    //   $.ajax({
    //     method : "POST",
    //     url : "../Ajax/retrievePR_toedit.php",
    //     data : {id : col_ID},
    //     dataType : "JSON",
    //     success : function(data){
    //       // $("#txtRDDID").prop("value", data);
    //       // if(data == "ERROR"){
    //       //   alert(data);
    //       // }
    //       // else{
    //       //   console.log(data);
    //       //   window.open('../pages/print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
    //       // }
    //       console.log(data.prUnits);
    //       alert(data.prUnits);

    //       $('#txtCharging').val(data.cpr[0])
    //       $('#txtPurpose').val(data.cpr[1])
    //     }
    //   });
    // }
    // else if(colStatus == "For Clearance (PPGSS)"){
    //   alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
    //   // $("#modalPPGSS").modal();
    // }
    // else{
    //   //DO NOTHING
    // }
    // else if(colStatus == "For Clearance (PPGSS)"){
    //   alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
    //   $("#modalPPGSS").modal();
    // }
    

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

  // function computeFinalTotalFee(){
  //  // console.log($("td input.textboxTotal"));
  //  // console.log($("td input.textboxTotal").length);
  //  var txtTotalValues = 0;
  //  var arrayTxtTotal = [];
    
  //  var numberOfTotaltextbox = $("td input.textboxTotal").length;
  //  // console.log(numberOfTotaltextbox);
  //  if(numberOfTotaltextbox > 1){
  //    // console.log($("td input.textboxTotal"));
  //    $("td input.textboxTotal").each(function(){
  //      // arrayTxtTotal += $(this).val();
  //      arrayTxtTotal.push(parseFloat($(this).val())); 
  //      //
  //    });

  //    txtTotalValues = arrayTxtTotal.reduce(function(a, b){
  //      return a + b;
  //    }, 0);

  //    $("#txtFinalFee").val(txtTotalValues.toFixed(2));

  //    $("#txtFinalFee").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
  //      $(this).removeClass('animated flash');
  //    });       
  //  }
  //  else{
  //    // console.log($("td input.textboxTotal").val());
  //    var finalfee = parseFloat($("td input.textboxTotal").val());
  //    $("#txtFinalFee").val(finalfee.toFixed(2));

  //    $("#txtFinalFee").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
  //      $(this).removeClass('animated flash');
  //    }); 
  //  }
  //  // console.log(txtTotalValues);
  //  // console.log(arrayTxtTotal);
  // }

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
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
              'id="txtUnitQty'+numberOfRows+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>'+
              'id="txtTotal'+numberOfRows+'" autocomplete="off" value=0 required readonly style="width: 100%;"></td>'+
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
    // alert($("#tblICTClearanceform tbody tr").length);

    // e.preventDefault();

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

</script>

</html>