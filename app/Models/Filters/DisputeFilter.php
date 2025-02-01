<?php
declare(strict_types=1);

namespace App\Models\Filters;

use App\Models\Dispute;
use App\Models\Paygate;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;

/**
 * Class UserFilter.
 *
 * This class represents a filter for users.
 */
class DisputeFilter {

    /**
     * Perform the model filter.
     */
    public static function perform(EloquentDataTable $dataTable): EloquentDataTable {

        return $dataTable
            ->searchPane('paygate_id', fn() => self::filterByPaygateID())
            ->searchPane('dispute_id', fn() => self::filterByDisputeID())
            ->searchPane('status', fn() => self::filterByStatus())
            ->searchPane('dispute_amount_currency', fn() => self::filterByDisputeAmountCurrency());
    }

    /**
     * Filter disputes by paygate ID.
     *
     * @return Collection
     */
    private static function filterByPaygateID(): Collection {
        return Paygate::query()->select(DB::raw('`id` as value, `name` as label, COUNT(*) as total'))->groupBy('id')->get();
    }

    /**
     * Filter disputes by dispute ID.
     *
     * @return Collection
     */
    private static function filterByDisputeID(): Collection {
        return Dispute::query()->select(DB::raw('`dispute_id` as value, `dispute_id` as label, COUNT(*) as total'))->groupBy('dispute_id')->get();
    }

    /**
     * Filter disputes by status.
     *
     * @return Collection
     */
    private static function filterByStatus(): Collection {
        return Dispute::query()->select(DB::raw('`status` as value, `status` as label, COUNT(*) as total'))->groupBy('status')->get();
    }

    /**
     * Filter disputes by dispute amount currency.
     *
     * @return Collection
     */
    private static function filterByDisputeAmountCurrency(): Collection {
        return Dispute::query()->select(DB::raw('`dispute_amount_currency` as value, `dispute_amount_currency` as label, COUNT(*) as total'))->groupBy('dispute_amount_currency')->get();
    }
}
