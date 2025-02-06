<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\TimeHelper;
use App\Paygate\PayPalAPI;
use App\Services\DataTables\DisputeDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class Dispute.
 *
 * This controller is responsible for managing dispute-related operations.
 */
class Dispute extends BaseController {

    /**
     * Action `index`.
     */
    public function index(DisputeDataTable $dataTable) {
        //        $this->filterDateRange($dataTable);
        //
        //        return $dataTable->render('dispute.index');
        $clientId     = 'AfGFZ63l-30heXk1Xf2iNiO0SnhhIKeaEq9uIsqQt4kPenxBk_ZNwFhLTDDRDsX1bdV8_uVTMPnBgLnK';
        $clientSecret = "EECgn7P9B5dgKFFvQWFQ6AH0AGqmm1ibbl7G_7njz59SKX-EKvZWCeY9beP-a8TU64WoC6FwPqdreAak";
        $paypal       = new PayPalAPI($clientId, $clientSecret, true);
        $response     = $paypal->provideSupportingInfo("PP-R-GQM-10106357", "Additional supporting details for the dispute.");
        echo "<pre>";
        print_r($response);
    }

    /**
     * Action `show`.
     *
     * @param int|string $id Dispute ID to show
     *
     * @throws \Exception
     */
    public function show(int|string $id): View {
        $dispute = \App\Models\Dispute::findOrFail($id);
        $paygate = \App\Models\Paygate::findOrFail($dispute->paygate_id);
        $api_data = $paygate->api_data ?? [];
        if (!isset($api_data['client_key'], $api_data['secret_key'])) {
            abort(500, 'Missing API credentials');
        }
        $paypalApi = new PayPalAPI($api_data['client_key'], $api_data['secret_key'], $paygate->mode == 0 // true = sandbox mode
        );
        $dispute_arr     = $paypalApi->getDisputeDetails($dispute->dispute_id);
        $transactionData = $dispute_arr['disputed_transactions'][0] ?? null;
        if (!$transactionData) {
            abort(404, 'No transaction data found');
        }
        $seller_transaction_id = $transactionData['seller_transaction_id'] ?? null;
        $create_time           = $transactionData['create_time'] ?? null;
        if (!$seller_transaction_id || !$create_time) {
            abort(500, 'Invalid dispute transaction data');
        }
        $timestamp       = TimeHelper::getStartAndEndOfDay($create_time);
        $transaction_arr = $paypalApi->listTransaction($timestamp['start'], $timestamp['end'], $seller_transaction_id);
        return view('dispute.show', compact('dispute_arr', 'dispute', 'transaction_arr'));
    }

    /**
     * Action `delete`.
     *
     * @param int|string $id      Dispute ID to delete
     * @param Request    $request Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse {
        //
    }
}
