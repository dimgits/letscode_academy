<?php
/**
 * SMTP / email configuration.
 *
 * Fill these in with your own SMTP provider details. A few common options:
 *
 *  - Gmail: host "smtp.gmail.com", port 587, encryption "tls".
 *    Username = your Gmail address. Password = a 16-character "App Password"
 *    (Google Account > Security > 2-Step Verification > App passwords) --
 *    your normal Gmail password will NOT work here.
 *
 *  - Mailtrap (for local testing, emails never actually leave Mailtrap):
 *    host "sandbox.smtp.mailtrap.io", port 2525, credentials from your
 *    Mailtrap inbox settings.
 *
 *  - Any other SMTP provider (SendGrid, Brevo/Sendinblue, your host's mail
 *    server, etc.) works the same way -- just plug in their host/port/creds.
 */

return [
    'host'       => 'smtp.gmail.com',
    'port'       => 587,
    'encryption' => 'tls', // 'tls' or 'ssl'
    'username'   => 'letscodeacademy.go@gmail.com',
    'password'   => 'zougnjuyaufjqesa',

    'from_email' => 'letscodeacademy.go@gmail.com',
    'from_name'  => 'LetsCode',

    // Base URL used to build the confirmation link in emails.
    // Change this to match wherever the signup_page folder is served from.
    'base_url'   => 'http://localhost/training-registration-updated/signup_page',
];
