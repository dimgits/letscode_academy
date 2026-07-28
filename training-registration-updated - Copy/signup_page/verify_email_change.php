<?php
include "db.php";

$token = trim($_GET['token'] ?? '');
$status = 'invalid';

if ($token !== '') {
    $stmt = $conn->prepare("
        SELECT * FROM tb_registrations
        WHERE email_change_token = ? AND email_change_expires > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        $newEmail = $row['pending_email'];
        $update = $conn->prepare("
            UPDATE tb_registrations
            SET email = ?, pending_email = NULL, email_change_token = NULL, email_change_expires = NULL
            WHERE ID = ?
        ");
        $update->bind_param("si", $newEmail, $row['ID']);
        $update->execute();
        $status = 'done';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Confirm Email Change | LetsCode!</title>

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

            <?php if ($status === 'done'): ?>
                <div class="portal-icon-circle">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h1>Email Updated!</h1>
                <p class="portal-subtitle">Your email address has been changed. Use your new email next time you need to reset your password.</p>
                <a href="portal_login.php" class="btn btn-purple btn-block">
                    Back to Login <i class="bi bi-arrow-right"></i>
                </a>
            <?php else: ?>
                <div class="portal-icon-circle" style="background:linear-gradient(135deg,#f87171,#dc2626);box-shadow:0 0 0 8px rgba(248,113,113,.12),0 0 40px rgba(248,113,113,.4);">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h1>Invalid or Expired Link</h1>
                <p class="portal-subtitle">This confirmation link isn't valid anymore. Please request the email change again from your Settings page.</p>
                <a href="portal_login.php" class="btn btn-purple btn-block">
                    Back to Login <i class="bi bi-arrow-right"></i>
                </a>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>
