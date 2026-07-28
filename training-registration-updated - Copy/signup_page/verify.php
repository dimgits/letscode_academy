<?php
include "db.php";
include "mailer.php";
include "email_templates.php";

$token = isset($_GET['token']) ? trim($_GET['token']) : '';

$status = '';   // 'success' | 'already' | 'invalid'
$username = '';
$password = '';
$fullName = '';

if ($token === '') {
    $status = 'invalid';
} else {
    $stmt = $conn->prepare("SELECT * FROM tb_registrations WHERE verify_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        $status = 'invalid';
    } elseif ($row['is_verified'] == 1) {
        $status = 'already';
        $fullName = $row['full_name'];
    } else {
        $fullName = $row['full_name'];

        // Generate a unique username from the student's name.
        $base = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $row['full_name']));
        if ($base === '') {
            $base = 'student';
        }

        do {
            $username = $base . rand(100, 999);
            $check = $conn->prepare("SELECT id FROM tb_registrations WHERE student_username = ?");
            $check->bind_param("s", $username);
            $check->execute();
            $exists = $check->get_result()->num_rows > 0;
        } while ($exists);

        // Generate a random password.
        $password = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'), 0, 10);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $update = $conn->prepare("
            UPDATE tb_registrations
            SET is_verified = 1,
                verified_at = NOW(),
                student_username = ?,
                student_password = ?,
                verify_token = NULL
            WHERE id = ?
        ");
        $update->bind_param("ssi", $username, $hashedPassword, $row['ID']);
        $update->execute();

        $loginLink = mail_base_url() . "/portal_login.php";
        $emailHtml = credentials_email_html($fullName, $username, $password, $loginLink);
        send_email($row['email'], $fullName, "Your LetsCode account is ready", $emailHtml);

        $status = 'success';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Confirm Registration - LetsCode</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Portal CSS (shared theme) -->
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
                <a href="../landing_page/index.php" class="btn btn-outline">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="portal-shell">
        <div class="portal-card text-center">

            <?php if ($status === 'success'): ?>

                <div class="portal-icon-circle">
                    <i class="bi bi-check-lg"></i>
                </div>

                <h1>Email confirmed!</h1>
                <p class="portal-subtitle">Welcome, <?= htmlspecialchars($fullName) ?>! Your learning platform account has been created. We've also emailed these to you.</p>

                <div class="creds-box">
                    <div><strong>Username:</strong> <?= htmlspecialchars($username) ?></div>
                    <div><strong>Password:</strong> <?= htmlspecialchars($password) ?></div>
                </div>

                <p class="portal-subtitle">Please change your password on the first login.</p>

                <a href="portal_login.php" class="btn btn-purple btn-block">Log in now</a>

            <?php elseif ($status === 'already'): ?>

                <div class="portal-icon-circle" style="background:linear-gradient(135deg,#22c55e,#16a34a);box-shadow:0 0 0 8px rgba(34,197,94,.12),0 0 40px rgba(34,197,94,.4);">
                    <i class="bi bi-hand-thumbs-up-fill"></i>
                </div>

                <h1>Already confirmed</h1>
                <p class="portal-subtitle">Hi <?= htmlspecialchars($fullName) ?>, this registration has already been confirmed. Use the username and password that were emailed to you to log in.</p>

                <a href="portal_login.php" class="btn btn-purple btn-block">Go to login</a>

            <?php else: ?>

                <div class="portal-icon-circle" style="background:linear-gradient(135deg,#f87171,#dc2626);box-shadow:0 0 0 8px rgba(248,113,113,.12),0 0 40px rgba(248,113,113,.4);">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <h1>Invalid or expired link</h1>
                <p class="portal-subtitle">This confirmation link isn't valid. Please check the link from your email, or sign up again if you haven't already.</p>

                <a href="signup.php" class="btn btn-purple btn-block">Back to sign up</a>

            <?php endif; ?>

        </div>
    </div>

</body>
</html>
