<?php
/**
 * @project gdt-cashier
 * @author hoep
 * @email hiepnguyen3624@gmail.com
 * @date 2025-03-31
 * @time 7:31 AM
 */

namespace App\Models\Filters;

use App\Models\RoleHierarchy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;

class RoleHierarchyFilter
{
    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {

        return $dataTable
            ->searchPane(
                'parent_role',
                fn () => self::filterRole(),
            );
    }

    /**
     * Filter permissions by role.
     */
    private static function filterRole(): Collection
    {
        return RoleHierarchy::query()
            ->select(DB::raw('`parent_role` as value, `parent_role` as label, COUNT(*) as total'))
            ->groupBy('parent_role')
            ->get();
    }
}
