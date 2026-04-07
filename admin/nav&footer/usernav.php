<?php
// Add this PHP block to the very top of the file
$base_url = ''; // This should be the root folder of your project on the server
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $base_url; ?>/index2025.php">
            <img src="<?php echo $base_url; ?>../nav&foot_logo/navlogo.png" alt="PTC Logo" height="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            </ul>
            <div class="d-flex navbutton align-items-center">
                <button class="btn btn-outline-success me-2" id="uploadbtn"
                    onclick="location.href='<?php echo $base_url; ?>/admin/uploadcert.php';">
                    <i class="fa-solid fa-file-arrow-up me-2"></i>Upload Certificate
                </button>
                <button class="btn btn-outline-success me-2" id="participantbtn"
                    onclick="location.href='<?php echo $base_url; ?>/admin/participants.php';">
                    <i class="fa-solid fa-users me-2"></i>Participants
                </button>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle ddownadmin" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="me-1" src="<?php echo $base_url; ?>/admin/dada.jpg">Admin<br><span
                            class="useradmin">PTC-ADMIN</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="<?php echo $base_url; ?>/logout.php">Logout</a></li>
                        <li><a class="dropdown-item"
                                href="<?php echo $base_url; ?>/admin/editVariables.php">Settings</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>