<?php

namespace App\Models\Filters;

use Yajra\DataTables\EloquentDataTable;

class OrderTrackingFilter
{
    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {

        return $dataTable;
    }
}
