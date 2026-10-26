<?php
$page_event_year = 2025; 

// CHANGE 1 of 3: Added the session_start() command to the very top of the file.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require('./dbcon.php');

$session_videos = [
    ['number' => 1, 'title' => 'Textile Sustainability and Circularity', 'youtube_id' => 'Qt6UVab6v9U'],
    ['number' => 2, 'title' => 'Digital Textile Technologies', 'youtube_id' => 'yiYAUX5KHOs'],
    ['number' => 3, 'title' => 'Textile Machinery and Industry', 'youtube_id' => 't9Tbz9CV2AU'],
    ['number' => 4, 'title' => 'Textile Heritage and Conservation', 'youtube_id' => 'TJdxnycItD0'],
    ['number' => 5, 'title' => 'Textiles in Human Security and Defense', 'youtube_id' => 'kDC05dU5PuU'],
    ['number' => 6, 'title' => 'Textile and Apparel Design', 'youtube_id' => 'tgHy_V5KVLo'],
    ['number' => 7, 'title' => 'Nonwoven Textile Innovations', 'youtube_id' => 'jKDVCN1SH30'],
    ['number' => 8, 'title' => 'Textile Dyes and Colorants', 'youtube_id' => 'TtiKj1nMN7M'],
    ['number' => 9, 'title' => 'Textile Waste Management and Remediation', 'youtube_id' => 'GLptvVMC6io'],
    ['number' => 10, 'title' => 'Composite and High-Performance Textiles', 'youtube_id' => 'lv1bEm8F7Ns'],
    ['number' => 11, 'title' => 'Natural Textile Materials', 'youtube_id' => 'DNHNfa8Y-W4'],
    ['number' => 12, 'title' => 'Textile Finishing and Functionalization', 'youtube_id' => '0MFjNJRP3HE'],
    ['number' => 13, 'title' => 'Textile Biotechnology', 'youtube_id' => 'SY7UK9dbRng'],
    ['number' => 14, 'title' => 'Textile Fiber Assemblies', 'youtube_id' => 'SdSxQoH1Ick'],
    ['number' => 15, 'title' => 'Textiles in Society and Economy', 'youtube_id' => 'f_YHl-hG-Qw'],
    ['number' => 16, 'title' => 'Biomedical Textiles', 'youtube_id' => 'gHjM_nKFq-w'],
    ['number' => 17, 'title' => 'Textile Quality Assurance', 'youtube_id' => 'OjRLjtd310Y'],
];
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />
    <title>Philippine Textile Congress</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="./LandingPage.css">
    <link rel="stylesheet" type="text/css" href="./card.css">
    <link rel="stylesheet" type="text/css" href="./ptc2025-videos.css">
    <link rel="stylesheet" type="text/css" href="./header-footer/footers.css">
    <link rel="stylesheet" type="text/css" href="./header-footer/NavbarStyle.css">
</head>


