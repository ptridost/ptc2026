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

    // var_dump($_SESSION['account_ID']);
    // die();
    // die();
    // require_once("../operations/getPRequests_forSignature.php");
    // require_once("../operations/generateCRN.php");

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
  <title>For Signature PR</title>
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
          <!-- <center><h2>Purchase Request <br><br> <muted><h3>For Signature Action</h3></muted></h2></center> -->
          <center><p class="h1 pHeader" style="">Purchase Request</p></center>
          <center><p class="h5 pHeader" style="">For Signature Action</p></center>
        </div>
      </div>  
    </div>

    <br>

    <?php echo '<input type="text"  class="form-control forms_textbox d-none" name="txtaccountType" id="txtaccountType" placeholder="ACCOUNT TYPE" value="'.$_SESSION['AccountType'].'" autocomplete="off">'?>

    <div class="container-fluid">
      <div class="row" style="padding-top: 1%; padding-right: 1%;">
        <div class="col-lg-3">
          <?php require_once("../accessory/sidebar_actions.php");?>

        </div>
        <div class="col-lg-9">
          <div class="card">
            <div class="card-header" style="background-color: #AC92E4; color: #ffffff;">Purchase Requests (Notification)</div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 col-xl-3">
                  <div class="form-group">
                    <?php

                      if($_SESSION['Username'] == "010"){
                          echo '<select class="form-control" id="select_SearchColumn" name="select_SearchColumn">
                                  <option value="1">SEARCH BY PR NUMBER:</option>
                                  <option value="0">SEARCH BY ID:</option>                                  
                                  <option value="8">SEARCH BY STATUS:</option>
                                  <option value="4">SEARCH BY LOCATION:</option>
                                  <option value="7">SEARCH BY PROJECT:</option>
                                </select>';
                      }
                      else{
                          echo '<select class="form-control" id="select_SearchColumn" name="select_SearchColumn">
                                  <option value="0">SEARCH BY ID:</option>
                                  <option value="1">SEARCH BY PR NUMBER:</option>
                                  <option value="8">SEARCH BY STATUS:</option>
                                  <option value="4">SEARCH BY LOCATION:</option>
                                  <option value="7">SEARCH BY PROJECT:</option>
                                </select>';
                      }

                    ?>
                    
                  </div>  
                </div>
                <div class="col-lg-9 col-md-12 col-xl-9">
                  <input type="text" class="form-control forms_textbox" name="txtSearchPR" id="txtSearchPR" placeholder="TYPE HERE..." onkeyup="search_tblPR()" autocomplete="off" style="border: none; border-bottom: 2px double #009aee;">
                </div>
              </div>
              <ul class="list-group" style="height: 650px; overflow-y: auto;">
                <!-- <li class="list-group-item">First item</li>
                <li class="list-group-item">Second item</li>
                <li class="list-group-item">Third item</li> --> 
                <!-- <a href="print_pr_pdf.php?ID=insertnum" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">(DATE TIME) Purchase Request ID: by OD-PICTS<span class="badge badge-secondary mr-5">✔ Approve</span> <span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a>   -->
                <table id="tblPR" class="table table-hover table-bordered table-sm">
                  <thead class="thead-white bg-primary text-white tableheader">
                    <td>ID</td>
                    <td>P.R. No.</td>
                    <td>Date & Time Requested</td>
                    <td>View PR</td>
                    <td>Location</td>
                    <td>Date & Time of Last Action</td>
                    <td>Last Processed By</td>
                    <td>Project</td>
                    <td>Status</td>
                    <td colspan="2">Action</td>
                  </thead>
                  <tbody id="tblDashboardBody">
                    <?php
                    //   for ($i=0; $i < count($purchrequest_ID); $i++) { 
                    //     echo '<tr id="row'.$i.'">
                    //         <td>'.$purchrequest_ID[$i].'</td>
                    //         <td>'.$purchrequest_No[$i].'</td>
                    //         <td>'.$purchrequest_DateTime[$i].'</td>
                    //         <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID[$i].'&acctID='.$account_ID[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                    //         <td>'.$purchrequest_location[$i].'</td>
                    //         <td>'.$pr_datetimelastaction[$i].'</td>
                    //         <td>'.$pr_lastprocessedby[$i].'</td>
                    //         <td>'.$acct_Project[$i].'</td>
                    //         <td>'.$purchrequest_status[$i].'</td>
                    //         <td><a id="lnkProceed" class="btn formsbutton">✔</a><a id="lnkRevert" data-toggle="modal" href="#modalRevert" class="btn btn-warning text-white"><i class="fas fa-undo"></i></a></td>
                    //     </tr>';
                    //   }
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

    

<!--MODAL-->

