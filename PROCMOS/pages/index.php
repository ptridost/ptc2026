<?php

require_once("../DatabaseConnection/databaseconnection.php");
require_once("../operations/login.php");
require_once("../operations/insertVisitors.php");

    if(!empty($_SESSION['Username'])){
    echo '<script type="text/javascript">alert("Bonjour '.$_SESSION['Username'].'");</script>';
    echo '<script type="text/javascript">window.location.href = "purchase_request.php";</script>';
  }
  else{
    //do nothing
  }


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>ProcMIS - Login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
  <link rel="icon" href="../photos/procurement.png" type="image/png" sizes="16x16">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../vendor/loginV18/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../vendor/loginV18/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../vendor/loginV18/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../vendor/loginV18/animate/animate.css">
<!--===============================================================================================-->  
  <link rel="stylesheet" type="text/css" href="../vendor/loginV18/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../vendor/loginV18/animsition/css/animsition.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../vendor/loginV18/select2/select2.min.css">
<!--===============================================================================================-->  
  <link rel="stylesheet" type="text/css" href="../vendor/loginV18/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
  <!-- <link href="../vendor/css/bootstrap.min.css" rel="stylesheet">
  <link href="../vendor/css/jquery-ui.min.css" rel="stylesheet">
  <link href="../vendor/fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
  <link href="../vendor/css/animate.css" rel="stylesheet"> -->
  
  <link rel="stylesheet" type="text/css" href="../vendor/css/footer.css">  
<!--===============================================================================================-->

  <link rel="stylesheet" type="text/css" href="../vendor/css/loginV18/util.css">
  <link rel="stylesheet" type="text/css" href="../vendor/css/loginV18/main.css">
  
<!--===============================================================================================-->
</head>
<body style="background-color: #fff;">
  <nav class="navbar navbar-expand-md nav-bg navbar-dark animated slideInDown" style="background-color: #00aeef;">
  <!-- Brand -->
  <!-- Redirect /PTRI_SRRIS/veer/ http://services.dost-ptri-rypic.com/pages/index.php -->
  <a class="navbar-brand navBar-logo" href="services.dost-ptri.com" style="padding-top: 0; padding-bottom: 0; margin-right: 0.4rem"><img width="50px" src="../photos/ptrionlywhite.png">
    </a>

    <a class="navbar-brand navBar-GovName" href="#" style="font-size: 14px; padding-top: 0; padding-bottom: 0; line-height: 1.2">
      <span>Department of Science and Technology</span>
      <br>
      <span><b>PHILIPPINE TEXTILE RESEARCH INSTITUTE</b></span>
    </a>
