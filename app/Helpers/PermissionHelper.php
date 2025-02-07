<?php

namespace App\Helpers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if user has access to route.
     */
    public static function hasAccess(string $routeName): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        $allowedRoutes = Permission::getAllowedRoutes($user->role);

        return in_array($routeName, $allowedRoutes);
    }
}
