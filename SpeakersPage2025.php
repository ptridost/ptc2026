<?php
// This tells the navbar what year this page is for
$page_event_year = 2025; 

// This MUST be the very first line of the file
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
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css'>

    <title>PTC 2025 Speakers</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="./header-footer/NavbarStyle.css">
    <link rel="stylesheet" type="text/css" href="./header-footer/footers.css">
    <link rel="stylesheet" type="text/css" href="./speakersPage.css">
</head>

<body>
    <?php include("header-footer/navbar.php"); ?>

    <main>
        <div class="container-fluid speakers-hero">
            <div class="wrapper">
                <div class="heading">
                    <span data-heading="2">2</span><span data-heading="0">0</span><span data-heading="2">2</span><span
                        data-heading="5">5</span>
                </div>
            </div>
            <h1 class="speakers-hero__title">Featured Expert Speakers</h1>
        </div>

        <section class="speaker-grid">
            <div class="container-lg">
                <div class="speakers-intro">
                    <h1>Say hello to our speakers</h1>
                    <h2>We couldn't be more excited about our lineup.</h2>
                </div>
                <div class="row g-4 justify-content-center">
                    <?php
                    // This part of the script will now check for 2025 speakers.
                    // For now, it will find none and will trigger the 'else' block below.
                    $year_to_fetch = '2025';
                    $stmt = $dbcon->prepare("SELECT speaker_Name, speaker_Info, speaker_Img FROM tblspeakerses WHERE year = ? AND speaker_Img NOT IN ('placeholder.jpg','placeholder1.jpg') ORDER BY session_id, speaker_id");
                    $stmt->bind_param("s", $year_to_fetch);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // This part is now ready for the future. 
                        // Once you add 2025 speakers to the database, they will appear here automatically.
                        while($speaker = $result->fetch_assoc()){
                            $image_path = './User_Dashboard/2025/' . $speaker['speaker_Img'];
                            if (file_exists($image_path)) {
                ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="speaker-card">
                            <div class="speaker-card__image-container">
                                <img src="<?php echo htmlspecialchars($image_path); ?>" class="speaker-card__image"
                                    alt="<?php echo htmlspecialchars($speaker['speaker_Name']); ?>">
                            </div>
                            <div class="speaker-card__content">
                                <h3 class="speaker-card__name"><?php echo htmlspecialchars($speaker['speaker_Name']); ?>
                                </h3>
                                <p class="speaker-card__title"><?php echo htmlspecialchars($speaker['speaker_Info']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                            } 
                        } 
                    } else {
                        // THIS IS THE NEW "TO BE ANNOUNCED" CARD
                ?>
                    <div class="col-12">
                        <div class="tba-card">
                            <div class="tba-card__icon"><i class="fas fa-microphone-stand"></i></div>
                            <h3 class="tba-card__title">Stay Tuned!</h3>
                            <p class="tba-card__text">Our distinguished speakers for the 2025 Philippine Textile
                                Congress will be announced soon. Please check back for updates.</p>
                            <a href="speakersPage2024.php" class="btn btn-outline-primary">View 2024 Speakers</a>
                        </div>
                    </div>
                    <?php
                    }
                    $stmt->close();
                ?>
                </div>
            </div>
        </section>
    </main>
    <div id="progress">
        <span id="progress-value"><i class="fas fa-arrow-up"></i></span>
    </div>
    <?php include("header-footer/footer.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        xintegrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="/app.js"></script>
</body>

</html>