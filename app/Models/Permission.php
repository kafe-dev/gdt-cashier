<?php

namespace App\Models;

use App\Enums\RoleHierarchy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission Model
 *
 * @property int $id
 * @property int $role
 * @property array|null $routes
 */
class Permission extends Model
{
    use HasFactory;

    public $table = 'permissions';
    public $timestamps = false;

    protected $fillable = [
        'role',
        'routes',
    ];

    protected function casts(): array
    {
        return [
            'role' => 'integer',
            'routes' => 'array',
        ];
    }

    /**
     * Get the list of routes allowed to access a role,
     * combined with the entire role hierarchy.
     *
     * @param int $role
     * @return array
     */
    public static function getAllowedRoutes(int $role): array
    {
        $allowedRoles = RoleHierarchy::getAllowedRoles($role);

        $routes = [];

        foreach ($allowedRoles as $allowedRole) {
            $permission = self::where('role', $allowedRole)->first();
            if ($permission) {
                $routes = array_merge($routes, $permission->routes);
            }
        }

        return array_unique($routes);
    }

    public static function getRoleAllowedRoutes(int $role): array|null
    {
        $permission = self::where('role', $role)->first();
        return $permission ? $permission->routes : [];
    }

    public static function getAllowedRoutesWithHierarchy(int $role): array
    {
        $roles = RoleHierarchy::$hierarchy[$role] ?? [];
        $allRoutes = [];

        foreach ($roles as $role) {
            $temp = self::getRoleAllowedRoutes($role);
            if ($temp) {
                $allRoutes = array_merge($allRoutes, $temp);
            }
        }

        return array_unique($allRoutes);
    }
}
