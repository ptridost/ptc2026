<?php


// Session must be the very first thing
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user is already logged in
if (!empty($_SESSION['participant_id'])) {
    header("Location: User_Dashboard/dashboard.php");
    exit();
}
if (!empty($_SESSION['admin_id'])) {
    header("Location: admin/admindashboard.php");
    exit();
}

require_once 'csrf.php';
$csrf_token = generate_csrf_token();

// --- Flash Message System for Errors ---
$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Clear the error so it doesn't show again
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />

    <title>Login | Philippine Textile Congress</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="./login.css" />
    <link rel="stylesheet" type="text/css" href="./header&footer/Navbarstyle.css" />
    <link rel="stylesheet" type="text/css" href="./header&footer/footers.css" />
</head>

<body>
    <?php include("header&footer/navbar.php"); ?>

    <main class="container login-container">
        <div class="login-card">
            <div class="row g-0">
                <div class="col-lg-7 justify-content-center d-flex">
                    <img src="./img_outside/logo.png" class="login-card__image" alt="Philippine Textile Congress Logo">
                </div>
                <div class="col-lg-5">
                    <div class="login-card__form">
                        <h1 class="text-center">Login Form</h1>

                        <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                        <?php endif; ?>

                        <form action="process.php" method="POST">
                            <div class="inputBox mt-4">
                                <input type="text" required="required" id="email_address" name="username">
                                <span>Username / Email</span>
                            </div>
                            <div class="inputBox">
                                <input type="password" required="required" id="password" name="password">
                                <span>Password</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center my-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check" name="remember_me">
                                    <label class="form-check-label" for="check">Remember me</label>
                                </div>
                                <a href='./resetpassword/forgot-password.php'>Forgot Password?</a>
                            </div>

                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                            <div class="g-recaptcha mb-3 d-flex justify-content-center"
                                data-sitekey="6LcNTkwrAAAAABX9bm94Z0wq7noMopeZuQnQsEsR"></div>

                            <div class="text-center">
                                <button class="btn-login" type="submit" name="submit" id="submit">Log In</button>
                                <p class="mt-3">Don't have an account? <a href="./registration.php">Register here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include("header&footer/footer.php"); ?>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        xintegrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="/PTC/app.js"></script>
</body>

</html>