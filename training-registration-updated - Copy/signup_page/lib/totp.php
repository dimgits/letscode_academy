<?php
/**
 * Minimal TOTP (Time-based One-Time Password, RFC 6238) implementation.
 * Compatible with Google Authenticator, Authy, Microsoft Authenticator, etc.
 * No external libraries required.
 */

class TOTP
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /** Generate a random Base32 secret (for a new 2FA enrollment). */
    public static function generateSecret(int $length = 20): string
    {
        $secret = '';
        $bytes = random_bytes($length);
        for ($i = 0; $i < $length; $i++) {
            $secret .= self::BASE32_ALPHABET[ord($bytes[$i]) % 32];
        }
        return $secret;
    }

    private static function base32Decode(string $secret): string
    {
        $secret = strtoupper(preg_replace('/[^A-Z2-7]/', '', $secret));
        $bits = '';
        foreach (str_split($secret) as $char) {
            $val = strpos(self::BASE32_ALPHABET, $char);
            if ($val === false) continue;
            $bits .= str_pad(decbin($val), 5, '0', STR_PAD_LEFT);
        }

        $bytes = '';
        foreach (str_split($bits, 8) as $byte) {
            if (strlen($byte) === 8) {
                $bytes .= chr(bindec($byte));
            }
        }
        return $bytes;
    }

    /** Generate the 6-digit code for a given secret at a given time step. */
    public static function generateCode(string $secret, ?int $timestamp = null, int $period = 30): string
    {
        $timestamp ??= time();
        $counter = (int) floor($timestamp / $period);

        $binaryCounter = pack('N*', 0) . pack('N*', $counter);
        $key = self::base32Decode($secret);

        $hash = hash_hmac('sha1', $binaryCounter, $key, true);
        $offset = ord($hash[19]) & 0x0F;

        $truncated =
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF);

        $code = $truncated % 1000000;
        return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
    }

    /** Verify a submitted code, allowing +/- 1 time step of clock drift. */
    public static function verifyCode(string $secret, string $code, int $period = 30, int $window = 1): bool
    {
        $code = trim($code);
        $now = time();

        for ($i = -$window; $i <= $window; $i++) {
            $expected = self::generateCode($secret, $now + ($i * $period), $period);
            if (hash_equals($expected, $code)) {
                return true;
            }
        }

        return false;
    }

    /** otpauth:// URI for QR-code provisioning. */
    public static function provisioningUri(string $secret, string $accountName, string $issuer = 'LetsCode'): string
    {
        $label = rawurlencode($issuer) . ':' . rawurlencode($accountName);
        return 'otpauth://totp/' . $label .
            '?secret=' . rawurlencode($secret) .
            '&issuer=' . rawurlencode($issuer) .
            '&algorithm=SHA1&digits=6&period=30';
    }

    /** Image URL for rendering the QR code client-side (no server dependency). */
    public static function qrCodeUrl(string $otpauthUri, int $size = 200): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size .
            '&data=' . rawurlencode($otpauthUri);
    }
}
