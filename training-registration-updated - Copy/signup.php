<?php
session_start();

$formErrors = $_SESSION['signup_errors'] ?? [];
$oldInput = $_SESSION['signup_old'] ?? [];
unset($_SESSION['signup_errors'], $_SESSION['signup_old']);

// Allow the course dropdown to be pre-selected via a query string, e.g.
// signup.php?course=Web%20Development (used by the course detail pages'
// "Join Us" buttons). Only accept values that are actually valid options.
$validCourses = [
    'Web Development',
    'Mobile App Development',
    'UI / UX Design',
    'Cyber Security',
    'Data Analytics',
    'Artificial Intelligence',
];

$preselectedCourse = '';
if (isset($oldInput['course']) && in_array($oldInput['course'], $validCourses, true)) {
    $preselectedCourse = $oldInput['course'];
} elseif (isset($_GET['course']) && in_array($_GET['course'], $validCourses, true)) {
    $preselectedCourse = $_GET['course'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign Up | LetsCode!</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/signup.css">

</head>

<body>

    <!-- ========================= -->
    <!-- Background Glow -->
    <!-- ========================= -->

    <div class="bg-blur blur1"></div>
    <div class="bg-blur blur2"></div>
    <div class="bg-blur blur3"></div>

    <!-- ========================= -->
    <!-- Navbar -->
    <!-- ========================= -->

    <nav class="navbar navbar-expand-lg fixed-top glass-nav">

        <div class="container">

            <a class="navbar-brand logo" href="../landing_page/index.php">

                <i class="bi bi-code-slash"></i>

                LetsCode!

            </a>

            <a href="../landing_page/index.php" class="btn btn-outline-light px-4">

                <i class="bi bi-arrow-left"></i>
                Back to Home

            </a>

        </div>

    </nav>

    <!-- ========================= -->
    <!-- Sign Up Section -->
    <!-- ========================= -->

    <section class="signup-section">

        <div class="container">

            <div class="row justify-content-center align-items-center g-4">

                <!-- Left: Intro Text -->

                <div class="col-lg-5 signup-intro">

                    <span class="badge-purple">

                        Start Learning Today

                    </span>

                    <h1>

                        Join The Next Batch Of

                        <span>Future Developers</span>

                    </h1>

                    <p>

                        Fill out the form to reserve your spot. Our team will
                        reach out with everything you need to know before
                        your first class.

                    </p>

                    <div class="intro-points">

                        <div class="intro-point">

                            <i class="bi bi-patch-check-fill"></i>

                            <span>Beginner friendly, no experience needed</span>

                        </div>

                        <div class="intro-point">

                            <i class="bi bi-patch-check-fill"></i>

                            <span>Learn directly from industry mentors</span>

                        </div>

                        <div class="intro-point">

                            <i class="bi bi-patch-check-fill"></i>

                            <span>Earn a certificate upon graduation</span>

                        </div>

                    </div>

                </div>

                <!-- Right: Form Card -->

                <div class="col-lg-6">

                    <div class="signup-card">

                        <div class="signup-card-header">

                            <h3>Create Your Account</h3>

                            <p>It only takes a minute to get started.</p>

                        </div>

                        <form id="signupForm" action="register.php" method="POST">

                            <div class="form-group">

                                <label for="fullName">Full Name</label>

                                <input type="text" class="form-control" id="fullName" name="full_name"
                                    value="<?= htmlspecialchars($oldInput['full_name'] ?? '') ?>"
                                    placeholder="e.g. Dimas Aditya" required>

                                <div class="invalid-feedback">Please enter your full name.</div>

                            </div>

                            <div class="form-group">

                                <label for="email">Email Address</label>

                                <input type="email" class="form-control<?= isset($formErrors['email']) ? ' is-invalid' : '' ?>" id="email" name="email"
                                    value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>"
                                    placeholder="e.g. dimas@example.com" required>

                                <div class="invalid-feedback"><?= isset($formErrors['email']) ? htmlspecialchars($formErrors['email']) : 'Please enter a valid email address.' ?></div>

                            </div>

                            <div class="row">

                                <div class="col-md-7 form-group">

                                    <label for="phone">Phone Number</label>

                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="<?= htmlspecialchars($oldInput['phone'] ?? '') ?>"
                                        placeholder="e.g. 0812 3456 7890" required>

                                    <div class="invalid-feedback">Please enter a valid phone number.</div>

                                </div>

                                <div class="col-md-5 form-group">

                                    <label for="age">Age</label>

                                    <input type="number" class="form-control" id="age" name="age" min="10"
                                        max="100" value="<?= htmlspecialchars($oldInput['age'] ?? '') ?>" placeholder="e.g. 21" required>

                                    <div class="invalid-feedback">Please enter your age.</div>

                                </div>

                            </div>

                            <div class="form-group">

                                <label for="course">Course You Want To Join</label>

                                <select class="form-select" id="course" name="course" required>

                                    <option value="" <?= $preselectedCourse === '' ? 'selected' : '' ?> disabled>Choose a course</option>
                                    <?php foreach ($validCourses as $courseOption): ?>
                                    <option value="<?= htmlspecialchars($courseOption) ?>" <?= $preselectedCourse === $courseOption ? 'selected' : '' ?>><?= htmlspecialchars($courseOption) ?></option>
                                    <?php endforeach; ?>

                                </select>

                                <div class="invalid-feedback">Please select a course.</div>

                            </div>

                            <button type="submit" class="btn btn-purple btn-lg w-100 mt-3" id="signupBtn">

                                <span class="btn-label">Sign Up</span>

                                <span class="btn-spinner d-none">

                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Sending...

                                </span>

                            </button>

                            <p class="form-note">

                                By signing up, you agree to be contacted by LetsCode! regarding your
                                registration and course details.

                            </p>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- ========================= -->
    <!-- Success Modal -->
    <!-- ========================= -->

    <div class="modal-overlay" id="successModal">

        <div class="modal-box">

            <div class="checkmark-circle">

                <i class="bi bi-check-lg"></i>

            </div>

            <h3>Thanks for joining us!</h3>

            <p id="successModalText">

                We can't wait to see you on the class soon! Additional
                information will be sent to your email for more details.

            </p>

            <button type="button" class="btn btn-purple" id="closeModalBtn">

                Got it

            </button>

        </div>

    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS -->
    <script src="assets/js/signup.js"></script>

    <?php if (isset($_GET['success'])): ?>
    <script>
    window.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("successModal");
        <?php if (isset($_GET['mail_error'])): ?>
        const text = document.getElementById("successModalText");
        if (text) {
            text.textContent = "Your registration was saved, but we couldn't send the confirmation email right now. Please contact us so we can confirm your spot manually.";
        }
        <?php endif; ?>
        if (modal) {
            modal.classList.add("show");
        }
    });
    </script>
    <?php endif; ?>

</body>
</html>
