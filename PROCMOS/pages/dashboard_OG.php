<?php
  require_once("../DatabaseConnection/databaseconnection.php");
  require_once("../operations/getPRequests.php");     

  // if(isset($_SESSION['Username'])){
  //   // $appointID = $_SESSION['ProceedID'];
  //   // require_once("../operations/verifyAddress.php");
  //   // require_once("../operations/getClient.php");
  //   // require_once("../operations/getCtourdetails.php");
  //   // // echo '<script type="text/javascript">alert("TEST");</script>';

  //   // if($appointmentService == "TIPS Plant Tour Services"){
  //   //  //PROCEED
  //   //  $serviceCRN = $appointmentService;
  //   //  require_once("../operations/generateCRN.php");      
  //   // }
  //   // else{
  //   //  echo '<script type="text/javascript">alert("FAILED");</script>';    
  //   //  header("Location: index.php");
  //   // }
  //   // var_dump($_SESSION['Division']);
  //   // var_dump($_SESSION['Section']);
  //   // die();
  //   require_once("../operations/submit_purchaserequest.php");
  // }
  // else{
  //   echo '<script type="text/javascript">alert("ERROR! You need to log in first");</script>';
  //   // echo '<script type="text/javascript">alert('. $_SESSION['ProceedID']) .');</script>';
  //   // die();
  //   echo '<script type="text/javascript">window.location.href = "index.php";</script>';
  // } 

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
    // require_once("../operations/getRequestors.php");
    // require_once("../operations/submit_purchaserequest.php");

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
  <title>Dashboard</title>
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
<!-- <nav class="navbar navbar-light navbar-expand-md nav-bg">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Property Procurement Monitoring System</a>
            </li>
            
        </ul>
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#">PPMIS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            
            <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
         
            <a class="dropdown-item" href="../operations/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </li>
        </ul>
    </div>
</nav> -->

    <br>

    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <center><h2>Purchase Request Form</h2></center>
        </div>
      </div>  
      <div class="row">
        <div class="col-sm-12">
          <center><a href="purchase_request.php" class="btn btn-primary">CREATE P.R.</a></center>
        </div>
      </div> 
    </div>

    <br>

    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <center><h2>Dashboard</h2></center>
        </div>
      </div>  
    </div>

    <br>

    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card" style="height: 450px; overflow-y: auto;">
            <div class="card-header" style="background-color: #74009A; color: #ffffff;">Purchase Requests (notification)</div>
            <div class="card-body">
              <ul class="list-group">
                <!-- <li class="list-group-item">First item</li>
                <li class="list-group-item">Second item</li>
                <li class="list-group-item">Third item</li> --> 
                <!-- <a href="print_pr_pdf.php?ID=insertnum" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">(DATE TIME) Purchase Request ID: by OD-PICTS<span class="badge badge-secondary mr-5">✔ Approve</span> <span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>   -->
                <table id="tblPR" class="table table-hover table-bordered table-sm">
                  <thead class="thead-dark">
                    <td>ID</td>
                    <td>Date & Time Requested</td>
                    <td>Purchase Request PDF</td>
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
                            <td>'.$purchrequest_DateTime[$i].'</td>
                            <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID[$i].'&acctID='.$account_ID[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>VIEW THIS PURCHASE REQUEST</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                            <td>'.$purchrequest_location[$i].'</td>
                            <td>'.$pr_datetimelastaction[$i].'</td>
                            <td>'.$pr_lastprocessedby[$i].'</td>
                            <td>'.$purchrequest_status[$i].'</td>
                            <td><a id="lnkProceed" class="btn formsbutton ">✔</a></td>
                        </tr>';
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
      
    </div>

    <div class="container">
      
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

  <div class="modal fade" id="modalPPGSS" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #f4e318;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">VALIDATION OF PURCHASE REQUEST</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form method="POST" action="../operations/verifyPayment.php">
          <input type="text"  class="form-control forms_textbox" name="txtRDDID" id="txtRDDID" placeholder="RDD ID" autocomplete="off" style="display: none;">
      <input type="text"  class="form-control forms_textbox" name="txtAppID" id="txtAppID" placeholder="Appointment ID" autocomplete="off" style="display: none;">
      <input type="text"  class="form-control forms_textbox textboxesTPDCDash" placeholder="Select desired Date of Appointment below" autocomplete="off" value="INPUT PR NUMBER BELOW AND TICK THE CHECKBOX IF ICT-RELATED" readonly>
      <input type="text"  class="form-control forms_textbox txtCenter" name="txtPRnumber" id="txtPRnumber" placeholder="PR NUMBER" autocomplete="off" required>
      <br>

      <div class="form-row">
        <div class="col">
          <center><input type="checkbox" id="chkAgree" name="chkAgree" required>
          <label for="chkAgree">ICT-Related</label></center>
        </div>
      </div>

      <center><button type="submit" class="btn formsbutton btnProceedDashboard">APPROVE PR</button></center>
      <h6><p class="text-muted">Upon clicking this button, your e-signature will also be attached in the Purchase Request form</p></h6>
        </form>
      </div>
    </div>
  </div>
</div>



</body>

<!--Banana, Abaca, Pineapple, Bamboo-->

<script>

  $(document).ready(function(){
        $("#liPR").addClass("active");
        // $("#modalPPGSS").modal();
        
    });

  $(document).ready(function(){
     $("#start").click(function(){
        var check = $(this).next().child().html();

     });
  });

  $('#tblPR').on('click','#lnkProceed', function(){
    //get the current row
    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    var colServiceName = currentRow.find("td:eq(3)").text();
    var colStatus = currentRow.find("td:eq(6)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);

    if(colStatus == "FOR SIGNATURE (REQUESTOR/PROJECT LEADER)"){
      //DO NOTHING
      let text = "Are you sure you have already reviewed this P.R. and want to attach your e-signature?\n\nChoose OK to proceed or Cancel.";
      if (confirm(text) == true) {
        // text = "You pressed OK!";
        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_requestor.php",
          data : {id : col_ID},
          dataType : "html",
          success : function(data){
            // $("#txtRDDID").prop("value", data);
            if(data == "ERROR"){
              alert(data);
            }
            else{
              console.log(data);
              window.open('../pages/print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
            }
          }
        });
      } 
      else {
        // text = "You canceled!";
        //do nothing
      }
    }
    else if(colStatus == "For Clearance (PPGSS)"){
      alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
      $("#modalPPGSS").modal();
    }
    

  });

  

</script>

</html>