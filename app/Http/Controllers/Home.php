<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Paygate as PaygateModel;
use App\Models\Store as StoreModel;
use App\Models\Order as OrderModel;
use App\Models\Dispute as DisputeModel;

/**
 * Class Home.
 *
 * This is the default controller for the application.
 */
class Home extends BaseController
{
    /**
     * Action `index`.
     *
     * Renders the app homepage.
     */
    public function index(Request $request): View
    {
        $startDate = $request->query('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->format('Y-m-d'));

        $openPaygate = count(PaygateModel::query()->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', PaygateModel::STATUS_ACTIVE)
            ->get());
        $liveStores = count(StoreModel::query()->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', StoreModel::STATUS_ACTIVE)
            ->get());
        $successOrders = count(OrderModel::query()->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderModel::STATUS_PAID)
            ->get());
        {
            $revenues = OrderModel::query()->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', OrderModel::STATUS_PAID)
                ->selectRaw('DATE(created_at) as date, SUM(paid_amount) as total_revenue')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
            $chartData = [
                'categories' => $revenues->pluck('date')->toArray(),
                'data' => $revenues->pluck('total_revenue')->toArray(),
            ];
        }
        $totalRevenues = OrderModel::query()->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderModel::STATUS_PAID)
            ->sum('paid_amount');

        $disputeRate = $this->getDisputeRate();

        return view('home.index',
            [
                'open_paygates' => $openPaygate,
                'live_stores' => $liveStores,
                'success_orders' => $successOrders,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'chartData' => $chartData,
                'total_revenue' => (float)$totalRevenues,
            ]);
    }

    private function getDisputeRate(): float
    {
        return $disputes = DisputeModel::query()->count();
    }
}
