<html lang="en">
         <link rel="stylesheet" type="text/css" href="/header&footer/footers.css">
     <link rel="stylesheet" type="text/css" href="/header&footer/Navbarstyle.css">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="shortcut icon" type="image/x-icon" href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />

    <title>PTRI CONGRESS</title>
    <link rel="stylesheet" type="text/css" href="login.css"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="registration.css"/>
  
  <script src="registration.js"></script>
  
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:wght@700;800&family=Roboto:wght@700;900&display=swap" rel="stylesheet">
  
   <link rel="stylesheet" type="text/css" href="/header&footer/footers.css">
     <link rel="stylesheet" type="text/css" href="/header&footer/Navbarstyle.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.20.0/js/mdb.min.js" integrity="sha512-XFd1m0eHgU1F05yOmuzEklFHtiacLVbtdBufAyZwFR0zfcq7vc6iJuxerGPyVFOXlPGgM8Uhem9gwzMI8SJ5uw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" type="text/css" href="/header&footer/footers.css">
    <link rel="stylesheet" type="text/css" href="/header&footer/Navbarstyle.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="~/Scripts/jquery-1.10.2.js"></script>

  </head>
  <body>
    <script type="text/javascript">
                    (function ($, window, document) {
                        $(function () {
                            $("form input").on({
                                "keyup": function () {
                                    var pass = $('#password1').val();
                                    var confirmPass = $('#password2').val();
                                    var saveButton = $("#save-btn");
                                    if (pass == confirmPass) {
                                        saveButton.removeAttr('disabled');
                                        $('#message').html('Password Matched').css('color', 'green').css('margin-left', '20px').css('margin-top', '30px');
                                    } else {
                                        saveButton.attr('disabled', 'disabled');
                                        $('#message').html('Password Not Matched').css('color', 'red').css('margin-left', '20px').css('margin-top', '30px');
                                    }
                                }
                            });
                        });

                       

                    }(window.jQuery, window, document));
                  </script>
    <div id="frm">
      <form action="forgotpass.php" method="POST" enctype="multipart/form-data">
    <div class="container contr">
      
      <div class="row">

       
        <div class="col-lg-7 col-md-6  col-sm-12  loginpic" >
        <img src="logo.png">
      </div>


        <div class="col-lg-5 input" >
        <br>
        <br>
      
          <h1>Reset Password</h1>
  
            <div class="col-lg-8 passwordcol">
            <div class="input-container">
              <input type="text" class="input-field" name="username" id="username" placeholder="Please enter your registered email" required> 
            </div>
          </div>

          <div class="col-lg-8 passwordcol">
            <div class="input-container">
              <input type="password" class="input-field" name="password1" id="password1" placeholder="Password" autocomplete="on"  minlength="8" required>  
              <label for="input-field" class="input-label"></label>
              <span class="input-highlight"></span>
              <p>At least 8 characters</p>
            </div>
            </div>
            

            <div class="col-lg-8">
            <div class="input-container">
              <input type="password" class="input-field" name="password2" id="password2" placeholder="Confirm Password" autocomplete="on" required="true">
              <label for="input-field" class="input-label"></label>
              <span class="input-highlight"></span>
            <span id='message'></span>
            </div>
            </div>


            
            <br>
            <button style="width: 50%; height: 10%; display: block;" class="button" type="submit" name="submit" id="save-btn">SAVE</button>
          
  




      </div>

    </div>
  </div>
  </div>
</div>
 </form>

</div>







          <?php 

            $servername = "localhost";
            $username = "root";
            $password = "ptc@ptri@picts";
            $dbname = "sampledb";

            $dbcon = new mysqli($servername, $username, $password, $dbname);


            if(isset($_POST['submit'])){
              $username   = $_POST['username'];
              $password1  = $_POST['password1'];
              $hashed_password  = md5($password1);

            $sql = "SELECT * FROM participant WHERE username = '$username'";
            $all_session = $dbcon->query($sql);
            
                
                  while($row = mysqli_fetch_assoc($all_session)){
                    print_r($row);
                    if($username == $row['username']){

                      mysqli_query($dbcon, "UPDATE participant SET password = '$hashed_password' WHERE username = '$username'");


                    ?>
                    <script>
              
                     alert("Your password has been updated");
                      window.location = 'loginn.php';
                  


                    
                    </script>


<?php
}
}
}
?>
<br>

  </body>
</html>