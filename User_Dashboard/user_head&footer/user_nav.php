<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../dbcon.php'; // Corrected path example

// Pages that include this navbar do not all define a local congress type.
$congress_type = $congress_type ?? ($_SESSION['congress_type'] ?? 'PTC');

// Use the single, consistent database connection
// Make sure the path to dbcon.php is correct from where this file is included.
// require_once __DIR__ . '/dbcon.php'; // Example of a more robust include

// --- Fetch Notification Data ---
$find_notifications = "SELECT n_id, notifications_name, message FROM inf WHERE active = 1";
$result_notifications = $dbcon->query($find_notifications);
$active_notifications = [];
if ($result_notifications) {
    while($row = $result_notifications->fetch_assoc()){
        $active_notifications[] = $row;
    }
}
$count_active = count($active_notifications);

// --- Fetch User Data ---
$user_name = '';
// Check if the participant_id is set in the session to prevent errors
if (isset($_SESSION['participant_id'])) {
    // Use Prepared Statements to prevent SQL injection
    $stmt = $dbcon->prepare("SELECT firstName, middleName, lastName FROM participant WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['participant_id']);
    $stmt->execute();
    $result_user = $stmt->get_result();
    if ($user_row = $result_user->fetch_assoc()) {
        $user_name = htmlspecialchars(ucfirst($user_row["firstName"])) . ' ' . htmlspecialchars(ucfirst(substr($user_row["middleName"], 0, 1))) . '. ' . htmlspecialchars(ucfirst($user_row["lastName"]));
    }
    $stmt->close();
}

/*
IMPORTANT NOTE on the UPDATE query from your original file:
The logic to update notifications (SET active = 0) should not be on this navigation bar file.
This kind of database update should be handled by a separate PHP script that is called
when a user performs a specific action (like clicking a "Mark as Read" button),
usually via a form submission or an AJAX call. Running an UPDATE on every page load is incorrect.
*/
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light user-nav">
    <div class="container-fluid">
        <a href="<?php echo BASE_PATH; ?>/index2026.php">
            <?php if ((string)$congress_type === 'GTC') : ?>
            <img src="<?php echo BASE_PATH; ?>/GDC/assets/img/GTC_LOGO_2026.png" alt="GTC Logo" class="user-nav__logo" style = "max-height:70px;">
            <?php else: ?>
            <img src="<?php echo BASE_PATH; ?>/nav&foot_logo/navlogo.png" alt="PTC Logo" class="user-nav__logo">
            <?php endif; ?>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserContent"
            aria-controls="navbarUserContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarUserContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">

                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="notification-bell">
                            <i class="fas fa-bell"></i>
                            <?php if ($count_active > 0): ?>
                            <span class="notification-badge"><?php echo $count_active; ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notification-box"
                        aria-labelledby="notificationDropdown">
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-nav__profile" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="user-nav__avatar" src="<?php echo BASE_PATH; ?>/user_profile/user.png"
                            alt="User Avatar">
                        <span class="user-nav__name"><?php echo $user_name; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/User_Dashboard/dashboard.php"><i
                                    class="fa fa-clipboard"></i> Dashboard</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/logout.php" name="logout"><i
                                    class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
