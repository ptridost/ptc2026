<!doctype html>
<html lang="en">
  <head>
   
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>SPEAKERS</title>
    <link rel="stylesheet" type="text/css" href="SpeakersPage.css"/>
      <script defer src="app.js"></script>
      <?php $dbcon = mysqli_connect("localhost","root","","sampledb") or die(mysqli_error()); ?>
  </head>

  <body>
    <script src="Speakerspage.js"></script>
            <div id="progress">
          <span id="progress-value">&#x1F815;</span></div>
     </div>
    <div class="container-fluid containers1">
      <div class="row">
        <div class="col-md-12  text1">
          <div class="wrapper">
  <div class="heading text-center"> 
    <span class="char1" data-heading="2">2</span> 
    <span class="char2" data-heading="0">0</span> 
    <span class="char3" data-heading="2">2</span> 
    <span class="char4" data-heading="2">2</span> 
  
  </div>
</div>
    <div><h1 class="text-center">FEATURED EXPERT SPEAKERS</h1></div>
        </div>
      </div>
      </div>
   <section class="gallery min-vh-100 ">
     <div class="container-lg container2">
       <div><h1 class="text-start" id="h1p">Say hello to our speakers</h1></div>
      <div><h2 class="text-start" id="h2p">We couldn't be more excited about our lineup. Which talks will you be checking out?</h2></div><br>
        <div class="row gy-4 row-cols-1 row-cols-sm-2 row-cols-md-3 ">
              <?php
  $sql_rc = mysqli_query($dbcon, "SELECT * FROM speakers WHERE visibility = 'visible'");
        while($sql_rc_row = mysqli_fetch_array($sql_rc)){
  ?>

    <div class="col-lg-3 col-md-4 col-sm-6">
      <div class="col ">
      <img src="Speakers/<?php echo $sql_rc_row['name']; ?>" class="gallery-item" alt="gallery" >
    </div>
  </div>
  <?php 
          }
  ?>
           </div>

        </div>
  </section>


  </body>
</html>
