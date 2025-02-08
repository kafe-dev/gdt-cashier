<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\TimeHelper;
use App\Http\Middlewares\Auth;
use App\Paygate\PayPalAPI;
use App\Services\DataTables\DisputeDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Authen;
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
        $this->filterDateRange($dataTable);
        return $dataTable->render('dispute.index');
        //        $clientId     = 'AfGFZ63l-30heXk1Xf2iNiO0SnhhIKeaEq9uIsqQt4kPenxBk_ZNwFhLTDDRDsX1bdV8_uVTMPnBgLnK';
        //        $clientSecret = "EECgn7P9B5dgKFFvQWFQ6AH0AGqmm1ibbl7G_7njz59SKX-EKvZWCeY9beP-a8TU64WoC6FwPqdreAak";
        //        $paypal       = new PayPalAPI($clientId, $clientSecret, true);
        //        $response     = $paypal->provideSupportingInfo("PP-R-GQM-10106357", "Additional supporting details for the dispute.");
        //        echo "<pre>";
        //        print_r($response);
    }

    /**
     * Action `show`.
     *
     * @param int|string $id Dispute ID to show
     *
     * @throws \Exception
     */
    public function show(int|string $id): View {
        $dispute         = \App\Models\Dispute::findOrFail($id);
        $paygate         = \App\Models\Paygate::findOrFail($dispute->paygate_id);
        $paypalApi       = new PayPalAPI($paygate);
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
        $timestamp = TimeHelper::getStartAndEndOfDay($create_time);
        //        echo '<pre>';
        //        print_r($dispute_arr);
        //        die;
        $transaction_arr = $paypalApi->listTransaction($timestamp['start'], $timestamp['end'], $seller_transaction_id);
        return view('dispute.show', compact('dispute_arr', 'dispute', 'transaction_arr'));
    }

    /**
     * Action `store`.
     *
     * @param Request $request Illuminate request object
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function sendMessage(Request $request): RedirectResponse {
        $input        = $request->all();
        $paygate_id   = $input['paygate_id'] ?? '';
        $dispute_id   = $input['dispute_id'] ?? '';
        $dispute_code = $input['dispute_code'] ?? '';
        $message      = $input['message'] ?? '';
        $paygate      = \App\Models\Paygate::find($paygate_id);
        $paypalApi    = new PayPalAPI($paygate);
        $result       = $paypalApi->sendDisputeMessage($dispute_code, $message);
        return redirect()->route('app.dispute.show', ['id' => $dispute_id]);
    }
}
