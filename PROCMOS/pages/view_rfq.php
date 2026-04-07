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
    require_once("../operations/getPR_wRFQ.php");

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
  <title>View/Print/Download RFQ</title>
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
          <center><h2>Request for Quotation <br><br> <muted><h3>View, Print, & Download</h3></muted></h2></center>
        </div>
      </div>  
    </div>

    <br>

 

    <div class="container-fluid">
      <div class="row" style="padding-top: 1%; padding-right: 1%;">
        <div class="col-lg-3">
          <?php require_once("../accessory/sidebar_actions_rfq.php");?>

        </div>
        <div class="col-lg-9">
          <div class="card" style="height: 650px; overflow-y: auto;">
            <div class="card-header" style="background-color: #AC92E4; color: #ffffff;">Request for Quotation (Notification)</div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 col-xl-3">
                  <div class="form-group">
                    <select class="form-control" id="select_SearchColumn" name="select_SearchColumn">
                      <option value="1">SEARCH BY QUOTATION NUMBER:</option>
                      <option value="2">SEARCH BY PR NUMBER:</option>
                      <option value="0">SEARCH BY ID:</option>
                      <option value="8">SEARCH BY STATUS:</option>
                      <option value="5">SEARCH BY LOCATION:</option>
                    </select>
                  </div>  
                </div>
                <div class="col-lg-9 col-md-12 col-xl-9">
                  <input type="text" class="form-control forms_textbox" name="txtSearchPR" id="txtSearchPR" placeholder="TYPE HERE..." onkeyup="search_tblPR()" autocomplete="off" style="border: none; border-bottom: 2px double #009aee;">
                </div>
              </div>
              <ul class="list-group">
                <!-- <li class="list-group-item">First item</li>
                <li class="list-group-item">Second item</li>
                <li class="list-group-item">Third item</li> --> 
                <!-- <a href="print_pr_pdf.php?ID=insertnum" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">(DATE TIME) Purchase Request ID: by OD-PICTS<span class="badge badge-secondary mr-5">✔ Approve</span> <span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>   -->

                <table id="tblPR" class="table table-hover table-bordered table-sm">
                  <thead class="thead-dark">
                    <td>ID</td>
                    <td>Quotation Number</td>
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
                      for ($i=0; $i < count($purchrequest_ID); $i++) { 
                        echo '<tr id="row'.$i.'">
                            <td>'.$purchrequest_ID[$i].'</td>
                            <td>'.$prquotation_no[$i].'</td>
                            <td>'.$purchrequest_No[$i].'</td>
                            <td>'.$purchrequest_DateTime[$i].'</td>
                            <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID[$i].'&acctID='.$account_ID[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                            <td>'.$purchrequest_location[$i].'</td>
                            <td>'.$pr_datetimelastaction[$i].'</td>
                            <td>'.$pr_lastprocessedby[$i].'</td>
                            <td>'.$purchrequest_status[$i].'</td>
                            <td><a id="lnkShowQuotations" data-toggle="modal" href="#modalShowRFQS" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Show Quotations</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>';                   
                      }
                    ?>
                  </tbody>
                </table>


                <!-- <table id="tblRFQ" class="table table-hover table-bordered table-sm">
                  <thead class="thead-dark">
                    <td>ID</td>
                    <td>RFQ Date</td>
                    <td>Quotation No.</td>
                    <td>View PR.</td>
                    <td>PR No.</td>
                    <td>Supplier Name</td>
                    <td>Supplier Address</td>
                    <td>End-User</td>
                    <td>Upload Date</td>
                    <td>Action</td>
                  </thead>
                  <tbody>
                    <?php
                      /*for ($i=0; $i < count($rfq_ID); $i++) { 
                        echo '<tr id="row'.$i.'">
                            <td>'.$rfq_ID[$i].'</td>
                            <td>'.$rfq_Date[$i].'</td>
                            <td>'.$rfq_No[$i].'</td>


                            <td><a id="lnkViewPR" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                            <td>'.$pr_No[$i].'</td>
                            <td>'.$rfq_Company[$i].'</td>
                            <td>'.$rfq_Address[$i].'</td>
                            <td>'.$rfq_enduser[$i].'</td>
                            <td>'.$rfq_UploadDate[$i].'</td>
                            <td><a href="../RFQs/'.$rfq_filename[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This RFQ</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
                        </tr>';
                      }*/
                    ?>
                  </tbody>
                </table> -->


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

<div class="modal fade" id="modalShowRFQS" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">VIEW RFQS</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        
          
                <!-- <li class="list-group-item">First item</li>
                <li class="list-group-item">Second item</li>
                <li class="list-group-item">Third item</li> --> 
                <!-- <a href="print_pr_pdf.php?ID=insertnum" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">(DATE TIME) Purchase Request ID: by OD-PICTS<span class="badge badge-secondary mr-5">✔ Approve</span> <span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>   -->            
                <table id="tblRFQ" class="table table-hover table-bordered">
                  <thead class="thead-dark">
                    <td>ID</td>
                    <td>RFQ Date</td>
                    <td>Quotation No.</td>
                    <td>View PR.</td>
                    <td>PR No.</td>
                    <td>Supplier Name</td>
                    <td>Supplier Address</td>
                    <td>End-User</td>
                    <td>Upload Date</td>
                    <td>Action</td>
                  </thead>
                  <tbody>
                    <?php
                      // for ($i=0; $i < count($rfq_ID); $i++) { 
                      //   echo '<tr id="row'.$i.'">
                      //       <td>'.$rfq_ID[$i].'</td>
                      //       <td>'.$rfq_Date[$i].'</td>
                      //       <td>'.$rfq_No[$i].'</td>


                      //       <td><a id="lnkViewPR" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                      //       <td>'.$pr_No[$i].'</td>
                      //       <td>'.$rfq_Company[$i].'</td>
                      //       <td>'.$rfq_Address[$i].'</td>
                      //       <td>'.$rfq_enduser[$i].'</td>
                      //       <td>'.$rfq_UploadDate[$i].'</td>
                            
                      //       <td><a href="print_quotation_pdf.php?ID='.$rfq_ID.'&prNo='.$pr_No.'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This RFQ</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
                      //   </tr>';
                      // }
                    ?>
                  </tbody>
                </table>

                <!-- <td><a href="../RFQs/'.$rfq_filename[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This RFQ</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td> -->

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
        $("#pill_viewPR").addClass("active");
    });

  $(".navLinks").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

    $(".actionPills").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    }); 

  $('#tblRFQ').on('click','#lnkViewPR', function(){
    //get the current row

    var currentRow = $(this).closest("tr");

    var col_PRno = currentRow.find("td:eq(4)").text(); // get current row 1st TD value
    // var colServiceName = currentRow.find("td:eq(3)").text();
    // var colStatus = currentRow.find("td:eq(7)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(col_PRno);
    // var sessionAccountType = $("#txtaccountType").val();

    $.ajax({
      method : "POST",
      url : "../Ajax/viewPR_fromRFQ.php",
      data : {id : col_PRno},
      dataType : "JSON",
      success : function(data){
        // $("#txtRDDID").prop("value", data);
        window.open('print_pr_pdf.php?ID='+data.IDs[0]+'&acctID='+data.IDs[1]+'', '_blank');
        // if(data.includes("ERROR")){
        //   alert(data);
        // }
        // else{
        //   console.log(data);
        //   // alert(data);
        //   reload();
        //   // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
        // }
      }
    });
  });


  $('#tblRFQ').on('click','#lnkOpenQuotation', function(){
    //get the current row

    var currentRow = $(this).closest("tr");

    var col_rfqID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value

    var col_rfqDate = currentRow.find("td:eq(1)").text(); // get current row 1st TD value

    var col_PRno = currentRow.find("td:eq(4)").text(); // get current row 5th TD value
    
    var col_Name = currentRow.find("td:eq(5)").text();

    $.ajax({
      method : "POST",
      url : "../Ajax/validateRFQduration.php",
      data : {id : col_rfqID, prNo : col_PRno, rfqDate : col_rfqDate},
      dataType : "JSON",
      success : function(data){
        // if(data.error[0].includes("ERROR")){
        //   // alert(data);
        //   console.log(data);
        //   $("#modalh4dMessage").html(data);
        //   $("#modalDanger").modal();
        // }
        // else{
          console.log(data);
          if(data.validation[0] == "false"){
              $("#modalh4dMessage").html("These <strong>quotations cannot be opened yet </strong>. <br>You still have <strong>" + data.validation[1] + "</strong> days to open this.");
              $("#modalDanger").modal();
          }
          else{
              $("#loadingDIV").removeClass("d-none");
              $("#loadingDIV").fadeIn();

              $.ajax({
                method : "POST",
                url : "../Ajax/openRFQs.php",
                data : {name : col_Name, id : col_rfqID, prNo : col_PRno},
                dataType : "html",
                success : function(data){
                  
                  if(data.includes("ERROR")){
                      console.log(data);
                      $("#modalh4dMessage").html(data);
                      $("#modalDanger").modal();
                      $("#loadingDIV").fadeOut();
                  }
                  else{
                      console.log(data);
                      $("#modalh4cMessage").html(data);
                      $("#ModalConfirmation").modal();
                      $("#loadingDIV").fadeOut();

                      window.open('print_quotation_pdf.php?ID='+col_rfqID+'&prNo='+col_PRno+'', '_blank');
                  }
                    // console.log(data);
                    // if(data.validation[0] == "false"){
                    //     $("#modalh4dMessage").html("These <strong>quotations cannot be opened yet </strong>. <br>You still have <strong>" + data.validation[1] + "</strong> days to open this.");
                    //     $("#modalDanger").modal();
                    // }
                    // else{
                      
                    //     window.open('print_quotation_pdf.php?ID='+col_rfqID+'&prNo='+col_PRno+'', '_blank');
                    // }
                  
                }
              });
              // window.open('print_quotation_pdf.php?ID='+col_rfqID+'&prNo='+col_PRno+'', '_blank');
          }
          // $("#modalh4cMessage").html("");
          // $("#ModalConfirmation").modal();

          // getPRs_forICT();
          // alert(data);
          // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
        // }
        // $("#txtRDDID").prop("value", data);
        // window.open('print_pr_pdf.php?ID='+data.IDs[0]+'&acctID='+data.IDs[1]+'', '_blank');
        // if(data.includes("ERROR")){
        //   alert(data);
        // }
        // else{
        //   console.log(data);
        //   // alert(data);
        //   reload();
        //   // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
        // }
      }
    });
  });


  $('#tblPR').on('click','#lnkShowQuotations', function(){
    //get the current row

    var currentRow = $(this).closest("tr");

    var col_RFQno = currentRow.find("td:eq(1)").text(); // get current row 1st TD value
    // var colServiceName = currentRow.find("td:eq(3)").text();
    // var colStatus = currentRow.find("td:eq(7)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(col_PRno);
    // var sessionAccountType = $("#txtaccountType").val();
    // alert(col_RFQno);

    $.ajax({
      method : "POST",
      url : "../Ajax/getRFQs.php",
      data : {qNumber : col_RFQno},
      dataType : "html",
      success : function(data){
        // $("#txtRDDID").prop("value", data);
        // window.open('print_pr_pdf.php?ID='+data.IDs[0]+'&acctID='+data.IDs[1]+'', '_blank');
        if(data.includes("ERROR")){
          alert(data);
        }
        else{
          if(data.length === 0){
            $("#tblRFQ tbody").html("<tr><td colspan='10' style='text-align: center'><b>No quotation uploaded yet</b></td></tr>")
          }
          else{
            $("#tblRFQ tbody").html(data);
            // console.log(data);
          }
        }
      }
    });

    

    
    

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