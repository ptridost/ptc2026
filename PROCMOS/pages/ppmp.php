<?php
  require_once("../DatabaseConnection/databaseconnection.php");
  require_once("../operations/insertVisitors.php");
  // require_once("../operations/getPRequests.php");     

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
    require_once("../operations/getAPPs.php");
    // require_once("../operations/submit_purchaserequest.php");

  }
  else{
    // echo '<script type="text/javascript">alert("ERROR! You need to log in first");</script>';
    // echo '<script type="text/javascript">alert('. $_SESSION['ProceedID']) .');</script>';
    // die();
    // echo '<script type="text/javascript">window.location.href = "index.php";</script>';
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PIMS - P.P.M.P.</title>
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
          <center><h2>Upload P.P.M.P</h2></center>
          <form action="../operations/uploadPPMP.php" method="POST" enctype="multipart/form-data">
            <!-- Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit"> -->
            <div class="form-group">
              <h5><p class="text-muted">Please only upload Excel (.xlsx & .xls) file type here <br> and follow this filename format: PPMP2ndQ_GAA_ProjectName_2024 / PPMP2ndQ_GIA_ProjectName_2024</p></h5>
              <input type="file" class="form-control-file border" name="inputFile_APP" style="cursor: pointer;">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>

          </form>
        </div>
      </div>  
    </div>

    <br>

    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <center><h2>DOWNLOAD</h2></center>
        </div>
      </div>  
    </div>

    <br>

    <div class="container-fluid">

      <div class="row">
        <div class="col table-responsive divAppt" style="">
          <!-- <button type="button" class="btn btn-primary btnWalk" data-toggle="modal" data-target="#modalWalk">Add Walk-In Client</button> -->
          <!-- <input type="text" class="form-control forms_textbox" name="txtSearchPend" id="txtSearchPend" placeholder="SEARCH FOR CLIENT NAME" onkeyup="search_tblPending()" autocomplete="off"> -->
          <div class="row">
                <div class="col-md-4 col-xl-3">
                  <div class="form-group">
                    <select class="form-control" id="select_SearchColumn" name="select_SearchColumn">
                      <option value="5">SEARCH BY PROJECT:</option>
                      <option value="3">SEARCH BY UPLOADED BY:</option>
                      <option value="6">SEARCH BY STATUS:</option>
                      <option value="2">SEARCH BY FILENAME:</option>
                    </select>
                  </div>  
                </div>
                <div class="col-lg-9 col-md-12 col-xl-9">
                  <input type="text" class="form-control forms_textbox" name="txtSearchPPMP" id="txtSearchPPMP" placeholder="TYPE HERE..." onkeyup="search_tblPPMP()" autocomplete="off" style="border: none; border-bottom: 2px double #009aee;">
                </div>
              </div>
          <table id="tblPPMP" class="table table-hover table-bordered">
            <thead class="thead-white bg-primary text-white tableheader">
              <tr>
                <th>ID</th>
                <th>Date & Time Uploaded</th>
                <th>PPMP Name</th>
                <th>Uploaded By</th>
                <th>Division</th>
                <th>Project</th>
                <th>Status</th>
                <th colspan="3">Action</th>
                <!-- <th>Approved</th> -->
                <!-- <th>Action</th> -->
              </tr>
            </thead>
            <tbody>
              <?php
                for ($i=0; $i < count($appID); $i++) { 
                  echo '<tr id="row'.$i.'">
                      <td>'. $appID[$i] .'</td>
                      <td>'. $appDate[$i] .'</td>
                      <td>'. $appFilename[$i] .'</td>
                      <td>'. $appUploadedBy[$i] .'</td>
                      <td>'. $appDivision[$i] .'</td>
                      <td>'. $appProject[$i] .'</td>
                      <td>'. $appStatus[$i] .'</td>';

                      // if(stripos($_SESSION['AccountType'], "PPGSS") !== FALSE){
                      //   echo '<td><a href="#txtPRno" title="Edit ICT Clearance" id="lnkUpdateICTclearance" class="btn formsbutton"><i class="fas fa-edit"></i></a></a></td>
                      //       <td><a id="lnkAttachEsigICTclearance" class="btn formsbutton">✔</a></a></td><td><a id="lnkProceed" href="../APPs/'.$appFilename[$i].'" target="_blank" class="btn btn-primary">DOWNLOAD</a></td></tr>';
                      // }
                      // else{
                      //   //nothing
                      //   echo '<td></td></tr>';
                      // }
                      if($_SESSION['AccountType'] == "PPGSS-GIA"){
                        echo '<td><a href="#txtPRno" title="Edit ICT Clearance" id="lnkUpdateICTclearance" class="btn formsbutton"><i class="fas fa-edit"></i></a></a></td>
                            <td><a id="lnkAttachEsigICTclearance" class="btn formsbutton">✔</a></a></td><td><a id="lnkProceed" href="../APPs/'.$appFilename[$i].'" target="_blank" class="btn btn-primary">DOWNLOAD</a></td></tr>';
                      }

                      else if($_SESSION['AccountType'] == "PPGSS-GAA"){
                        echo '<td><a href="#txtPRno" title="Edit ICT Clearance" id="lnkUpdateICTclearance" class="btn formsbutton"><i class="fas fa-edit"></i></a></a></td>
                            <td><a id="lnkAttachEsigICTclearance" class="btn formsbutton">✔</a></a></td><td><a id="lnkProceed" href="../APPs/'.$appFilename[$i].'" target="_blank" class="btn btn-primary">DOWNLOAD</a></td></tr>';
                      }
                      else{
                        //do nothing
                        // echo '<a id="pill_editPR" class="nav-link actionPills" href="attach_ictclearance.php">For ICT Clearance</a>';
                        echo '<td><a id="lnkProceed" href="../PPMPs/'.$appFilename[$i].'" target="_blank" class="btn btn-primary">DOWNLOAD</a></td></tr>';
                      }

                      
                  
                }
              ?>
            </tbody> 
          </table>      
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



</body>

<!-- Confirmation Modal -->
  <div class="modal fade" id="ModalConfirmation">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div id="modalHeader" class="modal-header d-block">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 id="h3ConfirmTitle" class="modal-title text-center" style="">ERROR</h3>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <center><h4 id="modalh4Message"></h4></center>
        </div>        
      </div>
    </div>
  </div>


<script>

  $(document).ready(function(){
     $("#start").click(function(){
        var check = $(this).next().child().html();

     });

     $("#linav_Modules").addClass("active");

     // $("#ModalConfirmation").modal();
  });


   function search_tblPPMP(){
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("txtSearchPPMP");
    filter = input.value.toLowerCase();
    table = document.getElementById("tblPPMP");
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