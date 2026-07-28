<?php
include "db.php";
include "mailer.php";
include "email_templates.php";

$message = "";
$messageType = ""; // 'success' | 'error'

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $identifier = trim($_POST["identifier"] ?? "");

    if ($identifier === "") {
        $message = "Please enter your username or email.";
        $messageType = "error";
    } else {
        $stmt = $conn->prepare("
            SELECT * FROM tb_registrations
            WHERE (student_username = ? OR email = ?) AND is_verified = 1
        ");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();

        // Always show the same success message whether or not the account
        // exists -- avoids leaking which usernames/emails are registered.
        $message = "If that account exists, we've sent a password reset link to its registered email address.";
        $messageType = "success";

        if ($student) {
            $resetToken = bin2hex(random_bytes(32));

            $update = $conn->prepare("
                UPDATE tb_registrations
                SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                WHERE ID = ?
            ");
            $update->bind_param("si", $resetToken, $student["ID"]);
            $update->execute();

            $resetLink = mail_base_url() . "/reset_password.php?token=" . $resetToken;
            $emailHtml = reset_password_email_html($student["full_name"], $resetLink);
            send_email($student["email"], $student["full_name"], "Reset your LetsCode password", $emailHtml);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | LetsCode!</title>

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

            <span class="badge-purple">
                <i class="bi bi-key-fill"></i>
                Account Recovery
            </span>

            <div class="portal-icon-circle">
                <i class="bi bi-key-fill"></i>
            </div>

            <h1>Reset Your Password</h1>
            <p class="portal-subtitle">Enter your username or email and we'll send you a reset link</p>

            <?php if ($message && $messageType === 'error'): ?>
                <div class="alert-box alert-error"><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($message) ?></div>
            <?php elseif ($message && $messageType === 'success'): ?>
                <div class="alert-box alert-success"><i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label for="identifier">Username or Email</label>
                    <input type="text" class="form-control" id="identifier" name="identifier" placeholder="Your username or email" required autofocus>
                </div>

                <button type="submit" class="btn btn-purple btn-block">
                    Send Reset Link
                    <i class="bi bi-send-fill"></i>
                </button>

            </form>

            <div class="portal-footer">
                Remembered your password? <a href="portal_login.php">Back to login</a>
            </div>

        </div>
    </div>

</body>
</html>