<body>

    <?php include("header-footer/navbar.php"); // Includes the "smart" navbar ?>
    <main>

        <div class="page-content-wrapper">
            <section class="ptc-session-videos" aria-labelledby="session-videos-title">
                <div class="container">
                    <div class="ptc-session-videos__heading text-center">
                        <span class="ptc-session-videos__eyebrow">Philippine Textile Congress 2025</span>
                        <h2 id="session-videos-title">Session Videos</h2>
                        <p>Watch the recorded discussions from all 17 PTC 2025 sessions.</p>
                    </div>

                    <div class="row g-4">
                        <?php foreach ($session_videos as $session): ?>
                            <?php
                            $session_number = (int) $session['number'];
                            $session_title = htmlspecialchars($session['title'], ENT_QUOTES, 'UTF-8');
                            $youtube_id = rawurlencode($session['youtube_id']);
                            $youtube_url = 'https://youtu.be/' . $youtube_id;
                            ?>
                            <div class="col-12 col-lg-6">
                                <article class="ptc-session-card h-100">
                                    <div class="ratio ratio-16x9 ptc-session-card__video">
                                        <iframe
                                            src="https://www.youtube-nocookie.com/embed/<?= $youtube_id ?>"
                                            title="PTC 2025 Session <?= $session_number ?> - <?= $session_title ?>"
                                            loading="lazy"
                                            referrerpolicy="strict-origin-when-cross-origin"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen></iframe>
                                    </div>
                                    <div class="ptc-session-card__body">
                                        <span class="ptc-session-card__number">Session <?= $session_number ?></span>
                                        <h3><?= $session_title ?></h3>
                                        <a href="<?= $youtube_url ?>" target="_blank" rel="noopener noreferrer"
                                            class="ptc-session-card__link"
                                            aria-label="Watch PTC 2025 Session <?= $session_number ?> on YouTube">
                                            Watch on YouTube
                                            <span aria-hidden="true">&rarr;</span>
                                        </a>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <div class="container bannerp1 text-center">
                <div class="row justify-content-start">
                    <center><img src="./img_outside/callforabstract2025.jpg" alt="Your Image Description"
                            class="shadow-lg img-fluid call-to-action-image"></center>
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
                    <div class="info pt-5 text-center">
                        <h1> <span style="color:#BB121B">Philippine</span>
                            <span style="color:#163180">Textile </span><span style="color:#EFC130">Congress</span>
                        </h1>
                        <p> A platform for researchers, students, creatives, industry partners, and textile experts to
                            converge,converse and collaborate to spur innovation in Philippine textiles.</p>
                        <center><a href="registration.php" target="_blank" class="btn btn-md text-center"
                                id="btn1"><span>REGISTER NOW</span></a></center>

                        <script>
                        const btn = document.querySelector('#btn1'); // Correctly select by ID
                        if (btn) {
                            btn.onmousemove = function(e) {
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

            <div class="container banner">
                <div class="row">
                    <div class="col-md-12 vid">
                        <center>
                            <video height="50%" width="100%" loop muted autoplay="autoplay">
                                <source src="landingpageimg/PTC_Teaser_Oct_2025.mp4" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </center>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="card px-5 pb-5 pt-3 border-0 text-dark bg-white shadow-lg hidden"
                    style="border-radius: 20px;">
                    <div class="row">
                        <h4 class="fw-bold text-center col-12 mb-5">Announcement</h4>
                        <div class="col-12 order-1 order-md-2 d-flex flex-column align-items-center ">
                            <img src="landingpageimg/2025_PTC.png" alt="2025 PTC" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            <div class="container my-5">
                <div class="accordion shadow-lg" id="eventDetailsAccordion" style=" overflow: hidden;">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <img src="./img_outside/Rc.png" class="accordion-logo me-3"
                                    alt="Research Colloquium Logo">
                                Research Colloquium
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#eventDetailsAccordion">
                            <div class="accordion-body">
                                <p>The Philippine Textile Research Colloquium is a hybrid conference of researchers from
                                    the Philippines and abroad, which aims to present different studies and researches
                                    in the various disciplines intersecting in the field of textiles. The Textile
                                    Research Colloquium shall be a venue for dialogue and discussion on insights gained
                                    through the conduct of textile research.</p>
                                <h5 class="mt-4">OBJECTIVES:</h5>
                                <ul>
                                    <li>Provide a venue for highlighting and showcasing multifaceted textile researches
                                        from the Philippines and abroad</li>
                                    <li>Highlight inclusivity and the circular economy of textiles in the different
                                        academic fields</li>
                                    <li>Update academics, professionals, and partners with the latest trends and
                                        development on textiles</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <img src="./img_outside/InnovationDialogue.png" class="accordion-logo me-3"
                                    alt="Innovation Dialogues Logo">
                                Innovation Dialogues
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#eventDetailsAccordion">
                            <div class="accordion-body">
                                <p>As part of the mandate of DOST-PTRI to be the premier textile research and
                                    development arm of the Department of Science and Technology, it strives to adapt an
                                    integrative development approach. The Philippine Textile Congress Innovation
                                    Dialogues shall bring together academic institutions specializing in textile
                                    research and allied fields to foster inter sectoral dialogue and bring together an
                                    exchange of ideas and discourse regarding innovation and the future of the
                                    Philippine textile industry.</p>
                                <h5 class="mt-4">OBJECTIVES:</h5>
                                <ul>
                                    <li>Adapt a transdisciplinary and integrative development approach to spur the
                                        relevant textile R&D innovations for sustainable development.</li>
                                    <li>Gather academic institutions specializing in textiles and allied fields for
                                        fruitful discussions on an inter-sectoral institutional research and development
                                        agenda</li>
                                    <li>Establish a Textile R&D Consortium between academic partners and institutions
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="4"></div>
            <div id="1">
                <div class="container conrc hidden">
                    <div class="row">
                        <center>
                            <div class="col-sm-1 col-md-1 ">
                                <img src="./img_outside/Rc.png" id="rclogo">
                            </div>
                        </center>
                    </div>
                    <div class="row h1">
                        <center>
                            <h1 class="conrc__title">Research Colloquium</h1>
                        </center>
                    </div>

                    <div class="row h2">
                        <center>
                            <div class="col-md-12">
                                <p class="conrc__subtitle">The Philippine Textile Research Colloquium is a virtual
                                    conference of
                                    researchers from the Philippines and abroad, with the aim of presenting different
                                    studies
                                    and research projects in the various disciples intersecting in the field of
                                    textiles.
                                </p>
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
    </main>

    <div id="progress">
        <span id="progress-value"><i class="fas fa-arrow-up"></i></span>
    </div>

    <div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel"
        aria-hidden="true">
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

    <?php include("header-footer/footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="/app.js"></script>

    <script>
    $(document).ready(function() {
        const scrollProgress = document.getElementById("progress");

        if (scrollProgress) {
            scrollProgress.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        let calcScrollValue = () => {
            if (!scrollProgress) return;

            let pos = document.documentElement.scrollTop;
            let calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrollValue = Math.round((pos * 100) / calcHeight);
            // Show or hide the button
            scrollProgress.style.display = (pos > 100) ? "grid" : "none";
            scrollProgress.style.background =
                `conic-gradient(#0E2369 ${scrollValue}%, #d7d7d7 ${scrollValue}%)`;
        };

        // 3. Attach the visual update function to the scroll and load events.
        window.onscroll = calcScrollValue;
        window.onload = calcScrollValue;
        // --- END OF CORRECTION ---


        // --- Logic for Gallery Modal (Preserved) ---
        const galleryModal = document.getElementById('imageGalleryModal');
        if (galleryModal) {
            galleryModal.addEventListener('show.bs.modal', function(event) {
                const link = event.relatedTarget;
                const imageUrl = link.getAttribute('href');
                const modalImage = document.getElementById('modalImage');
                modalImage.setAttribute('src', imageUrl);
            });
        }

        // --- Flip Card Logic (Preserved) ---
        $('.flip-card').on('click', function(event) {
            event.preventDefault();
            $(this).toggleClass('is-flipped');
        });
    });
    </script>


</body>

</html>
