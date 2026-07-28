<?php
include "db.php";

$token = isset($_GET['token']) ? trim($_GET['token']) : (isset($_POST['token']) ? trim($_POST['token']) : '');

$status = '';   // 'form' | 'done' | 'invalid'
$error = '';
$fullName = '';

if ($token === '') {
    $status = 'invalid';
} else {
    $stmt = $conn->prepare("
        SELECT * FROM tb_registrations
        WHERE reset_token = ? AND reset_expires > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        $status = 'invalid';
    } else {
        $fullName = $row['full_name'];
        $status = 'form';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $newPassword = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (strlen($newPassword) < 8) {
                $error = "Password must be at least 8 characters.";
            } elseif ($newPassword !== $confirmPassword) {
                $error = "Passwords don't match.";
            } else {
                $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

                $update = $conn->prepare("
                    UPDATE tb_registrations
                    SET student_password = ?, reset_token = NULL, reset_expires = NULL
                    WHERE ID = ?
                ");
                $update->bind_param("si", $hashed, $row['ID']);
                $update->execute();

                $status = 'done';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password | LetsCode!</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/portal.css">

</head>
<body>

    <div class="bg-blur blur1"></div>
    <div class="bg-blur blur2"></div>
    <div class="bg-blur blur3"></div>

    <nav class="glass-nav">
        <div class="nav-inner">
            <a class="logo" href="../landing_page/index.php">
                <i class="bi bi-code-slash"></i>
                LetsCode!
            </a>
            <div class="nav-actions">
                <a href="portal_login.php" class="btn btn-outline">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Login</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="portal-shell">
        <div class="portal-card text-center">

            <?php if ($status === 'form'): ?>

                <span class="badge-purple">
                    <i class="bi bi-shield-lock-fill"></i>
                    New Password
                </span>

                <div class="portal-icon-circle">
                    <i class="bi bi-key-fill"></i>
                </div>

                <h1>Set a New Password</h1>
                <p class="portal-subtitle">Hi <?= htmlspecialchars($fullName) ?>, choose a new password below.</p>

                <?php if ($error): ?>
                    <div class="alert-box alert-error"><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">

                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="At least 8 characters" required minlength="8">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required minlength="8">
                    </div>

                    <button type="submit" class="btn btn-purple btn-block">
                        Update Password
                        <i class="bi bi-check-lg"></i>
                    </button>

                </form>

            <?php elseif ($status === 'done'): ?>

                <div class="portal-icon-circle">
                    <i class="bi bi-check-lg"></i>
                </div>

                <h1>Password Updated!</h1>
                <p class="portal-subtitle">Your password has been changed. You can now log in with your new password.</p>

                <a href="portal_login.php" class="btn btn-purple btn-block">
                    Go to Login
                    <i class="bi bi-arrow-right"></i>
                </a>

            <?php else: ?>

                <div class="portal-icon-circle" style="background:linear-gradient(135deg,#f87171,#dc2626);box-shadow:0 0 0 8px rgba(248,113,113,.12),0 0 40px rgba(248,113,113,.4);">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <h1>Invalid or Expired Link</h1>
                <p class="portal-subtitle">This reset link isn't valid anymore. Reset links expire after 30 minutes -- please request a new one.</p>

                <a href="forgot_password.php" class="btn btn-purple btn-block">
                    Request a New Link
                    <i class="bi bi-arrow-clockwise"></i>
                </a>

            <?php endif; ?>

        </div>
    </div>

</body>
</html>
