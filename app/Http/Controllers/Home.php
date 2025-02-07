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
        {
            $disputeCounts = DisputeModel::query()
                ->selectRaw('status, COUNT(*) as count')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $totalDisputes = array_sum($disputeCounts);

            $statusPending = (
                ($disputeCounts[DisputeModel::STATUS_UNDER_REVIEW] ?? 0) +
                ($disputeCounts[DisputeModel::STATUS_WAITING_FOR_BUYER_RESPONSE] ?? 0) +
                ($disputeCounts[DisputeModel::STATUS_WAITING_FOR_SELLER_RESPONSE] ?? 0) +
                ($disputeCounts[DisputeModel::STATUS_OPEN] ?? 0)
            );

            $statusFailed = (
                ($disputeCounts[DisputeModel::STATUS_DENIED] ?? 0) +
                ($disputeCounts[DisputeModel::STATUS_CLOSED] ?? 0) +
                ($disputeCounts[DisputeModel::STATUS_EXPIRED] ?? 0)
            );

            $statusResolved = ($disputeCounts[DisputeModel::STATUS_RESOLVED] ?? 0);

            $chartDataDispute = [
                'labels' => ['Resolved', 'Pending', 'Failed'],
                'data' => [
                    $totalDisputes > 0 ? round(($statusResolved / $totalDisputes) * 100, 2) : 0,
                    $totalDisputes > 0 ? round(($statusPending / $totalDisputes) * 100, 2) : 0,
                    $totalDisputes > 0 ? round(($statusFailed / $totalDisputes) * 100, 2) : 0,
                ],
            ];
        }
        return view('home.index',
            [
                'open_paygates' => $openPaygate,
                'live_stores' => $liveStores,
                'success_orders' => $successOrders,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'chartData' => $chartData,
                'total_revenue' => (float)$totalRevenues,
                'chartDataDispute' => $chartDataDispute,
            ]);
    }

    private function getDisputeRate(): float
    {
        return $disputes = DisputeModel::query()->count();
    }
}
