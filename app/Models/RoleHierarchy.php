<?php
/**
 * @project gdt-cashier
 * @author hoep
 * @email hiepnguyen3624@gmail.com
 * @date 2025-03-26
 * @time 7:49 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHierarchy extends Model
{
    protected $table = 'role_hierarchies';
    protected $fillable = [
        'parent_role',
        'child_role',
    ];

    public static function getAllowedRoles(int $role): array
    {
        $childRoles = self::where('parent_role', $role)
            ->pluck('child_role')
            ->toArray();

        return array_merge([$role], $childRoles);
    }

    public static function getAllowedRoutesWithHierarchy(int $role): array
    {
        $childRoles = self::where('parent_role', $role)
            ->pluck('child_role')
            ->toArray();

        $allRoutes = [];
        foreach ($childRoles as $childRole) {
            $temp = Permission::getRoleAllowedRoutes($childRole);
            if ($temp) {
                $allRoutes = array_merge($allRoutes, $temp);
            }
        }

        return array_unique($allRoutes);
    }
}
