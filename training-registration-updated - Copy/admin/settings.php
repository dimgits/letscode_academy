<?php
include "auth.php";
include "../signup_page/db.php";
include "includes/functions.php";

run_admin_migrations($conn);

$adminId = (int) $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $formType = $_POST['form_type'] ?? '';

    // ---------- Change username ----------
    if ($formType === 'username') {
        $newUsername = trim($_POST['new_username'] ?? '');

        if ($newUsername === '') {
            flash_set('error', 'Username cannot be empty.');
        } else {
            $check = $conn->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
            $check->bind_param("si", $newUsername, $adminId);
            $check->execute();

            if ($check->get_result()->num_rows > 0) {
                flash_set('error', 'That username is already taken.');
            } else {
                $stmt = $conn->prepare("UPDATE admins SET username = ? WHERE id = ?");
                $stmt->bind_param("si", $newUsername, $adminId);
                $stmt->execute();

                $_SESSION['admin_username'] = $newUsername;
                flash_set('success', 'Username updated successfully.');
            }
        }

        header("Location: settings.php");
        exit();
    }

    // ---------- Change password ----------
    if ($formType === 'password') {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row || !password_verify($current, $row['password'])) {
            flash_set('error', 'Current password is incorrect.');
        } elseif (strlen($new) < 6) {
            flash_set('error', 'New password must be at least 6 characters.');
        } elseif ($new !== $confirm) {
            flash_set('error', 'New password and confirmation do not match.');
        } else {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $update->bind_param("si", $hashed, $adminId);
            $update->execute();

            flash_set('success', 'Password changed successfully.');
        }

        header("Location: settings.php");
        exit();
    }

    // ---------- Dashboard preferences ----------
    if ($formType === 'preferences') {
        $rowsPerPage = (int) ($_POST['rows_per_page'] ?? 10);
        $accent = $_POST['theme_accent'] ?? '#7c3aed';

        $allowedRows = [5, 10, 20, 50];
        if (!in_array($rowsPerPage, $allowedRows, true)) {
            $rowsPerPage = 10;
        }

        $allowedAccents = ['#7c3aed', '#2563eb', '#059669', '#ea580c', '#dc2626', '#db2777'];
        if (!in_array($accent, $allowedAccents, true)) {
            $accent = '#7c3aed';
        }

        $stmt = $conn->prepare("UPDATE admins SET rows_per_page = ?, theme_accent = ? WHERE id = ?");
        $stmt->bind_param("isi", $rowsPerPage, $accent, $adminId);
        $stmt->execute();

        flash_set('success', 'Dashboard preferences saved.');

        header("Location: settings.php");
        exit();
    }
}

$admin = get_current_admin($conn);

$accentOptions = [
    '#7c3aed' => 'Purple',
    '#2563eb' => 'Blue',
    '#059669' => 'Green',
    '#ea580c' => 'Orange',
    '#dc2626' => 'Red',
    '#db2777' => 'Pink',
];
?>

<!DOCTYPE html>
<html>

<head>

<title>Settings - LetsCode Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/css/dashboard.css?v=<?= filemtime(__DIR__ . '/assets/css/dashboard.css') ?>">
<link rel="stylesheet" href="assets/css/admin-ui.css?v=<?= filemtime(__DIR__ . '/assets/css/admin-ui.css') ?>">

<style>
    .settings-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));
        gap:20px;
    }

    .settings-card h3{
        color:var(--accent);
        margin-bottom:6px;
    }

    .settings-card p.hint{
        font-size:13px;
        opacity:.65;
        margin-bottom:18px;
    }

    .settings-card label{
        display:block;
        font-size:13px;
        opacity:.8;
        margin-bottom:6px;
        margin-top:14px;
    }

    .settings-card input[type="text"],
    .settings-card input[type="password"],
    .settings-card select{
        width:100%;
        padding:12px;
        border-radius:8px;
        border:none;
        outline:none;
        font-family:inherit;
    }

    .settings-card button{
        margin-top:20px;
        background:var(--accent);
        color:white;
        border:none;
        padding:12px 22px;
        border-radius:10px;
        cursor:pointer;
        font-family:inherit;
        transition:.2s;
    }

    .settings-card button:hover{
        filter:brightness(.88);
    }

    .accent-options{
        display:flex;
        gap:10px;
        margin-top:8px;
        flex-wrap:wrap;
    }

    .accent-swatch{
        position:relative;
    }

    .accent-swatch input{
        position:absolute;
        opacity:0;
        inset:0;
        cursor:pointer;
    }

    .accent-swatch span{
        display:block;
        width:34px;
        height:34px;
        border-radius:50%;
        border:2px solid rgba(255,255,255,.2);
    }

    .accent-swatch input:checked + span{
        border-color:white;
        box-shadow:0 0 0 2px var(--accent);
    }
</style>

</head>

<body style="--accent: <?= htmlspecialchars($admin['theme_accent']) ?>;">

<?php $active = 'settings'; include "includes/sidebar.php"; ?>

<div class="main">

<h1>Settings</h1>

<div class="settings-grid" style="margin-top:25px;">

    <div class="card settings-card">
        <h3>Username</h3>
        <p class="hint">Change the username used to log into this admin panel.</p>

        <form method="POST">
            <input type="hidden" name="form_type" value="username">

            <label for="new_username">New username</label>
            <input type="text" id="new_username" name="new_username" value="<?= htmlspecialchars($admin['username']) ?>" required>

            <button type="submit">Save Username</button>
        </form>
    </div>

    <div class="card settings-card">
        <h3>Password</h3>
        <p class="hint">Update your password. You'll need your current password to confirm.</p>

        <form method="POST">
            <input type="hidden" name="form_type" value="password">

            <label for="current_password">Current password</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New password</label>
            <input type="password" id="new_password" name="new_password" minlength="6" required>

            <label for="confirm_password">Confirm new password</label>
            <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>

            <button type="submit">Change Password</button>
        </form>
    </div>

    <div class="card settings-card">
        <h3>Dashboard Preferences</h3>
        <p class="hint">Personalize how your dashboard looks and behaves.</p>

        <form method="POST">
            <input type="hidden" name="form_type" value="preferences">

            <label for="rows_per_page">Students per page</label>
            <select id="rows_per_page" name="rows_per_page">
                <?php foreach ([5, 10, 20, 50] as $n): ?>
                    <option value="<?= $n ?>" <?= $admin['rows_per_page'] == $n ? 'selected' : '' ?>><?= $n ?></option>
                <?php endforeach; ?>
            </select>

            <label>Accent color</label>
            <div class="accent-options">
                <?php foreach ($accentOptions as $hex => $name): ?>
                    <label class="accent-swatch" title="<?= $name ?>">
                        <input type="radio" name="theme_accent" value="<?= $hex ?>" <?= $admin['theme_accent'] === $hex ? 'checked' : '' ?>>
                        <span style="background:<?= $hex ?>;"></span>
                    </label>
                <?php endforeach; ?>
            </div>

            <button type="submit">Save Preferences</button>
        </form>
    </div>

</div>

</div>

<?php include "includes/ui_partials.php"; ?>

<script src="assets/js/admin-ui.js?v=<?= filemtime(__DIR__ . '/assets/js/admin-ui.js') ?>"></script>

</body>
</html>
