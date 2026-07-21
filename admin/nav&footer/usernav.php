<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../dbcon.php';
require_once __DIR__ . '/../../event_year.php';

$base_url = BASE_PATH;
$current_ptc_year = getCurrentEventYear($dbcon);
$home_page = "index{$current_ptc_year}.php";
if (!file_exists(__DIR__ . "/../../{$home_page}")) {
    $home_page = 'index2026.php';
}

$current_admin_page = basename($_SERVER['PHP_SELF']);
function adminNavActive($pages, $currentPage) {
    return in_array($currentPage, (array) $pages, true) ? ' active' : '';
}
?>
<button class="admin-sidebar-toggle" type="button" id="adminSidebarToggle" aria-controls="adminSidebar"
    aria-expanded="false" aria-label="Open admin navigation">
    <i class="fa-solid fa-bars"></i>
</button>
<div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>

<aside class="admin-sidebar" id="adminSidebar" aria-label="Admin navigation">
    <div class="admin-sidebar__brand">
        <a href="<?php echo $base_url; ?>/admin/admindashboard.php" aria-label="PTC Admin Dashboard">
            <img src="<?php echo $base_url; ?>/nav&foot_logo/navlogo.png" alt="PTC Logo">
        </a>
        <span>Administration</span>
        <small>PTC <?php echo htmlspecialchars((string) $current_ptc_year); ?></small>
    </div>

    <nav class="admin-sidebar__nav">
        <p class="admin-sidebar__label">Overview</p>
        <a class="admin-sidebar__link<?php echo adminNavActive('admindashboard.php', $current_admin_page); ?>"
            href="<?php echo $base_url; ?>/admin/admindashboard.php">
            <i class="fa-solid fa-table-columns"></i><span>Dashboard</span>
        </a>

        <p class="admin-sidebar__label">Event Management</p>
        <a class="admin-sidebar__link<?php echo adminNavActive('sessions.php', $current_admin_page); ?>"
            href="<?php echo $base_url; ?>/admin/sessions.php">
            <i class="fa-regular fa-calendar-days"></i><span>Sessions</span>
        </a>
        <a class="admin-sidebar__link<?php echo adminNavActive(['participants.php', 'participant_profile.php'], $current_admin_page); ?>"
            href="<?php echo $base_url; ?>/admin/participants.php">
            <i class="fa-solid fa-users"></i><span>Participants</span>
        </a>
        <a class="admin-sidebar__link<?php echo adminNavActive('monitoring.php', $current_admin_page); ?>"
            href="<?php echo $base_url; ?>/admin/monitoring.php">
            <i class="fa-solid fa-chart-line"></i><span>Monitoring</span>
        </a>
        <a class="admin-sidebar__link<?php echo adminNavActive('speaker_rating.php', $current_admin_page); ?>"
            href="<?php echo $base_url; ?>/admin/speaker_rating.php">
            <i class="fa-solid fa-star"></i><span>Speaker Ratings</span>
        </a>

        <p class="admin-sidebar__label">Records &amp; Reports</p>
        <a class="admin-sidebar__link<?php echo adminNavActive('uploadcert.php', $current_admin_page); ?>"
            href="<?php echo $base_url; ?>/admin/uploadcert.php">
            <i class="fa-solid fa-file-arrow-up"></i><span>Upload Certificate</span>
        </a>
        <a class="admin-sidebar__link<?php echo adminNavActive('viewpartcert.php', $current_admin_page); ?>"
            href="<?php echo $base_url; ?>/admin/viewpartcert.php">
            <i class="fa-solid fa-certificate"></i><span>Certificates</span>
        </a>
        <a class="admin-sidebar__link<?php echo adminNavActive('overall.php', $current_admin_page); ?>"
            href="<?php echo $base_url; ?>/admin/overall.php">
            <i class="fa-solid fa-file-lines"></i><span>Summary Report</span>
        </a>

        <p class="admin-sidebar__label">System</p>
        <a class="admin-sidebar__link<?php echo adminNavActive('editVariables.php', $current_admin_page); ?>"
            href="<?php echo $base_url; ?>/admin/editVariables.php">
            <i class="fa-solid fa-gear"></i><span>Settings</span>
        </a>
        <a class="admin-sidebar__link" href="<?php echo $base_url; ?>/<?php echo $home_page; ?>">
            <i class="fa-solid fa-arrow-up-right-from-square"></i><span>View Public Site</span>
        </a>
    </nav>

    <div class="admin-sidebar__account">
        <img src="<?php echo $base_url; ?>/admin/dada.jpg" alt="Admin profile">
        <div><strong>Administrator</strong><span>PTC Admin</span></div>
        <a href="<?php echo $base_url; ?>/logout.php" title="Log out" aria-label="Log out">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>
    </div>
</aside>

<script>
function initializeAdminSidebar() {
    if (!document.body || document.body.classList.contains('admin-shell-active')) return;
    document.body.classList.add('admin-shell-active');
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.getElementById('adminSidebarToggle');
    const backdrop = document.getElementById('adminSidebarBackdrop');
    if (!sidebar || !toggle || !backdrop) return;

    function setSidebar(open) {
        sidebar.classList.toggle('is-open', open);
        backdrop.classList.toggle('is-visible', open);
        document.body.classList.toggle('admin-sidebar-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close admin navigation' : 'Open admin navigation');
        toggle.innerHTML = open ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
    }

    toggle.addEventListener('click', function () { setSidebar(!sidebar.classList.contains('is-open')); });
    backdrop.addEventListener('click', function () { setSidebar(false); });
    window.addEventListener('resize', function () { if (window.innerWidth >= 992) setSidebar(false); });
}

if (document.body) {
    initializeAdminSidebar();
} else {
    document.addEventListener('DOMContentLoaded', initializeAdminSidebar, { once: true });
}
</script>
