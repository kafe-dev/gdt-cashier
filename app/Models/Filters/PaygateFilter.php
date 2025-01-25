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
class PaygateFilter
{
    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {
        return $dataTable
            ->searchPane(
                'name',
                fn () => self::filterUsername(),
            )
            ->searchPane(
                'email',
                fn () => self::filterEmail(),
            );
    }

    /**
     * Filter users by username.
     */
    private static function filterUsername(): Collection
    {
        return User::query()
            ->select(DB::raw('`name` as value, `name` as label, COUNT(*) as total'))
            ->groupBy('name')
            ->get();
    }

    /**
     * Filter users by email.
     */
    private static function filterEmail(): Collection
    {
        return User::query()
            ->select(DB::raw('`email` as value, `email` as label, COUNT(*) as total'))
            ->groupBy('email')
            ->get();
    }
}
