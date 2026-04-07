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
    require_once("../operations/getAOCs.php");

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
  <title>View/Print/Download AOC</title>
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
          <center><h2>Abstract of Canvass <br><br> <muted><h3>View, Print, & Download</h3></muted></h2></center>
        </div>
      </div>  
    </div>

    <br>

 

    <div class="container-fluid">
      <div class="row" style="padding-top: 1%; padding-right: 1%;">
        <div class="col-lg-3">
          <?php require_once("../accessory/sidebar_actions_aoc.php");?>

        </div>
        <div class="col-lg-9">
          <div class="card" style="height: 650px; overflow-y: auto;">
            <div class="card-header" style="background-color: #AC92E4; color: #ffffff;">Abstract of Canvass (Notification)</div>
            <div class="card-body">
              <ul class="list-group">
                <!-- <li class="list-group-item">First item</li>
                <li class="list-group-item">Second item</li>
                <li class="list-group-item">Third item</li> --> 
                <!-- <a href="print_pr_pdf.php?ID=insertnum" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">(DATE TIME) Purchase Request ID: by OD-PICTS<span class="badge badge-secondary mr-5">✔ Approve</span> <span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>   -->

                <table id="tblPR" class="table table-hover table-bordered table-sm">
                  <thead class="thead-dark">
                    <td>ID</td>
                    <td>AOC Number</td>
                    <td>P.R. No.</td>
                    <td>Date & Time Requested</td>
                    <td>View PR</td>
                    <td>Location</td>
                    <td>Last Processed By</td>
                    <td>Status</td>
                    <td>Action</td>
                  </thead>
                  <tbody>
                    <?php
                      for ($i=0; $i < count($purchrequest_ID); $i++) { 
                        echo '<tr id="row'.$i.'">
                            <td>'.$purchrequest_ID[$i].'</td>
                            <td>'.$aoc_number[$i].'</td>
                            <td>'.$purchrequest_No[$i].'</td>
                            <td>'.$aoc_date[$i].'</td>
                            <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID[$i].'&acctID='.$account_ID[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
                            




                            <td>'.$purchrequest_location[$i].'</td>
                            <td>'.$pr_lastprocessedby[$i].'</td>
                            <td>'.$aoc_status[$i].'</td>
                             <td><a href="print_aoc_pdf.php?ID='.$aoc_ID[$i].'&prNo='.$purchrequest_No[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This AOC</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>';

                            // <td><a href="../AOCs/'.$aoc_pdf[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This AOC</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>'
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
                      for ($i=0; $i < count($rfq_ID); $i++) { 
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


  $('#tblPR').on('click','#lnkViewPR', function(){
    //get the current row

    var currentRow = $(this).closest("tr");

    var col_PRno = currentRow.find("td:eq(3)").text(); // get current row 1st TD value
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

 

</script>

</html>