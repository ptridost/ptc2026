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

    require_once("../operations/getPRs_forrfq.php");
    require_once("../operations/getRequestors.php");

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
  <title>For RFQ</title>
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
          <center><h2>Request for Quotation <br><br> <muted><h3>Create</h3></muted></h2></center>
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
            <div class="card-header" style="background-color: #AC92E4; color: #ffffff;">Purchase Requests (Notification)</div>
            <div class="card-body">
              <div class="row">
                  <div class="col-md-4 col-xl-3">
                    <div class="form-group">
                      <select class="form-control" id="select_SearchColumn" name="select_SearchColumn">
                        <option value="0">SEARCH BY ID:</option>
                        <option value="1" selected>SEARCH BY QUOTATION NUMBER:</option>
                        <option value="2">SEARCH BY PR NUMBER:</option>
                        <option value="7">SEARCH BY STATUS:</option>
                        <option value="4">SEARCH BY LOCATION:</option>
                        <!-- <option value="7">SEARCH BY PROJECT:</option> -->
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
                            <td>'.$purchrequest_status[$i].'</td>';                          
                        if ($_SESSION['AccountType'] == "PPGSS-RFQ"){
                          echo '<td><a id="lnkShowQuotations" data-toggle="modal" href="#modalShowRFQS" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Show Quotations</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>';
                        }
                        else if($_SESSION['AccountType'] == "PPGSS"){
                          echo '<td><a id="lnkProceedRFQ" data-toggle="modal" href="#modalRFQidentify" class="btn formsbutton">✔</a></td></tr>';
                        }                        
                      }
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

<div class="modal fade" id="modalRFQidentify" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">IDENTIFY RFQ</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <!-- <form id="formRFQproceed" method="POST" action="proceed_rfq.php"> -->
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_no" id="txtPR_no" placeholder="PR Number" autocomplete="off" required>


         <!--  <div class="form-group">
            <center><h5>CHOOSE</h5></center>
            <select class="form-control" id="select_PRidentification" name="select_PRidentification">
              <option value="PR Finished (Cash Advance)">For Cash Advance</option>
              <option value="For RFQ">For RFQ</option>
              <option value="For AOC">For AOC</option>
              <option value="For PO">For PO</option>
            </select>
          </div> -->

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->

          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" id="txtrfqRemarks" placeholder="(INPUT IF ANY)"></textarea>      
          <br>

          <center><button id="btnProceedRFQ" type="submit" class="btn btn-primary">PROCEED FOR AOC</button></center>
       <!--  </form> -->
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

</body>

<!--Banana, Abaca, Pineapple, Bamboo-->

<script>

  $(document).ready(function(){
      $("#linav_Modules").addClass("active");
      $("#pill_actionPR").addClass("active");



      $('#tblPR tbody tr td').each(function() {
        var TDconfirm = $(this).attr('id');

        if(TDconfirm == "rfqNo"){
          var valRFQno = $(this).html();
          if(valRFQno == "Not Yet Assigned"){
            //do nothing
          }
          else{
            $(this).css({"background-color": "#018d01", "font-weight": "bold"});
          }
        }
        else{
          //DO NOTHING
        }
      });


  });


  function validateCharacters(evt){
    // var character = String.fromCharCode(evt.which);
  //   console.log(evt.id);
  // //    var code = evt.which;

  //   console.log($("#" + evt.id).val());
    if($("#" + evt.id).val() != ""){
      // alert("MAY LAMAN");
      $("#" + evt.id).css({"border": "1px solid #74009A", "border-bottom": "2px solid #74009A"});
    }
    else{
      // alert("WALANG LAMAN");
      $("#" + evt.id).css({"border": "1px solid #ced4da", "border-bottom": "1px solid #ced4da"});
    }
  }

  $(".readonly").on('keydown paste focus mousedown', function(e){
    if(e.keyCode != 9){ // ignore tab
      e.preventDefault();
    }
  });

  
  // $("#lnkPreview_RFQ").click(function(){
  //   var txtQuotation_no = $("#txtRFQno").val();
  //   var txtPR_no = $("#txtPRno").val();

  //   alert(txtPR_no);

  //   $.ajax({
  //     method : "POST",
  //     url : "../Ajax/affixQuotation_number.php",
  //     data : {quotationNo : txtQuotation_no, pr_no : txtPR_no},
  //     dataType : "html",
  //     success : function(data){
  //       // $("#txtRDDID").prop("value", data);
  //       if(data.includes("ERROR")){
  //         alert(data);
  //       }
  //       else{
  //         alert(data);
  //         window.location.href = "rfqpreview_pdf.php?ID=" + $("#txtPRno").val();
  //         console.log(data);
  //         // alert(data);
  //         reload();
  //         // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
  //       }
  //     }
  //   });
  //   // window.open('https://support.wwf.org.uk/earth_hour/index.php?type=individual',
  //   // '_blank' // <- This is what makes it open in a new window.
  //   // );
  // });

  $('#tblPR').on('click','#lnkProceedRFQ', function(){
    //get the current row
    var currentRow = $(this).closest("tr");

    // var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    // var col_RFQnumber = currentRow.find("td:eq(1)").text();
    // alert (col_ID);
    // var colServiceName = currentRow.find("td:eq(3)").text();
    var col_PRno = currentRow.find("td:eq(2)").text();
    // var colStatus = currentRow.find("td:eq(8)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);

    $("#txtPR_no").val(col_PRno);

    
  });

  

  $("#btnProceedRFQ").click(function(){
    var txtPR_no = $("#txtPR_no").val();
    var txtRFQ_remarks = $("#txtrfqRemarks").val();

    // alert(txtRFQ_remarks);
    

    $.ajax({
      method : "POST",
      url : "../Ajax/submitRFQ.php",
      data : {pr_no : txtPR_no, remarks : txtRFQ_remarks},
      dataType : "html",
      success : function(data){
        // $("#txtRDDID").prop("value", data);
        if(data.includes("ERROR")){
          alert(data);
        }
        else{
          alert(data);
          // window.location.href = "rfqpreview_pdf.php?ID=" + $("#txtPRno").val();
          console.log(data);
          // alert(data);
          window.location.reload();
          // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
        }
      }
    });
    // window.open('https://support.wwf.org.uk/earth_hour/index.php?type=individual',
    // '_blank' // <- This is what makes it open in a new window.
    // );
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