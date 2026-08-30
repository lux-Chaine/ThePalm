<?php

namespace App\Core\Middleware;

use App\Models\User;
use Closure;

class PermissionMiddleware
{
    public function handle($request, Closure $next, string $permission)
    {
        // Get user from token (simplified version)
        $token = $request->header('Authorization');
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized - No token provided'
            ], 401);
        }

        // Parse token (simplified - in production use JWT)
        $token = str_replace('Bearer ', '', $token);
        $tokenData = explode(':', base64_decode($token));
        
        if (count($tokenData) < 2) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid token'
            ], 401);
        }

        $userId = $tokenData[0];
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'User not found'
            ], 401);
        }

        // Check permission
        if (!$user->hasPermission($permission)) {
            return response()->json([
                'success' => false,
                'error' => 'Forbidden - Insufficient permissions',
                'required_permission' => $permission,
                'user_permissions' => $user->getPermissions()
            ], 403);
        }

        // Add user to request for controllers
        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}
