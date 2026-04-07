<?php
  require_once("../DatabaseConnection/databaseconnection.php");
  require_once("../operations/insertVisitors.php");

  if(isset($_SESSION['suppEmail'])){
    echo '<script type="text/javascript">alert("Bonjour! '. $_SESSION['suppName'] .'");</script>';
    echo '<script type="text/javascript">window.location.href = "dashboard.php";</script>';

  }
  else{

    // Generate CSRF token
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    require_once("../operations/registerSupplier.php");

    // echo '<script type="text/javascript">alert("ERROR! You need to log in first");</script>';
    // // echo '<script type="text/javascript">alert('. $_SESSION['ProceedID']) .');</script>';
    // // die();
    
  } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ProcMIS - Supplier</title>
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

  <?php require_once("../accessory/navbar_supplier_index.php");?>

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
        <div class="col-lg-5 offset-lg-1">
          <center><h2 class="">Supplier's Registration</h2></center>
        </div>
        <div class="col-lg-4">
          
        </div>
      </div>  
    </div>

    <br>

    <div class="container-fluid">
      <!-- <div class="row">
        <div class="col-lg-8">
          <div class="col-lg-3 offset-lg-10" style="">
            <label style="font-size: 0.8rem; text-align: right;">FAD-PPGSS Form No. 15 <br>Rev. 1/15-05-17</label>
          </div> 
        </div>
        <div class="col-lg-4">

        </div>
        <div class="col-sm-3 offset-sm-11" style="margin-top: -2%">
          <label style="font-size: 0.8rem; text-align: right;">FAD-PPGSS Form No. 15 <br>Rev. 1/15-05-17</label>
        </div> -->
      </div> 
      <div class="row">
        <div class="col-lg-8">
            <div id="lblAsterisk" class="col-sm-12 animated flash proLong">
              <i class="fas fa-asterisk" style="color: #8d0101;"></i> <label style="color: #8d0101">All fields are required</label>
            </div>
        </div>
        <div class="col-lg-4">
          
        </div>
        <!-- <div id="lblAsterisk" class="col-sm-12 animated flash proLong">
          <i class="fas fa-asterisk" style="color: #8d0101;"></i> <label style="color: #8d0101">All fields are required except: Stock No.</label>
        </div> -->
      </div>
    </div>

    <div class="container-fluid">
      <div class="row" style="padding-top: 1%; padding-right: 1%;">
        <div class="col-lg-8">
            <form method="POST" action="index.php" id="formRegisterSupplier" enctype="multipart/form-data" style="width: 100%">
            <div class="form-row">
              <div class="col-lg-6">



                


                <?php echo '<input type="text" class="form-control prtextboxessupplier d-none" id="txtUsername" name="txtUsername" placeholder="Username" value="'.$_SESSION['Username'].'" autocomplete="off" required readonly>';?>









                <b><label for="txtSuppName">NAME OF COMPANY</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtSuppName" name="txtSuppName" placeholder="Eg. Apple Incorporation" value="" autocomplete="off" required>';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              <div class="col-sm-6">
                <b><label for="txtSuppEmail">SUPPLIER EMAIL</label></b>
                <?php echo '<input type="email" class="form-control prtextboxessupplier" id="txtSuppEmail" name="txtSuppEmail" placeholder="Eg. juandc@gmail.com" autocomplete="off" value="" required>';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtSection" name="txtSection" placeholder="Eg. (Planning Information and Communication Technology Staff)" autocomplete="off" required readonly> -->
              </div>
            </div>
            <div class="form-row mt-3">
              <div class="col-sm-12">
                <b><label for="txtSuppAddress">BUSINESS ADDRESS</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtSuppAddress" name="txtSuppAddress" placeholder="Eg. 57 20th Avenue, Barangay United Bayanihan Tanay, Rizal" autocomplete="off" value="" required>';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              
            </div>

            <div class="form-row mt-3">
              <div class="col-sm-6">
                <b><label for="txtContactPerson">DATE ESTABLISHED</label></b>
                <?php echo '<input type="date" class="form-control prtextboxessupplier" id="txtDateEstablished" name="txtDateEstablished" autocomplete="off" required value="">';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              <div class="col-sm-6">
                <b><label for="select_CreditAccommodation">CREDIT ACCOMMODATION</label></b>
                <select class="form-control prtextboxessupplier" id="select_CreditAccommodation" name="select_CreditAccommodation" required>
                                      <option value="30 - days">30 - days</option>
                                      <option value="15 - days">15 - days</option>
                                      <option value="Cash-Payment">Cash-Payment</option>                                                                              
                              </select>
              </div>              
            </div>

            <div class="form-row mt-3">
              <div class="col-sm-6">
                <b><label for="txtContactPerson">VAT Number</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtVatNumber" name="txtVatNumber" placeholder="Eg. 012-456-789" autocomplete="off" required value="">';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>
              <div class="col-sm-6">
                <b><label for="txtContactPerson">TIN</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtTIN" name="txtTIN" placeholder="Eg. 357-159-852-000" autocomplete="off" required value="">';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>    
              
            </div>

            <div class="form-row mt-3">
            <div class="col-sm-4">
                <b><label for="txtContactPerson">CONTACT PERSON</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtContactPerson" name="txtContactPerson" placeholder="Eg. Juan Dela Cruz" autocomplete="off" required value="">';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
            <div class="col-sm-4">
                <b><label for="txtSuppNumber">SUPPLIER CELLPHONE NUMBER</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtcellphoneNumber" name="txtcellphoneNumber" placeholder="Eg. 09667942654" maxlength="40" onkeypress="validateCharacter(event)" autocomplete="off" required value="">';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              <div class="col-sm-4">
                <b><label for="txtSuppNumber">SUPPLIER TELEPHONE NUMBER</label></b>
                <?php echo '<input type="text" class="form-control prtextboxessupplier" id="txtSuppNumber" name="txtSuppNumber" placeholder="Eg. 88681234" maxlength="40" onkeypress="validateCharacter(event)" autocomplete="off" required value="">';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              
            </div>

            <div class="form-row mt-3">
            <div class="col-sm-12">
                <b><label for="txtContactPerson">NATURE OF BUSINESS</label></b>&nbsp;<small>(Please select one or more categories)</small>
                <br>
                <div class="form-row">
                  <div class="col-lg-6">
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check7">
                          <input type="checkbox" class="form-check-input" id="check7" name="chk_NatureOfBusiness[]" checked value="ICT">Communication Equipment & Parts and Accessories, Computer Furniture, Fixtures, Furniture, Furniture Parts and Accessories, Information Technology, Information Technology Parts & Accessories & Peripherals
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check8">
                          <input type="checkbox" class="form-check-input" id="check8" name="chk_NatureOfBusiness[]" value="Construction">Construction Materials and Supplies, and, Hardware and Construction Supplies, Machine Tools, Signage and Accessories, Metal Fabrication
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check10">
                          <input type="checkbox" class="form-check-input" id="check10" name="chk_NatureOfBusiness[]" value="Medical">Diagnostic and Laboratory Services, Drugs and Medicines, Medical and Dental Equipment Medical Supplies and Laboratory Instrument
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check20">
                          <input type="checkbox" class="form-check-input" id="check20" name="chk_NatureOfBusiness[]" value="Vehicle">Vehicle Parts and Accessories, Vehicle Repair and Maintenance, Fuels/Fuel Additives & Lubricants & Anti Corrosive
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check11">
                          <input type="checkbox" class="form-check-input" id="check11" name="chk_NatureOfBusiness[]" value="Events">Events Management, Hotel and Lodging and Meeting Facilities, Lease and Rental of Property or Building
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check15">
                          <input type="checkbox" class="form-check-input" id="check15" name="chk_NatureOfBusiness[]" value="Industrial">Industrial Machinery and Equipment  Industrial pumps and compressors, Laboratory Supplies and Equipment
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check16">
                          <input type="checkbox" class="form-check-input" id="check16" name="chk_NatureOfBusiness[]" value="Water">Services, Video Production Services, Water and Waste Water Treatment Supply & Disposal, Water Service Connection Materials/Fittings
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check23">
                          <input type="checkbox" class="form-check-input" id="check23" name="chk_NatureOfBusiness[]" value="Registration">Registration/Training Fees
                        </label>
                      </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check12">
                          <input type="checkbox" class="form-check-input" id="check12" name="chk_NatureOfBusiness[]" value="Firefighting">Fire Fighting & Rescue and Safety Equipment, Catering Services
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check13">
                          <input type="checkbox" class="form-check-input" id="check13" name="chk_NatureOfBusiness[]" value="Janitorial">Janitorial Supplies, Laundry Services, Pest Control Services
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check14">
                          <input type="checkbox" class="form-check-input" id="check14" name="chk_NatureOfBusiness[]" value="Repair">General Repair and Maintenance Services
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check9">
                          <input type="checkbox" class="form-check-input" id="check9" name="chk_NatureOfBusiness[]" value="Architectural">Construction Projects, Architectural Design, Consulting Services
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check21">
                          <input type="checkbox" class="form-check-input" id="check21" name="chk_NatureOfBusiness[]" value="Corporate">Corporate Giveaways, and Tokens and Awards
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check17">
                          <input type="checkbox" class="form-check-input" id="check17" name="chk_NatureOfBusiness[]" value="Printing">Printing Services and Printing Supplies
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check19">
                          <input type="checkbox" class="form-check-input" id="check19" name="chk_NatureOfBusiness[]" value="Office">Office Equipment, Parts, Accessories, Consumables, Supplies and Devices
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check5">
                          <input type="checkbox" class="form-check-input" id="check5" name="chk_NatureOfBusiness[]" value="Aircon">Airconditioning Units and Maintenance Services
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check6">
                          <input type="checkbox" class="form-check-input" id="check6" name="chk_NatureOfBusiness[]" value="Chemicals">Chemical Detergents, Chemicals, and Chemical Products
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check18">
                          <input type="checkbox" class="form-check-input" id="check18" name="chk_NatureOfBusiness[]" value="Textiles">Textiles and Garments
                        </label>
                      </div>     
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check4">
                          <input type="checkbox" class="form-check-input" id="check4" name="chk_NatureOfBusiness[]" value="Agricultural
                          Chemicals">Agricultural Chemicals
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check1">
                          <input type="checkbox" class="form-check-input" id="check1" name="chk_NatureOfBusiness[]" value="Manufacturer">Manufacturer
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check2">
                          <input type="checkbox" class="form-check-input" id="check2" name="chk_NatureOfBusiness[]" value="Service">Service Provider
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check3">
                          <input type="checkbox" class="form-check-input" id="check3" name="chk_NatureOfBusiness[]" value="Trading">Trading Firms
                        </label>
                      </div>   
                       <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check22">
                          <input type="checkbox" class="form-check-input" id="check22" name="chk_NatureOfBusiness[]" value="Representation">Representation Expenses
                        </label>
                      </div> 
                      <div class="form-check">
                        <label class="form-check-label prtextboxessupplier" for="check24">
                          <input type="checkbox" class="form-check-input" id="check24" name="chk_NatureOfBusiness[]" value="Delivery">Transportation and Delivery Expenses
                        </label>
                      </div> 
                  </div>
                </div>

                <!-- check25 -->
                
                
              </div>  
              
            </div>

            <div class="form-row mt-3">
              <div class="col-sm-6">
                <b><label for="select_BusinessType">BUSINESS TYPE</label></b>
                <select class="form-control prtextboxessupplier" id="select_BusinessType" name="select_BusinessType" style="border-width: 1px 1px 2px; border-style: solid; border-color: rgb(116, 0, 154);" required>
                  <option value="Sole" selected>Sole Proprietorship</option>
                  <option value="Corporation">Corporation</option>
                </select>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  

              <div id="divDTI" class="col-sm-6">
                <b><label for="inputFile_dti">DTI REGISTRATION</label></b>
                <div class="form-group">     
                  <h6><p class="text-muted">Please only upload PDF (.pdf) file type here</p></h6>
                  <input type="file" class="form-control-file prtextboxessupplier" id="inputFile_dti" name="inputFile_dti" style="cursor: pointer;" required>
                </div>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div> 

              <div id="divSEC" class="col-sm-6 d-none">
                <b><label for="inputFile_sec">SEC REGISTRATION</label></b>
                <div class="form-group">     
                  <h6><p class="text-muted">Please only upload PDF (.pdf) file type here</p></h6>
                  <input type="file" class="form-control-file prtextboxessupplier" id="inputFile_sec" name="inputFile_sec" style="cursor: pointer;">
                </div>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div> 
              
            </div>


            <div class="form-row mt-3">
              <!-- <div class="col-sm-4">
                <b><label for="txtPassword">E-SIGNATURE</label></b>
                <div class="form-group">     
                  <h6><p class="text-muted">Please only upload PNG (.png) file type here</p></h6>
                  <input type="file" class="form-control-file prtextboxessupplier" name="inputFile_esig" style="cursor: pointer;" required>
                </div>
              </div>  -->
              <div class="col-sm-6">
                <b><label for="inputFile_tin">TIN/CERTIFICATE OF REGISTRATION</label></b>
                <div class="form-group">     
                  <h6><p class="text-muted">Please only upload PDF (.pdf) file type here</p></h6>
                  <input type="file" class="form-control-file prtextboxessupplier" name="inputFile_tin" style="cursor: pointer;" required>
                </div>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>

              <div class="col-sm-6">
                <b><label for="inputFile_itr">INCOME TAX RETURN</label></b>
                <div class="form-group">     
                  <h6><p class="text-muted">Please only upload PDF (.pdf) file type here</p></h6>
                  <input type="file" class="form-control-file prtextboxessupplier" name="inputFile_itr" style="cursor: pointer;" required>
                </div>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>              
            </div>

            <div class="form-row mt-3">
              <div class="col-sm-6">
                <b><label for="inputFile_philgeps">PHILGEPS REGISTRATION CERTIFICATION (RED/PLATINUM MEMBERSHIP)</label></b>
                <div class="form-group">     
                  <h6><p class="text-muted">Please only upload PDF (.pdf) file type here</p></h6>
                  <input type="file" class="form-control-file prtextboxessupplier" name="inputFile_philgeps" style="cursor: pointer;" required>
                </div>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div> 

              <div class="col-sm-6">
                <b><label for="inputFile_permit">CURRENT MAYOR'S PERMIT</label></b>
                <div class="form-group">     
                  <h6><p class="text-muted">Please only upload PDF (.pdf) file type here</p></h6>
                  <input type="file" class="form-control-file prtextboxessupplier" name="inputFile_permit" style="cursor: pointer;" required>
                </div>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div> 

              
              
            </div>
              
            
            <div class="form-row mt-3">
              <div class="col-sm-6">
                <b><label for="txtPassword">PASSWORD</label></b>
                <?php echo '<input type="password" class="form-control prtextboxessupplier" id="txtPassword" name="txtPassword" minlength="8" autocomplete="off" onkeypress="validatePasswordChar(event)" required value="">';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              <div class="col-sm-6">
                <b><label for="txtConfirmPassword">CONFIRM PASSWORD</label></b>
                <?php echo '<input type="password" class="form-control prtextboxessupplier" id="txtConfirmPassword" name="txtConfirmPassword"  maxlength="40" onkeypress="validatePasswordChar(event)" minlength="8" autocomplete="off" required value="">';?>
                <!-- <input type="text" class="form-control prtextboxessupplier" id="txtDivision" name="txtDivision" placeholder="Eg. (Office of the Director)" autocomplete="off" required readonly> -->
              </div>  
              
            </div>
            <span id="#message"></span>
            <p style="font-size: smaller; color: red;">Note: Password must be at least 8 characters and must have special character(s)</p>

            <br>

            <div class="formFooter" style="display: block;">
              <div class="form-row">
                <div class="col">
                  <input type="checkbox" id="chkAgree" name="chkAgree" required>
                  <label for="chkAgree">I have reviewed all the details inputted and have agreed to the <a href="../accessory/privacynotice.pdf" target="_blank">terms of service</a>.</label>
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
                  <?php echo '<input type="hidden" name="csrf_token" value="'. $_SESSION['csrf_token'] . '">'; ?>
                  <center><button type="submit" class="btn formsbuttonsupplier" id="btnAddAccount">SUBMIT</button></center>
                </div>
              </div>
            </div>
          </form>

            

        </div>
        <div class="col-lg-4" style="background-image: url('../photos/ptri_building_blurred.png');">
          <?php //require_once("../accessory/sidebar_actions.php");?>

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