<div class="modal fade" id="modalView" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">VIEW REMARKS OF THE PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formView" method="POST">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDview" id="txtPR_IDview" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="6" id="txtRemarksview" name="txtRemarksview" readonly placeholder="(INPUT IF ANY)"></textarea>
          <br>

          <div class="row">
              <div class="col-sm-6">
                  <button type="button" class="btn btn-warning btnRemarksAction d-none" disabled><i class="fas fa-pen-square"></i> REVISE</button>
              </div>
              <div class="col-sm-6">
                  <button type="button" id="btn_reSubmitPR" class="btn btn-success btnRemarksAction d-none"><i class="fas fa-retweet"></i> RE-SUBMIT</button>
              </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

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
        <form id="formAffix" method="POST">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_ID" id="txtPR_ID" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" id="txtAffixRemarks" placeholder="(INPUT IF ANY)"></textarea>      
          <br>
          
          <input type="checkbox" id="chkForOIC" name="chkForOIC">
          <label for="chkForOIC">For OIC Signature override</label><small class="text-muted"> (leave unchecked if not)</small>
          <br>
          <center><button type="submit" class="btn formsbutton">CONFIRM E-SIGNATURE</button></center>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAffixPPMPClearance" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">Clearance of PPMP</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formAffixPPMPClearance">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDaffixPPMP" id="txtPR_IDaffixPPMP" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" name="txtAffixRemarksPPMP" id="txtAffixRemarksPPMP" placeholder="(INPUT IF ANY)"></textarea>      
          <br>
          
          <input type="checkbox" id="chkForOIC" name="chkForOIC">
          <label for="chkForOIC">For OIC Signature override</label><small class="text-muted"> (leave unchecked if not)</small>
          <br>
          <center><button type="submit" class="btn formsbutton">CONFIRM E-SIGNATURE</button></center>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAffixSection" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">CLEAR PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formAffixSection">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDsection" id="txtPR_IDsection" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" id="txtAffixRemarks_section" name="txtAffixRemarks_section" placeholder="(INPUT IF ANY)"></textarea>      
          <br>

          <input type="checkbox" id="chkForOICSection" name="chkForOICSection">
          <label for="chkForOICSection">For OIC Signature override</label><small class="text-muted"> (leave unchecked if not)</small>
          <br>

          <center><button type="submit" class="btn formsbutton">VERIFY</button></center>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAffixChief" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">CLEAR PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formAffixChief">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDchief" id="txtPR_IDchief" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" id="txtAffixRemarks_chief" name="txtAffixRemarks_chief" placeholder="(INPUT IF ANY)"></textarea>      
          <br>

          <input type="checkbox" id="chkForOICDivision" name="chkForOICDivision">
          <label for="chkForOICDivision">For OIC Signature override</label><small class="text-muted"> (leave unchecked if not)</small>
          <br>
          <center><button type="submit" class="btn formsbutton">VERIFY</button></center>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalRevert" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block bg-warning" style="">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">REVERT PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formRevert">
          


          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->

          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDreject" id="txtPR_IDreject" placeholder="PR ID" autocomplete="off" required>


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" name="txtPR_Remarksreject" id="txtPR_Remarksreject" placeholder="(INPUT DETAILS)" required></textarea>      
          <br>
          <center><button type="submit" class="btn btn-warning text-white">REJECT</button></center>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalRevert_chief" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block bg-warning" style="">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">REVERT PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formRevertchief">
          


          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->

          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDrejectchief" id="txtPR_IDrejectchief" placeholder="PR ID" autocomplete="off" required>


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" name="txtPR_Remarksrejectchief" id="txtPR_Remarksrejectchief" placeholder="(INPUT DETAILS)" required></textarea>      
          <br>
          <center><button type="submit" class="btn btn-warning text-white">REJECT</button></center>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalRevert_FAD" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block bg-warning" style="">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">REVERT PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formRevertFAD">
          


          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->

          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDrejectfad" id="txtPR_IDrejectfad" placeholder="PR ID" autocomplete="off" required>


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" name="txtPR_Remarksrejectfad" id="txtPR_Remarksrejectfad" placeholder="(INPUT DETAILS)" required></textarea>      
          <br>
          <center><button type="submit" class="btn btn-warning text-white">REJECT</button></center>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalRevert_PPGSS" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block bg-warning" style="">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">REVERT PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formRevertPPGSS">
          


          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->

          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDrejectPPGSS" id="txtPR_IDrejectPPGSS" placeholder="PR ID" autocomplete="off" required>


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" name="txtPR_RemarksrejectPPGSS" id="txtPR_RemarksrejectPPGSS" placeholder="(INPUT DETAILS)" required></textarea>      
          <br>
          <center><button type="submit" class="btn btn-warning text-white">SEND BACK</button></center>
        </form>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modalClearPPGSS" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">Clear PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formClearPPGSS" method="POST">
          

          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDclearPPGSS" id="txtPR_IDclearPPGSS" placeholder="PR ID" autocomplete="off" required>


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" id="txtclearRemarksPPGSS" placeholder="(INPUT IF ANY)"></textarea>      
          <br>
          
          <input type="checkbox" id="chkICTClearance" name="chkICTClearance">
          <label for="chkICTClearance">For ICT Clearance</label><small class="text-muted"> (leave unchecked if not)</small>
          <br>
          <center><button type="submit" class="btn formsbutton">VERIFY</button></center>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalAffixPPGSS" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">AFFIX PR NO. & E-SIGNATURE</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formAffixPPGSS" method="POST">
          

          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDaffixPPGSS" id="txtPR_IDaffixPPGSS" placeholder="PR ID" autocomplete="off" required>
          <center><label for="txtReference" style="color: #757575;text-align: center; font-size: 1.25rem; margin-bottom: .5rem; font-weight: 500; line-height: 1.2; margin-top: 0;">Purchase Request Number</label></center>

          <div class="form-row divGAAprno" style="display: none">
              <div class="col">
                <b><p class="bg-primary text-white" style="text-align: center; font-size: larger;">GAA</p></b>
                <?php echo '<input type="text" class="form-control forms_textbox"
             placeholder="PR Number" autocomplete="off" readonly>';            
            ?>
              </div>
          </div>

          <div class="form-row divGIAprno" style="display: none">
              <div class="col">
                <b><p class="bg-info text-white" style="text-align: center; font-size: larger;">GIA</p></b>
                <?php echo '<input type="text"  class="form-control forms_textbox" placeholder="PR Number" autocomplete="off" readonly>';            
            ?>
              </div>
          </div>


          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" id="txtAffixRemarksPPGSS" placeholder="(INPUT IF ANY)"></textarea>      
          <br>
          
          <center><button type="submit" class="btn formsbutton">VERIFY</button></center>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAffixFAD" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">AFFIX E-SIGNATURE</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formAffixFAD" method="POST">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDaffixFAD" id="txtPR_IDaffixFAD" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" id="txtAffixRemarksFAD" placeholder="(INPUT IF ANY)"></textarea>      
          <br>
          <center><button type="submit" class="btn formsbutton">APPROVE CLEARANCE</button></center>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAffixOD" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">AFFIX E-SIGNATURE</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formAffixOD" method="POST">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDaffixOD" id="txtPR_IDaffixOD" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" id="txtAffixRemarksOD" placeholder="(INPUT IF ANY)"></textarea>      
          <br>
          <center><button type="submit" class="btn formsbutton">APPROVE PR</button></center>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAffixODsec" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">CLEARANCE OF PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formAffixODsec" method="POST">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDaffixODsec" id="txtPR_IDaffixODsec" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="5" id="txtAffixRemarksODsec" placeholder="(INPUT IF ANY)"></textarea>      
          <br>
          <center><button type="submit" class="btn formsbutton">CLEAR PR</button></center>
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

