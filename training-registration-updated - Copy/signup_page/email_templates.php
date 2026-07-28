<?php
/**
 * Small HTML email templates, styled to match the LetsCode brand.
 */

function email_shell(string $title, string $bodyHtml): string
{
    return '
    <div style="background:#0f172a;padding:40px 20px;font-family:Arial,Helvetica,sans-serif;">
        <div style="max-width:480px;margin:0 auto;background:#1e293b;border-radius:16px;overflow:hidden;">
            <div style="background:#7c3aed;padding:24px 30px;">
                <span style="font-size:22px;font-weight:bold;color:#ffffff;">LetsCode</span>
            </div>
            <div style="padding:30px;color:#e5e7eb;">
                <h2 style="color:#ffffff;margin:0 0 14px;">' . htmlspecialchars($title) . '</h2>
                ' . $bodyHtml . '
            </div>
            <div style="padding:18px 30px;background:#111827;color:#6b7280;font-size:12px;">
                &copy; ' . date('Y') . ' LetsCode. All rights reserved.
            </div>
        </div>
    </div>';
}

function confirmation_email_html(string $fullName, string $course, string $confirmLink): string
{
    $body = '
        <p style="line-height:1.6;">Hi ' . htmlspecialchars($fullName) . ',</p>
        <p style="line-height:1.6;">
            Thanks for registering for <strong>' . htmlspecialchars($course) . '</strong> at LetsCode!
            Please confirm your email address to activate your registration.
        </p>
        <p style="text-align:center;margin:30px 0;">
            <a href="' . htmlspecialchars($confirmLink) . '"
               style="background:#7c3aed;color:#ffffff;text-decoration:none;padding:14px 28px;border-radius:10px;display:inline-block;font-weight:bold;">
               Confirm my registration
            </a>
        </p>
        <p style="line-height:1.6;font-size:13px;color:#9ca3af;">
            If the button above doesn\'t work, copy and paste this link into your browser:<br>
            <span style="color:#c4b5fd;">' . htmlspecialchars($confirmLink) . '</span>
        </p>
        <p style="line-height:1.6;font-size:13px;color:#9ca3af;">
            Didn\'t sign up for this? You can safely ignore this email.
        </p>';

    return email_shell('Confirm your registration', $body);
}

function reset_password_email_html(string $fullName, string $resetLink): string
{
    $body = '
        <p style="line-height:1.6;">Hi ' . htmlspecialchars($fullName) . ',</p>
        <p style="line-height:1.6;">
            We received a request to reset your LetsCode learning platform password.
            Click the button below to choose a new one. This link expires in 30 minutes.
        </p>
        <p style="text-align:center;margin:30px 0;">
            <a href="' . htmlspecialchars($resetLink) . '"
               style="background:#7c3aed;color:#ffffff;text-decoration:none;padding:14px 28px;border-radius:10px;display:inline-block;font-weight:bold;">
               Reset my password
            </a>
        </p>
        <p style="line-height:1.6;font-size:13px;color:#9ca3af;">
            If the button above doesn\'t work, copy and paste this link into your browser:<br>
            <span style="color:#c4b5fd;">' . htmlspecialchars($resetLink) . '</span>
        </p>
        <p style="line-height:1.6;font-size:13px;color:#9ca3af;">
            Didn\'t request this? You can safely ignore this email -- your password won\'t change.
        </p>';

    return email_shell('Reset your password', $body);
}

function credentials_email_html(string $fullName, string $username, string $password, string $loginLink): string
{
    $body = '
        <p style="line-height:1.6;">Hi ' . htmlspecialchars($fullName) . ',</p>
        <p style="line-height:1.6;">
            Your email is confirmed and your LetsCode learning platform account is ready. Here are your login details:
        </p>
        <div style="background:#0f172a;border:1px solid #334155;border-radius:10px;padding:16px 20px;margin:20px 0;">
            <p style="margin:0 0 8px;"><strong>Username:</strong> ' . htmlspecialchars($username) . '</p>
            <p style="margin:0;"><strong>Password:</strong> ' . htmlspecialchars($password) . '</p>
        </div>
        <p style="text-align:center;margin:30px 0;">
            <a href="' . htmlspecialchars($loginLink) . '"
               style="background:#7c3aed;color:#ffffff;text-decoration:none;padding:14px 28px;border-radius:10px;display:inline-block;font-weight:bold;">
               Log in to LetsCode
            </a>
        </p>
        <p style="line-height:1.6;font-size:13px;color:#9ca3af;">
            We recommend changing your password after your first login.
        </p>';

    return email_shell('Your account is ready!', $body);
}
