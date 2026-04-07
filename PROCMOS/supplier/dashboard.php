<?php
  require_once("../DatabaseConnection/databaseconnection.php");
  require_once("../operations/insertVisitors.php");
  // require_once("../operations/getPRequests.php");     
  // require_once("../operations/insertVisitors.php");
  // require_once("../operations/getDashboard_Reports.php");

 // var_dump($_SESSION['suppEmail']);
 // die();

  if(isset($_SESSION['suppName'])){
      

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
  <title>Supplier - Dashboard</title>
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

  <?php require_once("../accessory/navbar_supplier.php");?>

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
    <div class="progress" style="height: 40px;">
      <a href="#" class="progress-bar progress-bar-striped progress-bar-animated text-center" style="width:33%">
        QUOTATION
      </a>
      <a href="supp_po.php" class="progress-bar-striped progress-bar-animated text-center" style="width:33%">
        PURCHASE ORDER
      </a>
      <a href="#" class="progress-bar-striped progress-bar-animated text-center" style="width:34%">
        NOTICE TO PROCEED
      </a>
    </div>

    <br>

    <?php echo '<input type="text"  class="form-control forms_textbox d-none" name="txtaccountType" id="txtaccountType" placeholder="ACCOUNT TYPE" value="'.$_SESSION['AccountType'].'" autocomplete="off">'?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <center><p class="h1 pHeader" style="">DASHBOARD</p></center>
          <center><p class="h5 pHeader" style="">Request for Quotation</p></center>
        </div>
      </div> 

      <!-- <div class="row">
        <div class="col-sm-12 ml-5">
          <button class="btn btn-primary pl-5 pr-5 font-weight-bold" style="background-color: #cf4200fff">Filter</button>
        </div>
      </div> -->

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

      


      <div class="row mt-5" >
        <div class="col-sm-3">
          <?php require_once("../accessory/sidebar_actions_supplier.php");?>

        </div>
        <div class="col-sm-9" style="border-width: 1px;">
          <!-- <center><h1 class="display-1">LEFT SIDE</h1></center> -->
          <div class="row">
            <div class="col-md-4 col-xl-3">
              <div class="form-group">
                <select class="form-control" id="select_SearchColumn" name="select_SearchColumn">
                  <option value="1">SEARCH BY PR NUMBER:</option>
                  <option value="2">SEARCH BY QUOTATION NUMBER:</option>
                </select>
              </div>  
            </div>
            <div class="col-lg-9 col-md-12 col-xl-9">
              <input type="text" class="form-control forms_textbox" name="txtSearchDashboard" id="txtSearchDashboard" placeholder="TYPE HERE..." onkeyup="search_tblDashboard()" autocomplete="off" style="border: none;
    border-bottom: 2px double #009aee;">
            </div>
          </div>
          <ul class="list-group tblDashboard_holder" style="overflow-y: auto;">
          <table id="tblDashboard" class="table table-bordered table-striped table-sm table-hover">
            <thead class="thead-white bg-primary text-white tableheader" style="">
              <th scope="col">ID</th>
              <th scope="col">P.R. No.</th>
              <th scope="col">Quotation Number</th>
              <th scope="col">View PR</th>
              <th scope="col">Action</th>
            </thead>
            <tbody id="tblDashboardBody">
              <?php
                // for ($i=0; $i < count($purchrequest_ID); $i++) { 
                //   echo '<tr id="row'.$i.'">
                //       <td>'.$purchrequest_DateTime[$i].'</td>
                //       <td>'.$purchrequest_No[$i].'</td>




                //       <td>Not Yet Assigned</td>
                //       <td>Not Yet Assigned</td>
                //       <td>'.$purchrequest_location[$i].'</td>
                //       <td>'.$acct_Project[$i].'</td>
                //       <td>'.$purchrequest_status[$i].'</td>
                //   </tr>';
                // }
              ?>
            </tbody>
          </table>
        </ul>
        </div>
        






      
    </div>

    <div class="row mt-3" style="padding-top: 1%; padding-right: 1%;">
        <!-- <div class="col-lg-3">
          

        </div> -->


        <div class="col-lg-12">

          <form id="formsubmitRFQ" method="POST" action="#" id="" style="width: 100%">
            <div class="form-row">
              <div class="col-lg-4">



                












                <b><label for="txtPRno">PR NUMBER</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" readonly id="txtPRno" name="txtPRno" placeholder="(SYSTEM-GENERATED)" value="" autocomplete="off" required>';?>
                
              </div> 
              <div class="col-sm-4">
                <b><label for="txtSAI">ICT CLEARANCE NUMBER</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" readonly id="txtICTNo" name="txtICTNo" placeholder="(SYSTEM-GENERATED)" autocomplete="off" value="">';

                  // echo '<input type="text"  class="form-control forms_textbox" name="txtPRNo_crn" id="txtPRNo_crn" placeholder="PR No. CRN" value="'. $ictclearance_no .'" autocomplete="off" readonly required>';
                ?>
               

              </div> 
              <div class="col-sm-4">
                <b><label for="txtSAI">QUOTATION NUMBER</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" readonly id="txtqNo" name="txtqNo" placeholder="(SYSTEM-GENERATED)" autocomplete="off" value="">';

                 
                ?>
                <!-- <input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" required readonly> -->

              </div>
            </div>
            
           
            <br>

            <input type="text"  class="forms_textbox" name="txtNumberOfRows" 
                id="txtNumberOfRows" placeholder="ID" autocomplete="off" 
                style="display: none">

            <table id="tblRFQform" class="table table-responsive table-bordered" style="margin-top: 1%;">
              <thead>
                <tr class="">
                  <!-- <th scope="col-sm-2">STOCK NO.</th>
                  <th scope="col-sm-2">UNIT</th>
                  <th scope="col-sm-6">ITEM DESCRIPTION</th>
                  <th scope="col-sm-1">QTY</th>
                  <th scope="col-sm-1">UNIT COST</th>
                  <th scope="col-sm-1">TOTAL COST</th>
                  <th id="thActions" scope="col-sm-1" style="">ACTION</th> -->
                          <th class="col-sm-1">ITEM NO.</th>
                          <th class="col-sm-2">PARTICULARS</th>
                          <th class="col-sm-1">QTY</th>
                          <th class="col-sm-1">UNIT PRICE</th>
                          <th class="col-sm-2">ITEM AND DESCRIPTION</th>
                          <th class="col-sm-1">SUPPLIER UNIT PRICE</th>                          
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>

            <br>
            
            <div class="form-row">
              <div class="col-lg-3">  
                <b><label for="txtBrandModel">Brand and Model</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtBrandModel" name="txtBrandModel" placeholder="E.g. (HP 131A)" value="" autocomplete="off" required>';?>
                
              </div> 
              <div class="col-sm-3">
                <b><label for="select_DeliveryPeriod">Delivery Period</label></b>
                <?php echo '<select class="form-control prtextboxessupplier" id="select_DeliveryPeriod" name="select_DeliveryPeriod" required>
	                                    <option value="5">5 calendar days</option>
	                                    <option value="15">15 calendar days</option>	                                    
                                      <option value="30">30 calendar days</option>	                                    
                                      <option value="60">60 calendar days</option>	                                    
                                      <option value="120">120 calendar days</option>	                                                                 
	                            </select>';

               
                ?>
               

              </div> 
              <div class="col-sm-3">
                <b><label for="txtWarranty">Warranty Details</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtWarranty" name="txtWarranty" placeholder="E.g. (1-YEAR WARRANTY OF PARTS AND SERVICE)" autocomplete="off" value="" required>';

                 
                ?>
               

              </div>
              <div class="col-sm-3">
                <b><label for="txtPriceValidity">Price Validity</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtPriceValidity" name="txtPriceValidity" placeholder="E.g. (45 DAYS)" autocomplete="off" value="" required>';

                 
                ?>
                <!-- <input type="text" class="form-control prtextboxes" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" required readonly> -->

              </div>
            </div>



            <br>

            <div class="form-row">
              <div class="col-lg-12">
                <b><label for="txt_DeliveryPlace">Delivery Place</label></b>     
                <input type="text" class="form-control prtextboxessupplier" id="txt_DeliveryPlace" name="txt_DeliveryPlace" placeholder="E.g. (3rd Floor, Room 306 DOST-PTRI, Gen. Santos Ave.
Bicutan, Taguig City)" autocomplete="off" required>
              </div>
            </div>

            <br>

            <div class="form-row">
              <div class="col-lg-6">
                <b><label for="txtRFQ_remarks">Remarks <label class="text-muted">(if any)</label></label></b>     
                <textarea rows="5" class="form-control forms_textbox prtextboxessupplier" id="txtRFQ_remarks" name="txtRFQ_remarks"></textarea>
              </div>
              <div class="col-lg-6">
                  <div class="form-group">
                    <b><label for="inputFile_attachments">Additional Attachments <label class="text-muted">(if any)</label></label></b>     
                    <h6><p class="text-muted small"><i>[Notarized OSS, PCAB License (Infra), Exclusive Distributorship, PRC or any Professional License (Consulting Services), etc...]</i></p></h6>
                    <h6><p class="text-muted">Please only upload PDF (.pdf) file types here</p></h6>
                    <input type="file" class="form-control-file prtextboxessupplier" id="inputFile_attachments" name="inputFile_attachments" style="cursor: pointer;" multiple>
                  </div>
              </div>
            </div>




            <!-- <table id="tblAOCform" class="table table-responsive table-bordered" style="margin-top: 1%;">
              <thead>
                <tr class="">
                    <th class="col-sm-1">Item No.</th>
                    <th class="col-sm-1">Quantity</th>
                    <th class="col-sm-1">Unit</th>
                    <th class="col-sm-3">Particulars</th>
                    <th class="col-sm-2">Supplier Name</th>
                    <th class="col-sm-1">Supplier Price</th>
                    <th class="col-sm-3">Supplier Description</th>
                </tr>
              </thead>
              <tbody>
                <tr id="row1">
                  <td><input type="text" class="form-control forms_textbox prtextboxes readonly" name="txtItem1" 
                  id="txtItem1" autocomplete="off" value="1" required style="width: 100%;"></td>
                  <td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnit1" 
                  id="txtUnit1" autocomplete="off" required style="width: 100%;"></td>
                  <td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtQty1" 
                  id="txtQty1" autocomplete="off" required style="width: 100%;"></td>
                  <td><textarea rows="5" class="form-control forms_textbox" id="txtItemParticulars" name="txtItemParticulars" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>
                  <td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtSupplierName1" 
                  id="txtSupplierName1" autocomplete="off" required style="width: 100%;"></td>
                  <td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtSupplierPrice1" 
                  id="txtSupplierPrice1" autocomplete="off" required style="width: 100%;"></td>
                  <td><textarea rows="5" class="form-control forms_textbox" id="txtItemDesc1" name="txtItemDesc1" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>
                </tr>
              </tbody>
            </table> -->


            <br>

           

            

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
                  <center><button type="submit" class="btn formsbuttonsupplier" id="btn_submitQuotation">SUBMIT</button></center>
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

  


