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

    <?php echo '<input type="text"  class="form-control forms_textbox d-none" name="txtaccountType" id="txtaccountType" placeholder="ACCOUNT TYPE" value="'.$_SESSION['AccountType'].'" autocomplete="off">'?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <center><h1 class="display-4">DASHBOARD</h1></center>
        </div>
      </div> 

      <div class="row">
        <div class="col-sm-12 ml-5">
          <button class="btn btn-primary pl-5 pr-5 font-weight-bold" style="background-color: #cf4200fff">Filter</button>
        </div>
      </div>

      <!-- <div class="row mt-3">


        <div class="col-sm-2" style="background-color: #009AEE;">
          <div class="row">
            <div class="col-sm-12 pt-1">
              <p style="color: #ffffff">Prepared PR (Month)</p>
              <center><h1 style="color: #fff">003</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #00ADF2;">
          <div class="row">
            <div class="col-sm-12 pt-1">
              <p style="color: #ffffff">Prepared PR (Year)</p>
              <center><h1 style="color: #fff">011</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #00BFFF;">
          <div class="row">
            <div class="col-sm-12 pt-1">
              <p style="color: #ffffff">Processed PO</p>
              <center><h1 style="color: #fff">008</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #00CDFE;">
          <div class="row">
            <div class="col-sm-12 pt-1">
              <p style="color: #ffffff">Cancelled PO</p>
              <center><h1 style="color: #fff">001</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #7BD9F6;">
          <div class="row">
            <div class="col-sm-12 pt-1">
              <p style="color: #ffffff">PO Delivered On-Time</p>
              <center><h1 style="color: #fff">008</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #1484CD;">
          <div class="row">
            <div class="col-sm-12 pt-1">
              <p style="color: #ffffff">Delayed Delivery</p>
              <center><h1 style="color: #fff">000</h1></center>
            </div>
          </div>
        </div>


      </div> -->

      <div class="row mt-3">


        <div class="col-sm-2" style="background-color: #009AEE;">
          <div class="row">
            <div class="col-sm-12 pt-1 font-weight-bold">
              <p style="color: #ffffff">Prepared PR (Month)</p>
              <center><h1 style="color: #fff">003</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #00CDFE;">
          <div class="row">
            <div class="col-sm-12 pt-1 font-weight-bold">
              <p style="color: #ffffff">Prepared PR (Year)</p>
              <center><h1 style="color: #fff">011</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #00597c;">
          <div class="row">
            <div class="col-sm-12 pt-1 font-weight-bold">
              <p style="color: #ffffff">Processed PO</p>
              <center><h1 style="color: #fff">008</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #13c4ff;">
          <div class="row">
            <div class="col-sm-12 pt-1 font-weight-bold">
              <p style="color: #ffffff">Cancelled PO</p>
              <center><h1 style="color: #fff">001</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #1484CD;">
          <div class="row">
            <div class="col-sm-12 pt-1 font-weight-bold">
              <p style="color: #ffffff">PO Delivered On-Time</p>
              <center><h1 style="color: #fff">008</h1></center>
            </div>
          </div>
        </div>

        <div class="col-sm-2" style="background-color: #7BD9F6;">
          <div class="row">
            <div class="col-sm-12 pt-1 font-weight-bold">
              <p style="color: #ffffff">Delayed Delivery</p>
              <center><h1 style="color: #fff">000</h1></center>
            </div>
          </div>
        </div>


      </div>


      <div class="row mt-5" style="height: 620px;">
        <div class="col-xl-8 col-lg-6 col-md-12" style="border-width: 1px;">
          <!-- <center><h1 class="display-1">LEFT SIDE</h1></center> -->
          <div class="row">
            <div class="col-md-4 col-xl-3">
              <div class="form-group">
                <select class="form-control" id="select_SearchColumn" name="select_SearchColumn">
                  <option value="1">SEARCH FOR PR NUMBER:</option>
                  <option value="5">SEARCH FOR STATUS:</option>
                  <option value="4">SEARCH FOR LOCATION:</option>
                </select>
              </div>  
            </div>
            <div class="col-lg-9 col-md-12 col-xl-9">
              <input type="text" class="form-control forms_textbox" name="txtSearchDashboard" id="txtSearchDashboard" placeholder="TYPE HERE..." onkeyup="search_tblDashboard()" autocomplete="off" style="border: none;
    border-bottom: 2px double #009aee;">
            </div>
          </div>
          
          <table id="tblDashboard" class="table table-bordered table-striped table-md table-hover">
            <thead class="thead-white bg-primary text-white" style="">
              <th scope="col">Date of PR</th>
              <th scope="col">PR Number</th>
              <th scope="col">Date of PO</th>
              <th scope="col">PO Number</th>
              <th scope="col">Location</th>
              <th scope="col">Status</th>
            </thead>
            <tbody>
              <?php
                for ($i=0; $i < count($purchrequest_ID); $i++) { 
                  echo '<tr id="row'.$i.'">
                      <td>'.$purchrequest_DateTime[$i].'</td>
                      <td>'.$purchrequest_No[$i].'</td>




                      <td>Not Yet Assigned</td>
                      <td>Not Yet Assigned</td>
                      <td>'.$purchrequest_location[$i].'</td>
                      <td>'.$purchrequest_status[$i].'</td>
                  </tr>';
                }
              ?>
            </tbody>
          </table>
        </div>
        <div class="col-xl-4 col-lg-6" style="border-width: 1px;">
          <!-- <center><h1 class="display-1">RIGHT SIDE</h1></center> -->
          <div id="div1strow_menu" class="row ml-3">
            <div class="col-lg-4">
              <center>
                <a type="button" href="ppmp.php" class="btn btn-primary   btn-lg p-4 pl-5 pr-5" style="border-radius: 20px;"><i class="fas fa-file-excel fa-5x"></i></a>
                <b><p>Project Procurement Management Plan</p></b>
              </center>
            </div>
            <div class="col-lg-4">
              <center>
                <a type="button" href="purchase_request.php" class="btn btn-primary btn-lg p-4 pl-5 pr-5" style="border-radius: 20px;"><i class="fas fa-file-invoice fa-5x"></i></a>
                <b><p>Purchase Request</p></b>
              </center>
            </div>
            <div class="col-lg-4">
              <center>
                <a href="view_rfq.php" type="button" class="btn btn-primary btn-lg p-4 pl-5 pr-5" style="border-radius: 20px;"><i class="fas fa-file-alt fa-5x"></i></a>
                <b><p>Request for Quotation</p></b>
              </center>
            </div>
          </div>

          <div id="div2ndrow_menu" class="row ml-3 mt-5">
            <div class="col-lg-4">
              <center>
                <a href="view_aoc.php" type="button" class="btn btn-primary btn-lg p-4 pl-5 pr-5" style="border-radius: 20px;"><i class="fas fa-file-contract fa-5x"></i></a>
                <b><p>Abstract of Canvass</p></b>
              </center>
            </div>
            <div class="col-lg-4">
              <center>
                <button type="button" class="btn btn-primary btn-lg p-4 pl-5 pr-5" style="border-radius: 20px;"><i class="fas fa-book fa-5x"></i></button>
                <b><p>BAC Resolution</p></b>
              </center>
            </div>
            <div class="col-lg-4">
              <center>
                <button type="button" class="btn btn-primary btn-lg p-4 pl-5 pr-5" style="border-radius: 20px;"><i class="fas fa-receipt fa-5x"></i></button>
                <b><p>Purchase Order</p></b>
              </center>
            </div>
          </div>

          <div id="divICTclearance" class="row ml-3" style="display: none">
            <div class="col-lg-4 offset-sm-4">
              <center>
                <a type="button" href="view_ictclearance.php" class="btn btn-primary btn-lg p-4 pl-5 pr-5" style="border-radius: 20px;"><i class="fas fa-desktop fa-4x"></i></a>
                <b><p>ICT Clearance</p></b>
              </center>
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
        $("#linav_Dashboard").addClass("active");
        
        // $("#modalPPGSS").modal();
        if($("#txtaccountType").val().includes("PICTS")){
          // alert("FOR ICT Clearance");
          

          $("#div2ndrow_menu").removeClass("mt-5");
          // console.log($(".row .ml-3 .mt-5"));
          $("#divICTclearance").show();
          $("#lnk_ICTclearance").show();
          
        }
        else{
          // alert("NOT ICT Clearance")
        }
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

  function search_tblDashboard(){
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("txtSearchDashboard");
    filter = input.value.toLowerCase();
    table = document.getElementById("tblDashboard");
    tr = table.getElementsByTagName("tr");

    var columnToBeSearch = $("#select_SearchColumn").val();

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
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