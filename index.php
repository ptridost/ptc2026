<?php
define('BASE_PATH', '');
// if(isset($_COOKIE['visit'])){
// header("Location:maintenance.php");
// }

// setCookie("visit","1",time()+60*60*7*12);
//
require('./dbcon.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <link rel="shortcut icon" type="image/x-icon" href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css'>

    <title>Philippine Textile Congress</title>

    <link rel="stylesheet" type="text/css" href="./LandingPage.css">
    <link rel="stylesheet" type="text/css" href="./card.css">
    <link rel="stylesheet" type="text/css" href="./header-footer/footers.css">
    <link rel="stylesheet" type="text/css" href="./header-footer/NavbarStyle.css">
    
    <link rel="mask-icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111" />
      
    <script defer src="app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" charset="utf-8"></script>
    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-2c7831bb44f98c1391d6a4ffda0e1fd302503391ca806e7fcc7b9b87197aec26.js"></script>
  </head>
  <body>
    
    <div style="padding-top: 20px;"></div>
    <div class="container bannerp1" >
        <div class="row justify-content-start">
            <div class="col-md-1 info"></div>
                <center><img src="./img_outside/callforabstract2025.jpg" alt="Your Image Description" class="shadow-lg img-fluid call-to-action-image" style="border-radius: 15px;"></center>
                <div class="container pt-4">
                    <div class="row g-4">
                        <div class="col-md-6 d-flex flex-column gap-4">
                            <div class="square-image-container shadow-lg">
                                <img src="tc_assets_2025/tc20251.jpg" alt="Description of image 1">
                            </div>
                            <div class="square-image-container shadow-lg">
                                <img src="tc_assets_2025/tc20252.jpg" alt="Description of image 2">
                            </div>
                            <div class="square-image-container shadow-lg">
                                <img src="tc_assets_2025/tc20253.jpg" alt="Description of image 3">
                            </div>
                        </div>

                        <div class="col-md-6 d-flex flex-column gap-4">
                            <div class="square-image-container shadow-lg">
                                <img src="tc_assets_2025/tc20254.jpg" alt="Description of image 4">
                            </div>
                            <div class="square-image-container shadow-lg">
                                <img src="tc_assets_2025/tc20255.jpg" alt="Description of image 5">
                            </div>
                            <div class="square-image-container shadow-lg">
                                <img src="tc_assets_2025/tc20256.jpg" alt="Description of image 6">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info">
                    <h1 class="text-center"> <span style="color:#BB121B">Philippine</span>
                    <span style="color:#163180">Textile </span><span style="color:#EFC130">Congress</span></h1>
                    <p class="text-center"> A platform for researchers, students, creatives, industry partners, and textile experts to converge,converse and collaborate to spur innovation in Philippine textiles.</p>
                    <center><a href="registration.php" target="_blank" class="btn btn-md text-center" id="btn1"><span>REGISTER NOW</span></a></center>

                    <script>
                        const btn = document.querySelector('#btn1'); // Correctly select by ID
                        if (btn) {
                            btn.onmousemove = function(e){
                                const x = e.pageX - btn.offsetLeft;
                                const y = e.pageY - btn.offsetTop;
                                btn.style.setProperty('--x', x + 'px');
                                btn.style.setProperty('--y', y + 'px');
                            }
                        }
                    </script>
                </div>
        </div>
    </div>

    <div class="container banner" >
        <div class="row">
            <div class="col-md-12 vid">
                <center> 
                    <video height="50%" width="100%" loop muted autoplay="autoplay" >
                    <source src="<php? echo BASE_BATH; ?>/landingpageimg/PTC_Teaser_Oct_2025.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </center>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card px-5 pb-5 pt-3 border-0 text-dark bg-white shadow-lg hidden" style="border-radius: 20px;">
            <div class="row">
                <h4 class="fw-bold text-center col-12 mb-5">Announcement</h4>
                <div class="col-12 order-1 order-md-2 d-flex flex-column align-items-center ">
                    <img src="landingpageimg/2025_PTC.png" alt="2025 PTC" class="img-fluid" >
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="card-grid-container">
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-face flip-card-front">
                        <article class="event-card shadow-lg">
                            <header class="event-card__header">
                                <h4 class="event-card__title">Research<br>Colloquium</h4>
                                <img src="./img_outside/Rc.png" alt="Research Colloquium Logo" class="event-card__logo">
                            </header>
                            <div class="event-card__body">
                                <p>The Philippine Textile Research Colloquium is a hybrid conference of researchers from the Philippines and abroad, which aims to present different studies and researches in the various disciplines intersecting in the field of textiles. The Textile Research Colloquium shall be a venue for dialogue and discussion on insights gained through the conduct of textile research.</p>
                                <div class="flip-instruction">Click card for objectives</div>
                            </div>
                        </article>
                    </div>
                    <div class="flip-card-face flip-card-back">
                        <article class="event-card shadow-lg">
                            <div class="event-card__body">
                                <h5>OBJECTIVES:</h5>
                                <ul>
                                    <li>Provide a venue for highlighting and showcasing multifaceted textile researches from the Philippines and abroad</li>
                                    <li>Highlight inclusivity and the circular economy of textiles in the different academic fields</li>
                                    <li>Update academics, professionals, and partners with the latest trends and development on textiles</li>
                                </ul>
                            </div>
                        </article>
                    </div>
                </div>
            </div>

            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-face flip-card-front">
                        <article class="event-card">
                            <header class="event-card__header">
                                <h4 class="event-card__title">Innovation<br>Dialogues</h4>
                                <img src="./img_outside/InnovationDialogue.png" alt="Innovation Dialogues Logo" class="event-card__logo">
                            </header>
                            <div class="event-card__body">
                                <p>As part of the mandate of DOST-PTRI to be the premier textile research and development arm of the Department of Science and Technology, it strives to adapt an integrative development approach. The Philippine Textile Congress Innovation Dialogues shall bring together academic institutions specializing in textile research and allied fields to foster inter sectoral dialogue and bring together an exchange of ideas and discourse regarding innovation and the future of the Philippine textile industry. This year, Innovation Dialogues will bring textile R&D opportunities to our students and researchers in diverse fields as we go to academic partners for technology pitching through our Joint Research Undertaking (JRU), Open Laboratory Contracts, and Collaborative Research Agreements, encouraging them to pursue R&D in textile science and technology.</p>
                                <div class="flip-instruction">Click card for objectives</div>
                            </div>
                        </article>
                    </div>
                    <div class="flip-card-face flip-card-back">
                        <article class="event-card shadow-lg">
                            <div class="event-card__body">
                                <h5>OBJECTIVES:</h5>
                                <ul>
                                    <li>Adapt a transdisciplinary and integrative development approach to spur the relevant textile R&D innovations for sustainable development.</li>
                                    <li>Gather academic institutions specializing in textiles and allied fields for fruitful discussions on an inter-sectoral institutional research and development agenda</li>
                                    <li>Establish a Textile R&D Consortium between academic partners and institutions</li>
                                </ul>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div><div id="4"></div>
    <div id="1">
        <div class="container conrc hidden">
            <div class="row" >
            <center>
                <div class="col-sm-1 col-md-1 " >
                    <img src="./img_outside/Rc.png" id="rclogo">
                </div>
            </center>
        </div>
        <div class="row h1">
            <center><h1 class="conrc__title">Research Colloquium</h1></center>
        </div>

        <div class="row h2">
            <center>
            <div class="col-md-12">
                    <p class="conrc__subtitle">The Philippine Textile Research Colloquium is a virtual conference of researchers from the Philippines and abroad, with the aim of presenting different studies and research projects in the various disciples intersecting in the field of textiles.</p>
            </div>
            </center>
        </div>
    
        <div class="row g-3 justify-content-center mt-4">
            <?php
                $event_year = 2025;
                $table_name = "rc_events_" . $event_year;
                // Ensure this path is correct for the web. Root-relative (starting with /) is usually best.
                $folder_path = "./rcpics" . $event_year . "/";
                
                $sql_query = "SELECT title FROM {$table_name} WHERE status = 1 ORDER BY id;";
                $result = $dbcon->query($sql_query);
                
                if ($result && $result->num_rows > 0) {
                    while($row = mysqli_fetch_assoc($result)){
                        $web_image_path = $folder_path . htmlspecialchars($row["title"]);
                        
                        // This is the simplified and corrected loop.
                        // We have removed the file_exists() check to solve the issue.
                        echo 
                        '<div class="col-md-4">
                            <a href="' . $web_image_path . '" 
                            class="gallery-link" 
                            data-bs-toggle="modal" 
                            data-bs-target="#imageGalleryModal">
                                <div class="rcpic2024 lazy-bg" data-bgimage="' . $web_image_path . '">
                                </div>
                            </a>
                        </div>';
                    }
                } else {
                    echo '<p class="text-center text-white">No images to display at this time.</p>';
                }
            ?> 
        </div>
    </div>
</div>

    <div id="progress">
      <span id="progress-value"><i class="fas fa-arrow-up"></i></span> 
    </div>
    <div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageGalleryModalLabel">Session Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="" id="modalImage" class="img-fluid" alt="Gallery Image Preview">
      </div>
    </div>
  </div>
</div>
    <?php
    include("./header-footer/navbar.php" );
    include("./header-footer/footer.php" );
    $dbcon->close();
    ?>
    <script>
        const galleryModal = document.getElementById('imageGalleryModal');
    if (galleryModal) {
        galleryModal.addEventListener('show.bs.modal', function (event) {
            const link = event.relatedTarget;
            const imageUrl = link.getAttribute('href');
            const modalImage = document.getElementById('modalImage');
            modalImage.setAttribute('src', imageUrl);
        });
    }
    </script>

    <script>
        // --- Flip Card Logic ---
        const cards = document.querySelectorAll('.flip-card');
        cards.forEach(card => {
            card.addEventListener('click', function(event) {
                event.preventDefault(); 
                card.classList.toggle('is-flipped');
            });
        });

        // --- Scroll-to-Top Button Animation Logic ---
        let calcScrollValue = () => {
            let scrollProgress = document.getElementById("progress");
            let progressValue = document.getElementById("progress-value");
            let pos = document.documentElement.scrollTop;
            let calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            
            let scrollValue = Math.round((pos * 100) / calcHeight);
            
            if (pos > 100) {
                scrollProgress.style.display = "grid";
            } else {
                scrollProgress.style.display = "none";
            }
            
            scrollProgress.addEventListener("click", () => {
                document.documentElement.scrollTop = 0;
            });
            
            scrollProgress.style.background = `conic-gradient(#163180 ${scrollValue}%, #d7d7d7 ${scrollValue}%)`;
        };

        window.onscroll = calcScrollValue;
        window.onload = calcScrollValue;
    </script>
  </body>
</html>
