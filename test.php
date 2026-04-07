<?php
     require 'connection.php';
     

     if(isset($_POST["login"])){
      $email_address = $_POST['email_address']; 
      $password = $_POST['password'];
echo"<script> alert('Data Inserted Successfully'); </script>";
     else
      echo"<script> alert('Data Inserted Successfully'); </script>";

   }
     ?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Hello, world!</title>
    <link rel="stylesheet" type="text/css" href="login.css"/>
  </head>
  <body>
    <div class="container">

      

      <div class="row">

       
        <div class="col-md-7 loginpic" >
        <img src="loginn.jpg">
      </div>


        <div class="col-md-4 " id="input" >
          <h1>Log In</h1>
          <div class="inputBox">
            <input type="text" required="required" placeholder="Username" name="email_address">
          </div><br>
          <div class="inputBox">
            <input type="text" required="required" placeholder="Password" name="password">
            
          </div>
            <br>
           <div class="wrapper">
             <input type="checkbox" id="check">
             <label for="check">Remember me</label>
           </div>
            <br>
            <br>
        <button type="submit" name="login">Login</button>

      </div>
    </div>
  </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>