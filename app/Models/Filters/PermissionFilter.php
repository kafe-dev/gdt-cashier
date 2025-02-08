<?php

namespace App\Models\Filters;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;

class PermissionFilter
{
    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {

        return $dataTable
            ->searchPane(
                'role',
                fn () => self::filterRole(),
            );
    }

    /**
     * Filter permissions by role.
     */
    private static function filterRole(): Collection
    {
        return Permission::query()
            ->select(DB::raw('`role` as value, `role` as label, COUNT(*) as total'))
            ->groupBy('role')
            ->get();
    }
}