<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #e49f92;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">Login</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <!-- <form id="formCancel" action="#"> -->
          <div id="dangerpopup" class="alert alert-danger alert-dismissible fade show d-none">
              <button type="button" class="close">&times;</button>
              This field is required!
          </div>

          <form id="formLoginSupplier" action="../operations/loginSupplier.php" method="POST">
              <div class="form-group">     
                <b><label for="txtloginEmail">Email</label></b>
                  <?php echo '<input type="email" class="form-control prtextboxessupplieralt readonly" id="txtloginEmail" name="txtloginEmail" placeholder="Input your email" autocomplete="off" value="" required>';?>
              </div>

              <div class="form-group">     
                <b><label for="txtloginPassword">Password</label></b>
                  <?php echo '<input type="password" class="form-control prtextboxessupplieralt readonly" id="txtloginPassword" name="txtloginPassword" placeholder="Input your password" autocomplete="off" value="" required>';?>
              </div>

              <div class="form-group">     
                <a class="small" href="#" id="lnkForgot">Forgot password</a>
              </div>

              <br>
              <center><button type="submit" class="btn formsbuttonsupplieralt">LOGIN</button></center>
          </form>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          
          
        <!-- </form> -->
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalForgot" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block" style="background-color: #e49f92;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-center">Forgot Password</h5>
      </div>
      <div class="modal-body" style="padding: 5%">
        <!-- <form id="formCancel" action="#"> -->
          <div id="dangerpopup" class="alert alert-danger alert-dismissible fade show d-none">
              <button type="button" class="close">&times;</button>
              This field is required!
          </div>
          <div class="form-group"> 
            <a class="small" href="#" id="lnkBackForgot">Back</a>
          </div>

          <form id="formForgot" action="#" method="POST">
              <div class="form-group">     
                <b><label for="txtforgotEmail">Email</label></b>
                  <?php echo '<input type="email" class="form-control prtextboxessupplieralt readonly" id="txtforgotEmail" name="txtforgotEmail" placeholder="Input your registered email" autocomplete="off" value="" required>';?>
              </div>

              <br>
              <center><button type="submit" class="btn formsbuttonsupplieralt">SEND RESET LINK</button></center>
          </form>

          <!-- <input type="date"  class="form-control forms_textbox" name="txtDateAppoint" id="txtDateAppoint" placeholder="Date of Appointment" autocomplete="off" required>
      <input type="time"  class="form-control forms_textbox" name="txtTimeAppoint" id="txtTimeAppoint" placeholder="Time of Appointment" autocomplete="off" required> -->


          
          
        <!-- </form> -->
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
            <h3 id="h3ConfirmTitle" class="modal-title text-center" style="">ProcMIS</h3>
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

