<?php
session_start();
include "db.php";
include "includes/courses_data.php";
include "includes/portal_functions.php";
include "lib/totp.php";
include "mailer.php";
include "email_templates.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: portal_login.php");
    exit();
}

$studentId = (int) $_SESSION["student_id"];
$student = get_student($conn, $studentId);
if (!$student) {
    header("Location: portal_logout.php");
    exit();
}

$tab = $_GET['tab'] ?? 'profile';
if (!in_array($tab, ['profile', 'security', 'notifications'], true)) {
    $tab = 'profile';
}

$errors = [];
$success = '';

// ---------------------------------------------------------------
// PROFILE: name / birthday / location / avatar
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $tab = 'profile';
    $fullName = trim($_POST['full_name'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $location = trim($_POST['location'] ?? '');

    if ($fullName === '') {
        $errors[] = "Full name can't be empty.";
    }

    $avatarPath = $student['profile_picture'];
    if (!empty($_FILES['profile_picture']['name'])) {
        $file = $_FILES['profile_picture'];
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "There was a problem uploading your photo.";
        } elseif ($file['size'] > 3 * 1024 * 1024) {
            $errors[] = "Profile picture must be under 3MB.";
        } elseif (!isset($allowed[$file['type']])) {
            $errors[] = "Profile picture must be a JPG, PNG, or WEBP image.";
        } else {
            $dir = __DIR__ . '/assets/uploads/avatars';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $filename = 'student_' . $studentId . '_' . time() . '.' . $allowed[$file['type']];
            if (move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) {
                $avatarPath = 'assets/uploads/avatars/' . $filename;
            } else {
                $errors[] = "Couldn't save your profile picture. Please try again.";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE tb_registrations SET full_name = ?, birthday = ?, location = ?, profile_picture = ? WHERE ID = ?");
        $birthdayVal = $birthday !== '' ? $birthday : null;
        $locationVal = $location !== '' ? $location : null;
        $stmt->bind_param("ssssi", $fullName, $birthdayVal, $locationVal, $avatarPath, $studentId);
        $stmt->execute();

        $_SESSION['student_name'] = $fullName;
        $success = "Your profile has been updated.";
        $student = get_student($conn, $studentId);
    }
}

// ---------------------------------------------------------------
// SECURITY: change password
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $tab = 'security';
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!password_verify($current, $student['student_password'])) {
        $errors[] = "Your current password is incorrect.";
    } elseif (strlen($new) < 8) {
        $errors[] = "New password must be at least 8 characters.";
    } elseif ($new !== $confirm) {
        $errors[] = "New passwords don't match.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE tb_registrations SET student_password = ? WHERE ID = ?");
        $stmt->bind_param("si", $hashed, $studentId);
        $stmt->execute();
        $success = "Your password has been changed.";
    }
}

// ---------------------------------------------------------------
// SECURITY: request email change (sends verification link to new address)
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_email'])) {
    $tab = 'security';
    $newEmail = trim($_POST['new_email'] ?? '');
    $currentPassword = $_POST['email_current_password'] ?? '';

    if (!password_verify($currentPassword, $student['student_password'])) {
        $errors[] = "Your current password is incorrect.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    } elseif (strcasecmp($newEmail, $student['email']) === 0) {
        $errors[] = "That's already your current email address.";
    } else {
        $check = $conn->prepare("SELECT ID FROM tb_registrations WHERE email = ? AND ID != ?");
        $check->bind_param("si", $newEmail, $studentId);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $errors[] = "That email address is already in use.";
        } else {
            $token = portal_random_token();
            $expires = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');

            $stmt = $conn->prepare("UPDATE tb_registrations SET pending_email = ?, email_change_token = ?, email_change_expires = ? WHERE ID = ?");
            $stmt->bind_param("sssi", $newEmail, $token, $expires, $studentId);
            $stmt->execute();

            $link = mail_base_url() . "/verify_email_change.php?token=" . urlencode($token);
            $body = '<p style="line-height:1.6;">Hi ' . htmlspecialchars($student['full_name']) . ',</p>
                <p style="line-height:1.6;">You requested to change the email address on your LetsCode account to this one. Click below to confirm. This link expires in 30 minutes.</p>
                <p style="text-align:center;margin:30px 0;">
                    <a href="' . htmlspecialchars($link) . '" style="background:#7c3aed;color:#ffffff;text-decoration:none;padding:14px 28px;border-radius:10px;display:inline-block;font-weight:bold;">Confirm new email</a>
                </p>
                <p style="line-height:1.6;font-size:13px;color:#9ca3af;">Didn\'t request this? You can safely ignore this email.</p>';

            send_email($newEmail, $student['full_name'], 'Confirm your new email address', email_shell('Confirm your new email', $body));
            $success = "We've sent a confirmation link to $newEmail. Click it to finish changing your email.";
            $student = get_student($conn, $studentId);
        }
    }
}

