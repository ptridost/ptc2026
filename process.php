<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'dbcon.php';
require_once 'csrf.php';

if (isset($_POST['submit'])) {

    // 1. Validate CSRF Token
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        $_SESSION['login_error'] = 'Invalid form submission. Please try again.';
        header('Location: Loginn.php');
        exit;
    }

    // 2. Validate reCAPTCHA (optional, can be removed if not needed)
    $recaptchaSecret = '6LdFIPAtAAAAAHmLgsAHN21GfY6H0PSoaaXIZzoU'; // Your secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        $_SESSION['login_error'] = 'CAPTCHA verification failed. Please try again.';
        header('Location: Loginn.php');
        exit;
    }
    
    // 3. Get username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ===================================================================
    // FINAL, UNIFIED LOGIN LOGIC
    // ===================================================================

    // A single, secure query to find a user by username
    $stmt = $dbcon->prepare("SELECT id, password, type, congress_type FROM participant WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $stored_hash = $user['password'];
        
        // Securely verify the password (handles both new and old md5 passwords)
        if (password_verify($password, $stored_hash) || md5($password) === $stored_hash) {
            
            // If it was an old md5 password, upgrade it now
            if (md5($password) === $stored_hash) {
                $new_secure_hash = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $dbcon->prepare("UPDATE participant SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_secure_hash, $user['id']);
                $update_stmt->execute();
                $update_stmt->close();
            }
            
            // Regenerate session ID for security
            session_regenerate_id(true);

            // --- ROLE-BASED REDIRECTION ---
            if ($user['type'] === 'Admin') {
                $_SESSION['admin_id'] = $user['id'];
                header("Location: admin/admindashboard.php");
                exit();
            } 
            elseif ($user['type'] === 'Participant') {
                $_SESSION['participant_id'] = $user['id'];
                $_SESSION['congress_type'] = $user['congress_type'];
                header("Location: User_Dashboard/dashboard.php");
                exit();
            }
            else {
                // Unknown user type, handle as an error
                $_SESSION['login_error'] = 'User account has an invalid role.';
                header("Location: Loginn.php");
                exit();
            }
        }
    }

    // If we reach this point, either the user was not found or the password was incorrect.
    $_SESSION['login_error'] = 'Incorrect username or password. Please try again.';
    header("Location: Loginn.php");
    exit();

} else {
    // If the form wasn't submitted, redirect away.
    header("Location: Loginn.php");
    exit();
}
?>