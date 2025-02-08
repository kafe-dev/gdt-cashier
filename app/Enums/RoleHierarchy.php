<?php

namespace App\Enums;

use App\Models\User;

class RoleHierarchy
{
    public static array $hierarchy = [
        User::ROLE_ADMIN => [User::ROLE_SUB_ADMIN, User::ROLE_SUPPORT, User::ROLE_ACCOUNTANT, User::ROLE_SELLER, User::ROLE_USER],
        User::ROLE_SUB_ADMIN => [User::ROLE_SUPPORT, User::ROLE_ACCOUNTANT, User::ROLE_SELLER, User::ROLE_USER],
        User::ROLE_SUPPORT => [User::ROLE_USER],
        User::ROLE_ACCOUNTANT => [User::ROLE_USER],
        User::ROLE_SELLER => [User::ROLE_USER],
        User::ROLE_USER => [],
    ];

    public static function getAllowedRoles(mixed $role): array
    {
        return array_merge([$role], self::$hierarchy[$role] ?? []);
    }
}
