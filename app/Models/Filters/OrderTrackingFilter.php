<?php

namespace App\Models\Filters;

use App\Models\OrderTracking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;

class OrderTrackingFilter
{

    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {
        return $dataTable
            ->searchPane('paygate_name', fn() => self::filterByColumn('paygate_name'))
            ->searchPane('invoice_number', fn() => self::filterByColumn('invoice_number'))
            ->searchPane('transaction_id', fn() => self::filterByColumn('transaction_id'))
            ->searchPane('has_tracking_number', fn() => self::filterTrackingNumber('has_tracking_number'))
            ->searchPane('courier_code', fn() => self::filterByColumn('courier_code'))
            ->searchPane('tracking_status', fn() => self::filterTrackingStatus())
            ->searchPane('type', fn() => self::filterByColumn('type'));
    }

    /**
     * Generic filter method for simple columns.
     */
    private static function filterByColumn(string $column): Collection
    {
        return OrderTracking::query()
            ->select(DB::raw("`$column` as value, `$column` as label, COUNT(*) as total"))
            ->groupBy($column)
            ->get();
    }

    /**
     * Filter order tracking records by tracking status.
     */
    private static function filterTrackingStatus(): Collection
    {
        $statuses = [
            'inforeceived' => 'Info Received',
            'transit'      => 'In Transit',
            'pickup'       => 'Out for Delivery',
            'undelivered'  => 'Failed Attempt',
            'delivered'    => 'Delivered',
            'exception'    => 'Exception',
            'expired'      => 'Expired',
            'notfound'     => 'Not Found',
            'pending'      => 'Pending',
        ];

        $collection = new Collection();

        foreach ($statuses as $key => $label) {
            $collection->push(
                (object)[
                    'value' => $key,
                    'label' => $label,
                    'total' => OrderTracking::where('tracking_status', $key)->count(),
                ]
            );
        }

        return $collection;
    }

    /**
     * Filter order tracking records by date.
     */
    private static function filterByDate(string $column): Collection
    {
        return OrderTracking::query()
            ->select(DB::raw("DATE(`$column`) as value, DATE(`$column`) as label, COUNT(*) as total"))
            ->groupBy(DB::raw("DATE(`$column`)"))
            ->get();
    }

    private static function filterTrackingNumber(string $column): Collection
    {
        return OrderTracking::query()
            ->select(
                DB::raw(
                    "`$column` as value,
        CASE `$column`
            WHEN 0 THEN 'Not tracked'
            WHEN 1 THEN 'Tracked'
            ELSE ''
        END as label, COUNT(*) as total"
                )
            )
            ->groupBy($column)
            ->get();
    }

}
