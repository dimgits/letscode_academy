<?php

require_once('mail_config.php');

$mail = getMailer();

$mail->addAddress('YOUR_EMAIL@gmail.com');

$mail->Subject = 'SMTP Test';

$mail->Body = '<h2>It works 🎉</h2>';

$mail->send();

echo "Email Sent!";