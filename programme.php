

<!DOCTYPE html>
<html lang="en-US">
<head>
<link rel="shortcut icon" type="image/x-icon" href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />

    <title>Philippine Textile Congress</title>

<meta charset="utf-8">
<?php 
// Include calendar helper functions 
include_once 'functions.php'; 
?>
<!-- Stylesheet file -->
<link rel="stylesheet" href="sessions.css">

<!-- jQuery library -->
<script src="jquery.min.js"></script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/header-footer/footers.css">
<link rel="stylesheet" type="text/css" href="/header-footer/NavbarStyle.css">


</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12 allprogtxt" id="allprogtxt"> <h1 id="progtxt">SCHEDULE OF ACTIVITIES</h1>
</div>
</div>
</div>
<div class="container" id="calcon">
<div class="row">
<div class="col-md-5">
<h1 class="cartext">Pick a date.</h1>
<br>
<div id="calendar_div">
<?php echo getCalender(); ?>
</div>
</div>

<div class="col-md-7" id="concar"> 
<h1 class="cartext">Here's the session of the day you choose.</h1><div id="carouselExampleDark" class="carousel carousel-light slide" data-bs-ride="carousel">

  <div class="carousel-indicators" >

    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1" style="background-color: darkblue;"></button>
    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2" style="background-color: darkblue;"></button>
    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3" style="background-color: darkblue;"></button>
  </div>

  <div class="carousel-inner" id="event_list">   
  </div>
   
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev" style="background:#D11217;">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
     </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next" style="background:#D11217;">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
  </button>
</div>
</div>

</div>
</div>
    
    <?php  include("header-footer/navbar.php" );?>
   <?php  include("header-footer/footer.php" );?>
</body>
</html>