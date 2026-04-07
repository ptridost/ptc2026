<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="../login.css"/>

</head>
<body>
<div id="frm">
    <div class="container contr">
      
      <div class="row">

       
        <div class="col-lg-6 col-md-6  col-sm-12  loginpic" >
        <img src="/img_outside/logo.png">
      </div>


        <div class="col-lg-5 col-md-6 col-sm-12 input" >

        <br>
        <br>
        <div class="input1">
          <h1 style="font-size: 45px;">Reset Password</h1>
          <form action="forgot-password.php" method="POST" autocomplete="">
          <div class="inputBox">
            <?php
                        if(count($errors) > 0){
                            ?>
                            <div class="alert alert-danger text-center">
                                <?php 
                                     foreach($errors as $error){
                                         echo $error;
                                    }
                                ?>
                            </div>
                            <?php
                        }
                    ?>
          <input type="email" name="email" placeholder="Enter email address" required value="<?php echo $email ?>">
          </div><br>
          <div class="inputBox">
            
        <input style="background-color: #1C3989; color: white;" class="button" type="submit" name="check-email" value="Continue">

      </div>
    </form>
    </div>
  </div>
  </div>
</div>
 </form>

</div>

</body>
</html>