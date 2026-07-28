<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

function getMailer()
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    // IMPORTANT: fill these in yourself, and never commit real credentials
    // to source control. Use your Gmail address and a 16-character Gmail
    // "App Password" (Google Account > Security > 2-Step Verification >
    // App passwords) -- your normal Gmail password will NOT work here.
    $mail->Username = 'letscodeacademy.go@gmail.com';

    $mail->Password = 'zougnjuyaufjqesa';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;

    $mail->setFrom(
        'letscodeacademy.go@gmail.com',
        'Training Registration'
    );

    $mail->isHTML(true);

    return $mail;
}