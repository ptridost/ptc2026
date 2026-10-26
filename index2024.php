<?php
// This tells the navbar what year this page is for
$page_event_year = 2024; 

// This MUST be the very first line of the file to access login status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require('./dbcon.php');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />

    <title>Philippine Textile Congress 2024</title>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="./LandingPage.css">
    <link rel="stylesheet" type="text/css" href="./card.css">
    <link rel="stylesheet" type="text/css" href="./header-footer/NavbarStyle.css">
    <link rel="stylesheet" type="text/css" href="./header-footer/footers.css">
</head>

<body>
    <?php include("header-footer/navbar.php"); ?>

    <main>
        <div class="container" style="margin-top: 120px;">
            <div class="info text-center">
                <h1> <span style="color:#BB121B">Philippine</span> <span style="color:#163180">Textile </span><span
                        style="color:#EFC130">Congress</span></h1>
                <p> A platform for researchers, students, creatives, industry partners, and textile experts to
                    converge,converse and collaborate to spur innovation in Philippine textiles.</p>
            </div>
        </div>

        <div class="container banner">
            <div class="row">
                <div class="col-md-12 vid text-center">
                    <video width="100%" loop muted autoplay="autoplay" plays-inline>
                        <source src="landingpageimg/PTC2024TeaserRevision.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>

        <section class="gallery11 container my-5">
            <div class="row gy-4 justify-content-center">
                <div class="col-12 col-md-6">
                    <a href="#4" class="summary-card-link">
                        <article class="summary-card shadow-sm">
                            <header class="summary-card__header">
                                <img src="./img_outside/rcnobg.png" class="summary-card__logo"
                                    alt="Research Colloquium Logo">
                                <h4 class="summary-card__title">Research Colloquium</h4>
                            </header>
                            <div class="summary-card__body">
                                <p>The Philippine Textile Research Colloquium is a virtual conference of Researchers
                                    from the Philippines and from abroad, with the aim of presenting different studies
                                    and research projects in the various disciplines intersecting in the field of
                                    textiles.</p>
                            </div>
                        </article>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="#5" class="summary-card-link">
                        <article class="summary-card shadow-sm">
                            <header class="summary-card__header">
                                <img src="./img_outside/idnobg.png" class="summary-card__logo"
                                    alt="Innovation Dialogues Logo">
                                <h4 class="summary-card__title">Innovation Dialogues</h4>
                            </header>
                            <div class="summary-card__body">
                                <p>The Philippine Textile Innovation Dialogues is the gathering of the different threads
                                    of the textile sector which fosters Intersectoral dialogue and brings together an
                                    exchange of ideas and discourse regarding innovation and the future of the
                                    Philippine Textile Industry.</p>
                            </div>
                        </article>
                    </a>
                </div>
            </div>
        </section>

        <div id="1">
            <div class="container conrc mb-5 hidden">
                <div class="row text-center">
                    <div class="col-sm-1 col-md-1 mx-auto"><img src="landingpageimg/rclogo.jpg" id="rclogo"
                            class="img-fluid"></div>
                    <h1 class="conrc__title">Research Colloquium</h1>
                    <div class="col-md-12">
                        <p class="conrc__subtitle">The Philippine Textile Research Colloquium is a virtual conference of
                            researchers from the Philippines and abroad, with the aim of presenting different studies
                            and research projects in the various disciples intersecting in the field of textiles.</p>
                    </div>
                </div>
                <div class="row g-3 justify-content-center mt-4">
                    <?php
                        $sql_query = "SELECT title FROM rc_events_2024 WHERE status = 1 ORDER BY id;";
                        $result = $dbcon->query($sql_query);
                        while($row = mysqli_fetch_assoc($result)){
                            $image_path = './rcpics2024/' . htmlspecialchars($row["title"]);
                            echo 
                            '<div class="col-md-4">
                                <a href="' . $image_path . '" class="gallery-link" data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                                    <div class="rcpic2024 lazy-bg" data-bgimage="' . $image_path . '"></div>
                                </a>
                            </div>';
                        }
                    ?>
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
                <div class="modal-body"><img src="" id="modalImage" class="img-fluid" alt="Gallery Image Preview"></div>
            </div>
        </div>
    </div>

    <?php include("header-footer/footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="/app.js"></script>
    <script>
    $(document).ready(function() {
        // Logic for Gallery Modal
        const galleryModal = document.getElementById('imageGalleryModal');
        if (galleryModal) {
            galleryModal.addEventListener('show.bs.modal', function(event) {
                const link = event.relatedTarget;
                const imageUrl = link.getAttribute('href');
                const modalImage = document.getElementById('modalImage');
                modalImage.setAttribute('src', imageUrl);
            });
        }
    });
    </script>
</body>

</html>