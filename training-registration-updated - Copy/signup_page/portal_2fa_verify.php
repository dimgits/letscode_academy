<?php
session_start();
include "db.php";
include "includes/portal_functions.php";
include "lib/totp.php";

// Must have passed the password step first.
if (!isset($_SESSION['twofa_student_id'])) {
    header("Location: portal_login.php");
    exit();
}

$student = get_student($conn, (int) $_SESSION['twofa_student_id']);
if (!$student || !$student['twofa_enabled']) {
    header("Location: portal_login.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');

    if (TOTP::verifyCode($student['twofa_secret'], $code)) {
        // Promote the pending login into a real session.
        $_SESSION["student_id"] = $student["ID"];
        $_SESSION["student_name"] = $student["full_name"];
        $_SESSION["student_course"] = $student["course"];
        unset($_SESSION['twofa_student_id']);

        bump_login_streak($conn, $student);

        header("Location: portal_dashboard.php");
        exit();
    }

    $error = "Invalid code. Please check your authenticator app and try again.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Two-Factor Verification | LetsCode!</title>

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
                <i class="bi bi-shield-lock-fill"></i>
                Two-Factor Authentication
            </span>

            <div class="portal-icon-circle">
                <i class="bi bi-phone-fill"></i>
            </div>

            <h1>Enter Your Code</h1>
            <p class="portal-subtitle">Open your authenticator app and enter the 6-digit code for LetsCode.</p>

            <?php if ($error): ?>
                <div class="alert-box alert-error"><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" name="code" placeholder="6-digit code" maxlength="6" inputmode="numeric" autofocus required style="text-align:center;font-size:20px;letter-spacing:4px;">
                </div>
                <button type="submit" class="btn btn-purple btn-block">
                    Verify <i class="bi bi-arrow-right"></i>
                </button>
            </form>

        </div>
    </div>

</body>
</html>
