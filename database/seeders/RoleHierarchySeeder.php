<?php
/**
 * @project gdt-cashier
 * @author hoep
 * @email hiepnguyen3624@gmail.com
 * @date 2025-03-26
 * @time 7:51 AM
 */

namespace Database\Seeders;

use App\Models\RoleHierarchy;
use Illuminate\Database\Seeder;

class RoleHierarchySeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['role' => 'admin', 'allowed_roles' => ['sub_admin', 'support', 'accountant', 'seller', 'user']],
            ['role' => 'sub_admin', 'allowed_roles' => ['support', 'accountant', 'seller', 'user']],
            ['role' => 'support', 'allowed_roles' => ['user']],
            ['role' => 'accountant', 'allowed_roles' => ['user']],
            ['role' => 'seller', 'allowed_roles' => ['user']],
            ['role' => 'user', 'allowed_roles' => []],
        ];

        foreach ($roles as $role) {
            RoleHierarchy::updateOrCreate(['role' => $role['role']], $role);
        }
    }
}
