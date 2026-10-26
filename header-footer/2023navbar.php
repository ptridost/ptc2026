<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Navbar Bootstrap</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="./NavbarStyle.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />



    <link rel="preconnect" href="https://fonts.googleapis.com">
   
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  </head>
  
  <body>
  
  <nav class="navbar fixed-top navbar-expand-lg navbar-light" id="navnav">
  <div class="container-fluid logo">
    <nav class="navbar navbar-light">
  <a class="navbar-brand" id="brand" href="http://philippinetextilecongress.com/index.php">
    <img src="./img_outside/navlogo.png" id="logog"></a>
</nav>
    <button class="navbar-toggler justify-content" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto gap-1"> 
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="http://philippinetextilecongress.com/index2023.php">HOME</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://philippinetextilecongress.com/programme.php">PROGRAMME</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://philippinetextilecongress.com/speakersPage2023.php">SPEAKERS</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            2023 PTC
          </a>
          <ul class="dropdown-menu border-0" aria-labelledby="navbarDropdown" id="dropdownmenu">
        <li class="2022" style="text-align: center; align-items: center; width: 121px; margin-bottom: 10px;"><a class="dropdown-item"  href="http://philippinetextilecongress.com/index.php" target="_blank">2025</a></li>
        <li class="2022" style="text-align: center; align-items: center; width: 121px; margin-bottom: 10px;"><a class="dropdown-item"  href="http://philippinetextilecongress.com/index2024.php" target="_blank">2024</a></li>
        <li class="2022" style="text-align: center; align-items: center; width: 121px; margin-bottom: 10px;"><a class="dropdown-item"  href="http://philippinetextilecongress.com/index2022.php" target="_blank">2022</a></li>
        </ul>
         </li>
         
        <li class="nav-item">
          <a class="nav-link" href="http://philippinetextilecongress.com/contacts/contact.php">CONTACTS</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="http://philippinetextilecongress.com/analytics2024.php"> STATISTICS</a>
        </li>
        </div>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav ms-auto mb-1 ">
          <button onclick=" window.open('http://philippinetextilecongress.com/loginn.php', '_self'); return false;" type="button" class="button" href="http://localhost/loginn.php">
        <span class="buttontext">LOGIN</span>
        <span class="buttonicon"><i class="fa-solid fa-user-plus"></i></span>
        </button>
          
          <button onclick=" window.open('http://philippinetextilecongress.com/registration.php', '_self'); return false;" type="button" class="button1" href="#">
        <span class="buttontext">REGISTER</span>
        <span class="buttonicon">
        <i class="fa-solid fa-qrcode fa-lg"></i> 
        </span>
        </button>

      </ul>
    </div>
</nav>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>