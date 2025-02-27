<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 09/02/2025
 * @time 16:41
 */

namespace App\Models\Filters;

use App\Models\PaypalTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;

class PaypalTransactionFilter
{
    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable
    {
        return $dataTable
            ->searchPane('date', fn () => self::filterDate())
            ->searchPane('time', fn () => self::filterTime())
            ->searchPane('timezone', fn () => self::filterTimezone())
            ->searchPane('paygate_id', fn () => self::filterPaygateId())
            ->searchPane('name', fn () => self::filterName())
            ->searchPane('status', fn () => self::filterStatus())
            ->searchPane('currency', fn () => self::filterCurrency())
            ->searchPane('transaction_id', fn () => self::filterTransactionId())
            ->searchPane('invoice_number', fn () => self::filterInvoiceNumber())
            ->searchPane('balance', fn () => self::filterBalance())
            ->searchPane('closed_at', fn () => self::filterByClosedAt())
            ->searchPane('last_checked_at', fn () => self::filterByLastCheckedAt())
            ->searchPane('exported_at', fn () => self::filterByExportedAt())
            ->searchPane('created_at', fn () => self::filterByCreatedAt())
            ->searchPane('updated_at', fn () => self::filterByUpdatedAt());
    }

    private static function filterDate(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`date` as value, `date` as label, COUNT(*) as total'))
            ->groupBy('date')
            ->get();
    }

    private static function filterTime(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`time` as value, `time` as label, COUNT(*) as total'))
            ->groupBy('time')
            ->get();
    }

    private static function filterTimezone(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`timezone` as value, `timezone` as label, COUNT(*) as total'))
            ->groupBy('timezone')
            ->get();
    }

    private static function filterPaygateId(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`paygate_id` as value, `paygate_id` as label, COUNT(*) as total'))
            ->groupBy('paygate_id')
            ->get();
    }

    private static function filterName(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`name` as value, `name` as label, COUNT(*) as total'))
            ->groupBy('name')
            ->get();
    }

    private static function filterStatus(): Collection
    {
        $statuses = [
            'D' => ['label' => 'Denied', 'class' => 'badge-soft-danger'],
            'P' => ['label' => 'Pending', 'class' => 'badge-soft-info'],
            'S' => ['label' => 'Completed', 'class' => 'badge-soft-success'],
            'V' => ['label' => 'Refunded', 'class' => 'badge-soft-purple'],
        ];

        $collection = new Collection();

        foreach ($statuses as $key => $status) {
            $collection->push([
                'value' => $key,
                'label' => '<span class="badge ' . $status['class'] . '">' . $status['label'] . '</span>',
                'total' => PaypalTransaction::query()->where('status', $key)->count(),
            ]);
        }

        return $collection;
    }

    private static function filterCurrency(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`currency` as value, `currency` as label, COUNT(*) as total'))
            ->groupBy('currency')
            ->get();
    }

    private static function filterTransactionId(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`transaction_id` as value, `transaction_id` as label, COUNT(*) as total'))
            ->groupBy('transaction_id')
            ->get();
    }

    private static function filterInvoiceNumber(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`invoice_number` as value, `invoice_number` as label, COUNT(*) as total'))
            ->groupBy('invoice_number')
            ->get();
    }

    private static function filterBalance(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`balance` as value, `balance` as label, COUNT(*) as total'))
            ->groupBy('balance')
            ->get();
    }

    private static function filterByClosedAt(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`closed_at` as value, `closed_at` as label'))
            ->get();
    }

    private static function filterByLastCheckedAt(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`last_checked_at` as value, `last_checked_at` as label'))
            ->get();
    }

    private static function filterByExportedAt(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`exported_at` as value, `exported_at` as label'))
            ->get();
    }

    private static function filterByCreatedAt(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`created_at` as value, `created_at` as label'))
            ->get();
    }

    private static function filterByUpdatedAt(): Collection
    {
        return PaypalTransaction::query()
            ->select(DB::raw('`updated_at` as value, `updated_at` as label'))
            ->get();
    }
}