// ---------------------------------------------------------------
// SECURITY: 2FA setup / confirm / disable
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['twofa_start_setup'])) {
    $tab = 'security';
    $_SESSION['twofa_pending_secret'] = TOTP::generateSecret();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['twofa_confirm'])) {
    $tab = 'security';
    $code = $_POST['twofa_code'] ?? '';
    $pendingSecret = $_SESSION['twofa_pending_secret'] ?? '';

    if ($pendingSecret === '' || !TOTP::verifyCode($pendingSecret, $code)) {
        $errors[] = "That code didn't match. Please try again.";
    } else {
        $stmt = $conn->prepare("UPDATE tb_registrations SET twofa_enabled = 1, twofa_secret = ? WHERE ID = ?");
        $stmt->bind_param("si", $pendingSecret, $studentId);
        $stmt->execute();
        unset($_SESSION['twofa_pending_secret']);
        $success = "Two-factor authentication is now enabled on your account.";
        $student = get_student($conn, $studentId);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['twofa_disable'])) {
    $tab = 'security';
    $stmt = $conn->prepare("UPDATE tb_registrations SET twofa_enabled = 0, twofa_secret = NULL WHERE ID = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $success = "Two-factor authentication has been turned off.";
    $student = get_student($conn, $studentId);
}

// ---------------------------------------------------------------
// NOTIFICATIONS
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_notifications'])) {
    $tab = 'notifications';
    $notifyLessons = isset($_POST['notify_lessons']) ? 1 : 0;
    $notifyHomework = isset($_POST['notify_homework']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE tb_registrations SET notify_lessons = ?, notify_homework = ? WHERE ID = ?");
    $stmt->bind_param("iii", $notifyLessons, $notifyHomework, $studentId);
    $stmt->execute();

    $success = "Your notification preferences have been saved.";
    $student = get_student($conn, $studentId);
}

$pendingSecret = $_SESSION['twofa_pending_secret'] ?? null;
$qrUrl = $pendingSecret ? TOTP::qrCodeUrl(TOTP::provisioningUri($pendingSecret, $student['email'])) : null;

$activePage = 'settings';
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Settings | LetsCode!</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/portal.css">

</head>
<body>

    <div class="bg-blur blur1"></div>
    <div class="bg-blur blur2"></div>
    <div class="bg-blur blur3"></div>

    <?php include "includes/portal_nav.php"; ?>

    <div class="portal-shell wide">
        <div class="dash-wrap narrow">

            <div class="page-heading">
                <div>
                    <span class="badge-purple"><i class="bi bi-gear-fill"></i> Account Settings</span>
                    <h1>Settings</h1>
                    <p>Manage your profile, security, and notification preferences.</p>
                </div>
            </div>

            <div class="settings-tabs">
                <button type="button" class="settings-tab <?= $tab === 'profile' ? 'active' : '' ?>" data-tab="profile">
                    <i class="bi bi-person-fill"></i> Profile
                </button>
                <button type="button" class="settings-tab <?= $tab === 'security' ? 'active' : '' ?>" data-tab="security">
                    <i class="bi bi-shield-lock-fill"></i> Security
                </button>
                <button type="button" class="settings-tab <?= $tab === 'notifications' ? 'active' : '' ?>" data-tab="notifications">
                    <i class="bi bi-bell-fill"></i> Notifications
                </button>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert-box alert-error">
                    <?php foreach ($errors as $e): ?><div><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
                </div>
            <?php elseif ($success): ?>
                <div class="alert-box alert-success"><i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- PROFILE PANEL -->
            <div class="settings-panel <?= $tab === 'profile' ? 'active' : '' ?>" data-panel="profile">
                <div class="settings-card">

                    <div class="avatar-upload-row">
                        <img src="<?= avatar_url($student) ?>" alt="Profile picture" class="avatar-preview" id="avatarPreview">
                        <div>
                            <label for="profile_picture_input" class="btn btn-outline btn-sm-icon">
                                <i class="bi bi-camera-fill"></i> Change photo
                            </label>
                            <div class="field-hint">JPG, PNG or WEBP. Max 3MB.</div>
                        </div>
                    </div>

                    <form method="POST" enctype="multipart/form-data" action="settings.php?tab=profile">
                        <input type="file" name="profile_picture" id="profile_picture_input" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="previewAvatar(this)">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="birthday">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" value="<?= htmlspecialchars($student['birthday'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email_display">Email</label>
                                <input type="email" class="form-control" id="email_display" value="<?= htmlspecialchars($student['email']) ?>" disabled>
                                <div class="field-hint">Change your email from the Security tab.</div>
                            </div>
                            <div class="form-group">
                                <label for="location">Where are you from</label>
                                <input type="text" class="form-control" id="location" name="location" placeholder="e.g. Denpasar, Bali" value="<?= htmlspecialchars($student['location'] ?? '') ?>">
                            </div>
                        </div>

                        <button type="submit" name="update_profile" value="1" class="btn btn-purple">
                            <i class="bi bi-check-lg"></i> Save Profile
                        </button>
                    </form>
                </div>
            </div>

            <!-- SECURITY PANEL -->
            <div class="settings-panel <?= $tab === 'security' ? 'active' : '' ?>" data-panel="security">

                <div class="settings-card">
                    <h3><i class="bi bi-key-fill"></i> Change Password</h3>
                    <form method="POST" action="settings.php?tab=security">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" minlength="8" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="8" required>
                            </div>
                        </div>
                        <button type="submit" name="change_password" value="1" class="btn btn-purple">
                            <i class="bi bi-check-lg"></i> Update Password
                        </button>
                    </form>
                </div>

                <div class="settings-card">
                    <h3><i class="bi bi-envelope-fill"></i> Change Email</h3>
                    <p class="field-hint" style="margin-bottom:16px;">Current email: <strong><?= htmlspecialchars($student['email']) ?></strong>
                        <?php if (!empty($student['pending_email'])): ?>
                            &nbsp;·&nbsp;<span style="color:#facc15;">Pending confirmation: <?= htmlspecialchars($student['pending_email']) ?></span>
                        <?php endif; ?>
                    </p>
                    <form method="POST" action="settings.php?tab=security">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_email">New Email Address</label>
                                <input type="email" class="form-control" id="new_email" name="new_email" required>
                            </div>
                            <div class="form-group">
                                <label for="email_current_password">Confirm Password</label>
                                <input type="password" class="form-control" id="email_current_password" name="email_current_password" required>
                            </div>
                        </div>
                        <button type="submit" name="change_email" value="1" class="btn btn-purple">
                            <i class="bi bi-send-fill"></i> Send Verification Link
                        </button>
                    </form>
                </div>

                <div class="settings-card">
                    <h3><i class="bi bi-shield-lock-fill"></i> Two-Factor Authentication</h3>

                    <?php if ($student['twofa_enabled']): ?>
                        <div class="twofa-status enabled">
                            <i class="bi bi-patch-check-fill"></i>
                            <div>
                                <strong>2FA is enabled</strong>
                                <span>Your account is protected with an authenticator app code at login.</span>
                            </div>
                        </div>
                        <form method="POST" action="settings.php?tab=security" style="margin-top:16px;">
                            <button type="submit" name="twofa_disable" value="1" class="btn btn-outline">
                                <i class="bi bi-x-circle"></i> Disable 2FA
                            </button>
                        </form>

                    <?php elseif ($pendingSecret): ?>
                        <div class="twofa-setup">
                            <p class="field-hint">Scan this QR code with Google Authenticator, Authy, or any TOTP app, then enter the 6-digit code it gives you.</p>
                            <img src="<?= htmlspecialchars($qrUrl) ?>" alt="2FA QR Code" class="twofa-qr">
                            <p class="field-hint">Can't scan it? Enter this code manually: <code><?= htmlspecialchars($pendingSecret) ?></code></p>

                            <form method="POST" action="settings.php?tab=security" class="twofa-confirm-form">
                                <input type="text" class="form-control" name="twofa_code" placeholder="6-digit code" maxlength="6" inputmode="numeric" required>
                                <button type="submit" name="twofa_confirm" value="1" class="btn btn-purple">
                                    <i class="bi bi-check-lg"></i> Verify & Enable
                                </button>
                            </form>
                        </div>

                    <?php else: ?>
                        <div class="twofa-status disabled">
                            <i class="bi bi-shield-slash"></i>
                            <div>
                                <strong>2FA is optional</strong>
                                <span>Add an extra layer of security to your account with an authenticator app.</span>
                            </div>
                        </div>
                        <form method="POST" action="settings.php?tab=security" style="margin-top:16px;">
                            <button type="submit" name="twofa_start_setup" value="1" class="btn btn-purple">
                                <i class="bi bi-shield-plus"></i> Set Up 2FA
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- NOTIFICATIONS PANEL -->
            <div class="settings-panel <?= $tab === 'notifications' ? 'active' : '' ?>" data-panel="notifications">
                <div class="settings-card">
                    <h3><i class="bi bi-bell-fill"></i> Notification Preferences</h3>
                    <form method="POST" action="settings.php?tab=notifications">

                        <label class="toggle-row">
                            <div>
                                <strong>New Lesson Alerts</strong>
                                <span>Get an email whenever a new lesson is added to your course.</span>
                            </div>
                            <span class="toggle-switch">
                                <input type="checkbox" name="notify_lessons" <?= $student['notify_lessons'] ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                            </span>
                        </label>

                        <label class="toggle-row">
                            <div>
                                <strong>Homework Reminders</strong>
                                <span>Get reminded by email about upcoming or overdue homework.</span>
                            </div>
                            <span class="toggle-switch">
                                <input type="checkbox" name="notify_homework" <?= $student['notify_homework'] ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                            </span>
                        </label>

                        <button type="submit" name="update_notifications" value="1" class="btn btn-purple" style="margin-top:8px;">
                            <i class="bi bi-check-lg"></i> Save Preferences
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="assets/js/portal.js"></script>

</body>
</html>
