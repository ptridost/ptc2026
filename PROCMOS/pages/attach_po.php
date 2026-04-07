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

    // require_once("../operations/getPRs_foraoc.php");
    // require_once("../operations/getRequestors.php");
    // require_once("../operations/generateCRN.php");
    // require_once("../operations/submit_aoc.php");
    // require_once("../operations/validatePDFupload.php");

    // $_SESSION['successAOCno'] = "24-273";
    // 24-273
    // unset($_SESSION['successAOCno']);

    // var_dump($_SESSION['Name']);
    // die();

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
  <title>For PO</title>
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
          <center><h2>Purchase Order <br><br> <muted><h3>Create</h3></muted></h2></center>
        </div>
      </div>  
    </div>

    <br>

    <?php echo '<input type="text" class="form-control prtextboxes readonly d-none" id="txtCheckUploadedAOC" name="txtCheckUploadedAOC" placeholder="(SYSTEM-GENERATED)" autocomplete="off" value="'. $_SESSION['successUploadedAOC'] .'">';?>

    <div class="container-fluid">
      <div class="row" style="padding-top: 1%; padding-right: 1%;">
        <div class="col-lg-3">
          <?php require_once("../accessory/sidebar_actions_po.php");?>
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
                    <td>ICT Clearance No.</td>
                    <td>Quotation No.</td>
                    <td>AOC No.</td>
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
                      //   echo '<tr id="rowlist'.$i.'">
                      //       <td>'.$purchrequest_ID[$i].'</td>
                      //       <td>'.$purchrequest_No[$i].'</td>
                      //       <td>'.$prictclearance_no[$i].'</td>
                      //       <td>'.$prquotation_no[$i].'</td>
                      //       <td>'.$purchrequest_DateTime[$i].'</td>
                      //       <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID[$i].'&acctID='.$account_ID[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
                      //       <td><a id="lnkShowQuotations" data-toggle="modal" href="#modalShowRFQS" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Show Quotations</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>



                      //       <td>'.$purchrequest_location[$i].'</td>
                      //       <td>'.$pr_datetimelastaction[$i].'</td>
                      //       <td>'.$pr_lastprocessedby[$i].'</td>
                      //       <td>'.$purchrequest_status[$i].'</td>
                      //       <td><a id="lnkAttachAOC" href="#lblPRFormNo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create Abstract of Canvass</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
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

      














<!--MODAL-->

<div class="modal fade" id="modalPO" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">Create PO</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <!-- <form id="formCancel" action="#"> -->
          <div id="dangerpopup" class="alert alert-danger alert-dismissible fade show d-none">
              <button type="button" class="close">&times;</button>
              This field is required!
          </div>

          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_no" id="txtPR_no" placeholder="PR Number" autocomplete="off" required>

          <input type="text"  class="form-control forms_textbox d-none" name="txtAOC_no" id="txtAOC_no" placeholder="AOC Number" autocomplete="off" required>

          <input type="text"  class="form-control forms_textbox d-none" name="txtRFQ_no" id="txtRFQ_no" placeholder="RFQ Number" autocomplete="off" required>

          <center><h5>PO Number</h5></center>
          <?php echo '<input type="text" class="form-control prtextboxes readonly" id="txtPONo" name="txtPONo" placeholder="(SYSTEM-GENERATED)" autocomplete="off" required style="text-align: center">';?>

          <br>

          <center><h5>Mode of Procurement</h5></center>
          <input list="modes"  class="form-control prtextboxes" name="txtModeProcurement" id="txtModeProcurement" placeholder="Type here or select options below" autocomplete="off" required style="text-align: center;">
          <datalist id="modes">
              <option value="Shopping">
              <option value="Negotiated Procurement">
              <option value="Direct Contracting">
              <option value="Small Value Procurement">
          </datalist>

          <br>

          <center><h5>Remarks</h5></center>
          <textarea class="form-control prtextboxes" rows="5" id="txtAffixRemarks" placeholder='(Input "VAT INCLUSIVE..., with Certification"' required style="text-align: center;"></textarea> 

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          
          <br>
          <center><button id="btnSubmitForPO" type="submit" class="btn formsbutton">CREATE</button></center>
        <!-- </form> -->
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalUploadPO" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">Upload Approved PO</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <!-- <form id="formCancel" action="#"> -->
          <div id="dangerpopup" class="alert alert-danger alert-dismissible fade show d-none">
              <button type="button" class="close">&times;</button>
              This field is required!
          </div>

          <form id="formUploadAOC" action="#" method="POST" enctype="multipart/form-data">
              <!-- <div class="form-group">     
                <b><label for="txtAOCNo">AOC NUMBER</label></b>
                  <?php echo '<input type="text" class="form-control prtextboxes readonly" id="txtAOCNo" name="txtAOCNo" placeholder="(SYSTEM-GENERATED)" autocomplete="off" value="'. $_SESSION['successAOCno'] .'" required>';?>
              </div>
              

              <br> -->

              <div class="form-group">     
                <h6><p class="text-muted">Please only upload PDF (.pdf) file type here (1 whole file only)</p></h6>
                <input type="file" class="form-control-file border" name="inputFile_AOC" style="cursor: pointer;" required>
              </div>

              
              <br>
              <center><button id="btnUploadAOC" type="submit" class="btn formsbutton">SEND PO</button></center>
          </form>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          
          
        <!-- </form> -->
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

