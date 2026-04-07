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
    require_once("../operations/generateCRN.php");
    

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
                            <td id="rfqNo">'.$prquotation_no[$i].'</td>
                            <td>'.$purchrequest_No[$i].'</td>
                            <td>'.$purchrequest_DateTime[$i].'</td>
                            <td><a href="print_pr_pdf.php?ID='.$purchrequest_ID[$i].'&acctID='.$account_ID[$i].'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>View This Purchase Request</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>




                            <td>'.$purchrequest_location[$i].'</td>
                            <td>'.$pr_datetimelastaction[$i].'</td>
                            <td>'.$pr_lastprocessedby[$i].'</td>
                            <td>'.$purchrequest_status[$i].'</td>
                            <td><a id="lnkAttachRFQ" href="#lblPRFormNo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><b>Create RFQ for this</b><span class="badge badge-danger badge-pill animated flash infinite">NEW</span></a></td>
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


      <!--MODAL-->

<div class="modal fade" id="modalAttachRFQ" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #AC92E4;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">ATTACH QUOTATION</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPRno" id="txtPRno" placeholder="PR Number" autocomplete="off" required>



          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->
      <div id="divGenerateRFQ">
        <div class="form-row">
          <div class="col">
            <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="Quotation Number" style="border: none; text-align: center; font-size: larger;">
          </div>
          <div class="col">
            <input type="text"  class="form-control forms_textbox readonly" name="txtRFQno" id="txtRFQno" placeholder="Quotation Number" autocomplete="off" required>
          </div>
        </div>

        <div class="form-row">
          <div class="col">
            <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="PR Category" style="border: none; text-align: center; font-size: larger;">
          </div>
          <div class="col">
            <select class="form-control" id="select_prcategory" name="select_prcategory" style="border-width: 1px 1px 2px; border-style: solid; border-color: rgb(116, 0, 154);">
              <option value="ICT">Communication Equipment & Parts and Accessories, Computer Furniture, Fixtures, Furniture, Furniture Parts and Accessories, Information Technology, Information Technology Parts & Accessories & Peripherals</option>
              <option value="Construction">Construction Materials and Supplies, and, Hardware and Construction Supplies, Machine Tools, Signage and Accessories, Metal Fabrication</option>
              <option value="Medical">Diagnostic and Laboratory Services, Drugs and Medicines, Medical and Dental Equipment Medical Supplies and Laboratory Instrument</option>
              <option value="Vehicle">Vehicle Parts and Accessories, Vehicle Repair and Maintenance, Fuels/Fuel Additives & Lubricants & Anti Corrosive</option>
              <option value="Events">Events Management, Hotel and Lodging and Meeting Facilities, Lease and Rental of Property or Building</option>
              <option value="Industrial">Industrial Machinery and Equipment  Industrial pumps and compressors, Laboratory Supplies and Equipment</option>
              <option value="Water">Services, Video Production Services, Water and Waste Water Treatment Supply & Disposal, Water Service Connection Materials/Fittings</option>
              <option value="Firefighting">Fire Fighting & Rescue and Safety Equipment, Catering Services</option>
              <option value="Janitorial">Janitorial Supplies, Laundry Services, Pest Control Services</option>
              <option value="Repair">General Repair and Maintenance Services</option>
              <option value="Architectural">Construction Projects, Architectural Design, Consulting Services</option>
              <option value="Corporate">Corporate Giveaways, and Tokens and Awards</option>
              <option value="Printing">Printing Services and Printing Supplies</option>
              <option value="Office">Office Equipment, Parts, Accessories, Consumables, Supplies and Devices</option>
              <option value="Aircon">Airconditioning Units and Maintenance Services</option>
              <option value="Chemicals">Chemical Detergents, Chemicals, and Chemical Products</option>
              <option value="Textiles">Textiles and Garments</option>
              <option value="Agricultural">Agricultural Chemicals</option>
              <option value="Manufacturer">Manufacturer</option>
              <option value="Service">Service Provider</option>
              <option value="Trading">Trading Firms</option>
              <option value="Representation">Representation Expenses</option>
              <option value="Registration">Registration/Training Fees</option>
              <option value="Delivery">Transportation and Delivery Expenses</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="col">
            <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="Duration" style="border: none; text-align: center; font-size: larger;">
          </div>
          <div class="col">
            <select class="form-control" id="select_RFQduration" name="select_RFQduration" style="border-width: 1px 1px 2px; border-style: solid; border-color: rgb(116, 0, 154);">
              <option value="3">3 days</option>
              <option value="5">5 days</option>
              <option value="7">7 days</option>
              <option value="10">10 days</option>
            </select>
          </div>
        </div>

          

        <br>

        <div id="divReposting" style="display: none;">
          <center><input type="checkbox" id="chkReposting" name="chkReposting" checked>
            <label for="chkReposting">For Reposting</label><small class="text-muted"> (leave unchecked if not)</small>
            <br>
          </center>
        </div>
        <center><a href="#" id="lnkPreview_RFQ" class="btn btn-md btn-primary" style="width: max-content">GENERATE QUOTATION</a></center>

      </div>

        

        <form id="formAffix" action="../operations/uploadRFQ.php" method="POST" enctype="multipart/form-data">
          
          



          <div class="form-group">     
            <center><a href="#" target="_blank" id="lnkView_GeneratedRFQ" class="btn btn-md btn-primary" style="width: max-content">VIEW QUOTATION PDF</a></center>
            <br>
            <center><h5><p class="">Upload of accomplished RFQ</p></h5></center>
            <div class="form-row">
              <div class="col">
                <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="RFQ Date" style="border: none; text-align: center; font-size: larger;">
              </div>
              <div class="col">
                <input type="text"  class="form-control forms_textbox readonly" name="txtRFQdateSubmit" id="txtRFQdateSubmit" placeholder="RFQ Date" autocomplete="off" required>
              </div>
            </div>

            <div class="form-row">
              <div class="col">
                <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="Quotation Number" style="border: none; text-align: center; font-size: larger;">
              </div>
              <div class="col">
                <input type="text"  class="form-control forms_textbox readonly" name="txtRFQnoSubmit" id="txtRFQnoSubmit" placeholder="Quotation Number" autocomplete="off" required>
              </div>
            </div>

            <div class="form-row">
              <div class="col">
                <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="PR Number" style="border: none; text-align: center; font-size: larger;">
              </div>
              <div class="col">
                <input type="text"  class="form-control forms_textbox readonly" name="txtPRnoSubmit" id="txtPRnoSubmit" placeholder="PR Number" autocomplete="off" required>
              </div>
            </div>
            
            <div class="form-row">
              <div class="col">
                <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="Company Name" style="border: none; text-align: center; font-size: larger;">
              </div>
              <div class="col">
                <input type="text"  class="form-control forms_textbox" name="txtRFQcompanySubmit" id="txtRFQcompanySubmit" placeholder="ABC Trading Inc." autocomplete="off" onkeyup="validateCharacters(this)" required>
              </div>
            </div>


            <div class="form-row">
              <div class="col">
                <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="Company Address" style="border: none; text-align: center; font-size: larger;">
              </div>
              <div class="col">
                <textarea rows="3" required placeholder="123 11th Street One Ave. Brgy. Dos Municipality, Province" onkeyup="validateCharacters(this)" class="form-control forms_textbox" id="txtRFQaddressSubmit" name="txtRFQaddressSubmit"></textarea>
              </div>
            </div>

            <div class="form-row">
              <div class="col">
                <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="Company Email" style="border: none; text-align: center; font-size: larger;">
              </div>
              <div class="col">
                <input type="email"  class="form-control forms_textbox" name="txtRFQcompanyemail" id="txtRFQcompanyemail" placeholder="abctrading@email.com" autocomplete="off" required>
              </div>
            </div>

            <div class="form-row">
              <div class="col">
                <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="Company Telephone Number" style="border: none; text-align: center; font-size: larger;">
              </div>
              <div class="col">
                <input type="text"  class="form-control forms_textbox" name="txtRFQcompanytel" id="txtRFQcompanytel" placeholder="8812-0123 / 0901-234-56789 / ....." autocomplete="off" required>
              </div>
            </div>
            

            <div class="form-row">
              <div class="col">
                <input type="text"  class="form-control forms_textbox" tabIndex="-1" autocomplete="off" value="End-User" style="border: none; text-align: center; font-size: larger;">
              </div>
              <div class="col">
                <input type="text"  class="form-control forms_textbox readonly" name="txtRFQenduserSubmit" id="txtRFQenduserSubmit" placeholder="End-User" autocomplete="off" required>
              </div>
            </div>

            <br>
            
            <h6><p class="text-muted">Please only upload PDF (.pdf) file type here (1 whole file only)</p></h6>
            <input type="file" class="form-control-file border" name="inputFile_RFQ" required style="cursor: pointer;">
          </div>

          <center><button type="submit" class="btn formsbutton">SUBMIT QUOTATION</button></center>
        </form>
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

  $('#chkReposting').click(function(){
          var actualNumber = $("#txtRFQno").val();

          if($('#chkReposting').is(':checked')){
              // alert("checked");            
              $("#txtRFQno").val(actualNumber+"R");
          }
          else{
              // alert("unchecked");
              var length = actualNumber.length;
              // var lastCharacter = length - 1;

              $("#txtRFQno").val(actualNumber.slice(0, length-1));
          }


      });

  $(document).ready(function(){
      $("#linav_Modules").addClass("active");
      $("#pill_editPR").addClass("active");

      


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

  $(".navLinks").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

    $(".actionPills").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });


  $("#tblPR tr td#rfqNo").dblclick(function(){
        // alert($(this).html());
        var rfqNum = $(this).html();
        var currentRow = $(this).closest("tr");
        var col_PRno = currentRow.find("td:eq(2)").text();
        // console.log(col_ID);
        // var col_ID = currentRow.find("td:eq(0)").text();
        // alert(col_PRno);

        if(rfqNum == "Not Yet Assigned"){
          //do nothing
        }
        else{
            $("#divGenerateRFQ").css("display", "block");
            $("#formAffix").css("display", "none");
            
            $("#txtPRno").val(col_PRno);

            $("#modalAttachRFQ").modal();

            $.ajax({
                method : "POST",
                url : "../Ajax/retrievepreview_RFQ.php",
                data : {prNo : col_PRno},
                dataType : "JSON",
                success : function(data){
                  
                    console.log(data);
                    // alert(data.prUnits);                
                    
                    $('#txtRFQno').val(data.dce[1] + "R");
                }
              });
        }

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

  
  $("#lnkPreview_RFQ").click(function(){
    var txtQuotation_no = $("#txtRFQno").val();
    var txtPR_no = $("#txtPRno").val();
    var select_prcategory = $("#select_prcategory").val();
    var select_RFQduration = $("#select_RFQduration").val();

    // alert(txtPR_no);

    $.ajax({
      method : "POST",
      url : "../Ajax/affixQuotation_number.php",
      data : {quotationNo : txtQuotation_no, pr_no : txtPR_no, prcategory : select_prcategory, rfqDuration : select_RFQduration},
      dataType : "html",
      success : function(data){
        // $("#txtRDDID").prop("value", data);
        if(data.includes("ERROR")){
          alert(data);
        }
        else{
          alert(data);
          window.location.href = "print_rfq_pdf.php?ID=" + $("#txtPRno").val();
          console.log(data);
          // alert(data);
          // window.location.reload();
          // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
        }
      }
    });
    // window.open('https://support.wwf.org.uk/earth_hour/index.php?type=individual',
    // '_blank' // <- This is what makes it open in a new window.
    // );
  });

  $('#tblPR').on('click','#lnkAttachRFQ', function(){
    //get the current row
    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    var col_RFQnumber = currentRow.find("td:eq(1)").text();
    // alert (col_ID);
    // var colServiceName = currentRow.find("td:eq(3)").text();
    var col_PRno = currentRow.find("td:eq(2)").text();
    var colStatus = currentRow.find("td:eq(8)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);
    

    $("#txtPRno").val(col_PRno);
    $("#txtPRnoSubmit").val(col_PRno);
    $("#lnkView_GeneratedRFQ").attr("href", "print_rfq_pdf.php?ID=" + col_PRno);

    // alert(col_RFQnumber);

    if(col_RFQnumber == "Not Yet Assigned"){
      $("#divGenerateRFQ").css("display", "block");
      $("#formAffix").css("display", "none");
    }
    else{
      $("#divGenerateRFQ").css("display", "none");
      $("#formAffix").css("display", "block");
    }

    $("#modalAttachRFQ").modal();

    $.ajax({
      method : "POST",
      url : "../Ajax/retrievepreview_RFQ.php",
      data : {prNo : col_PRno},
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
        console.log(data);
        // alert(data.prUnits);

        
        

        $('#txtRFQdateSubmit').val(data.dce[0]);
        if(col_RFQnumber == "Not Yet Assigned")
          $('#txtRFQno').val(data.dce[1]);
        else{
          $('#txtRFQnoSubmit').val(col_RFQnumber);
        }
        $('#txtRFQenduserSubmit').val(data.dce[2]);

        $('.readonly').css({"border": "1px solid #74009A", "border-bottom": "2px solid #74009A"});
      }
    });

    // if($('#txtRFQdateSubmit').val() != ""){
    //   alert("true");
    //   $('.readonly').css({"border": "1px solid #74009A", "border-bottom": "2px solid #74009A"});
    // }
    // else{
    //   //do nothing
    //   alert("false");
    // }


    // if(colStatus == "FOR SIGNATURE (REQUESTOR/PROJECT LEADER)"){
    //   $.ajax({
    //     method : "POST",
    //     url : "../Ajax/retrievePR_toedit.php",
    //     data : {id : col_ID},
    //     dataType : "JSON",
    //     success : function(data){
    //       // $("#txtRDDID").prop("value", data);
    //       // if(data == "ERROR"){
    //       //   alert(data);
    //       // }
    //       // else{
    //       //   console.log(data);
    //       //   window.open('../pages/print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
    //       // }
    //       console.log(data.prUnits);
    //       alert(data.prUnits);

    //       $('#txtCharging').val(data.cpr[0])
    //       $('#txtPurpose').val(data.cpr[1])
    //     }
    //   });
    // }
    // else if(colStatus == "For Clearance (PPGSS)"){
    //   alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
    //   // $("#modalPPGSS").modal();
    // }
    // else{
    //   //DO NOTHING
    // }
    // else if(colStatus == "For Clearance (PPGSS)"){
    //   alert("THIS IS NOW FOR CLEARANCE IN PPGSS");
    //   $("#modalPPGSS").modal();
    // }
    

  });

    function computeTotalFee(x){
    // console.log($("table :focus"));
    // console.log($(x).val());
    // console.log($(x).parent().next().children());
    // console.log($(x).parent().next().next().children());
    var qty = $(x).val();
    var unitFee = $(x).parent().next().children().val();
    var totalFee = unitFee * qty;
    $(x).parent().next().next().children().val(totalFee.toFixed(2));
  }

  function computeTotalFee_fromUnit(x){
    // console.log($("table :focus"));
    // console.log($(x).val());
    // console.log($(x).parent().next().children());
    // console.log($(x).parent().next().next().children());
    var unitFee = $(x).val();
    var qty = $(x).parent().prev().children().val();
    var totalFee = unitFee * qty;
    $(x).parent().next().children().val(totalFee.toFixed(2));   
  }

  // function computeFinalTotalFee(){
  //  // console.log($("td input.textboxTotal"));
  //  // console.log($("td input.textboxTotal").length);
  //  var txtTotalValues = 0;
  //  var arrayTxtTotal = [];
    
  //  var numberOfTotaltextbox = $("td input.textboxTotal").length;
  //  // console.log(numberOfTotaltextbox);
  //  if(numberOfTotaltextbox > 1){
  //    // console.log($("td input.textboxTotal"));
  //    $("td input.textboxTotal").each(function(){
  //      // arrayTxtTotal += $(this).val();
  //      arrayTxtTotal.push(parseFloat($(this).val())); 
  //      //
  //    });

  //    txtTotalValues = arrayTxtTotal.reduce(function(a, b){
  //      return a + b;
  //    }, 0);

  //    $("#txtFinalFee").val(txtTotalValues.toFixed(2));

  //    $("#txtFinalFee").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
  //      $(this).removeClass('animated flash');
  //    });       
  //  }
  //  else{
  //    // console.log($("td input.textboxTotal").val());
  //    var finalfee = parseFloat($("td input.textboxTotal").val());
  //    $("#txtFinalFee").val(finalfee.toFixed(2));

  //    $("#txtFinalFee").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
  //      $(this).removeClass('animated flash');
  //    }); 
  //  }
  //  // console.log(txtTotalValues);
  //  // console.log(arrayTxtTotal);
  // }

  var numberOfRows = 1;
  $("#btnAddRow").click(function() {
    // var lastTR = $("#row1").clone();
    // var finalTR = lastTR
    // console.log(lastTR);
    // $("#row1").after(finalTR);
    // console.log($("tr tbody").last());
    // $("#thActions").css("display", "block");
    // $("#txtFinalFee").val("EXPERIMENT");
    numberOfRows += 1;
    var lastRow = $("tbody tr").last();
    lastRow.after('<tr id="row'+numberOfRows+'" class="newAddedRow">'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
              'id="txtUnitQty'+numberOfRows+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>'+
              'id="txtTotal'+numberOfRows+'" autocomplete="off" value=0 required readonly style="width: 100%;"></td>'+
              '<td style="vertical-align: middle;" onclick="deleteRow(this)"><i id="btnDeleteRow" class="fas fa-minus-circle fa-2x" style="color: #8d0101; cursor: pointer;"></i></td>'+
            '</tr>');

    $(".newAddedRow").fadeIn();
  });

  function deleteRow(x){
    var allInputs = [];
    var allInputData = [];
    var withData = 0;
    $(x).prevAll().each(function(){
      // console.log($(this).children());
      allInputs.push($(this).children());
    });

    // console.log(allInputs[4]);

    for(var i=0; i<allInputs.length; i++){
      /*allInputData.push(allInputs[i].val());*/
      // console.log(allInputs[i].val() == 0);
      if(allInputs[i].val() != 0){
        // console.log("Eto ay may laman: " + i);
        // withData = withData + 1;
        withData++;
      }     
      else{
        // console.log("Eto ay walang laman: " + i);
      }
      // console.log(withData);
    }

    if(withData > 0){
      var deleteAnswer = confirm("Are you sure you want to delete this?");

      if(deleteAnswer == true){
        // console.log("YES");
        // console.log(x);
        $(x).parent().fadeOut();

        setTimeout(function(){
          $(x).parent().remove();
        }, 400);
        // console.log($(this))
      }
      else{
        // console.log("NO");
      }
    }
    else{
      // $(x).parent().fadeOut();
      // $(x).parent().remove();

      $(x).parent().fadeOut();

      setTimeout(function(){
        $(x).parent().remove();
      }, 400);

    }

    // var allInputs = $(x).prevAll();
    // var selected = allInputs[0];
    // console.log(selected.children());
  }

  $("form").submit(function(e){
    // console.log($("table tbody tr"));
    // console.log($("table tbody tr").length);
    // alert($("#tblICTClearanceform tbody tr").length);
    $("#txtNumberOfRows").val($("#tblICTClearanceform tbody tr").length);
    // var newFinalFee = $("#txtFinalFee").val();
    // $("#txtNewFinalFee").val(newFinalFee);
    // var numberOfTotaltextbox = $("td input.prtextboxes").length;
    // // console.log(numberOfTotaltextbox);
    // if(numberOfTotaltextbox > 1){
    //  if($("#txtDiscount").val()){
    //    var discountpercentage = $("#txtDiscount").val();
    //    // alert(discountpercentage/100);
    //    var discount = discountpercentage / 100;

    //    var subtotal = $("#txtFinalFee").val();
    //    var discountamount = subtotal * discount;
    //    // alert($("td input.textboxTotal").val() - ($("td input.textboxTotal").val() * discount));

    //    // $("#txtDiscount").val(discountamount);
    //    $("#txtDiscount").val(discountamount.toFixed(2));
    //    // alert($("#txtDiscount").val());
    //    // e.preventDefault();
    //    $("#txtDiscount").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
    //      $(this).removeClass('animated flash');
    //    });
    //  }               
    // }
    // else{
    //  // console.log($("td input.textboxTotal").val());
    //  if($("#txtDiscount").val()){
    //    // console.log("with discount");
    //    var discountpercentage = $("#txtDiscount").val();
    //    // alert(discountpercentage/100);
    //    var discount = discountpercentage / 100;

    //    var subtotal = $("#txtFinalFee").val();
    //    var discountamount = subtotal * discount;
    //    // alert($("td input.textboxTotal").val() - ($("td input.textboxTotal").val() * discount));

    //    $("#txtDiscount").val(discountamount.toFixed(2));
    //    // alert($("#txtDiscount").val());
    //    // e.preventDefault();
    //    $("#txtDiscount").addClass("animated flash").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
    //      $(this).removeClass('animated flash');
    //    });
    //  }
    // }
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