<?php
  
  if($_SESSION['successEsig'] == true){

    echo '<script type="text/javascript">$("#modalConfirmation").modal("show");</script>';
    unset($_SESSION['successEsig']);
  }
  else{
    //do nothing
  }
?>

<!--Banana, Abaca, Pineapple, Bamboo-->

<script>

  $(document).ready(function(){
        $("#linav_Modules").addClass("active");
        $("#pill_actionPR").addClass("active");
        // $('.toast').toast('show');

        getPRs_forSignature();
        getPRs_crn();
    });


    function getPRs_forSignature(){
        $.ajax({
            method: "POST",
            url: "../Ajax/getPRs_forSignature.php",
            dataType: "html",
            success: function(data){
                // alert("Hello");
                // console.log(data);
                $("#tblPR tbody").html(data);
            }
        });
    }

    function getPRs_crn(){
        $.ajax({
            method: "POST",
            url: "../Ajax/generateCRN.php",
            dataType: "JSON",
            success: function(data){
                // alert("Hello");
                // console.log(data);
                $(".divGAAprno").find("input").attr("value",data.Nos[0]);
                $(".divGIAprno").find("input").attr("value",data.Nos[1]);
            }
        });
    }

    $(".navLinks").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

    $(".actionPills").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

  $('#tblPR').on('click','#lnkProceed', function(){
    //get the current row

    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    var colServiceName = currentRow.find("td:eq(3)").text();
    var colProject = currentRow.find("td:eq(7)").text();
    var colStatus = currentRow.find("td:eq(8)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);

    var checkGIAGAA = colProject.toUpperCase();
    var sessionAccountType = $("#txtaccountType").val();

    if(sessionAccountType == "PPGSS"){
      if(colStatus == "For Signature (Project Leader)"){
        alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
        // $("#modalPPGSS").modal();
        
      }
      else if(colStatus == "For Signature (Requestor/Project Leader)"){
        $("#txtPR_IDsection").val(col_ID);

        
        $("#modalAffixSection").modal();        
      }
      else if(colStatus == "For PR number"){
        $("#txtPR_IDaffixPPGSS").val(col_ID);
        
        if(checkGIAGAA.includes("GAA")){
          $("#modalAffixPPGSS").modal();

          $(".divGIAprno").css("display", "none");
          $(".divGAAprno").css("display", "block");
          $(".divGIAprno").find("input").removeAttr("id");
          $(".divGAAprno").find("input").attr("id","txtReference");
          $(".divGIAprno").find("p").removeAttr("id");
          $(".divGAAprno").find("p").attr("id","pGIAGAA");
        }
        else if(checkGIAGAA.includes("GIA")){
          $("#modalAffixPPGSS").modal();

          $(".divGAAprno").css("display", "none");
          $(".divGIAprno").css("display", "block");
          $(".divGAAprno").find("input").removeAttr("id");
          $(".divGIAprno").find("input").attr("id","txtReference");
          $(".divGAAprno").find("p").removeAttr("id");
          $(".divGIAprno").find("p").attr("id","pGIAGAA");
        }
        else{
          alert("Error! Fund source: GAA/GIA in the charging is not specified");
        }
      }
      else if(colStatus == "For Clearance (PPGSS)"){
        $("#txtPR_IDclearPPGSS").val(col_ID);

        $("#modalClearPPGSS").modal();
      }
    }
    else if(sessionAccountType == "PPGSS-PPMP"){
      if(colStatus == "For Signature (Project Leader)"){
        alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
        // $("#modalPPGSS").modal();
        
      }
      else if(colStatus == "For Clearance (PPMP)"){
        $("#txtPR_IDaffixPPMP").val(col_ID);

        
        $("#modalAffixPPMPClearance").modal();
        // alert(col_ID);
      }
    }
    else if(sessionAccountType == "Chief, FAD"){
      if(colStatus == "For Signature (Project Leader)"){
        alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
        // $("#modalPPGSS").modal();
        
      }
      else if(colStatus == "For Clearance (Chief, FAD)"){
        $("#txtPR_IDaffixFAD").val(col_ID);

        
        $("#modalAffixFAD").modal();
        // alert(col_ID);
      }
      else if(colStatus == "For Signature (Division Chief)"){
        alert("THIS HAS NOT YET BEEN CLEARED BY THE DIVISION CHIEF");
        // alert(col_ID);
      }
    }
    else if(sessionAccountType == "Sec, Director"){
      if(colStatus == "For Signature (Project Leader)"){
        alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
        // $("#modalPPGSS").modal();
        
      }
      else if(colStatus == "For Signature Sec (Director)"){
        $("#txtPR_IDaffixODsec").val(col_ID);

        
        $("#modalAffixODsec").modal();
        // alert(col_ID);
      }
    }
    else if(sessionAccountType == "Director"){
      if(colStatus == "For Signature (Project Leader)"){
        alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
        // $("#modalPPGSS").modal();
        
      }
      else if(colStatus == "For Signature (Director)"){
        $("#txtPR_IDaffixOD").val(col_ID);

        
        $("#modalAffixOD").modal();
        // alert(col_ID);
      }
    }
    else{
      if(colStatus == "For Signature (Requestor/Project Leader)"){
        // let text = "Are you sure you have already reviewed this P.R. and want to attach your e-signature?\n\nChoose OK to proceed or Cancel.";
        $("#txtPR_ID").val(col_ID);

        
        $("#modalAffix").modal();
        // alert(col_ID);
        
      }
      else if(colStatus == "For Clearance (PPGSS)"){
        alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
        // $("#modalPPGSS").modal();
      }
      else if(colStatus == "For Signature (Section Head)"){
        if(sessionAccountType == "Head, CDABUS"){
          $("#txtPR_IDsection").val(col_ID);
          $("#modalAffixSection").modal();
        }
        else if(sessionAccountType == "Head, PCTS"){
          $("#txtPR_IDsection").val(col_ID);
          $("#modalAffixSection").modal();
        }
        else if(sessionAccountType == "Head, TIPS"){
          $("#txtPR_IDsection").val(col_ID);
          $("#modalAffixSection").modal();
        }
        else if(sessionAccountType == "Head, PICTS"){
          $("#txtPR_IDsection").val(col_ID);
          $("#modalAffixSection").modal();
        }
        else{
          alert("This is for the Section Head access only!")
        }
      }
      else if(colStatus == "For Signature (Division Chief)"){
        if(sessionAccountType == "Chief, RDD"){

          $("#txtPR_IDchief").val(col_ID);
          $("#modalAffixChief").modal();
        }
        else if(sessionAccountType == "Chief, TSD"){

          $("#txtPR_IDchief").val(col_ID);
          $("#modalAffixChief").modal();
        }
        else{
          alert("This is for the Division Chief access only!")
        }
      }
    }

    
    

  });

$('#tblPR').on('click','#lnkRevert', function(){
    //get the current row

    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    var colServiceName = currentRow.find("td:eq(3)").text();
    var colProject = currentRow.find("td:eq(7)").text();
    var colStatus = currentRow.find("td:eq(8)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);

    // var checkGIAGAA = colProject.toUpperCase();
    var sessionAccountType = $("#txtaccountType").val();

    if(sessionAccountType == "PPGSS"){
      // if(colStatus == "For Signature (Project Leader)"){
      //   alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
      //   // $("#modalPPGSS").modal();
        
      // }
      // else if(colStatus == "For Clearance (PPGSS)"){
      //   $("#txtPR_IDaffixPPGSS").val(col_ID);

        
      //   $("#modalAffixPPGSS").modal();
        
      //   if(checkGIAGAA.includes("GAA")){
      //     $(".divGIAprno").css("display", "none");
      //     $(".divGAAprno").css("display", "block");
      //     $(".divGIAprno").find("input").removeAttr("id");
      //     $(".divGAAprno").find("input").attr("id","txtReference");
      //     $(".divGIAprno").find("p").removeAttr("id");
      //     $(".divGAAprno").find("p").attr("id","pGIAGAA");
      //   }
      //   else if(checkGIAGAA.includes("GIA")){
      //     $(".divGAAprno").css("display", "none");
      //     $(".divGIAprno").css("display", "block");
      //     $(".divGAAprno").find("input").removeAttr("id");
      //     $(".divGIAprno").find("input").attr("id","txtReference");
      //     $(".divGAAprno").find("p").removeAttr("id");
      //     $(".divGIAprno").find("p").attr("id","pGIAGAA");
      //   }
      //   else{
      //     alert("Error! Fund source: GAA/GIA in the charging is not specified");
      //   }
      // }
        if(colStatus == "For Signature (Project Leader)"){
          alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
          // $("#modalPPGSS").modal();
          
        }
        else if(colStatus == "For Clearance (PPGSS)"){
          $("#txtPR_IDrejectPPGSS").val(col_ID);

          
          $("#modalRevert_PPGSS").modal();
          // alert(col_ID);
        }

    }
    else if(sessionAccountType == "PPGSS-PPMP"){
      if(colStatus == "For Signature (Project Leader)"){
        alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
        // $("#modalPPGSS").modal();
        
      }
      else if(colStatus == "For Clearance (PPMP)"){
        $("#txtPR_IDreject").val(col_ID);

        
        $("#modalRevert").modal();
        // alert(col_ID);
      }
    }
    else if(sessionAccountType == "Sec, Director"){
      
      $("#txtPR_IDreject").val(col_ID);
      $("#modalRevert").modal();
    }
    // else if(sessionAccountType == "Chief, FAD"){
    //   if(colStatus == "For Signature (Project Leader)"){
    //     alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
    //     // $("#modalPPGSS").modal();
        
    //   }
    //   else if(colStatus == "For Clearance (Chief, FAD)"){
    //     $("#txtPR_IDaffixFAD").val(col_ID);

        
    //     $("#modalAffixFAD").modal();
    //     // alert(col_ID);
    //   }
    // }
    // else if(sessionAccountType == "Sec, Director"){
    //   if(colStatus == "For Signature (Project Leader)"){
    //     alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
    //     // $("#modalPPGSS").modal();
        
    //   }
    //   else if(colStatus == "For Signature Sec (Director)"){
    //     $("#txtPR_IDaffixODsec").val(col_ID);

        
    //     $("#modalAffixODsec").modal();
    //     // alert(col_ID);
    //   }
    // }
    // else if(sessionAccountType == "Director"){
    //   if(colStatus == "For Signature (Project Leader)"){
    //     alert("THIS HAS NOT YET BEEN SIGNED BY THE PROJECT LEADER/REQUESTOR");
    //     // $("#modalPPGSS").modal();
        
    //   }
    //   else if(colStatus == "For Signature (Director)"){
    //     $("#txtPR_IDaffixOD").val(col_ID);

        
    //     $("#modalAffixOD").modal();
    //     // alert(col_ID);
    //   }
    // }
    else{
      if(colStatus == "For Signature (Requestor/Project Leader)"){
        // let text = "Are you sure you have already reviewed this P.R. and want to attach your e-signature?\n\nChoose OK to proceed or Cancel.";
        $("#txtPR_ID").val(col_ID);

        
        $("#modalAffix").modal();
        // alert(col_ID);
        
      }
      else if(colStatus == "For Clearance (PPGSS)"){
        alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
        // $("#modalPPGSS").modal();
      }
      else if(colStatus == "For Clearance (Chief, FAD)"){
        if(sessionAccountType == "Chief, FAD"){

          $("#txtPR_IDrejectfad").val(col_ID);

        
          $("#modalRevert_FAD").modal();
        }
      }
      else if(colStatus == "For Clearance (Section Head)"){
        if(sessionAccountType == "Head, CDABUS"){
          $("#txtPR_IDsection").val(col_ID);
          $("#modalAffixSection").modal();
        }
        else if(sessionAccountType == "Head, PCTS"){
          $("#txtPR_IDsection").val(col_ID);
          $("#modalAffixSection").modal();
        }
        else if(sessionAccountType == "Head, TIPS"){
          $("#txtPR_IDsection").val(col_ID);
          $("#modalAffixSection").modal();
        }
        else if(sessionAccountType == "Head, PICTS"){
          $("#txtPR_IDsection").val(col_ID);
          $("#modalAffixSection").modal();
        }
        else{
          alert("This is for the Section Head access only!")
        }
      }
      else if(colStatus == "For Signature (Division Chief)"){
        if(sessionAccountType == "Chief, RDD"){

          $("#txtPR_IDrejectchief").val(col_ID);

        
          $("#modalRevert_chief").modal();

        }
        else if(sessionAccountType == "Chief, TSD"){

          $("#txtPR_IDreject").val(col_ID);

        
          $("#modalRevert").modal();
        }
        else{
          alert("This is for the Division Chief access only!")
        }
      }
    }

    
    

  });


$('#tblPR').on('click','#lnkView', function(){
    //get the current row


    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    var colServiceName = currentRow.find("td:eq(3)").text();
    var colStatus = currentRow.find("td:eq(8)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);

    $("#txtPR_IDview").val(col_ID);
    
    $.ajax({
      method : "POST",
      url : "../Ajax/retrieveRemarks.php",
      data : {id : col_ID},
      dataType : "html",
      success : function(data){
        // $("#txtRDDID").prop("value", data);
        if(data.includes("ERROR")){
          alert(data);
        }
        else{
          console.log(data);
          if(!data.toString().includes("PR HAS NO REMARKS")){
              // alert(data);
              // $('#txtRemarks').css({"background-color":"#fff !important", "border":"1px solid yellow"});
              $('#txtRemarksview').addClass("bg-warning");

              // alert(colStatus);

              if(colStatus == "For Revision"){
                  //hide action buttons
                  $('.btnRemarksAction').removeClass("d-none");
              }
              else{
                  //show action buttons
                  $('.btnRemarksAction').addClass("d-none");
              }

              
          }
          else{
              //do nothing
              $('#txtRemarks').removeClass("bg-warning");
              $('.btnRemarksAction').addClass("d-none");
          }

          $('#txtRemarksview').val(data);
        }
      }
    });


    
    

  });


  

  $("#formAffixPPMPClearance").submit(function(e){
    // console.log($("table tbody tr"));
    // console.log($("table tbody tr").length);
    // alert($("#tblPR_form tbody tr").length);
    $("#loadingDIV").removeClass("d-none");
    $("#loadingDIV").fadeIn();
    

    let text = "Are you sure you have already reviewed this P.R. and want to attach your initials?\n\nChoose OK to proceed or Cancel.";
      
      
    var col_ID = $("#txtPR_IDaffixPPMP").val();
    var txtremarks = $("#txtAffixRemarksPPMP").val();
      


      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/clearPR_ppmp.php",
          data : {id : col_ID, remarks : txtremarks},
          dataType : "html",
          success : function(data){
            $("#modalAffixPPMPClearance").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
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

                getPRs_forSignature();
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


  $("#formRevert").submit(function(e){
      let text = "Are you sure you have already reviewed this P.R. and want to revert back to the origin?\n\nChoose OK to proceed or Cancel.";
      
      
      var col_ID = $("#txtPR_IDreject").val();
      var txtremarks = $("#txtPR_Remarksreject").val();
      

      alert("exp");

      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/revertPR.php",
          data : {id : col_ID, remarks : txtremarks},
          dataType : "html",
          success : function(data){
            $("#modalRevert").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
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

                getPRs_forSignature();
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

  $("#formRevertchief").submit(function(e){
      let text = "Are you sure you have already reviewed this P.R. and want to revert back to the origin?\n\nChoose OK to proceed or Cancel.";
      
      
      var col_ID = $("#txtPR_IDrejectchief").val();
      var txtremarks = $("#txtPR_Remarksrejectchief").val();
      

      // console.log($("#txtPR_Remarksrejectchief").val());

      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/revertPR_chief.php",
          data : {id : col_ID, remarks : txtremarks},
          dataType : "html",
          success : function(data){
            $("#modalRevert_chief").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
                console.log(data);
                $("#modalh4dMessage").html(data);
                $("#modalDanger").modal();
                $("#loadingDIV").fadeOut();
            }
            else{
                console.log(data);
                $("#txtPR_Remarksrejectchief").val("");
                $("#modalh4cMessage").html(data);
                $("#ModalConfirmation").modal();
                $("#loadingDIV").fadeOut();

                getPRs_forSignature();
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

  $("#formRevertFAD").submit(function(e){
      let text = "Are you sure you have already reviewed this P.R. and want to revert back to the Division Chief?\n\nChoose OK to proceed or Cancel.";
      
      
      var col_ID = $("#txtPR_IDrejectfad").val();
      var txtremarks = $("#txtPR_Remarksrejectfad").val();
      
      // alert("samp");

      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/revertPR_fad.php",
          data : {id : col_ID, remarks : txtremarks},
          dataType : "html",
          success : function(data){
            $("#modalRevert_FAD").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
                console.log(data);
                $("#modalh4dMessage").html(data);
                $("#modalDanger").modal();
                $("#loadingDIV").fadeOut();
            }
            else{
                console.log(data);
                $("#txtPR_Remarksrejectfad").val("");
                $("#modalh4cMessage").html(data);
                $("#ModalConfirmation").modal();
                $("#loadingDIV").fadeOut();

                getPRs_forSignature();
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


  $("#formRevertPPGSS").submit(function(e){
      let text = "Are you sure you have already reviewed this P.R. and want to send back to the Division Chief?\n\nChoose OK to proceed or Cancel.";
      
      
      var col_ID = $("#txtPR_IDrejectPPGSS").val();
      var txtremarks = $("#txtPR_RemarksrejectPPGSS").val();
      
      // alert("samp");

      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/revertPR_PPGSS.php",
          data : {id : col_ID, remarks : txtremarks},
          dataType : "html",
          success : function(data){
            $("#modalRevert_PPGSS").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
                console.log(data);
                $("#modalh4dMessage").html(data);
                $("#modalDanger").modal();
                $("#loadingDIV").fadeOut();
            }
            else{
                console.log(data);
                $("#txtPR_Remarksrejectfad").val("");
                $("#modalh4cMessage").html(data);
                $("#ModalConfirmation").modal();
                $("#loadingDIV").fadeOut();

                getPRs_forSignature();
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


  $("#formAffix").submit(function(e){
      let text = "Are you sure you have already reviewed this P.R. and want to attach your e-signature?\n\nChoose OK to proceed or Cancel.";
      
      
      var col_ID = $("#txtPR_ID").val();
      var txtremarks = $("#txtAffixRemarks").val();

      var checkForOIC = $('#chkForOIC:checked').val();
      // alert(checkICT);
      if(checkForOIC == "on"){
        //checked
        var forOIC = "YES";
      }
      else{
        //unchecked
        var forOIC = "NO";
      }
      
      // alert(col_ID);

      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_requestor.php",
          data : {id : col_ID, remarks : txtremarks, oicESIG : forOIC},
          dataType : "html",
          success : function(data){
            $("#modalAffix").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
                console.log(data);
                $("#modalh4dMessage").html(data);
                $("#modalDanger").modal();
                $("#loadingDIV").fadeOut();
            }
            else{
                console.log(data);
                $("#txtAffixRemarks").val("");
                $("#modalh4cMessage").html(data);
                $("#ModalConfirmation").modal();
                $("#loadingDIV").fadeOut();

                getPRs_forSignature();
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

  $("#formAffixSection").submit(function(e){
      let text = "Are you sure you have already reviewed this P.R. and want to attach your e-signature?\n\nChoose OK to proceed or Cancel.";
      
      
      var col_ID = $("#txtPR_IDsection").val();
      var txtremarks = $("#txtAffixRemarks_section").val();

      var checkForOIC = $('#chkForOICSection:checked').val();
      // alert(checkICT);
      if(checkForOIC == "on"){
        //checked
        var forOIC = "YES";
      }
      else{
        //unchecked
        var forOIC = "NO";
      }
      
      // alert(col_ID);

      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_requestorsection.php",
          data : {id : col_ID, remarks : txtremarks, oicESIG : forOIC},
          dataType : "html",
          success : function(data){
            $("#modalAffixSection").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
                console.log(data);
                $("#modalh4dMessage").html(data);
                $("#modalDanger").modal();
                $("#loadingDIV").fadeOut();
            }
            else{
                console.log(data);
                $("#txtAffixRemarks").val("");
                $("#modalh4cMessage").html(data);
                $("#ModalConfirmation").modal();
                $("#loadingDIV").fadeOut();

                getPRs_forSignature();
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


  $("#formAffixChief").submit(function(e){
      let text = "Are you sure you have already reviewed this P.R. and want to attach your e-signature?\n\nChoose OK to proceed or Cancel.";

      
      var col_ID = $("#txtPR_IDchief").val();
      var txtremarks = $("#txtAffixRemarks_chief").val();

      var checkForOIC = $('#chkForOICDivision:checked').val();
      // alert(checkICT);
      if(checkForOIC == "on"){
        //checked
        var forOIC = "YES";
      }
      else{
        //unchecked
        var forOIC = "NO";
      }
      
      // alert(col_ID);

      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_requestorchief.php",
          data : {id : col_ID, remarks : txtremarks, oicESIG : forOIC},
          dataType : "html",
          success : function(data){
            $("#modalAffixChief").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
                console.log(data);
                $("#modalh4dMessage").html(data);
                $("#modalDanger").modal();
                $("#loadingDIV").fadeOut();
            }
            else{
                console.log(data);
                $("#txtAffixRemarks").val("");
                $("#modalh4cMessage").html(data);
                $("#ModalConfirmation").modal();
                $("#loadingDIV").fadeOut();

                getPRs_forSignature();
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

  $("#formAffixFAD").submit(function(e){
    let text = "Are you sure you have already reviewed this P.R. and want to attach your initials?\n\nChoose OK to proceed or Cancel.";
      
      
    var col_ID = $("#txtPR_IDaffixFAD").val();
    var txtremarks = $("#txtAffixRemarksFAD").val();
      


      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_requestorFAD.php",
          data : {id : col_ID, remarks : txtremarks},
          dataType : "html",
          success : function(data){
            $("#modalAffixFAD").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
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

                getPRs_forSignature();
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

  $("#formAffixODsec").submit(function(e){
      let text = "Are you sure you have already reviewed this P.R. and want to attach your initials?\n\nChoose OK to proceed or Cancel.";
      
      
      var col_ID = $("#txtPR_IDaffixODsec").val();
      var txtremarks = $("#txtAffixRemarksODsec").val();
      


      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_requestorODsec.php",
          data : {id : col_ID, remarks : txtremarks},
          dataType : "html",
          success : function(data){
            $("#modalAffixODsec").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
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

                getPRs_forSignature();
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


  $("#formAffixOD").submit(function(e){
    let text = "Are you sure you have already reviewed this P.R. and want to attach your initials?\n\nChoose OK to proceed or Cancel.";
      
      
    var col_ID = $("#txtPR_IDaffixOD").val();
    var txtremarks = $("#txtAffixRemarksOD").val();
      


      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_requestorOD.php",
          data : {id : col_ID, remarks : txtremarks},
          dataType : "html",
          success : function(data){
            $("#modalAffixOD").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
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

                getPRs_forSignature();
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
  

  $("#formClearPPGSS").submit(function(e){
    let text = "Are you sure you have already reviewed this P.R. and want to attach your initials?\n\nChoose OK to proceed or Cancel.";
      
      
      var col_ID = $("#txtPR_IDclearPPGSS").val();
      
      var txtremarks = $("#txtclearRemarksPPGSS").val();

      
      // alert(isGAAGIA);
      var checkICT = $('#chkICTClearance:checked').val();
      // alert(checkICT);
      if(checkICT == "on"){
        //checked
        var forICT = "YES";
      }
      else{
        //unchecked
        var forICT = "NO";
      }
      


      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_clearPPGSS.php",
          data : {id : col_ID, remarks : txtremarks, validateForICT : forICT},
          dataType : "html",
          success : function(data){
            $("#modalClearPPGSS").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
                console.log(data);
                $("#modalh4dMessage").html(data);
                $("#modalDanger").modal();
                $("#loadingDIV").fadeOut();
            }
            else{
                console.log(data);
                $("#txtclearRemarksPPGSS").val("");
                $("#modalh4cMessage").html(data);
                $("#ModalConfirmation").modal();
                $("#loadingDIV").fadeOut();

                getPRs_forSignature();
                getPRs_crn();
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


  $("#formAffixPPGSS").submit(function(e){
    let text = "Are you sure you have already reviewed this P.R. and want to attach your initials?\n\nChoose OK to proceed or Cancel.";
      
      
    var col_ID = $("#txtPR_IDaffixPPGSS").val();
      
      var txtPRNumber = $("#txtReference").val();
      var txtremarks = $("#txtAffixRemarksPPGSS").val();
      var currentPRNo_crn = $("#txtPRNo_crn").val();
      var isGAAGIA = $("#pGIAGAA").html();

      
      // alert(isGAAGIA);
      var checkICT = $('#chkICTClearance:checked').val();
      // alert(checkICT);
      if(checkICT == "on"){
        //checked
        var forICT = "YES";
      }
      else{
        //unchecked
        var forICT = "NO";
      }
      


      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/attachEsig_requestorPPGSS.php",
          data : {id : col_ID, prNumber : txtPRNumber, remarks : txtremarks, validateForICT : forICT, txtPRNo_crn : currentPRNo_crn, validateGIAGAA : isGAAGIA},
          dataType : "html",
          success : function(data){
            $("#modalAffixPPGSS").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
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

                getPRs_forSignature();
                getPRs_crn();
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