</nav>

  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100">
        <form class="login100-form validate-form animated slideInRight" action="index.php" method="POST">
          <span class="login100-form-title p-b-43">
            <i class="fa fa-user-circle fa-3x" aria-hidden="true"></i>
          </span>
          <span class="login100-form-title p-b-43">
            USER LOGIN
          </span>
          
          
          <div class="wrap-input100 validate-input" data-validate = "Username is required">
            <input class="input100" type="text" name="txtUsername">
            <span class="focus-input100"></span>
            <span class="label-input100">Username</span>
          </div>
          
          
          <div class="wrap-input100 validate-input" data-validate="Password is required">
            <input class="input100" type="password" name="txtPassword">
            <span class="focus-input100"></span>
            <span class="label-input100">Password</span>
          </div>

          <div class="flex-sb-m w-full p-t-3 p-b-32">
            <div class="contact100-form-checkbox">
              <!-- <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
              <label class="label-checkbox100" for="ckb1">
                Remember me
              </label> -->
            </div>

            <div>
              <a href="#" class="txt1">
                Forgot Password?
              </a>
            </div>
          </div>
      

          <div class="container-login100-form-btn">
            <button class="login100-form-btn">
              Login
            </button>
          </div>
          
          <!-- <div class="text-center p-t-46 p-b-11">
            <span class="txt2">
              or <a href="#">sign up</a>
            </span>
          </div> -->

          <!-- <div class="login100-form-social flex-c-m">
            <a href="#" class="login100-form-social-item flex-c-m bg1 m-r-5">
              <i class="fa fa-facebook-f" aria-hidden="true"></i>
            </a>

            <a href="#" class="login100-form-social-item flex-c-m bg2 m-r-5">
              <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
          </div> -->
        </form>

        <div class="login100-more animated slideInLeft" style="padding-top: 3%; background-image: url('../photos/ptri_building_blurred.png');">
          <div class="container">
            <div class="row">
              <div class="col-lg-12 text-center">
                <h1>PROCUREMENT MANAGEMENT INFORMATION SYSTEM</h1>
              </div>
            </div>

            

            <div class="row">
              <div class="col-lg-12 text-center">
                <h6 style="line-height: 1.6; padding: 5%; text-align: justify;">The Procurement  Management Information System (ProcMIS) aims to digitalize the procurement process of the Institute to facilitate a faster procurement process and ease of monitoring and tracking.  The ProcMIS can be accessed both by the DOST-PTRI’s users and suppliers.  It will have an access level depending on the responsibility of the users as well as activity and user log to track the users, activity, and actions of the users.  The system can also generate various procurement reports to ensure the accuracy of data and minimize the repetitive printing of reports.</h6>
              </div>
            </div>
          </div>
        </div>

        <footer class="footer-distributed animated slideInUp">
          <div class="footer-center">

            <div>
              <i class="fa fa-map-marker" title="Location"></i>
              <p><span>PTRI Building, General Santos Avenue            
                </span> Bicutan, Taguig City, Philippines</p>
            </div>

            <div>
              <i class="fa fa-phone" title="Phone"></i>
              <p>(02) 8 837 1325 <br> (02) 8 837 1157</p>
              <!-- <p>(02) 8 837 1157</p> -->
              
            </div>

            <div>
              <i class="fa fa-globe" aria-hidden="true" title="Website"></i>
              <p><a href="https://ptri.dost.gov.ph" target="_blank">ptri.dost.gov.ph</a>
             </div>

             <div>
              <i class="fa fa-envelope" title="Mail"></i>
              <p><a href="mailto:ptri@ptri.dost.gov.ph">ptri@ptri.dost.gov.ph</a><br>
                <a href="mailto:ptridost47@gmail.com">ptridost47@gmail.com</a></p>
             </div>

          </div>

          <div class="footer-center">

            <div>
              <p>Quicklinks:</p>
              
            </div>

            <div>
              <i class="fa fa-asd" title="RYPIC Portal"></i>
              <p><a href="https://dost-ptri-rypic.com" target="_blank">RYPIC Portal</a></p>
              <!-- <p>(02) 8 837 1157</p> -->
              
            </div>

            <div>
              <i class="fa fa-asd" title="Philippine Textile Portal"></i>
              <p><a href="https://philippinetextiles.com" target="_blank">Philippine Textile Portal</a></p>
              <!-- <p>(02) 8 837 1157</p> -->
              
            </div>

            <div>
              <i class="fa fa-asd" title="SRIS"></i>
              <p><a href="https://services.dost-ptri.com" target="_blank">Service Request Registration Information System</a></p>
              <!-- <p>(02) 8 837 1157</p> -->
              
            </div>

             

          </div>

          <div class="footer-right">

            <p class="footer-company-about">
            <span>Feedback: </span>
            We would like to hear from you.  Please <a href="feedback.php">click here</a> to send your questions, suggestions, and recommendations.


            


            <br><br>To our Valued Supplier, kindly fill out the <a href="../supplier/index.php">Supplier Information Sheet</a> to register your company profile to PTRI Supplier’s Database.
            </p>

            

          </div>
        </footer>



      </div>
    </div>
  </div>
  
  

<!--===============================================================================================-->
  <script src="../vendor/js/jquery-3.4.1.min.js"></script>

  <script src="../vendor/js/jquery-ui.min.js"></script>
  <script src="../vendor/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
  <script src="../vendor/css/loginV18/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
  <script src="../vendor/css/loginV18/bootstrap/js/popper.js"></script>
  
<!--===============================================================================================-->
  <script src="../vendor/css/loginV18/select2/select2.min.js"></script>
<!--===============================================================================================-->
  <script src="../vendor/css/loginV18/daterangepicker/moment.min.js"></script>
  <script src="../vendor/css/loginV18/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
  <script src="../vendor/css/loginV18/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script src="../vendor/css/loginV18/main.js"></script>

</body>
</html>

