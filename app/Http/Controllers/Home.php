<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Paygate as PaygateModel;
use App\Models\Store as StoreModel;
use App\Models\Order as OrderModel;
use App\Models\Dispute as DisputeModel;
use Carbon\Carbon;

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
                'chartData' => $this->getRevenueChartData($startDate, $endDate),
                'total_revenue' => (float)$totalRevenues,
                'chartDataDispute' => $this->getChartDataDispute($startDate, $endDate),
                'dispute_reports' => $this->getDisputeReports($startDate, $endDate),
                'paygate_reports' => $this->getPaygateReports($startDate, $endDate),
            ]);
    }

    private function getDisputeRate(): float
    {
        return $disputes = DisputeModel::query()->count();
    }

    /**
     * Get all Paygate report
     *
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getPaygateReports($startDate, $endDate): array
    {
        $allPaygatesReports = [];
        $paygateReports = [];

        $allPaygates = PaygateModel::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        if (!empty($allPaygates)) {
            foreach ($allPaygates as $paygate) {
                $paygateReports['revenue'] = 9000;
                $paygateReports['dispute_rate'] = 1.5;
                $paygateReports['id'] = $paygate->id;
                $paygateReports['limit'] = $paygate->limitation;
                $paygateReports['type'] = PaygateModel::TYPE[$paygate->type];
                $paygateReports['status'] = PaygateModel::STATUS[$paygate->status];
                $paygateReports['created_at'] = $paygate->created_at;
                $allPaygatesReports[] = $paygateReports;
            }
        }
        return $allPaygatesReports;
    }

    /**
     * Gets all dispute reports
     *
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getDisputeReports($startDate, $endDate): array
    {
        $disputeStats = DisputeModel::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->orderBy('date', 'desc')
            ->get();

        $allDates = [];
        $period = Carbon::parse($startDate)->toPeriod($endDate, '1 day');

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $allDates[$formattedDate] = [
                'under_review' => 0,
                'waiting_for_buyer' => 0,
                'waiting_for_seller' => 0,
                'open' => 0,
                'resolved' => 0,
                'denied' => 0,
                'closed' => 0,
                'expired' => 0,
            ];

        }

        $allStatuses = [
            'under_review' => DisputeModel::STATUS_UNDER_REVIEW,
            'waiting_for_buyer' => DisputeModel::STATUS_WAITING_FOR_BUYER_RESPONSE,
            'waiting_for_seller' => DisputeModel::STATUS_WAITING_FOR_SELLER_RESPONSE,
            'open' => DisputeModel::STATUS_OPEN,
            'resolved' => DisputeModel::STATUS_RESOLVED,
            'denied' => DisputeModel::STATUS_DENIED,
            'closed' => DisputeModel::STATUS_CLOSED,
            'expired' => DisputeModel::STATUS_EXPIRED,
        ];

        foreach ($disputeStats as $stat) {
            $date = $stat->date;
            $status = DisputeModel::STATUSES[$stat->status];
            $count = $stat->count;

            foreach ($allStatuses as $key => $value) {
                if ($status == $value) {
                    $allDates[$date][$key] += $count;
                }
            }
        }

        return $allDates;
    }

    /**
     * Get chart data dispute
     *
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getChartDataDispute($startDate, $endDate): array
    {
        $disputeCounts = DisputeModel::query()
            ->selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalDisputes = array_sum($disputeCounts);


        return [
            'labels' => ['Resolved', 'Expired', 'Closed', 'Denied', 'Open', 'Waiting for seller response', 'Waiting for buyer response', 'Under review'],
            'data' => [
                !empty($disputeCounts[DisputeModel::STATUS_RESOLVED]) ? round(($disputeCounts[DisputeModel::STATUS_RESOLVED] / $totalDisputes) * 100, 2) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_EXPIRED]) ? round(($disputeCounts[DisputeModel::STATUS_EXPIRED] / $totalDisputes) * 100, 2) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_CLOSED]) ? round(($disputeCounts[DisputeModel::STATUS_CLOSED] / $totalDisputes) * 100, 2) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_DENIED]) ? round(($disputeCounts[DisputeModel::STATUS_DENIED] / $totalDisputes) * 100, 2) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_OPEN]) ? round(($disputeCounts[DisputeModel::STATUS_OPEN] / $totalDisputes) * 100, 2) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_WAITING_FOR_SELLER_RESPONSE]) ? round(($disputeCounts[DisputeModel::STATUS_WAITING_FOR_SELLER_RESPONSE] / $totalDisputes) * 100, 2) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_WAITING_FOR_BUYER_RESPONSE]) ? round(($disputeCounts[DisputeModel::STATUS_WAITING_FOR_BUYER_RESPONSE] / $totalDisputes) * 100, 2) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_UNDER_REVIEW]) ? round(($disputeCounts[DisputeModel::STATUS_UNDER_REVIEW] / $totalDisputes) * 100, 2) : 0,
            ],
        ];
    }

    /**
     * Get chart data revenue
     *
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getRevenueChartData($startDate, $endDate): array
    {
        $revenues = OrderModel::query()->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderModel::STATUS_PAID)
            ->selectRaw('DATE(created_at) as date, SUM(paid_amount) as total_revenue')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        return [
            'categories' => $revenues->pluck('date')->toArray(),
            'data' => $revenues->pluck('total_revenue')->toArray(),
        ];
    }
}