<div class="modal fade" id="modalUpdateTempPW" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #CAE492;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">UPDATE PASSWORD</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <center><p id="pChangePW">CHANGE OF YOUR CURRENT PASSWORD</p></center>
        <p style="font-size: smaller; color: red;">Note: Password must be at least 8 characters and must have special character(s)</p>
        <form method="POST" id="formUpdatePW" action="../operations/updatePassword_supplier.php">
          <?php //echo '<input type="text"  class="form-control forms_textbox" name="txtAccountUsername" id="txtAccountUsername" placeholder="Account Username" value="'. $_SESSION['AdminUsername'] .'" autocomplete="off" required style="display: none;">';?>
      <input type="password"  class="form-control forms_textbox txtCenter" minlength="8" name="txtTempPass" id="txtTempPass" placeholder="current password" autocomplete="off" required>
      <input type="password"  class="form-control forms_textbox txtCenter" minlength="8" name="txtNewPass" id="txtNewPass" placeholder="new password" onkeypress="validateCharacters(event)" autocomplete="off" required>
      <p id="pNewPass" class="bg-danger text-white d-none"></p>
      <input type="password"  class="form-control forms_textbox txtCenter" minlength="8" name="txtConfirmPass" id="txtConfirmPass" placeholder="confirm password" onkeypress="validateCharacters(event)" autocomplete="off" required>
      <br>
      <center><button type="submit" class="btn formsbuttonsupplier">SUBMIT</button></center>
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

  $(function(){
        function checkTime(){
            setInterval(function(){
                var storedTimeStamp = sessionStorage.getItem("lastTimeStamp");
                computeTime(storedTimeStamp);
            },1000);
        }

        function computeTime(timeString){
            var currentTime = new Date();
            var formerTime = new Date(timeString);
            var timeDifference = currentTime - formerTime;
            var minutesPast = Math.floor((timeDifference/60000));

            if(minutesPast > 1){
                sessionStorage.removeItem("lastTimeStamp");
                //window.location = "../operations/logout.php";
                location.reload();
                return false;
            }
            else{
                console.log(currentTime + " - " + formerTime + " = " + minutesPast + " minutes past.");
            }
        }

        $(document).mousemove(function(){
            var timeStamp = new Date();
            sessionStorage.setItem("lastTimeStamp", timeStamp);
        });

        checkTime();
    });

  

  $(document).ready(function(){
        $("#pillsupp_createRFQ").addClass("active");

        $("#lnkChangePW").removeClass("d-none");

        getPRs_forQuotation();
    });

  $(document).ready(function(){
     $("#start").click(function(){
        var check = $(this).next().child().html();

     });
  });

  function getPRs_forQuotation(){
        $.ajax({
            method: "POST",
            url: "../Ajax/getPRs_forQuotation.php",
            dataType: "html",
            success: function(data){
                // alert("Hello");
                // console.log(data);
                $("#tblDashboard tbody").html(data);
            }
        });
    }


  $(".btnModules").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
  });

  $(".actionPills").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
  });

  $(".progress-bar-animated").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
  });

  $("#formsubmitRFQ").submit(function(e) {

    e.preventDefault();

  });

  $("#chkAgree").click(function() {
      if($("#chkAgree").is(':checked')){
          // alert("true");
          //proceed
          $(this).parent().css({"background-color":"#d4edda", "border-bottom":"2px solid #155724", "border-radius":"5px"});
      }
      else{
          $(this).parent().css({"background-color":"#f8d7da", "border-bottom":"2px solid #721c24", "border-radius":"5px"});
      }
  });

   $("#btn_submitQuotation").click(function() {
      // $("#loadingDIV").removeClass("d-none");
      // $("#loadingDIV").fadeIn();

      if($("#chkAgree").is(':checked')){
          // alert("true");
          //proceed

          let text = "Are you sure you have already reviewed this P.R. and want to submit this quotation?\n\nChoose OK to proceed or Cancel.";

          if (confirm(text) == true) {
              $("#loadingDIV").removeClass("d-none");
              $("#loadingDIV").fadeIn();    

              var file_data = $('#inputFile_attachments').prop('files');   
              var form_data = new FormData();                  

              //Loop through each file and append to form_data
              $.each(file_data, function(index, file) {
                  form_data.append('files[]', file); // Use 'files[]' to indicate an array of files
              });

              var txt_prNo = $("#txtPRno").val();
              var txt_rfqNo = $("#txtqNo").val();
              var txt_ictNo = $('#txtICTNo').val();

              var txt_remarks = $('#txtRFQ_remarks').val();

              var txtBrandModel = $("#txtBrandModel").val();
              var txtDeliveryPeriod = $("#select_DeliveryPeriod").val();
              var txtWarranty = $('#txtWarranty').val();
              var txtPriceValidity = $('#txtPriceValidity').val();

              var txt_DeliveryPlace = $("#txt_DeliveryPlace").val();

              var txt_numRows = $("#txtNumberOfRows").val();              

              var txtItemNo = [];
              var txtParticulars = [];
              var txtQty = [];
              var txtItemPrice = [];
              var txtItemDesc = [];
              var txtsupp_Price = [];

              // alert($("#txtPR_no").val());

              for (let i = 1; i < txt_numRows+1; i++) {
                  txtItemNo.push($("#txtItem"+i).val());
                  txtParticulars.push($("#txtItemParticulars"+i).val());
                  txtQty.push($("#txtQty"+i).val());
                  txtItemPrice.push($("#txtUnitFee"+i).val());
                  txtItemDesc.push($("#txtItemDesc"+i).val());
                  txtsupp_Price.push($("#txtSupplierPrice"+i).val());
              }


              // Loop through each element in the array and append separately
              $.each(txtItemNo, function(index, value) {
                  form_data.append('itemNo[]', value); // ✅ Correct: Append each item individually
              });

              $.each(txtParticulars, function(index, value) {
                  form_data.append('itemParticulars[]', value);
              });

              $.each(txtQty, function(index, value) {
                  form_data.append('qty[]', value);
              });

              $.each(txtItemPrice, function(index, value) {
                  form_data.append('itemPrice[]', value);
              });

              $.each(txtItemDesc, function(index, value) {
                  form_data.append('itemDesc[]', value);
              });

              $.each(txtsupp_Price, function(index, value) {
                  form_data.append('supp_Price[]', value);
              });

              // form_data.append('itemNo', txtItemNo); // Append additional data here
              // form_data.append('itemParticulars', txtParticulars); // Append additional data here
              // form_data.append('qty', txtQty); // Append additional data here
              // form_data.append('itemPrice', txtItemPrice); // Append additional data here
              // form_data.append('itemDesc', txtItemDesc); // Append additional data here
              // form_data.append('supp_Price', txtsupp_Price); // Append additional data here
              form_data.append('numRows', txt_numRows); // Append additional data here
              form_data.append('prNo', txt_prNo); // Append additional data here
              form_data.append('rfqNo', txt_rfqNo); // Append additional data here
              form_data.append('ictNo', txt_ictNo); // Append additional data here
              form_data.append('brandModel', txtBrandModel); // Append additional data here
              form_data.append('deliveryPeriod', txtDeliveryPeriod); // Append additional data here
              form_data.append('warranty', txtWarranty); // Append additional data here
              form_data.append('priceValidity', txtPriceValidity); // Append additional data here
              form_data.append('deliveryPlace', txt_DeliveryPlace); // Append additional data here
              form_data.append('remarks', txt_remarks); // Append additional data here

              

              // console.log(txt_numRows);

              // console.log(txtItemNo);
              // console.log(txtParticulars);
              // console.log(txtQty);
              // console.log(txtItemPrice);
              // console.log(txtItemDesc);
              // console.log(txtsupp_Price);

              $.ajax({
                  method : "POST",
                  url : "../Ajax/submitQuotation.php",
                  data : form_data,
                  cache: false,
                  contentType: false,
                  processData: false,
                  dataType : "html",
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

                    console.log(data);
                    // alert(data.prUnits);
                    if(data.includes("ERROR")){
                      // alert(data);
                      // console.log(data);
                      $("#modalh4dMessage").html(data);
                      $("#modalDanger").modal();
                      $("#loadingDIV").fadeOut();
                    }
                    else{
                      // console.log(data);
                      $("#txtPRno").val("");
                      $("#txtqNo").val("");
                      $("#txtICTNo").val("");
                      $("#txtBrandModel").val("");
                      //$("#txtDeliveryPeriod").val("");
                      
                      $('#txtWarranty').val("");
                      $('#txtPriceValidity').val("");
                      $('#txt_DeliveryPlace').val("");
                      $('#txtRFQ_remarks').val("");
                      $('#tblRFQform tbody').empty();
                      $("#chkAgree").prop("checked", false);
                      $("#chkAgree").removeProp("background-color");
                      $("#chkAgree").removeProp("border-bottom");
                      $("#chkAgree").removeProp("border-radius");

                      $("#modalh4cMessage").html(data);
                      $("#ModalConfirmation").modal();
                      $("#loadingDIV").fadeOut();

                      getPRs_forQuotation();
                      // alert(data);
                      // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
                    }

                    if($("#chkAgree").is(':checked')){
                        // alert("true");
                        //proceed
                        $(this).parent().css({"background-color":"#d4edda", "border-bottom":"2px solid #155724", "border-radius":"5px"});
                    }
                    else{
                        $(this).parent().css({"background-color":"#f8d7da", "border-bottom":"2px solid #721c24", "border-radius":"5px"});
                    }


                  }

                });

                // $.ajax({
                //   method : "POST",
                //   url : "../Ajax/submitQuotation.php",
                //   data : {
                //           itemNo : txtItemNo, 
                //           itemParticulars : txtParticulars, 
                //           qty : txtQty, 
                //           itemPrice : txtItemPrice, 
                //           itemDesc : txtItemDesc, 
                //           supp_Price : txtsupp_Price, 
                //           numRows : txt_numRows, 
                //           prNo : txt_prNo, 
                //           rfqNo : txt_rfqNo, 
                //           ictNo : txt_ictNo, 
                //           brandModel : txtBrandModel, 
                //           deliveryPeriod : txtDeliveryPeriod, 
                //           warranty : txtWarranty, 
                //           priceValidity : txtPriceValidity
                //         },
                //   dataType : "html",
                //   success : function(data){
                //     // $("#txtRDDID").prop("value", data);
                //     // if(data == "ERROR"){
                //       // alert(data);
                //     // }
                //     // else{
                //     //   console.log(data);
                //     //   window.open('../pages/print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
                //     // }
                //     // console.log(data);
                //     // alert(data.prUnits);

                //     // console.log(data);
                //     // alert(data.prUnits);
                //     if(data.includes("ERROR")){
                //       // alert(data);
                //       // console.log(data);
                //       $("#modalh4dMessage").html(data);
                //       $("#modalDanger").modal();
                //       $("#loadingDIV").fadeOut();
                //     }
                //     else{
                //       // console.log(data);
                //       $("#txtPRno").val("");
                //       $("#txtqNo").val("");
                //       $("#txtICTNo").val("");
                //       $("#txtBrandModel").val("");
                //       //$("#txtDeliveryPeriod").val("");
                //       $('#txtWarranty').val("");
                //       $('#txtPriceValidity').val("");
                //       $('#tblRFQform tbody').empty();
                //       $("#chkAgree").prop("checked", false);
                //       $("#chkAgree").removeProp("background-color");
                //       $("#chkAgree").removeProp("border-bottom");
                //       $("#chkAgree").removeProp("border-radius");

                //       $("#modalh4cMessage").html(data);
                //       $("#ModalConfirmation").modal();
                //       $("#loadingDIV").fadeOut();

                //       getPRs_forQuotation();
                //       // alert(data);
                //       // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
                //     }

                //     if($("#chkAgree").is(':checked')){
                //         // alert("true");
                //         //proceed
                //         $(this).parent().css({"background-color":"#d4edda", "border-bottom":"2px solid #155724", "border-radius":"5px"});
                //     }
                //     else{
                //         $(this).parent().css({"background-color":"#f8d7da", "border-bottom":"2px solid #721c24", "border-radius":"5px"});
                //     }




                //   }
                // });
          }
          else{
              //cancel
          }
      }
      else{
          // alert("false");
          // $("#chkMoreAgree").next().css("background-color","red");
          var DOM = document.getElementsByClassName("form-row");
          $("#chkAgree").parentsUntil(DOM, "div.col").css({"background-color":"#f8d7da", "border-bottom":"2px solid #721c24", "border-radius":"5px"});
          $("#chkAgree").focus();
          console.log($("#chkAgree").closest("div"));
          $("#dangerpopup").fadeIn(2000, "linear");
          
      }



      


  });

  

  var numberOfRows = 0;
  $('#tblDashboard').on('click','#lnkCreateRFQ', function(){
      $('#tblRFQform tbody').empty();

      var currentRow = $(this).closest("tr");

      var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
      // alert (col_ID);
      // var colServiceName = currentRow.find("td:eq(3)").text();
      var col_Qno = currentRow.find("td:eq(2)").text();
      var col_PRno = currentRow.find("td:eq(1)").text();
      // var col_ICTno = currentRow.find("td:eq(2)").text();
      // var colStatus = currentRow.find("td:eq(10)").text();

        $("#txtPRno").val(col_PRno);
        $("#txtqNo").val(col_Qno);

        // alert("test");

        $.ajax({
          method : "POST",
          url : "../Ajax/retrievePR_toattachRFQ.php",
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

            // console.log(data);
            // alert(data.prUnits);
            $('#txtICTNo').val(data.ictNo[0]);

            // console.log($("#row1"));

            // alert(data.particulars.length);
            // alert(data.rfq.length);
            // alert(data.particulars.length * data.rfq.length);
            // var totalRowsInPage = 1;

            for (let i = 0; i < data.desc.length; i++) {
              
                // console.log("test");
                numberOfRows += 1;
                var numberOfparticulars = i+1;

                // alert(numberOfRows);

                $("#tblRFQform tbody").append('<tr id="row'+numberOfparticulars+'" class="newAddedRow">'+
                        '<td><input type="text" class="form-control forms_textbox prtextboxessupplier" readonly name="txtItem'+numberOfparticulars+'" '+
                        'id="txtItem'+numberOfparticulars+'" autocomplete="off" value="'+numberOfparticulars+'" required style="width: 100%;"></td>'+
                        '<td><textarea rows="5" class="form-control forms_textbox prtextboxessupplier" id="txtItemParticulars'+numberOfparticulars+'" name="txtItemParticulars'+numberOfparticulars+'" readonly >'+data.desc[i]+'</textarea></td>'+
                        '<td><input type="text" readonly class="form-control forms_textbox prtextboxessupplier" name="txtQty'+numberOfparticulars+'" '+
                        'id="txtQty'+numberOfparticulars+'" value="'+data.qty[i]+' '+data.unit[i]+' " autocomplete="off" required style="width: 100%;"></td>'+
                        '<td><input type="text"  class="form-control forms_textbox prtextboxessupplier" name="txtUnitFee'+numberOfparticulars+'" '+
                          'id="txtUnitFee'+numberOfparticulars+'" value="Php '+data.unitCost[i]+'" autocomplete="off" required readonly style="width: 100%;"></td>'+

                        

                        '<td><textarea rows="5" class="form-control forms_textbox prtextboxessupplier" id="txtItemDesc'+numberOfparticulars+'" name="txtItemDesc'+numberOfparticulars+'" required></textarea></td>'+
                        '<td><input type="number" step="any" class="form-control forms_textbox prtextboxessupplier" name="txtSupplierPrice'+numberOfparticulars+'" '+
                        'id="txtSupplierPrice'+numberOfparticulars+'" value="" autocomplete="off" required style="width: 100%;"></td>'+
                        
                        '</tr>');
                
                numberOfRows++;
                $(".newAddedRow").fadeIn();
                
            }

            $("#txtNumberOfRows").val(numberOfparticulars);




          }
        });
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

  $("#txtNewPass").blur(function() {
    var newpass = $(this).val();
    var confirmpass = $("#txtConfirmPass").val();

    if(confirmpass)
    {
      // alert("MAY LAMAN");
      if(confirmpass == newpass){
        // alert("tama");
        $("#txtConfirmPass").css("border-width", "medium");
        $("#txtConfirmPass").css("border-color", "green");
        $(this).css("border-width", "medium");
        $(this).css("border-color", "green");
        $("#pNewPass").fadeOut();
      }
      else{
        // alert("mali");
        $("#txtConfirmPass").css("border-width", "medium");
        $("#txtConfirmPass").css("border-color", "red");
        $(this).css("border-width", "medium");
        $(this).css("border-color", "red");
        $("#pNewPass").html("Passwords Do Not Match");
        $("#pNewPass").removeClass("d-none");
        $("#pNewPass").fadeIn();
      }
    }
    else{
      // alert("WALANG LAMAN");
      $(this).css("border-width", "medium");
      $(this).css("border-color", "red");
      $("#pNewPass").html("Passwords Do Not Match");
      $("#pNewPass").removeClass("d-none");
    }
  });

  $("#txtConfirmPass").blur(function() {
    var confirmpass = $(this).val();
    var newpass = $("#txtNewPass").val();

    if(confirmpass)
    {
      // alert("MAY LAMAN");
      if(confirmpass == newpass){
        // alert("tama");
        $("#txtNewPass").css("border-width", "medium");
        $("#txtNewPass").css("border-color", "green");
        $(this).css("border-width", "medium");
        $(this).css("border-color", "green");
        $("#pNewPass").fadeOut();
      }
      else{
        // alert("mali");
        $("#txtNewPass").css("border-width", "medium");
        $("#txtNewPass").css("border-color", "red");
        $(this).css("border-width", "medium");
        $(this).css("border-color", "red");
        $("#pNewPass").html("Passwords Do Not Match");
        $("#pNewPass").removeClass("d-none");
        $("#pNewPass").fadeIn();
      }
    }
    else{
      // alert("WALANG LAMAN");
      $(this).css("border-width", "medium");
      $(this).css("border-color", "red");
      $("#pNewPass").html("Passwords Do Not Match");
      $("#pNewPass").removeClass("d-none");
    }
  });

  $("#txtNewPass").keyup(function() {
    var newpass = $(this).val();
    var confirmpass = $("#txtConfirmPass").val();

    if(confirmpass)
    {
      // alert("MAY LAMAN");
      if(confirmpass == newpass){
        // alert("tama");
        $("#txtConfirmPass").css("border-width", "medium");
        $("#txtConfirmPass").css("border-color", "green");
        $(this).css("border-width", "medium");
        $(this).css("border-color", "green");
        $("#pNewPass").fadeOut();
      }
      else{
        // alert("mali");
        $("#txtConfirmPass").css("border-width", "medium");
        $("#txtConfirmPass").css("border-color", "red");
        $(this).css("border-width", "medium");
        $(this).css("border-color", "red");
        // $(this).tooltip({title: "PASSWORDS DO NOT MATCH", placement: "top"}); 
      }
    }
    else{
      // alert("WALANG LAMAN");
    }
  });

  $("#txtConfirmPass").keyup(function() {
    var confirmpass = $(this).val();
    var newpass = $("#txtNewPass").val();

    if(confirmpass)
    {
      // alert("MAY LAMAN");
      if(confirmpass == newpass){
        // alert("tama");
        $("#txtNewPass").css("border-width", "medium");
        $("#txtNewPass").css("border-color", "green");
        $(this).css("border-width", "medium");
        $(this).css("border-color", "green");
        $("#pNewPass").fadeOut();
      }
      else{
        // alert("mali");
        $("#txtNewPass").css("border-width", "medium");
        $("#txtNewPass").css("border-color", "red");
        $(this).css("border-width", "medium");
        $(this).css("border-color", "red");
      }
    }
    else{
      // alert("WALANG LAMAN");
    }
  });

  function validateCharacters(evt){
    // var character = String.fromCharCode(evt.which);
    var character = evt.key;
  //    var code = evt.which;


    var specialChars = ["!","#","$","%","&","(",")","*","+",",","-",".","/",":",";","<","=",">","?","@","[","]","^","_","`","{","|","}","~","]",";"];

    // alert(evt.key);
    if(specialChars.includes(character)){
      // alert("TRUE");
      $("#txtTempPass").css("border-width", "medium");
      $("#txtTempPass").css("border-color", "green");
    }
    else{
      // alert("FALSE");
      // $("#btnSubmitPass").css("border-width", "medium");
      // $("#btnSubmitPass").css("border-color", "red");
    }
  }

  $("#formUpdatePW").submit(function(e) {
    // alert($("#txtConfirmPass").css("border-color"));
    var redColor = "rgb(255, 0, 0)";
    if($("#txtConfirmPass").css("border-color") == redColor || $("#txtNewPass").css("border-color") == redColor){
      alert("PASSWORDS DO NOT MATCH!");
      e.preventDefault(e);
    }
    else{
      //proceed
      var finalpass = $("#txtConfirmPass").val();

      // var specialChars = ["!","#","%","&","@"];
      var specialChars = ["!","#","$","%","&","(",")","*","+",",","-",".","/",":",";","<","=",">","?","@","[","]","^","_","`","{","|","}","~","]",";"];

      for(var i=0; i<finalpass.length; i++){
        // alert(finalpass[i]);
        if(specialChars.includes(finalpass[i])){
          // alert("TRUE");
          $("#txtTempPass").css("border-width", "medium");
          $("#txtTempPass").css("border-color", "green");

          var achieved = true;

          $(this).unbind();
          $(this).submit();
        }
        else{
          if(i == finalpass.length-1){
            if(achieved != true){
              if(specialChars.includes(finalpass[i])){
                // alert("TRUE");
                $("#txtTempPass").css("border-width", "medium");
                $("#txtTempPass").css("border-color", "green");
                $(this).unbind();
                $(this).submit();
              }
              else{
                $("#txtConfirmPass").css("border-width", "medium");
                $("#txtConfirmPass").css("border-color", "red");
                $("#txtNewPass").css("border-width", "medium");
                $("#txtNewPass").css("border-color", "red");
                alert("PASSWORDS DO NOT CONTAIN ANY SPECIAL CHARACTERS!");
                e.preventDefault(e);
              }
            }
            else{
              $("#txtTempPass").css("border-width", "medium");
              $("#txtTempPass").css("border-color", "green");
              $(this).unbind();
              $(this).submit();
            }
          }
          else{
            //just loop again
            // alert("just loop");
            e.preventDefault(e);
          }
          // alert("FALSE");
          // $("#btnSubmitPass").css("border-width", "medium");
          // $("#btnSubmitPass").css("border-color", "red");
        }
      }
    }
  });


  $("#select_approvedPR").change(function() {
    var answer_ddapprovedPR = $(this).val();

    if(answer_ddapprovedPR == "Approved"){
      $("#h1_numberOfApprovedPRs").fadeIn();
      $("#h1_numberOfCancelledPRs").fadeOut();
      $("#h1_numberOfPendingPRs").fadeOut();
    }

    else if(answer_ddapprovedPR == "Cancelled"){
      $("#h1_numberOfApprovedPRs").fadeOut();
      $("#h1_numberOfCancelledPRs").fadeIn();
      $("#h1_numberOfPendingPRs").fadeOut();
    }

    else if(answer_ddapprovedPR == "Pending"){
      $("#h1_numberOfApprovedPRs").fadeOut();
      $("#h1_numberOfPendingPRs").fadeIn();
      $("#h1_numberOfCancelledPRs").fadeOut();
    }

    // else if(answer_client == "Type of Client"){
    //  $(this).val("Academe");
    //  alert("Please select a Type of Client");
    // }

    else{
     //do nothing
    }
  });

</script>

</html>
