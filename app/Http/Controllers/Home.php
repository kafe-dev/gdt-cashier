<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Paygate as PaygateModel;
use App\Models\Transaction as TransactionModel;
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

        $openPaygate = count(
            PaygateModel::query()->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', PaygateModel::STATUS_ACTIVE)
                ->get()
        );
        $totalDisputes = count(
            DisputeModel::query()->whereBetween('created_at', [$startDate, $endDate])
                ->get()
        );
        $successOrders = count(
            OrderModel::query()->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', OrderModel::STATUS_PAID)
                ->get()
        );
        $totalRevenues = OrderModel::query()->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderModel::STATUS_PAID)
            ->sum('paid_amount');

        return view(
            'home.index',
            [
                'open_paygates' => $openPaygate,
                'total_disputes' => $totalDisputes,
                'success_orders' => $successOrders,
                'main_dispute_rate' => $this->getDisputeRate($startDate, $endDate),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'chartData' => $this->getRevenueChartData($startDate, $endDate),
                'total_revenue' => (float)$totalRevenues,
                'chartDataDispute' => $this->getChartDataDispute($startDate, $endDate),
                'dispute_reports' => $this->getDisputeReports($startDate, $endDate),
                'paygate_reports' => $this->getPaygateReports($startDate, $endDate),
            ]
        );
    }

    /**
     * Get dispute rate of all paygate
     *
     * @param $startDate
     * @param $endDate
     * @return float
     */
    private function getDisputeRate($startDate, $endDate): float
    {
        $transactionCount = count(TransactionModel::query()->whereBetween('created_at', [$startDate, $endDate])->get());
        $disputeCounts = count(DisputeModel::query()->whereBetween('created_at', [$startDate, $endDate])->get());

        if ($transactionCount === 0) {
            return 0.0;
        }

        return round(($disputeCounts / $transactionCount) * 100, 2);
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

        $allPaygates = PaygateModel::query()
            ->where('created_at', "<=", $endDate)
            ->orderBy('created_at', 'desc')
            ->get();

        $disputeCounts = DisputeModel::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('paygate_id')
            ->selectRaw('paygate_id, COUNT(*) as dispute_count')
            ->pluck('dispute_count', 'paygate_id')
            ->toArray();

        $transactionCount = TransactionModel::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('paygate_id')
            ->selectRaw('paygate_id, COUNT(*) as transaction_count')
            ->pluck('transaction_count', 'paygate_id')
            ->toArray();

        $revenue = OrderModel::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderModel::STATUS_PAID)
            ->groupBy('paygate_id')
            ->selectRaw('paygate_id, SUM(paid_amount) as revenue')
            ->pluck('revenue', 'paygate_id')
            ->toArray();

        foreach ($allPaygates as $paygate) {
            $paygateId = $paygate->id;

            $totalTransactions = array_key_exists($paygateId, $transactionCount) ? $transactionCount[$paygateId] : 0;
            $totalDisputes = array_key_exists($paygateId, $disputeCounts) ? $disputeCounts[$paygateId] : 0;
            $paygateRevenue = array_key_exists($paygateId, $revenue) ? $revenue[$paygateId] : 0;

            $paygateReports = [
                'id' => $paygateId,
                'revenue' => $paygateRevenue,
                'dispute_rate' => $totalTransactions > 0 ? round(($totalDisputes / $totalTransactions) * 100, 2) : 0,
                'total_disputes' => $totalDisputes,
                'total_transactions' => $totalTransactions,
                'limit' => $paygate->limitation,
                'type' => PaygateModel::TYPE[$paygate->type] ?? 'Unknown',
                'status' => PaygateModel::STATUS[$paygate->status] ?? 'Unknown',
                'created_at' => $paygate->created_at,
            ];

            $allPaygatesReports[] = $paygateReports;
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
            'labels' => [
                'Resolved',
                'Expired',
                'Closed',
                'Denied',
                'Open',
                'Waiting for seller response',
                'Waiting for buyer response',
                'Under review'
            ],
            'data' => [
                !empty($disputeCounts[DisputeModel::STATUS_RESOLVED]) ? round(
                    ($disputeCounts[DisputeModel::STATUS_RESOLVED] / $totalDisputes) * 100,
                    2
                ) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_EXPIRED]) ? round(
                    ($disputeCounts[DisputeModel::STATUS_EXPIRED] / $totalDisputes) * 100,
                    2
                ) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_CLOSED]) ? round(
                    ($disputeCounts[DisputeModel::STATUS_CLOSED] / $totalDisputes) * 100,
                    2
                ) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_DENIED]) ? round(
                    ($disputeCounts[DisputeModel::STATUS_DENIED] / $totalDisputes) * 100,
                    2
                ) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_OPEN]) ? round(
                    ($disputeCounts[DisputeModel::STATUS_OPEN] / $totalDisputes) * 100,
                    2
                ) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_WAITING_FOR_SELLER_RESPONSE]) ? round(
                    ($disputeCounts[DisputeModel::STATUS_WAITING_FOR_SELLER_RESPONSE] / $totalDisputes) * 100,
                    2
                ) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_WAITING_FOR_BUYER_RESPONSE]) ? round(
                    ($disputeCounts[DisputeModel::STATUS_WAITING_FOR_BUYER_RESPONSE] / $totalDisputes) * 100,
                    2
                ) : 0,
                !empty($disputeCounts[DisputeModel::STATUS_UNDER_REVIEW]) ? round(
                    ($disputeCounts[DisputeModel::STATUS_UNDER_REVIEW] / $totalDisputes) * 100,
                    2
                ) : 0,
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
