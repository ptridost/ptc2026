<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: ../Loginn.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create a New Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" type="text/css" href="../login.css"/>
</head>
<body>

    
<div id="frm">
    <div class="container contr">
      
      <div class="row">

       
        <div class="col-lg-6 mt-0 mb-0 loginpic" >
        <img src="/img_outside/logo.png">
      </div>


        <div class="col-lg-5 mt-0 mb-5 input" >

        <br>
        <br>
        <div class="input1">
          <h1 style="font-size: 48px; display: inline-block; line-height: 2; margin-top: -30px;">New Password</h1>
          <form action="new-password.php" method="POST" autocomplete="off">
          <div class="inputBox">
           <?php 
                    if(isset($_SESSION['info']) AND $_SESSION['info'] !== ""){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
          <input type="password" name="password" placeholder="Create new password" minlength="8" required>
          <br>
          <br>
          <input type="password" name="cpassword" placeholder="Confirm your password" required>
          </div><br>
          <div class="inputBox">
            
        <input style="background-color: #1C3989; color: white;" class="button" type="submit" name="change-password" value="Change">

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
