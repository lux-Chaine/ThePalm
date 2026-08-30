<?php

namespace App\Core\Auth;

class JWT
{
    private static string $secret = 'your-secret-key-change-in-production';
    private static int $accessTokenExpiry = 3600; // 1 hour
    private static int $refreshTokenExpiry = 604800; // 7 days

    public static function encode(array $payload, int $expiry = null): string
    {
        if ($expiry === null) {
            $expiry = self::$accessTokenExpiry;
        }

        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);

        $payload['exp'] = time() + $expiry;
        $payload['iat'] = time();

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac(
            'sha256',
            $base64UrlHeader . "." . $base64UrlPayload,
            self::$secret,
            true
        );

        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function decode(string $token): ?array
    {
        $tokenParts = explode('.', $token);

        if (count($tokenParts) !== 3) {
            return null;
        }

        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $tokenParts;

        $header = json_decode(self::base64UrlDecode($base64UrlHeader), true);
        $payload = json_decode(self::base64UrlDecode($base64UrlPayload), true);

        if (!$header || !$payload) {
            return null;
        }

        // Verify signature
        $signature = hash_hmac(
            'sha256',
            $base64UrlHeader . "." . $base64UrlPayload,
            self::$secret,
            true
        );

        $expectedSignature = self::base64UrlDecode($base64UrlSignature);

        if (!hash_equals($signature, $expectedSignature)) {
            return null;
        }

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null;
        }

        return $payload;
    }

    public static function generateAccessToken(int $userId, string $email, string $role): string
    {
        return self::encode([
            'sub' => $userId,
            'email' => $email,
            'role' => $role
        ]);
    }

    public static function generateRefreshToken(int $userId): string
    {
        return self::encode([
            'sub' => $userId,
            'type' => 'refresh'
        ], self::$refreshTokenExpiry);
    }

    public static function validateToken(string $token): bool
    {
        return self::decode($token) !== null;
    }

    public static function getUserIdFromToken(string $token): ?int
    {
        $payload = self::decode($token);
        return $payload['sub'] ?? null;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function setSecret(string $secret): void
    {
        self::$secret = $secret;
    }

    public static function setAccessTokenExpiry(int $seconds): void
    {
        self::$accessTokenExpiry = $seconds;
    }

    public static function setRefreshTokenExpiry(int $seconds): void
    {
        self::$refreshTokenExpiry = $seconds;
    }
}