<?php 

    if(isset($_GET['login'])){
      echo '<script type="text/javascript">$("#modalLogin").modal();</script>';
    }
    else{

    }

    if (isset($_SESSION['registrationSuccess'])){

      if (strpos($_SESSION['registrationSuccess'], "ERROR") !== false) {  // Use !== to distinguish between false and position 0
          // echo "The word '$word' was found in the text.";
        echo '<script>$("#modalh4dMessage").html('.$_SESSION['registrationSuccess'].'); $("#modalDanger").modal();</script>';
      } 
      else {
          // echo "The word '$word' was not found.";
        echo '<script>$("#modalh4cMessage").html('.$_SESSION['registrationSuccess'].'); $("#ModalConfirmation").modal();</script>';
      }

      unset($_SESSION['registrationSuccess']);
      
    }

?>

</body>

<!--Banana, Abaca, Pineapple, Bamboo-->

<script>

  $(document).ready(function(){
        $("#linav_Modules").addClass("active");
        $("#pill_createPR").addClass("active");
    });

    $(".actionPills").click(function() {
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

    $("#formLoginSupplier").submit(function(e) {
      $("#modalLogin").modal("hide");
      $("#loadingDIV").removeClass("d-none");
      $("#loadingDIV").fadeIn();
    });

    $("#formForgot").submit(function(e) {

      var col_Email = $("#txtforgotEmail").val();

      let text = "Are you sure you want to reset your password?\n\nChoose OK to proceed or Cancel.";

      if (confirm(text) == true) {
        text = "You pressed OK!";
        e.preventDefault();

        $("#modalForgot").modal("hide");
        $("#modalLogin").modal("hide");

        $("#loadingDIV").removeClass("d-none");
        $("#loadingDIV").fadeIn();

        $.ajax({
          method : "POST",
          url : "../Ajax/forgotpassword.php",
          data : {email : col_Email},
          dataType : "html",
          success : function(data){
            // $("#modalConforme").modal("hide");
            // $("#txtRDDID").prop("value", data);
            if(data.includes("ERROR")){
                console.log(data);
                $("#modalh4dMessage").html(data);
                $("#modalDanger").modal();
                $("#loadingDIV").fadeOut();
            }
            else{
                console.log(data);
                // alert(data);
                $("#txtforgotEmail").val("");
                $("#modalh4cMessage").html(data);
                $("#ModalConfirmation").modal();
                $("#loadingDIV").fadeOut();

                // getPRs_forPOacceptance();

                // window.open('../pages/print_po_pdf.php?ID='+col_ID, '_blank');
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

    $("#lnkChange").click(function() {
      // alert("CHANGE TO CDABUS");
      var sectionPresent = $("#txtSection").val();
      // alert(sectionPresent);
      if(sectionPresent == "Natural Fiber Utilization Section"){
        $("#txtSection").val("Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section");
      }
      else if(sectionPresent == "Chemicals, Dyes, and Auxiliaries and By-Product Utilization Section"){
        $("#txtSection").val("Natural Fiber Utilization Section");
      }
      else{
        alert("ERROR")
      }
    });

    $("#select_BusinessType").change(function() {
        var bussType = $(this).val();

       

        if(bussType == "Sole"){
          $("#divSEC").hide();

          $("#divDTI").removeClass("d-none");
          $("#divDTI").fadeIn();
          
          $("#inputFile_sec").removeAttr("required");
          $("#inputFile_dti").attr("required", "true");
        }

        else if(bussType == "Corporation"){
          $("#divDTI").hide();

          $("#divSEC").removeClass("d-none");
          $("#divSEC").fadeIn();
          
          $("#inputFile_dti").removeAttr("required");
          $("#inputFile_sec").attr("required", "true");
        }
    });

    $("#lnkForgot").click(function() {
          $("#modalLogin").addClass("animated flipOutY").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
            $(this).removeClass('animated flipOutY');
            $(this).hide();
            $("#modalForgot").show();
            $("#modalForgot").modal();
            // $("#modalForgot").addClass("animated flipInY")

            $("#modalForgot").addClass("animated flipInY").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
              $(this).removeClass('animated flipInY');
            });
          });
    });

    $("#lnkBackForgot").click(function() {
          $("#modalForgot").addClass("animated flipOutY").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
            $(this).removeClass('animated flipOutY');
            $(this).hide();
            $("#modalLogin").show();
            $("#modalLogin").modal();
            // $("#modalForgot").addClass("animated flipInY")

            $("#modalLogin").addClass("animated flipInY").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
              // $(this).removeClass('animated flipInY');
            });
          });
    });


    function validateCharacter (evt){
        var character = String.fromCharCode(evt.which);

        // alert(character);

        if(!(/[0-9]/.test(character))){
            // if(character == "/"){
            //   //proceed  
            // }
            // else{
            //   evt.preventDefault();
            // }

            evt.preventDefault();
        }
        else{
          // if(character == "/"){
          //   //proceed  
          // }
          // else{
          //   evt.preventDefault();
          // }
          //proceed
        }
    }

    function validatePasswordChar (evt){
        var character = evt.key;

        // alert(character);

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


    $('#txtPassword, #txtConfirmPassword').on('keyup', function () {
      if ($('#txtPassword').val() == $('#txtConfirmPassword').val()) {
        if($('#txtPassword').val() == ""){
            $('#txtPassword').addClass("prtextboxessupplier");
            $('#txtConfirmPassword').addClass("prtextboxessupplier");
        }
        else{

            if($('#txtPassword').val().length > 7){
                var specialChars = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;

                // alert(specialChars.test($('#txtPassword').val()));

                if(specialChars.test($('#txtPassword').val())){
                    $('#txtPassword').removeClass("prtextboxessupplier");
                    $('#txtConfirmPassword').removeClass("prtextboxessupplier");
                    // $('#txtPassword').css('border-bottom', '4px solid green');
                    $('#txtPassword').css({'border-bottom':'4px solid green', 'border-top':'1px solid green', 'border-left':'1px solid green', 'border-right':'1px solid green'}); 
                    $('#txtConfirmPassword').css({'border-bottom':'4px solid green', 'border-top':'1px solid green', 'border-left':'1px solid green', 'border-right':'1px solid green'}); 
                }
                else{
                   alert("Please include special character in your password");
                   // $("#btnSubmitPass").css("border-width", "medium");
                   // $("#btnSubmitPass").css("border-color", "red");
                    $('#txtPassword').removeClass("prtextboxessupplier");
                    $('#txtConfirmPassword').removeClass("prtextboxessupplier");
                    $('#txtPassword').css('border-bottom', '4px solid red');
                    $('#txtConfirmPassword').css('border-bottom', '4px solid red');
                }
            }
            else{
                alert("You need 8 or more characters for your password");

                $('#txtPassword').removeClass("prtextboxessupplier");
                $('#txtConfirmPassword').removeClass("prtextboxessupplier");
                $('#txtPassword').css('border-bottom', '4px solid red');
                $('#txtConfirmPassword').css('border-bottom', '4px solid red'); 
            }
        }        
      } 
      else{
            // alert("Passwords do no match!");

            $('#txtPassword').removeClass("prtextboxessupplier");
            $('#txtConfirmPassword').removeClass("prtextboxessupplier");
            $('#txtPassword').css('border-bottom', '4px solid red');
            $('#txtConfirmPassword').css('border-bottom', '4px solid red'); 
      }    
    });

   

  $("#formRegisterSupplier").submit(function(e){
      // e.preventDefault();
      var redColor = "rgb(255, 0, 0)";
      if($("#txtPassword").css("border-color") == redColor || $("#txtConfirmPassword").css("border-color") == redColor){
          alert("PASSWORD ERROR! Please check the requirements");
          e.preventDefault();
      }
      else{
          $("#loadingDIV").removeClass("d-none");
          $("#loadingDIV").fadeIn();
      }      
  });

 

</script>

</html>