<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 09/02/2025
 * @time 16:41
 */

namespace App\Models\Filters;

use Yajra\DataTables\EloquentDataTable;

class PaypalTransactionFilter
{
    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {

        return $dataTable;
    }
}
