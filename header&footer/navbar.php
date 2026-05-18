<?php
// File: header&footer/navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

 require_once(dirname(__DIR__) . '/config.php');

$year_suffix = isset($page_event_year) && $page_event_year !== null ? (string)$page_event_year : '2026';
$congress_type = $_SESSION['congress_type'] ?? ($_GET['congress_type'] ?? ($congress_type ?? 'PTC'));
if (isset($congress_type) && $congress_type !== null) {
    $congress_type = (string)$congress_type;
}

$user_firstname = null;
if (isset($_SESSION['participant_id'])) {
    require_once(dirname(__DIR__) . '/dbcon.php');
    $stmt = $dbcon->prepare("SELECT firstName FROM participant WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['participant_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $user_firstname = $user['firstName'];
    }
    $stmt->close();
}
?>
<nav class="px-3 navbar fixed-top navbar-expand-lg navbar-light shadow main-navbar">
    <div class="container">
        <?php if ((string) $year_suffix === '2026'): ?>
            <div class="nav-brand">
                <?php //var_dump($congress_type); // type checker for debugging?>
                <a class="navbar-brand" href="<?php echo BASE_PATH; ?>/gtc2026.php">
                    <img src="<?php echo BASE_PATH; ?>/GDC/assets/img/GTC_LOGO_2026.png" id="gtc-logo" alt="GTC Logo">
                </a>
                <div class="vr" style="width: 2px; height: 70px;"></div>
                <a class="navbar-brand" href="<?php echo BASE_PATH; ?>/index2026.php">
                    <img src="<?php echo BASE_PATH; ?>/img_outside/navlogo.png" id="logog" alt="PTC Logo">
                </a>
            </div>
        <?php else: ?>
            <a class="navbar-brand" href="<?php echo BASE_PATH; ?>/index2026.php">
                <img src="<?php echo BASE_PATH; ?>/img_outside/navlogo.png" id="logog" alt="PTC Logo">
            </a>
        <?php endif; ?>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link"
                        href="<?php echo BASE_PATH; ?>/<?php echo $congress_type === 'GTC' ? 'gtc2026.php' : 'index' . $year_suffix . '.php'; ?>">HOME</a></li>

                <?php if ((string) $year_suffix !== '2026'): ?>
                    <li class="nav-item"><a class="nav-link"
                            href="<?php echo BASE_PATH; ?>/programme<?php echo $year_suffix; ?>.php">PROGRAMME</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="<?php echo BASE_PATH; ?>/speakersPage<?php echo $year_suffix; ?>.php">SPEAKERS</a></li>
                <?php endif; ?>
                <?php if ($congress_type !== 'GTC'): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button">PAST EVENTS</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/index2025.php">2025 PTC</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/index2024.php">2024 PTC</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/index2023.php">2023 PTC</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/index2022.php">2022 PTC</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link"
                    href="<?php echo BASE_PATH; ?>/contacts/contact.php?year=<?php echo urlencode($year_suffix); ?>&congress_type=<?php echo $congress_type; ?>">CONTACTS</a>
                </li>
                <?php if ((string) $year_suffix !== '2026'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_PATH; ?>/analytics.php">STATISTICS</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <?php if (isset($_SESSION['participant_id']) && $user_firstname): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                            role="button">
                            <div class="profile-pic me-2"><?php echo strtoupper(substr($user_firstname, 0, 1)); ?></div>
                            Hi, <?php echo htmlspecialchars($user_firstname); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/User_Dashboard/dashboard.php">My
                                    Dashboard</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php elseif (isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_PATH; ?>/admin/admindashboard.php">Admin
                            Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_PATH; ?>/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item">
                        <button onclick="window.location.href='<?php echo BASE_PATH; ?>/Loginn.php?congress_type=<?php echo urlencode($congress_type); ?>'" type="button"
                            class="btn-nav-action btn-nav-action--primary">
                            <span class="buttontext">LOGIN</span>
                            <span class="buttonicon"><i class="fa-solid fa-user-plus"></i></span>
                        </button>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <button onclick="window.location.href='<?php echo BASE_PATH; ?>/registration.php?congress_type=<?php echo urlencode($congress_type); ?>'" type="button"
                            class="btn-nav-action btn-nav-action--danger">
                            <span class="buttontext">REGISTER</span>
                            <span class="buttonicon"><i class="fa-solid fa-qrcode fa-lg"></i></span>
                        </button>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>