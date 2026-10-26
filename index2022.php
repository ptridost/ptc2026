<?php
// This tells the navbar what year this page is for
$page_event_year = 2022; 

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

    <title>Philippine Textile Congress 2022</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="./LandingPage.css">
    <link rel="stylesheet" type="text/css" href="./header-footer/NavbarStyle.css">
    <link rel="stylesheet" type="text/css" href="./header-footer/footers.css">
</head>

<body>
    <?php include("header-footer/navbar.php"); ?>

    <main>
        <div class="container" style="margin-top: 100px;">
            <div class="row">
                <div class="col-md-12 vid text-center">
                    <video width="100%" loop muted autoplay="autoplay" plays-inline>
                        <source src="landingpageimg/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>

        <section class="gallery11 container my-5">
            <div class="row gy-4 row-cols-1 row-cols-sm-2 row-cols-md-3 justify-content-center">
                <?php
                    $sql_rc = $dbcon->query("SELECT * FROM events_description WHERE name IN ('ResearchColloquium.png', 'InnovationDialogues.png', 'FashionDesignExhibition.png')");
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
            <div class="container conrc my-5 hidden">
                <div class="row text-center">
                    <div class="col-12"><img src="landingpageimg/rclogo.jpg" id="rclogo" alt="Research Colloquium Logo">
                    </div>
                    <h1 class="conrc__title">Research Colloquium</h1>
                    <div class="col-md-11 mx-auto">
                        <p class="conrc__subtitle">The Philippine Textile Research Colloquium is a virtual conference of
                            researchers from the Philippines and abroad, with the aim of presenting different studies
                            and research projects in the various disciples intersecting in the field of textiles.</p>
                    </div>
                </div>
                <div class="row g-3 justify-content-center mt-4">
                    <div class="col-md-4"><a href="landingpageimg/picture1.png" class="gallery-link"
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024 lazy-bg" data-bgimage="landingpageimg/picture1.png"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/picture2.png" class="gallery-link"
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024 lazy-bg" data-bgimage="landingpageimg/picture2.png"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/picture3.png" class="gallery-link"
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024 lazy-bg" data-bgimage="landingpageimg/picture3.png"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/picture4.png" class="gallery-link"
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024 lazy-bg" data-bgimage="landingpageimg/picture4.png"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/picture5.png" class="gallery-link"
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024 lazy-bg" data-bgimage="landingpageimg/picture5.png"></div>
                        </a></div>
                    <div class="col-md-4"><a href="landingpageimg/picture6.png" class="gallery-link"
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                            <div class="rcpic2024 lazy-bg" data-bgimage="landingpageimg/picture6.png"></div>
                        </a></div>
                </div>
            </div>
        </div>

        <div id="2">
            <div class="container conid my-5 hidden">
                <div class="row text-center">
                    <div class="col-12"><img src="landingpageimg/idlogo.png" id="idlogo" alt="Innovation Dialogue Logo">
                    </div>
                    <h1 class="conid__title">Innovation Dialogue</h1>
                    <div class="col-md-11 mx-auto">
                        <p class="conid__subtitle">The Philippine Innovation Dialogues is the gathering of the threads
                            of textile sector which fosters inter-sectoral dialogue and brings together an exchange of
                            ideas and discourse discourse regarding innovation innovation and future future of the
                            Philippine textile industry.</p>
                    </div>
                </div>
                <div class="row g-3 justify-content-center mt-4">
                    <?php
                    $sql_id = $dbcon->query("SELECT * FROM innovation_dialogue WHERE visibility = 'visible'");
                    while($sql_id_row = $sql_id->fetch_assoc()){
                        $image_path = 'landingpageimg/' . htmlspecialchars($sql_id_row['name']);
                        echo 
                        '<div class="col-md-4">
                            <a href="' . $image_path . '" class="gallery-link" data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                                <img src="' . $image_path . '" class="img-fluid rounded shadow-sm gallery-image-natural" alt="Innovation Dialogue Image">
                            </a>
                        </div>';
                    }
                ?>
                </div>
            </div>
        </div>

        <div id="3">
            <div class="container confde my-5 hidden">
                <div class="row text-center">
                    <div class="col-12"><img src="landingpageimg/fdelogo.png" id="fdelogo"
                            alt="Fashion Design Exhibition Logo"></div>
                    <h1 class="confde__title">Fashion Design Exhibition</h1>
                    <div class="col-md-11 mx-auto">
                        <p class="confde__subtitle">The Philippine Fashion Design Exhibition is the avenue to display
                            garments exhibiting artistic styles of local fashion and garment student designers, by
                            incorporating natural tropical fiber-based textiles and Philippine dyes.</p>
                    </div>
                </div>
                <div class="row g-3 justify-content-center mt-4">
                    <?php
                        $sql_fde = $dbcon->query("SELECT * FROM fashion_design_exhibition WHERE visibility = 'visible'");
                        while($sql_fde_row = $sql_fde->fetch_assoc()){
                            $image_path = 'landingpageimg/' . htmlspecialchars($sql_fde_row['name']);
                            echo 
                            '<div class="col-md-4">
                                <a href="' . $image_path . '" class="gallery-link" data-bs-toggle="modal" data-bs-target="#imageGalleryModal">
                                    <div class="rcpic2024 rcpic2024--portrait lazy-bg" data-bgimage="' . $image_path . '"></div>
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
                    <h5 class="modal-title">2022 Gallery</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><img src="" id="modalImage" class="img-fluid" alt="Gallery Preview"></div>
            </div>
        </div>
    </div>

    <?php include("header-footer/footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        xintegrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
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