<?php

  

  // if (isset($_SESSION['successAOCno'])){

  //   // var_dump($_SESSION['successAOCno']);
  //   // die();
  //   if (isset($_SESSION['successUploadedAOC'])){
  //       if($_SESSION['successUploadedAOC'] == $_SESSION['successAOCno']){
            

  //           echo '<script>
  //                     $("#modalUploadAOC").modal("hide");
  //                     $("#modalh4cMessage").html("AOC no. '.$_SESSION['successAOCno'].' is successfully uploaded in the database");
  //                     $("#ModalConfirmation").modal();
  //                 </script>';

  //           unset($_SESSION['successUploadedAOC']);
  //           unset($_SESSION['successAOCno']);
  //       }
  //       else{
  //           echo '<script>
  //                     $("#modalh4dMessage").html("The AOC number do not match from each other (Uploaded and Submitted)");
  //                     $("#modalDanger").modal();
  //                     $("#modalUploadAOC").modal();
  //                 </script>';
            
  //       }
  //   }
  //   else{
  //       echo '<script>$("#modalUploadAOC").modal();</script>';
  //   }

  //   // unset($_SESSION['successAOCno']);
  // }
  // else{
  //   // echo '<script>
  //   //           $("#modalh4dMessage").html("data");
  //   //           $("#modalDanger").modal();
  //   //       </script>';

  // }
  
?>

