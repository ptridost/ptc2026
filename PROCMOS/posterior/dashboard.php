<?php
  require_once("../DatabaseConnection/databaseconnection.php");
  // require_once("../operations/getPRequests.php");     

  require_once("../operations/getUsers.php");

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
  //   // require_once("../operations/getRequestors.php");
  //   // require_once("../operations/submit_purchaserequest.php");

  // }
  // else{
  //   echo '<script type="text/javascript">alert("ERROR! You need to log in first");</script>';
  //   // echo '<script type="text/javascript">alert('. $_SESSION['ProceedID']) .');</script>';
  //   // die();
  //   echo '<script type="text/javascript">window.location.href = "index.php";</script>';
  // }

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

  <?php //require_once("../accessory/navbar.php");?>

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
<nav class="navbar navbar-light navbar-expand-md nav-bgPOST">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#" style="color: #ffffff">Property Procurement Monitoring System</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li> -->
        </ul>
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#" style="color: #ffffff">PPMIS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <!-- <li class="nav-item">
                <a class="nav-link" href="#">Right</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li> -->
            <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #ffffff">
        <?php echo $_SESSION['Username']?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
          <!-- <a class="dropdown-item" href="#"><i class="fas fa-id-badge"></i> <?php echo $_SESSION['Username'];?></a> -->
          <!-- <a id="lnkChangePW" class="dropdown-item" data-toggle="modal" href="#modalUpdateTempPW"><i class="fas fa-key"></i> Change Password</a> -->
            <a class="dropdown-item" href="../operations/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </li>
        </ul>
    </div>
</nav>

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
        <div class="col" style="">
          <center><h4>List of User Accounts</h4></center>
          <!-- <button type="button" class="btn btn-primary btnWalk" data-toggle="modal" data-target="#modalWalk">Add Walk-In Client</button> -->
          <input type="text" class="form-control forms_textbox" name="txtSearchAcctName" id="txtSearchAcctName" placeholder="SEARCH FOR ACCOUNT NAME" onkeyup="search_tblAcctName()" autocomplete="off">
          <div class="table-responsive divUsers">
            <table id="tblUsers" class="table table-hover table-bordered table-sm">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Account Name</th>
                <th>Designation</th>
                <th>Division</th>
                <th>Section</th>
                <th>Requestor</th>
                <th>Alternate OIC</th>
                <th>Signature</th>
                <!-- <th>Mobile Number</th>
                <th>Status</th> -->
                <!-- 
                 -->
                <!-- <th>Approved</th> -->
                <th colspan="2">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                for ($i=0; $i < count($account_ID); $i++) { 
                  echo '<tr id="row'.$i.'">
                      <td>'. $account_ID[$i] .'</td>
                      <td>'. $account_Username[$i] .'</td>
                      <td>'. $account_Password[$i] .'</td>
                      <td>'. $acct_Name[$i] .'</td>
                      <td>'. $acct_Designation[$i] .'</td>
                      <td>'. $acct_Division[$i] .'</td>
                      <td>'. $acct_Section[$i] .'</td>
                      <td>'. $acct_Requestor[$i] .'</td>
                      <td>'. $acct_altOIC[$i] .'</td>
                      <td><img src="../photos/'. $acct_signature[$i] .'" class="rounded" alt="User e-signature" width="250"></td>
                      <td><a id="lnkEditAccount" data-toggle="modal" data-target="#modalAccounts" title="Edit" class="fas fa-edit fa-2x" style="color: #f0ad4e"></a></td>
                      <td><a id="lnkDeleteAccount" data-toggle="modal" data-target="#modalDelete" title ="Delete" style="color: #8d0101" class="fas fa-trash fa-2x"></a></td>
                  </tr>';
                }
              ?>
            </tbody>
            <tfoot>
                <tr>
                  <td style="visibility: hidden;"></td>
                  <td style="visibility: hidden;"></td>
                  <td style="visibility: hidden;"></td>
                  <td style="visibility: hidden;"></td>
                  <td style="visibility: hidden;"></td>
                  <td style="visibility: hidden;"></td>
                  <td style="visibility: hidden;"></td>
                  <td style="visibility: hidden;"></td>
                  <td style="visibility: hidden;"></td>
                  <td style="visibility: hidden;"></td>
                  <td><a id="lnkAddAccount" data-toggle="modal" data-target="#modalAccounts" title="Add New Account" class="fas fa-user-plus fa-2x"></a></td>
                </tr>                 
              </tfoot>
          </table>   
          </div>
             
        </div>
      </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <br>
    <br>
    <br>
    <br>

    <br>
    <br>
    <br>

    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card" style="height: 288px; overflow-y: auto;">
            <div class="card-header" style="background-color: #f4e318;">Purchase Requests (notification)</div>
            <div class="card-body">
              <ul class="list-group">
                <!-- <li class="list-group-item">First item</li>
                <li class="list-group-item">Second item</li>
                <li class="list-group-item">Third item</li> --> 
                <!-- <a href="print_pr_pdf.php?ID=insertnum" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">(DATE TIME) Purchase Request ID: by OD-PICTS<span class="badge badge-secondary mr-5">✔ Approve</span> <span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>   -->
                <table id="tblPending" class="table table-hover table-borderless table-sm">
          
                  <tbody>
                    <?php
                      for ($i=0; $i < count($purchrequest_ID); $i++) { 
                        echo '<tr id="row'.$i.'">
                            <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID[$i].'&acctID='.$account_ID[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">'.$purchrequest_DateTime[$i].' Purchase Request ID: '.$purchrequest_ID[$i].' by '.$acct_Division[$i].' '.$acct_Section[$i].'<span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
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



</body>

<!--Banana, Abaca, Pineapple, Bamboo-->
e
<script>

  $(document).ready(function(){
     $("#start").click(function(){
        var check = $(this).next().child().html();

     });
  });


  function search_tblAcctName(){
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("txtSearchAcctName");
    filter = input.value.toLowerCase();
    table = document.getElementById("tblUsers");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[3];
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

  

</script>

</html>