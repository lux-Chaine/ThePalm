<?php

namespace App\Core\Middleware;

use App\Core\Auth\JWT;
use App\Core\Exceptions\UnauthorizedException;

class JWTMiddleware
{
    /**
     * Handle JWT authentication
     */
    public static function handle(): array
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (!$authHeader) {
            throw new UnauthorizedException('Authorization header missing');
        }

        // Extract token from Bearer header
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            throw new UnauthorizedException('Invalid authorization header format');
        }

        $token = $matches[1];

        try {
            $payload = JWT::decode($token);

            if (!$payload) {
                throw new UnauthorizedException('Invalid token');
            }

            // Check if token is expired
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                throw new UnauthorizedException('Token expired');
            }

            // Return user data from token
            return [
                'user_id' => $payload['sub'] ?? null,
                'email' => $payload['email'] ?? null,
                'role' => $payload['role'] ?? null,
                'token_type' => $payload['type'] ?? 'access',
            ];
        } catch (\Exception $e) {
            throw new UnauthorizedException('Token validation failed: ' . $e->getMessage());
        }
    }

    /**
     * Get authenticated user ID
     */
    public static function getUserId(): int
    {
        $userData = self::handle();
        return $userData['user_id'];
    }

    /**
     * Get authenticated user role
     */
    public static function getUserRole(): string
    {
        $userData = self::handle();
        return $userData['role'];
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole(string $role): bool
    {
        $userData = self::handle();
        return $userData['role'] === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public static function hasAnyRole(array $roles): bool
    {
        $userData = self::handle();
        return in_array($userData['role'], $roles);
    }

    /**
     * Validate refresh token
     */
    public static function validateRefreshToken(string $token): array
    {
        try {
            $payload = JWT::decode($token);

            if (!$payload) {
                throw new UnauthorizedException('Invalid refresh token');
            }

            if (($payload['type'] ?? '') !== 'refresh') {
                throw new UnauthorizedException('Not a refresh token');
            }

            if (isset($payload['exp']) && $payload['exp'] < time()) {
                throw new UnauthorizedException('Refresh token expired');
            }

            return [
                'user_id' => $payload['sub'] ?? null,
            ];
        } catch (\Exception $e) {
            throw new UnauthorizedException('Refresh token validation failed');
        }
    }
}
