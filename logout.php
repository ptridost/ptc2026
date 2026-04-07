<?php
// File: logout.php
// This single file securely logs out any user (Participant or Admin).

// 1. Start the session so we can access it.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Unset all of the session variables.
$_SESSION = array();

// 3. Destroy the session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finally, destroy the session itself.
session_destroy();

// 5. Use a standard PHP redirect to send the user to the login page.
header("Location: Loginn.php");
exit();
?>