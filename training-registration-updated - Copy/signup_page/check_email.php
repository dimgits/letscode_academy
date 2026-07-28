<?php
/**
 * Lightweight endpoint used by signup.js to check an email address as the
 * user types/leaves the field -- before they ever hit Submit.
 *
 * GET check_email.php?email=someone@gmail.com
 * Returns: { "validDomain": bool, "registered": bool }
 */

include "db.php";

header('Content-Type: application/json');

$email = isset($_GET['email']) ? trim($_GET['email']) : '';

$response = [
    'validDomain' => false,
    'registered'  => false,
];

if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $response['validDomain'] = (bool) preg_match('/@gmail\.com$/i', $email);

    if ($response['validDomain']) {
        $stmt = $conn->prepare("SELECT ID FROM tb_registrations WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $response['registered'] = $stmt->get_result()->num_rows > 0;
    }
}

echo json_encode($response);
