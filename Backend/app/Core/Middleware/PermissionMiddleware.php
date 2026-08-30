<?php

namespace App\Core\Middleware;

use App\Core\Auth\JWT;
use App\Core\Exceptions\UnauthorizedException;
use App\Core\Exceptions\ForbiddenException;

class PermissionMiddleware
{
    /**
     * Check if user has required permission
     */
    public static function check(string $requiredPermission): void
    {
        // First validate JWT token
        $userData = JWTMiddleware::handle();
        
        $userId = $userData['user_id'];
        $role = $userData['role'];

        // Role-based permission mapping
        $rolePermissions = self::getRolePermissions();

        $userPermissions = $rolePermissions[$role] ?? [];

        if (!in_array($requiredPermission, $userPermissions) && !in_array('*', $userPermissions)) {
            throw new ForbiddenException("Insufficient permissions. Required: {$requiredPermission}");
        }
    }

    /**
     * Get permissions by role
     */
    private static function getRolePermissions(): array
    {
        return [
            'admin' => ['*'], // All permissions
            'manager' => [
                'users.view',
                'users.manage',
                'rooms.view',
                'rooms.manage',
                'reservations.view',
                'reservations.manage',
                'guests.view',
                'guests.manage',
                'invoices.manage',
                'expenses.view',
                'expenses.create',
                'expenses.manage',
                'reports.view',
                'settings.manage',
            ],
            'receptionist' => [
                'rooms.view',
                'reservations.view',
                'reservations.manage',
                'guests.view',
                'guests.manage',
                'invoices.manage',
            ],
            'accountant' => [
                'invoices.manage',
                'expenses.view',
                'expenses.create',
                'expenses.manage',
                'reports.view',
            ],
            'housekeeping' => [
                'rooms.view',
            ],
        ];
    }

    /**
     * Check if user has any of the given permissions
     */
    public static function hasAnyPermission(array $permissions): bool
    {
        $userData = JWTMiddleware::handle();
        $role = $userData['role'];
        
        $rolePermissions = self::getRolePermissions();
        $userPermissions = $rolePermissions[$role] ?? [];

        // Admin has all permissions
        if (in_array('*', $userPermissions)) {
            return true;
        }

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public static function hasAllPermissions(array $permissions): bool
    {
        $userData = JWTMiddleware::handle();
        $role = $userData['role'];
        
        $rolePermissions = self::getRolePermissions();
        $userPermissions = $rolePermissions[$role] ?? [];

        // Admin has all permissions
        if (in_array('*', $userPermissions)) {
            return true;
        }

        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get current user's permissions
     */
    public static function getUserPermissions(): array
    {
        $userData = JWTMiddleware::handle();
        $role = $userData['role'];
        
        $rolePermissions = self::getRolePermissions();
        return $rolePermissions[$role] ?? [];
    }
}
