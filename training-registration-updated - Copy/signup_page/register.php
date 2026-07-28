<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include "db.php";
include "mailer.php";
include "email_templates.php";

$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$age = $_POST['age'];
$course = $_POST['course'];

// Keep whatever they typed so signup.php can repopulate the form if we
// bounce them back for a validation error below.
$_SESSION['signup_old'] = $_POST;

// Only @gmail.com addresses are accepted at this time.
if (!preg_match('/@gmail\.com$/i', $email)) {
    $_SESSION['signup_errors'] = ['email' => 'email credentials be used at this time is only @gmail!'];
    header("Location: signup.php");
    exit();
}

// Reject if this email is already registered.
$checkStmt = $conn->prepare("SELECT ID FROM tb_registrations WHERE email = ? LIMIT 1");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();

if ($checkStmt->get_result()->num_rows > 0) {
    $_SESSION['signup_errors'] = ['email' => 'Email is already registered. Please try another one.'];
    header("Location: signup.php");
    exit();
}

$verify_token = bin2hex(random_bytes(32));

$sql = "INSERT INTO tb_registrations
(full_name,email,phone,age,course,is_verified,verify_token)
VALUES (?,?,?,?,?,0,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiss",
    $full_name,
    $email,
    $phone,
    $age,
    $course,
    $verify_token
);

if ($stmt->execute()) {

    unset($_SESSION['signup_old'], $_SESSION['signup_errors']);

    $confirmLink = mail_base_url() . "/verify.php?token=" . $verify_token;
    $emailHtml = confirmation_email_html($full_name, $course, $confirmLink);

    $result = send_email($email, $full_name, "Confirm your LetsCode registration", $emailHtml);

    if ($result['success']) {
        header("Location: signup.php?success=1");
    } else {
        // Registration is saved either way -- don't block the user just
        // because the email failed to send (e.g. SMTP not configured yet).
        header("Location: signup.php?success=1&mail_error=1");
    }

    exit();

} else {
    echo "Registration failed: " . $stmt->error;
}
