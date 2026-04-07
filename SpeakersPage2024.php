<?php
// This tells the navbar what year this page is for
$page_event_year = 2024; 

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
    <title>PTC 2024 Speakers</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="./header&footer/Navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="./header&footer/footers.css">
    <link rel="stylesheet" type="text/css" href="./speakersPage.css">
</head>

<body>
    <?php include("header&footer/navbar.php"); ?>

    <main>
        <div class="container-fluid speakers-hero">
            <div class="wrapper">
                <div class="heading">
                    <span data-heading="2">2</span><span data-heading="0">0</span><span data-heading="2">2</span><span
                        data-heading="4">4</span>
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
                        // NOTE: Based on your previous code, I have assumed the column names for the speaker's
                        // name and title are 'speaker_name' and 'speaker_title'. 
                        // If these are incorrect, please change them here in the SELECT statement.
                        $year_to_fetch = '2024';
                        $stmt = $dbcon->prepare("SELECT speaker_Name, speaker_Info, speaker_Img FROM tblspeakerses WHERE year = ? AND speaker_Img NOT IN ('placeholder.jpg','placeholder1.jpg') ORDER BY session_id, speaker_id");
                        $stmt->bind_param("s", $year_to_fetch);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while($speaker = $result->fetch_assoc()){
                                $image_path = './User_Dashboard/2024/' . $speaker['speaker_Img'];
                                // We keep the file_exists check as you requested in your original logic
                                if (file_exists($image_path)) {
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="speaker-card">
                            <div class="speaker-card__image-container">
                                <img src="<?php echo htmlspecialchars($image_path); ?>" class="speaker-card__image"
                                    alt="<?php echo htmlspecialchars($speaker['speaker_name']); ?>">
                            </div>

                        </div>
                    </div>
                    <?php
                                } // end file_exists
                            } // end while loop
                        } else {
                            echo '<div class="col-12"><p class="text-center text-muted">Speakers for the 2024 event are not currently listed.</p></div>';
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

    <?php include("header&footer/footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        xintegrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="/app.js"></script>
</body>

</html>