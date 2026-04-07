<?php
$page_event_year = 2023; 
// This MUST be the very first line of the file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include your database connection and calendar functions
require('./dbcon.php'); 
include_once 'functions.php'; 
?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />
    <title>Programme | Philippine Textile Congress</title>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="./header&footer/Navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="./header&footer/footers.css">
    <link rel="stylesheet" href="sessions.css">
</head>

<body>
    <?php include("header&footer/navbar.php"); ?>

    <main class="container" style="margin-top: 120px;">
        <div class="row">
            <div class="col-12 text-center">
                <h1 id="progtxt">SCHEDULE OF ACTIVITIES</h1>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-5">
                <h2 class="cartext">Pick a date.</h2>
                <div id="calendar_div" class="mt-3">
                    <?php echo getCalender(); ?>
                </div>
            </div>

            <div class="col-md-7" id="concar">
                <h2 class="cartext">Here's the session for the day you chose.</h2>
                <div id="carouselExampleDark" class="carousel carousel-dark slide mt-3" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                    </div>
                    <div class="carousel-inner" id="event_list">
                        <div class="text-center p-5">
                            <p class="h4 text-muted">Please select a date from the calendar to view the schedule.</p>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <?php include("header&footer/footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        xintegrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="/app.js"></script>

    <script>
    // This function will be called by the calendar in functions.php
    function getEvents(date) {
        // Use AJAX to fetch events for the selected date
        $.ajax({
            type: 'POST',
            url: 'functions.php', // The same file that generates the calendar
            data: 'func=getEvents&date=' + date,
            success: function(response) {
                // The response will be the HTML for the carousel items
                let data = JSON.parse(response);
                $('#event_list').html(data.event_list);
                $('.carousel-indicators').html(data.indicators);
                // Make sure the first carousel item is marked as 'active'
                $('#event_list').find('.carousel-item').first().addClass('active');
            }
        });
    }
    </script>
</body>

</html>