<script>

  $(document).ready(function(){
      $("#linav_Modules").addClass("active");
      $("#pill_actionPR").addClass("active");
      
      getPRs_forpo();

      getCRNforpo();

      // $("#ModalConfirmation").modal();
  });

  $(".navLinks").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

    $(".actionPills").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

  $("#modalUploadAOC").on('hide.bs.modal', function (e) {
      var uploadedAOCno = $("#txtCheckUploadedAOC").val();
      var basisAOCno = $("#txtAOCNo").val();

      if(uploadedAOCno == basisAOCno){
          //proceed
          $("#txtAOCNo").val("");
          $("#txtCheckUploadedAOC").val("");
      }
      else{
          e.preventDefault();
      }
  });

  $(".readonly").on('keydown paste focus mousedown', function(e){
    if(e.keyCode != 9){ // ignore tab
      e.preventDefault();
    }
  });

  function getPRs_forpo(){
        $.ajax({
            method: "POST",
            url: "../Ajax/getPRs_forpo.php",
            dataType: "html",
            success: function(data){
                // alert("Hello");
                // console.log(data);
                $("#tblPR tbody").html(data);
            }
        });
    }

    function getCRNforpo(){
        $.ajax({
            method: "POST",
            url: "../Ajax/getCRNforpo.php",
            dataType: "html",
            success: function(data){
                // alert("Hello");
                // console.log(data);
                $("#txtPONo").val(data);
            }
        });
    }


  $('#tblPR').on('click','#lnkAttachPO', function(){
    //get the current row

    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    var col_PRno = currentRow.find("td:eq(1)").text();
    var col_RFQno = currentRow.find("td:eq(3)").text();
    var col_AOCno = currentRow.find("td:eq(4)").text();
    var colStatus = currentRow.find("td:eq(7)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);
    // var sessionAccountType = $("#txtaccountType").val();

    
    $("#txtPR_no").val(col_PRno);
    $("#txtRFQ_no").val(col_RFQno);    
    $("#txtAOC_no").val(col_AOCno);    


    
    $("#modalPO").modal();

    

    
    

  });

  $("#btnSubmitForPO").click(function(){
      let text = "Are you sure you have already reviewed this P.R. and want to proceed with this action?\n\nChoose OK to proceed or Cancel.";
      
          var poNumber = $("#txtPONo").val();
          var PRnumber = $("#txtPR_no").val();
          var RFQnumber = $("#txtRFQ_no").val();
          var AOCnumber = $("#txtAOC_no").val();
          var modeOfProcurement = $("#txtModeProcurement").val();
          var remarks = $("#txtAffixRemarks").val();
          
          // alert(dropdownNewStatus);


          if (confirm(text) == true) {
            text = "You pressed OK!";
            // e.preventDefault();

            if (modeOfProcurement == ""){
                // your code here.
                // alert("Please input completely");
                $("#modalh4dMessage").html("Please complete the details needed");
                $("#modalDanger").modal();

                $("#txtModeProcurement").focus();

            }
            else{
              if (remarks == ""){
                  $("#modalh4dMessage").html("Please complete the details needed");
                  $("#modalDanger").modal();

                  $("#txtAffixRemarks").focus();
                
              }
              else{
                  //continue

                  $("#loadingDIV").removeClass("d-none");
                  $("#loadingDIV").fadeIn();

                  $.ajax({
                    method : "POST",
                    url : "../Ajax/createPO.php",
                    data : {poNo : poNumber, prNo : PRnumber, rfqNo : RFQnumber, aocNo : AOCnumber, modePR : modeOfProcurement, poRemarks : remarks},
                    dataType : "JSON",
                    success : function(data){
                      // $("#txtRDDID").prop("value", data);

                      console.log(data);
                      let result = Object.keys(data).length;

                      console.log(result);
                      // alert(result);

                      $("#modalPO").modal("hide");

                      if(result > 5){
                        //string data object error
                        if(data.includes("ERROR")){
                          // alert(data);
                          console.log(data);
                          $("#modalh4dMessage").html(data);
                          $("#modalDanger").modal();
                          $("#loadingDIV").fadeOut();

                          getPRs_forpo();

                          getCRNforpo();
                        }
                        else{
                          //do nothing
                          // alert(data);
                          // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
                        }
                      }
                      else{
                          //array data object success
                          console.log(data);
                          $("#txtAffixRemarks").val("");
                          $("#txtModeProcurement").val("");
                          
                          $("#modalh4cMessage").html(data.success[0]);
                          $("#ModalConfirmation").modal();
                          $("#loadingDIV").fadeOut();

                          window.open("print_po_pdf.php?ID=" + data.success[1]);

                          getPRs_forpo();

                          getCRNforpo();
                      }


                    }
                  });
              }

                
            }

            
          } 
          else {
            // text = "You canceled!";
            //do nothing
          }

      // if(remarks.trim().length === 0){
      //     $("#dangerpopup").removeClass("d-none");
      //     $("#dangerpopup").fadeIn();
      // }
      // else{
      //     //proceed
      //     let text = "Are you sure you have already reviewed this P.R. and want to proceed with this action?\n\nChoose OK to proceed or Cancel.";
      
      
      //     var txtprNo = $("#txtPR_no").val();
          
      //     // alert(dropdownNewStatus);


      //     if (confirm(text) == true) {
      //       text = "You pressed OK!";
      //       // e.preventDefault();

      //       $("#loadingDIV").removeClass("d-none");
      //       $("#loadingDIV").fadeIn();

      //       $.ajax({
      //         method : "POST",
      //         url : "../Ajax/cancelPR.php",
      //         data : {prNo : txtprNo, newRemarks : remarks},
      //         dataType : "html",
      //         success : function(data){
      //           // $("#txtRDDID").prop("value", data);
      //           $("#modalCancel").modal("hide");
      //           if(data.includes("ERROR")){
      //             // alert(data);
      //             console.log(data);
      //             $("#modalh4dMessage").html(data);
      //             $("#modalDanger").modal();
      //             $("#loadingDIV").fadeOut();
      //           }
      //           else{
      //             console.log(data);
      //             $("#txtRemarks").val("");
      //             $("#modalh4cMessage").html(data);
      //             $("#ModalConfirmation").modal();
      //             $("#loadingDIV").fadeOut();

      //             getPRs_forcancellation();
      //             // alert(data);
      //             // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
      //           }
      //         }
      //       });
      //     } 
      //     else {
      //       // text = "You canceled!";
      //       //do nothing
      //     }
      // }

      
  });



  


  


</script>

</html>