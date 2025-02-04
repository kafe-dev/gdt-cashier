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

        return view('home.index',
            [
                'open_paygates' => count(PaygateModel::query()->where('status', PaygateModel::STATUS_ACTIVE)->get()),
                'live_stores' => count(StoreModel::query()->where('status', StoreModel::STATUS_ACTIVE)->get()),
                'success_orders' => count(OrderModel::query()->where('status', OrderModel::STATUS_PAID)->get()),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'chartData' => $chartData,
            ]);
    }

    private function getDisputeRate(): float
    {
        return $disputes = DisputeModel::query()->count();
    }
}
