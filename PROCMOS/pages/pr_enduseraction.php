<?php
  require_once("../DatabaseConnection/databaseconnection.php");

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
    // require_once("../operations/getPRequests.php");

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
  <title>End-User PR</title>
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
          <center><h2>Purchase Request <br><br> <muted><h3>End-User Action</h3></muted></h2></center>
        </div>
      </div>  
    </div>

    <br>

 

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
                    <select class="form-control" id="select_SearchColumn" name="select_SearchColumn">
                      <option value="0">SEARCH BY ID:</option>
                      <option value="1">SEARCH BY PR NUMBER:</option>
                      <option value="8">SEARCH BY STATUS:</option>
                      <option value="4">SEARCH BY LOCATION:</option>
                      <option value="7">SEARCH BY PROJECT:</option>
                    </select>
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
                  <tbody>
                    
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
        <form id="formAffix" method="POST">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_ID" id="txtPR_ID" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          <center><h5>REMARKS</h5></center>

          <textarea class="form-control" rows="6" id="txtRemarks" name="txtRemarks" readonly placeholder="(INPUT IF ANY)"></textarea>
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


<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #8b0000;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">DELETE PR</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <form id="formDeletePR" action="../operations/deletePR.php" method="POST">
          
          <input type="text"  class="form-control forms_textbox d-none" name="txtPR_IDdelete" id="txtPR_IDdelete" placeholder="PR ID" autocomplete="off" required>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->

          <br>
          <center><button type="submit" class="btn formsbutton deleteButton"><i class="fas fa-trash-alt fa-2x"></i></button></center>
          

          
          <br>
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



  <div id="loadingDIV" class="loader text-center" style="display: none;">
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
       
        getPRs_forEndUser(); 
    });


  function getPRs_forEndUser(){
        $.ajax({
            method: "POST",
            url: "../Ajax/getPRs_forEndUser.php",
            dataType: "html",
            success: function(data){
                // alert("Hello");
                // console.log(data);
                $("#tblPR tbody").html(data);
            }
        });
    }

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
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtstock'+numberOfRows+'" '+
              'id="txtstock'+numberOfRows+'" autocomplete="off" style="width: 100%;"></td>'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes" name="txtUnitQty'+numberOfRows+'" '+
              'id="txtUnitQty'+numberOfRows+'" autocomplete="off" required style="width: 100%;"></td>'+
              '<td><textarea rows="5" class="form-control forms_textbox" id="txtItem'+numberOfRows+'" name="txtItem'+numberOfRows+'" style="border: 1px solid #74009A; border-bottom: 2px solid #74009A;"></textarea></td>'+
              '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtQuantity'+numberOfRows+'" '+
              'id="txtQuantity'+numberOfRows+'" onkeyup="computeTotalFee(this);" autocomplete="off" step="0.01" min="0" required style="width: 100%;"></td>'+
              '<td><input type="number"  class="form-control forms_textbox prtextboxes" name="txtUnitFee'+numberOfRows+'" '+
              'id="txtUnitFee'+numberOfRows+'" autocomplete="off" step="0.01" min="0" required onkeyup="computeTotalFee_fromUnit(this);" style="width: 100%;"></td>'+
              '<td><input type="text"  class="form-control forms_textbox prtextboxes textboxTotal" name="txtTotal'+numberOfRows+'" min="0"'+
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

  

  $("#btn_reSubmitPR").click(function(){

      var prID = $("#txtPR_ID").val();
      var prRemarks = $("#txtRemarks").val();

      let text = "Are you sure you have already reviewed the all the details?\n\nChoose OK to proceed or Cancel.";

      if (confirm(text) == true) {
          text = "You pressed OK!";

          $("#loadingDIV").fadeIn();

          $.ajax({
            method : "POST",
            url : "../Ajax/resubmit_PR.php",
            data : {id : prID, remarks : prRemarks},
            dataType : "html",
            success : function(data){
              // $("#txtRDDID").prop("value", data);
              // if(data.includes("ERROR")){
              //   alert(data);
              // }
              // else{
              //   console.log(data);
              //   if(!data.toString().includes("PR HAS NO REMARKS")){
              //       // alert(data);
              //       // $('#txtRemarks').css({"background-color":"#fff !important", "border":"1px solid yellow"});
              //       $('#txtRemarks').addClass("bg-warning");
              //       $('.btnRemarksAction').removeClass("d-none");
                    
              //   }
              //   else{
              //       //do nothing
              //       $('#txtRemarks').removeClass("bg-warning");
              //       $('.btnRemarksAction').addClass("d-none");
              //   }

              //   $('#txtRemarks').val(data);
              // }

              $("#modalView").modal("hide");
                if(data.includes("ERROR")){
                  // alert(data);
                  console.log(data);

                  $("#modalh4dMessage").html(data);
                  $("#modalDanger").modal();
                  $("#loadingDIV").fadeOut();
                }
                else{
                  console.log(data);
                  // $("#txtRemarks").val("");
                  $("#modalh4cMessage").html(data);
                  $("#ModalConfirmation").modal();
                  $("#loadingDIV").fadeOut();
                  

                  getPRs_forEndUser();
                  // alert(data);
                  // window.open('print_pr_pdf.php?ID='+col_ID+'&acctID='+data+'', '_blank');
                }

                getPRs_forEndUser();
                
                console.log(data);
            }
          });
      }
      else{
          // text = "You canceled!";
          //do nothing
      }
  });

  $('#tblPR').on('click','#lnkProceed', function(){
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

    $("#txtPR_ID").val(col_ID);
    
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
              $('#txtRemarks').addClass("bg-warning");

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

          $('#txtRemarks').val(data);
        }
      }
    });


    
    

  });

  $('#tblPR').on('click','#lnkDelete', function(){
    //get the current row


    var currentRow = $(this).closest("tr");

    var col_ID = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    var col_PRno = currentRow.find("td:eq(1)").text();
    var colServiceName = currentRow.find("td:eq(3)").text();
    var colStatus = currentRow.find("td:eq(7)").text();
    // var colDateAppoint = currentRow.find("td:eq(2)").text();
    // var colTimeAppoint = currentRow.find("td:eq(3)").text();
    // var colClientName = currentRow.find("td:eq(4)").text();
    // var colClientEmail = currentRow.find("td:eq(5)").text();
    // var colMobile = currentRow.find("td:eq(6)").text();
    // alert(colStatus);
    if(col_PRno == "Not Yet Validated"){
      $("#txtPR_IDdelete").val(col_ID);
      $("#modalDelete").modal();
    }
    else{
      alert("Error! You cannot delete a PR with a PR Number already");
    }
    



  });

  $("#formDeletePR").submit(function(e){
      let text = "Are you sure you want to delete this P.R.? This cannot be undone\n\nChoose OK to proceed or Cancel.";

      if (confirm(text) == true) {
        //proceed form
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