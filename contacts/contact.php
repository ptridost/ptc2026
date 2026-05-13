<?php
// Keep navbar context consistent with the year being viewed.

$page_event_year = isset($_GET['year']) ? (int)$_GET['year'] : null;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Use a centralized database connection file
require('../dbcon.php');


$showAlert = false;

// --- FORM PROCESSING LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Sanitize and retrieve form data
    $firstname     = trim($_POST['firstname']);
    $lastname      = trim($_POST['lastname']);
    $email         = trim($_POST['email']);
    $contactnumber = trim($_POST['contactnumber']);
    $message       = trim($_POST['message']);

    // SECURE DATABASE INSERT USING PREPARED STATEMENTS
    $stmt = $dbcon->prepare("INSERT INTO `contact` (`FirstName`, `LastName`, `Email`, `ContactNumber`, `Message`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstname, $lastname, $email, $contactnumber, $message);

    if ($stmt->execute()) {
        // Post/Redirect/Get Pattern to prevent form resubmission
        header("Location: contact.php?status=success");
        exit();
    } else {
        // Optional: Handle error case
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Check if the page was loaded with a success status to show the alert
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $showAlert = true;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="../ptc.png" />

    <title>Contact Us | Philippine Textile Congress</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="contact.css">
    <link rel="stylesheet" type="text/css" href="../header&footer/Navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../header&footer/footers.css">
</head>

<body>

    <?php include("../header&footer/navbar.php"); ?>

    <main class="container contact-page">
        <div class="row mt-5">
            <div class="col-12 order-1 contact-header">
                <h1 class="text-center">Contact Us</h1>
                <div class="d-flex flex-md-row flex-column justify-content-center gap-2">
                    <p class="sub-header text-center h4 d-none d-md-block">Any inquiries?</p>
                    <p class="sub-header text-center h4">Write us a message!</p>
                </div>
            </div>

            <div class="col-12 col-lg-5 order-3 order-lg-2 mb-3">
                <div class="card contact-info-card">
                    <div class="card-header">
                        <p class="h5 text-center mt-3">Contact Information</p>
                    </div>
                    <div class="card-body">
                        <div class="contact-item">
                            <img class="icon" src="telephone.png" alt="Telephone icon">
                            <p>(02) 8837-1325</p>
                        </div>
                        <div class="contact-item">
                            <img class="icon" src="gmail.png" alt="Email icon">
                            <p>ptri@ptri.dost.gov.ph</p>
                        </div>
                        <div class="contact-item">
                            <img class="icon" src="facebook.png" alt="Facebook icon">
                            <a href="https://www.facebook.com/philippinetextilecongress/"
                                target="_blank">philippinetextilecongress</a>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p class="h5 text-center mt-3">HOW TO FIND US</p>
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.9481401134185!2d121.04504817590296!3d14.487666579830027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397cf1162342b39%3A0x1b73947e2ee43d00!2sPhilippine%20Textile%20Research%20Institute!5e0!3m2!1sen!2sph!4v1755841289446!5m2!1sen!2sph"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7 order-2 order-lg-3 mb-3">
                <div class="card shadow-lg contact-form-card">
                    <div class="card-body">
                        <h2 class="text-center mt-3 mb-4">Contact Form</h2>
                        <form action="contact.php" method="POST">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 text-muted ms-1 mb-3"><small>All fields marked with <span
                                                class="text-danger">*</span> are required.</small></div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-floating mb-3"><input type="text" class="form-control"
                                                name="firstname" id="firstname" placeholder="First Name" required><label
                                                for="firstname">First Name<span
                                                    class="text-danger ms-1">*</span></label></div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-floating mb-3"><input type="text" class="form-control"
                                                name="lastname" id="lastname" placeholder="Last Name" required><label
                                                for="lastname">Last Name<span class="text-danger ms-1">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3"><input type="email" class="form-control"
                                                name="email" id="email" placeholder="Email" required><label
                                                for="email">Email <span class="text-danger ms-1">*</span></label></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3"><input type="tel" class="form-control"
                                                name="contactnumber" id="contactnumber"
                                                placeholder="Contact Number"><label for="contactnumber">Contact
                                                Number</label></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating"><textarea placeholder="Write your message here..."
                                                class="form-control" name="message" id="message" required
                                                style="height: 100px; resize:none;"></textarea><label
                                                for="message">Message<span class="text-danger ms-1">*</span></label>
                                        </div>
                                    </div>
                                    <div class="text-center col-lg-12 mt-4"><button class="btn btn-primary w-100 py-3"
                                            type="submit" name="submit">Send Message</button></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include("../header&footer/footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        xintegrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../PTC/app.js"></script>

    <script>
    // SweetAlert for success message
    <?php
        if ($showAlert) {
            echo "
                Swal.fire(
                    'Success!',
                    'Your message has been sent successfully!',
                    'success'
                )
            ";
        }
        ?>

    // Manual triggers for navbar dropdowns
    $(document).ready(function() {
        $('#userDropdown, #navbarDropdown').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(this);
            dropdownInstance.toggle();
        });
    });
    </script>
</body>

</html>