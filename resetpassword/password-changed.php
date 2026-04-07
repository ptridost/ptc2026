<?php require_once "controllerUserData.php"; ?>
<?php
if($_SESSION['info'] == false){
    header('Location: ../Loginn.php');  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
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
          <h1 style="font-size: 38px; display: inline-block; line-height: 2; margin-top: 20px;">Password Changed</h1>
          <form action="../Loginn.php" method="POST">
          <div class="inputBox">
           <?php 
            if(isset($_SESSION['info'])){
                ?>
                <div class="alert alert-success text-center">
                <?php echo $_SESSION['info']; ?>
                </div>
                <?php
            }
            ?>
          
          </div><br>
          <div class="inputBox">
            
        <input style="background-color: #1C3989; color: white;" class="button" type="submit" name="login-now" value="Login Now">

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

