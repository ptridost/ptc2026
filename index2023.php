<?php
// This tells the navbar what year this page is for
$page_event_year = 2023; 

// This MUST be the very first line of the file to access login status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require('./dbcon.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />

    <title>Philippine Textile Congress 2023</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="./LandingPage.css">
    <link rel="stylesheet" type="text/css" href="./header&footer/Navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="./header&footer/footers.css">
</head>

<body>

    <?php include("header&footer/navbar.php"); // Includes the new "smart" navbar ?>

    <main>
        <div class="container" style="margin-top: 100px;">
            <div class="row">
                <div class="col-md-12 vid text-center">
                    <video width="100%" loop muted autoplay="autoplay" plays-inline>
                        <source src="landingpageimg/2023rev.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>

        <section class="gallery11 container my-5">
            <div class="row gy-4 row-cols-1 row-cols-sm-2 justify-content-center">
                <?php
                    $sql_rc = $dbcon->query("SELECT * FROM events_description WHERE name IN ('researchcol.png', 'InnovationDialogues1.png')");
                    while($sql_rc_row = $sql_rc->fetch_assoc()){
                ?>
                <div class="col">
                    <a href="#<?php echo $sql_rc_row['id']; ?>"> <img
                            src="landingpageimg/<?php echo $sql_rc_row['name']; ?>" class="img-fluid shadow-sm"
                            alt="gallery thumbnail"></a>
                </div>
                <?php 
                    }
                ?>
            </div>
        </section>

        <div id="1">
            <div class="container conrc">
                <div class="row text-center mb-4">
                    <div class="col-12"><img src="landingpageimg/rclogo.jpg" id="rclogo" alt="Research Colloquium Logo">
                    </div>
                    <div class="col-12">
                        <h1 class="conrc__title">Research Colloquium</h1>
                    </div>
                    <div class="col-md-11 mx-auto">
                        <p class="conrc__subtitle">The Philippine Textile Research Colloquium is a virtual conference of
                            researchers...</p>
                    </div>
                </div>

                <div class="row g-3 justify-content-center mt-4">
                    <div class="col-md-4"><a href="landingpageimg/plenary.png" class="gallery-link"
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/plenary.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s1.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s1.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s2.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s2.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s3.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s3.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s4.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s4.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s5.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s5.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s6.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s6.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s7.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s7.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/special.png" class="gallery-link"
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/special.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s8.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s8.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s9.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s9.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s10.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s10.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s11.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s11.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/s12.png" class="gallery-link" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/s12.png');"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/closing.png" class="gallery-link"
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024" style="background-image: url('landingpageimg/closing.png');"></div>
                        </a></div>
                </div>
            </div>
        </div>
    </main>

    <div id="progress">
        <span id="progress-value"><i class="fas fa-arrow-up"></i></span>
    </div>

    <div class="modal fade" id="imageGalleryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gallery Preview</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><img src="" id="modalImage" class="img-fluid" alt="Gallery Preview"></div>
            </div>
        </div>
    </div>

    <?php include("header&footer/footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        xintegrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="/app.js"></script>

    <script>
    $(document).ready(function() {
       

        // --- Logic for Gallery Modal ---
        const galleryModal = document.getElementById('imageGalleryModal');
        if (galleryModal) {
            galleryModal.addEventListener('show.bs.modal', function(event) {
                const link = event.relatedTarget;
                const imageUrl = link.getAttribute('href');
                const modalImage = document.getElementById('modalImage');
                modalImage.setAttribute('src', imageUrl);
            });
        }

        // --- Scroll-to-Top Button Logic ---
        let calcScrollValue = () => {
            let scrollProgress = document.getElementById("progress");
            if (!scrollProgress) return;
            let pos = document.documentElement.scrollTop;
            let calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrollValue = Math.round((pos * 100) / calcHeight);
            scrollProgress.style.display = (pos > 100) ? "grid" : "none";
            scrollProgress.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            scrollProgress.style.background =
                `conic-gradient(#163180 ${scrollValue}%, #d7d7d7 ${scrollValue}%)`;
        };
        window.onscroll = calcScrollValue;
        window.onload = calcScrollValue;
    });
    </script>

</body>

</html>