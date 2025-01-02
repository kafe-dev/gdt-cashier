<?php

declare(strict_types=1);

namespace App\Models\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class UserFilter.
 *
 * This class represents a filter for users.
 */
class UserFilter
{

    /**
     * Perform the model filter.
     *
     * @param  EloquentDataTable  $dataTable
     *
     * @return EloquentDataTable
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {
        return $dataTable
            ->searchPane(
                'username',
                fn() => self::filterUsername(),
            )
            ->searchPane(
                'email',
                fn() => self::filterEmail(),
            )
            ->searchPane(
                'role',
                fn() => self::filterRole(),
            )
            ->searchPane(
                'registration_ip',
                fn() => self::filterRegistrationIp(),
            )
            ->searchPane(
                'status',
                fn() => self::filterStatus(),
            )
            ->searchPane(
                'last_login_at',
                fn() => self::filterByLastLoginAt(),
            );
    }

    /**
     * Filter users by username.
     *
     */
    private static function filterUsername(): Collection
    {
        return User::query()
                   ->select(DB::raw('`username` as value, `username` as label, COUNT(*) as total'))
                   ->groupBy('username')
                   ->get();
    }

    /**
     * Filter users by email.
     *
     * @return Collection
     */
    private static function filterEmail(): Collection
    {
        return User::query()
                   ->select(DB::raw('`email` as value, `email` as label, COUNT(*) as total'))
                   ->groupBy('email')
                   ->get();
    }

    /**
     * Filter users by role.
     *
     * @return array
     */
    private static function filterRole(): array
    {
        $collection = [];

        foreach (User::ROLES as $key => $value) {
            $data['value'] = $key;
            $data['label'] = $value;
            $data['total'] = User::where('role', $key)->count();

            $collection[] = $data;
        }

        return $collection;
    }

    /**
     * Filter users by registration IP.
     *
     * @return Collection
     */
    private static function filterRegistrationIp(): Collection
    {
        return User::query()
                   ->select(DB::raw('`registration_ip` as value, `registration_ip` as label, COUNT(*) as total'))
                   ->groupBy('registration_ip')
                   ->get();
    }

    /**
     * Filter users by status.
     *
     * @return array
     */
    private static function filterStatus(): array
    {
        $collection = [];

        foreach (User::STATUSES as $key => $value) {
            $data['value'] = $key;
            $data['label'] = $value;
            $data['total'] = User::where('status', $key)->count();

            $collection[] = $data;
        }

        return $collection;
    }

    /**
     * Filter users by last login at.
     *
     * @return Collection
     */
    private static function filterByLastLoginAt(): Collection
    {
        return User::query()
                   ->select(DB::raw('`last_login_at` as value, `last_login_at` as label'))
                   ->get();
    }

}
