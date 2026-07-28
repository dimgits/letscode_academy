<?php
// Expects $active to be set by the including page to one of:
// 'dashboard' | 'students' | 'settings'
$active = $active ?? '';
?>
<button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu">☰</button>

<div class="sidebar" id="sidebar">

<div class="logo">
LetsCode
</div>

<a href="dashboard.php" class="<?= $active === 'dashboard' ? 'active-link' : '' ?>">Dashboard</a>

<a href="students.php" class="<?= $active === 'students' ? 'active-link' : '' ?>">Students</a>

<a href="settings.php" class="<?= $active === 'settings' ? 'active-link' : '' ?>">Settings</a>

<a href="logout.php">Logout</a>

</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
