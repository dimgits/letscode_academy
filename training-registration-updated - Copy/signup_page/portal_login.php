<?php
session_start();
include "db.php";
include "includes/portal_functions.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM tb_registrations WHERE student_username = ? AND is_verified = 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $student = $result->fetch_assoc();

        if (password_verify($password, $student["student_password"])) {

            if (!empty($student["twofa_enabled"])) {
                $_SESSION["twofa_student_id"] = $student["ID"];
                header("Location: portal_2fa_verify.php");
                exit();
            }

            $_SESSION["student_id"] = $student["ID"];
            $_SESSION["student_name"] = $student["full_name"];
            $_SESSION["student_course"] = $student["course"];

            bump_login_streak($conn, $student);

            header("Location: portal_dashboard.php");
            exit();
        }
    }

    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Login | LetsCode!</title>

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
                <a href="../landing_page/index.php" class="btn btn-outline">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </nav>

    <section class="portal-section">
        <div class="portal-section-inner">

            <!-- Left: Intro Text -->
            <div class="portal-intro">

                <span class="badge-purple">
                    <i class="bi bi-mortarboard-fill"></i>
                    Student Access
                </span>

                <h1>
                    Welcome Back,
                    <span>Future Developer</span>
                </h1>

                <p class="lead">
                    Log in to pick up right where you left off. Your course
                    materials, lessons, and progress are all waiting for you.
                </p>

                <div class="portal-intro-points">

                    <div class="portal-intro-point">
                        <i class="bi bi-patch-check-fill"></i>
                        <span>Access your enrolled course anytime</span>
                    </div>

                    <div class="portal-intro-point">
                        <i class="bi bi-patch-check-fill"></i>
                        <span>Track your lessons and progress</span>
                    </div>

                    <div class="portal-intro-point">
                        <i class="bi bi-patch-check-fill"></i>
                        <span>Pick up right where you left off</span>
                    </div>

                </div>

            </div>

            <!-- Right: Login Card -->
            <div class="portal-card-right">
                <div class="portal-card">

                    <div class="portal-icon-circle">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <h1>Student Login</h1>
                    <p class="portal-subtitle">Log in to access your learning platform.</p>

                    <?php if ($error): ?>
                        <div class="alert-box alert-error"><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Your username" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Your password" required>
                        </div>

                        <button type="submit" class="btn btn-purple btn-block">
                            Log In
                            <i class="bi bi-arrow-right"></i>
                        </button>

                    </form>

                    <div class="portal-footer">
                        <a href="forgot_password.php">Forgot your password?</a>
                    </div>

                    <div class="portal-divider">or</div>

                    <div class="portal-footer">
                        Haven't signed up yet? <a href="signup.php">Register here</a>
                    </div>

                </div>
            </div>

        </div>
    </section>

</body>
</html>
