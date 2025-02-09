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
use Illuminate\Support\Facades\Storage;

/**
 * Class Dispute.
 *
 * This controller is responsible for managing dispute-related operations.
 */
class Dispute extends BaseController
{

    /**
     * Action `index`.
     */
    public function index(DisputeDataTable $dataTable)
    {
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
    public function show(int|string $id): View
    {
        $dispute = \App\Models\Dispute::findOrFail($id);
        $paygate = \App\Models\Paygate::findOrFail($dispute->paygate_id);
        $paypalApi = new PayPalAPI($paygate);
        $dispute_arr = $paypalApi->getDisputeDetails($dispute->dispute_id);
        $transactionData = $dispute_arr['disputed_transactions'][0] ?? null;
        if (!$transactionData) {
            abort(404, 'No transaction data found');
        }
        $seller_transaction_id = $transactionData['seller_transaction_id'] ?? null;
        $create_time = $transactionData['create_time'] ?? null;
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
    public function sendMessage(Request $request): RedirectResponse
    {
        $input = $request->only([
            'paygate_id',
            'dispute_id',
            'dispute_code',
            'message',
        ]);
        $paygate = \App\Models\Paygate::find($input['paygate_id'] ?? null);
        if (!$paygate) {
            return redirect()->back()->with('error', 'Paygate không tồn tại.');
        }
        $paypalApi = new PayPalAPI($paygate);
        $result = $paypalApi->sendDisputeMessage($input['dispute_code'] ?? '', $input['message'] ?? '');
        if (!$result) {
            return redirect()->back()->with('error', 'Không thể gửi tin nhắn dispute.');
        }
        return redirect()->route('app.dispute.show', ['id' => $input['dispute_id'] ?? null])->with('success', 'Tin nhắn dispute đã được gửi thành công.');
    }

    /**
     * Make an offer to resolve a PayPal dispute.
     *
     * @param Request $request The HTTP request object containing offer details.
     * @param int|string $id The dispute ID from the database.
     * @return RedirectResponse Redirects to the dispute details page.
     */
    public function makeOffer(Request $request, int|string $id): RedirectResponse
    {
        try {
            $dispute = \App\Models\Dispute::findOrFail($id);
            $offerType = strtoupper($request->validate(['offer_type' => 'required|string'])['offer_type']);
            $data = $this->getValidatedMakeOfferData($request, $offerType);

            $amount = $data['amount'] ?? null;
            $currency = $data['currency'] ?? 'USD';
            $note = $data['note'];
            $invoiceId = $data['invoice_id'] ?? null;
            $returnAddress = [];

            if (!empty($data['address']) && !empty($data['country_code'])) {
                $returnAddress[] = [
                    'address_line_1' => $data['address'],
                    'country_code' => $data['country_code']
                ];
            }


            $PaypalApi = $this->getPaypalApiByDisputeId($id);
            $response = $PaypalApi->getDisputeDetails($dispute->dispute_id);

            $response = $PaypalApi->makeOfferToResolveDispute($dispute->dispute_id, $offerType, $note, (float)$amount, $currency, $invoiceId, $returnAddress);
            flash()->success('Make offer successful!');
        } catch (\Exception $e) {
            return redirect()->route('app.dispute.show', ['id' => $id]);
        }
        return redirect()->route('app.dispute.show', ['id' => $id]);
    }

    public function acknowledgeReturned(Request $request, int|string $id)
    {
        try {
            $status = $request->validate(['item_status' => 'required|in:NORMAL,ISSUE'])['item_status'];
            $data = $request->validate([
                'note' => 'nullable|string|max:2000',
                'evidence_type' => [($status === "ISSUE" ? 'required' : 'nullable'), 'in:PROOF_OF_DAMAGE,THIRDPARTY_PROOF_FOR_DAMAGE_OR_SIGNIFICANT_DIFFERENCE,DECLARATION,PROOF_OF_MISSING_ITEMS,PROOF_OF_EMPTY_PACKAGE_OR_DIFFERENT_ITEM,PROOF_OF_ITEM_NOT_RECEIVED'],
                'documents.*' => [($status === "ISSUE" ? 'required' : 'nullable'), 'mimes:jpg,jpeg,png,pdf|max:50MB'],
            ]);

            $documents = [];

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    //app/public/uploads
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = 'uploads/' . $filename;
                    $file->move(public_path('uploads'), $filename);
                    $documents[] = [
                        'name' => $file->getClientOriginalName(),
                        'url' => asset($path),
                    ];
                }
            }

            // this form only post 1 evidences
            $allEvidences = [];
            $evidences = [];
            if ($status === "ISSUE") {
                $evidences = [
                    'evidence_type' => $data['evidence_type'] ?? null,
                    'documents' => $documents ?? null,
                ];
            }
            $allEvidences[] = $evidences;
            $dispute = \App\Models\Dispute::findOrFail($id);
            $PaypalApi = $this->getPaypalApiByDisputeId($id);

            $response = $PaypalApi->acknowledgeReturnedItem($dispute->dispute_id, $data['note'], $allEvidences);
            flash()->success('Acknowledge Returned successful!');
        } catch (\Exception $e) {
            return redirect()->route('app.dispute.show', ['id' => $id]);
        }
        return redirect()->route('app.dispute.show', ['id' => $id]);
    }

    /**
     * Get All PaypalApi by Pay gate ID
     *
     * @param int|string $id
     * @return PayPalAPI
     * @throws \Exception
     */
    private function getPaypalApiByDisputeId(int|string $id): PayPalAPI
    {
        $dispute = \App\Models\Dispute::findOrFail($id);
        $paygate = \App\Models\Paygate::findOrFail($dispute->paygate_id);
        return new PayPalAPI($paygate);
    }

    /**
     * get all validated offer data
     *
     * @param Request $request
     * @param $offerType
     * @return array
     */
    private function getValidatedMakeOfferData(Request $request, $offerType): array
    {
        return $request->validate([
            'amount' => [
                'numeric',
                ($offerType !== "REPLACEMENT_WITHOUT_REFUND" ? 'required' : 'nullable'),
            ],
            'currency' => [
                ($offerType !== "REPLACEMENT_WITHOUT_REFUND" ? 'required' : 'nullable'),
                'string', 'size:3'
            ],
            'note' => 'required|string|max:2000',
            'invoice_id' => 'nullable|string|max:127',
            'address' => [
                'string', 'max:300',
                ($offerType === "REFUND_WITH_RETURN" ? 'required' : 'nullable'),
            ],
            'country_code' => [
                'string', 'size:2',
                ($offerType === "REFUND_WITH_RETURN" ? 'required' : 'nullable'),
            ],
        ]);
    }

}
