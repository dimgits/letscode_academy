<?php
/**
 * Standalone SMTP diagnostic. Visit this file directly in your browser:
 *   http://localhost/training-registration-updated/signup_page/test_smtp.php
 *
 * It tries to send a real test email using whatever is currently in
 * mail_config.php and prints the exact success/error message -- so you can
 * see precisely why sending is failing instead of guessing.
 *
 * DELETE THIS FILE once things are working -- it's not meant to stay on
 * a live/public server.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "mailer.php";

$config = require "mail_config.php";

echo "<pre>";
echo "Current mail_config.php values:\n";
echo "  host:       " . $config['host'] . "\n";
echo "  port:       " . $config['port'] . "\n";
echo "  encryption: " . $config['encryption'] . "\n";
echo "  username:   " . $config['username'] . "\n";
echo "  from_email: " . $config['from_email'] . "\n";
echo "  base_url:   " . $config['base_url'] . "\n\n";

if ($config['username'] === 'your-email@gmail.com' || $config['password'] === 'your-app-password') {
    echo "STOP: mail_config.php still has the placeholder username/password.\n";
    echo "Edit signup_page/mail_config.php and put in your real Gmail address\n";
    echo "and a 16-character Gmail App Password before testing.\n";
    echo "</pre>";
    exit;
}

$testTo = $config['username']; // sends the test email to yourself

echo "Attempting to send a test email to: $testTo ...\n\n";

$result = send_email($testTo, "Test", "SMTP Test - LetsCode", "<h2>It works! Your SMTP config is correct.</h2>");

if ($result['success']) {
    echo "SUCCESS: Email sent. Check the inbox (and spam folder) of $testTo.\n";
} else {
    echo "FAILED. PHPMailer error message:\n";
    echo $result['error'] . "\n";
}

echo "</pre>";
