<?php
$page_event_year = 2023; 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require('./dbcon.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />

    <title>PTC 2023 Speakers</title>

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
                <div class="heading"><span data-heading="2">2</span><span data-heading="0">0</span><span
                        data-heading="2">2</span><span data-heading="3">3</span></div>
            </div>
            <h1 class="speakers-hero__title">Featured Expert Speakers</h1>
        </div>
        <section class="speaker-grid">
            <div class="container-lg">
                <div class="speakers-intro">
                    <h1>Say hello to our speakers</h1>
                    <h2>We couldS n't be more excited about our lineup.</h2>
                </div>
                <div class="row g-4 justify-content-center">
                    <?php
                        // Using your original logic for 2023
                        $sql = $dbcon->query("SELECT * FROM speakers WHERE visibility = 'show'");
                        while($speaker = $sql->fetch_assoc()){
                            // Using the correct image path
                            $image_path = 'Speakers/' . htmlspecialchars($speaker['name']);
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="speaker-card">
                            <div class="speaker-card__image-container">
                                <img src="<?php echo $image_path; ?>" class="speaker-card__image"
                                    alt="<?php echo htmlspecialchars($speaker['name']); ?>">
                            </div>

                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>
    <div id="progress"><span id="progress-value"><i class="fas fa-arrow-up"></i></span></div>
    <?php include("header-footer/footer.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="/app.js"></script>
</body>

</html>