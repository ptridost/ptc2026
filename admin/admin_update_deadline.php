<?php
// Make sure you have your admin session check and database connection
session_start();
// Example: if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit(); }
require('../dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_number'])) {
    
    $session_number = $_POST['session_number'];
    
    // If the "Clear Override" box was checked, we set the deadline to NULL
    if (isset($_POST['clear_deadline'])) {
        $stmt = $dbcon->prepare("UPDATE tblptcsession SET feedback_override_deadline = NULL WHERE session_number = ?");
        $stmt->bind_param("i", $session_number);
        
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Override deadline cleared. Session now uses the default rule.'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error clearing deadline.'];
        }
        $stmt->close();
    } else {
        // Otherwise, we set the new deadline from the form input
        $new_deadline = $_POST['override_deadline'];
        
        if (empty($new_deadline)) {
            $_SESSION['flash_message'] = ['type' => 'warning', 'text' => 'No deadline was provided. No changes were made.'];
        } else {
            $stmt = $dbcon->prepare("UPDATE tblptcsession SET feedback_override_deadline = ? WHERE session_number = ?");
            $stmt->bind_param("si", $new_deadline, $session_number);
            
            if ($stmt->execute()) {
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Feedback override deadline has been updated!'];
            } else {
                $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error updating deadline.'];
            }
            $stmt->close();
        }
    }
} else {
    $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Invalid request.'];
}

// IMPORTANT: Redirect the admin back to your main sessions page
// Make sure this file path is correct for your project structure
header("Location: admindashboard.php"); // Or whatever your main admin page is called
exit();
?>