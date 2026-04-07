<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <title>PTRI CONGRESS</title>
    <link rel="stylesheet" type="text/css" href="LandingPage.css">
     <link rel="stylesheet" type="text/css" href="/header&footer/footers.css">
     <link rel="stylesheet" type="text/css" href="/header&footer/Navbarstyle.css">
    <script defer src="app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" charset="utf-8"></script>
   <?php $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error()); ?>
  </head>
  <body >

    <script src="jquery.magnify.js" charset="utf-8"></script>
        <script src="Speakerspage.js"></script>
            <div id="progress">
          <span id="progress-value">&#x1F815;</span></div>

<div class="container banner" >
   <?php
  $sql_rc = mysqli_query($dbcon, "SELECT * FROM landingpage_video WHERE visibility = 'visible'");
        while($sql_rc_row = mysqli_fetch_array($sql_rc)){
  ?>
  <div class="row">
      <div class="col-md-12 vid">
       <center> <video height="50%" width="100%" loop muted autoplay="autoplay" >

          <source src="landingpageimg/<?php echo $sql_rc_row['name']; ?>" type="video/mp4">
           
  Your browser does not support the video tag.
</video></center>

    </div>
   </div>
       <?php 
          }
  ?>

 </div>

    <div class="container bannerp1" >
  <div class="row justify-content-start">
       <div class="col-md-1 info"></div>
    <div class="col-md-10 info">
      <h1 class="text-center"> <span style="color:#BB121B">Philippine</span>
     <span style="color:#163180">Textile </span><span style="color:#EFC130">Congress</span></h1>
      <p class="text-center"> A platform for researchers, students, creatives, industry partners, and textile experts to converge,converse and collaborate to spur innovation in Philippine textiles.</p>
      <center><a href="#" class="btn btn-md text-center" id="btn1"><span>REGISTER NOW</span></a></center>
      <script>
        const btn = document.querySelector('.btn');
        btn.onmousemove = function(e){
          const x = e.pageX - btn.offsetLeft;
          const y = e.pageY - btn.offsetTop;

          btn.style.setProperty('--x', x + 'px');
          btn.style.setProperty('--y', y + 'px');
        }
      </script>

    </div>
  </div>
</div>
  <section class="gallery11 ">
     <div class="container-lg container02">
        <div class="row gy-4 row-cols-1 row-cols-sm-2 row-cols-md-3">
          <?php
  $sql_rc = mysqli_query($dbcon, "SELECT * FROM events_description WHERE visibility = 'visible'");
        while($sql_rc_row = mysqli_fetch_array($sql_rc)){
  ?>
           <div class="col hidden">
              <img src="landingpageimg/<?php echo $sql_rc_row['name']; ?>" class="item" alt="gallery">
           </div>
           <?php 
          }
  ?>
        </div>
      </div></section>


    <div class="container conrc hidden">
    <div class="row">

      <center><div class="col-sm-1 col-md-1 ">
      <img src="landingpageimg/rclogo.jpg" id="rclogo">
    </div></center>
  </div>
  <div class="row h1">
    <center><h1>Research Colloquium</h1></center>
  </div>
  <div class="row h2">

    <center>
      <div class="col-md-11">
        <h1>The Philippine Textile Research Colloquium is a virtual conference of researchers from the Philippines and abroad, with the aim of presenting different studies and research projects in the various disciples intersecting in the field of textiles.</h1>
      </div></center>
  </div>
   
 <div class="row h3">
  <div class="row gy-2 row-cols-1 row-cols-sm-2 row-cols-md-3">
  <?php
  $sql_rc = mysqli_query($dbcon, "SELECT * FROM research_colloquium WHERE visibility = 'visible'");
        while($sql_rc_row = mysqli_fetch_array($sql_rc)){
  ?>
    <div class="col-md-4 rcpic">
       <img src="landingpageimg/<?php echo $sql_rc_row['name']; ?>" id="image" class="gallery-item" alt="gallery">
    </div>

           <?php 
          }
  ?>
  </div>
     
</div></div>
<!-- Modal -->
<div class="modal fade" id="gallery-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Philippine Textile Congress</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="landingpageimg/<?php echo $sql_rc_row['name']; ?>" class="modal-img" alt="modal img">
      </div>
    </div>
  </div>
</div>
<div class="container conid hidden">
    <div class="row" >

      <center><div class="col-sm-1 col-md-1 ">
      <img src="landingpageimg/idlogo.png" id="idlogo">
    </div></center>
  </div>
  <div class="row h1">
    <center><h1>Innovation Dialogue</h1></center>
  </div>
  <div class="row h2">

    <center>
      <div class="col-md-11">
        <h1>The Philippine Innovation Dialogues is the gathering of the threads of textile sector which fosters inter-sectoral dialogue and brings together an exchange of ideas and discourse discourse regarding innovation innovation and future future of the Philippine textile industry.</h1>
      </div></center>
  </div>
 <div class="row h3">
  <div class="row gy-2 row-cols-1 row-cols-sm-2 row-cols-md-3">
  <?php
  $sql_rc = mysqli_query($dbcon, "SELECT * FROM innovation_dialogue WHERE visibility = 'visible'");
        while($sql_rc_row = mysqli_fetch_array($sql_rc)){
  ?>
   
    <div class="col-md-4 idpic" >
      <img src="landingpageimg/<?php echo $sql_rc_row['name']; ?>" id="image" class="gallery-item" alt="gallery" >
    </div>
        <?php 
          }
  ?>
  </div>
   

</div></div>


<div class="container confde hidden">
    <div class="row">

      <center><div class="col-sm-1 col-md-1 ">
      <img src="landingpageimg/fdelogo.png" id="fdelogo">
    </div></center>
  </div>
  <div class="row h1">
    <center><h1>Fashion Design Exhibition</h1></center>
  </div>
  <div class="row h2">

    <center>
      <div class="col-md-11">
        <h1>The Philippine Fashion Design Exhibition is the avenue to display garments exhibiting artistic styles of local fashion and garment student designers, by incorporating natural tropical fiber-based textiles and Philippine dyes.</h1>
      </div></center>
  </div>
 
<div class="row fderow">
  <div class="row gy-0 row-cols-1 row-cols-sm-2 row-cols-md-3">
  <?php
  $sql_rc = mysqli_query($dbcon, "SELECT * FROM fashion_design_exhibition WHERE visibility = 'visible'");
        while($sql_rc_row = mysqli_fetch_array($sql_rc)){
  ?>
   
  <div class="col-md-2 fdepic" id="fde1">
    <img src="landingpageimg/<?php echo $sql_rc_row['name']; ?>" id="image" class="gallery-item" alt="gallery">
  </div>
 <?php 
          }
  ?>

 </div>
  
</div></div>

   <?php  include("header&footer/navbar.php" );?>
   <?php  include("header&footer/footer.php" );?>
    <script src="main.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>