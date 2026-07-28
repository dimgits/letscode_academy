<?php
/**
 * Expects $activePage (one of: home, lessons, settings) and $student
 * (associative array from tb_registrations) to be set by the including page.
 */
$activePage = $activePage ?? '';
?>
<nav class="glass-nav">
    <div class="nav-inner">
        <a class="logo" href="portal_dashboard.php">
            <i class="bi bi-code-slash"></i>
            LetsCode!
        </a>

        <div class="portal-nav-links">
            <a href="portal_dashboard.php" class="portal-nav-link <?= $activePage === 'home' ? 'active' : '' ?>">
                <i class="bi bi-house-door-fill"></i>
                <span>Home</span>
            </a>
            <a href="lessons.php" class="portal-nav-link <?= $activePage === 'lessons' ? 'active' : '' ?>">
                <i class="bi bi-collection-play-fill"></i>
                <span>Lessons</span>
            </a>
            <a href="settings.php" class="portal-nav-link <?= $activePage === 'settings' ? 'active' : '' ?>">
                <i class="bi bi-gear-fill"></i>
                <span>Settings</span>
            </a>
        </div>

        <div class="nav-actions">
            <a href="portal_dashboard.php" class="portal-avatar-link" title="Dashboard">
                <img src="<?= avatar_url($student) ?>" alt="Profile" class="nav-avatar">
            </a>
            <a href="portal_logout.php" class="btn btn-outline btn-sm-icon">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</nav>

<div class="portal-mobile-tabs">
    <a href="portal_dashboard.php" class="portal-mobile-tab <?= $activePage === 'home' ? 'active' : '' ?>">
        <i class="bi bi-house-door-fill"></i>
        <span>Home</span>
    </a>
    <a href="lessons.php" class="portal-mobile-tab <?= $activePage === 'lessons' ? 'active' : '' ?>">
        <i class="bi bi-collection-play-fill"></i>
        <span>Lessons</span>
    </a>
    <a href="settings.php" class="portal-mobile-tab <?= $activePage === 'settings' ? 'active' : '' ?>">
        <i class="bi bi-gear-fill"></i>
        <span>Settings</span>
    </a>
    <a href="portal_logout.php" class="portal-mobile-tab">
        <i class="bi bi-box-arrow-right"></i>
        <span>Logout</span>
    </a>
</div>
