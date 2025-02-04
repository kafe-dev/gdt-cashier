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
class TransactionFilter
{
    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {

        return $dataTable;
    }

}
