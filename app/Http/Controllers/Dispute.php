<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Logs;
use App\Helpers\TimeHelper;
use App\Http\Middlewares\Auth;
use App\Paygate\PayPalAPI;
use App\Services\DataTables\DisputeDataTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Authen;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\json;

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
        //TODO cần truyền tham số theo reason của dispute.
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
        $input   = $request->only([
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
        $result    = $paypalApi->sendDisputeMessage($input['dispute_code'] ?? '', $input['message'] ?? '');
        if (!$result) {
            return redirect()->back()->with('error', 'Không thể gửi tin nhắn dispute.');
        }
        return redirect()->route('app.dispute.show', ['id' => $input['dispute_id'] ?? null])->with('success', 'Tin nhắn dispute đã được gửi thành công.');
    }

    /**
     * Make an offer to resolve a PayPal dispute.
     *
     * @param Request    $request The HTTP request object containing offer details.
     * @param int|string $id      The dispute ID from the database.
     *
     * @return RedirectResponse Redirects to the dispute details page.
     */
    public function makeOffer(Request $request, int|string $id): RedirectResponse {
        try {
            $dispute       = \App\Models\Dispute::findOrFail($id);
            $offerType     = strtoupper($request->validate(['offer_type' => 'required|string'])['offer_type']);
            $data          = $this->getValidatedMakeOfferData($request, $offerType);
            $amount        = $data['amount'] ?? null;
            $currency      = $data['currency'] ?? 'USD';
            $note          = $data['note'];
            $invoiceId     = $data['invoice_id'] ?? null;
            $returnAddress = [];
            if (!empty($data['address']) && !empty($data['country_code'])) {
                $returnAddress[] = [
                    'address_line_1' => $data['address'],
                    'country_code'   => $data['country_code'],
                ];
            }
            $PaypalApi = $this->getPaypalApiByDisputeId($id);
            $response  = $PaypalApi->getDisputeDetails($dispute->dispute_id);
            $response  = $PaypalApi->makeOfferToResolveDispute($dispute->dispute_id, $offerType, $note, (float) $amount, $currency, $invoiceId, $returnAddress);
            flash()->success('Make offer successful!');
        } catch (ValidationException $e) {
            flash()->error($e->getMessage());
            return redirect()->route('app.dispute.show', ['id' => $id]);
        } catch (\Exception $e) {
            return redirect()->route('app.dispute.show', ['id' => $id]);
        }
        return redirect()->route('app.dispute.show', ['id' => $id]);
    }

    /**
     * Acknowledges that the customer has returned an item for a dispute.
     *
     * @param Request    $request The incoming request containing item status, note, and evidence.
     * @param int|string $id      The ID of the dispute record in the database.
     *
     * @return RedirectResponse Redirects back to the dispute details page.
     */
    public function acknowledgeReturned(Request $request, int|string $id): RedirectResponse {
        try {
            $status    = $request->validate(['item_status' => 'required|in:NORMAL,ISSUE'])['item_status'];
            $data      = $request->validate([
                'note'          => 'nullable|string|max:2000',
                'evidence_type' => [
                    ($status === "ISSUE" ? 'required' : 'nullable'),
                    'in:PROOF_OF_DAMAGE,THIRDPARTY_PROOF_FOR_DAMAGE_OR_SIGNIFICANT_DIFFERENCE,DECLARATION,PROOF_OF_MISSING_ITEMS,PROOF_OF_EMPTY_PACKAGE_OR_DIFFERENT_ITEM,PROOF_OF_ITEM_NOT_RECEIVED',
                ],
                'documents.*'   => [
                    ($status === "ISSUE" ? 'required' : 'nullable'),
                    'mimes:jpg,jpeg,png,pdf|max:50MB',
                ],
            ]);
            $documents = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    //app/public/uploads
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path     = 'uploads/' . $filename;
                    $file->move(public_path('uploads'), $filename);
                    $documents[] = [
                        'name' => $file->getClientOriginalName(),
                        'url'  => asset($path),
                    ];
                }
            }
            // this form only post 1 evidences
            $allEvidences = [];
            $evidences    = [];
            if ($status === "ISSUE") {
                $evidences = [
                    'evidence_type' => $data['evidence_type'] ?? null,
                    'documents'     => $documents ?? null,
                ];
            }
            $allEvidences[] = $evidences;
            $dispute        = \App\Models\Dispute::findOrFail($id);
            $PaypalApi      = $this->getPaypalApiByDisputeId($id);
            $response       = $PaypalApi->acknowledgeReturnedItem($dispute->dispute_id, $data['note'], $allEvidences);
            flash()->success('Acknowledge Returned successful!');
        } catch (ValidationException $e) {
            flash()->error($e->getMessage());
            return redirect()->route('app.dispute.show', ['id' => $id]);
        } catch (\Exception $e) {
            return redirect()->route('app.dispute.show', ['id' => $id]);
        }
        return redirect()->route('app.dispute.show', ['id' => $id]);
    }

    /**
     * Get All PaypalApi by Pay gate ID
     *
     * @param int|string $id
     *
     * @return PayPalAPI
     * @throws \Exception
     */
    private function getPaypalApiByDisputeId(int|string $id): PayPalAPI {
        $dispute = \App\Models\Dispute::findOrFail($id);
        $paygate = \App\Models\Paygate::findOrFail($dispute->paygate_id);
        return new PayPalAPI($paygate);
    }

    /**
     * get all validated offer data
     *
     * @param Request $request
     * @param         $offerType
     *
     * @return array
     */
    private function getValidatedMakeOfferData(Request $request, $offerType): array {
        return $request->validate([
            'amount'       => [
                'numeric',
                ($offerType !== "REPLACEMENT_WITHOUT_REFUND" ? 'required' : 'nullable'),
            ],
            'currency'     => [
                ($offerType !== "REPLACEMENT_WITHOUT_REFUND" ? 'required' : 'nullable'),
                'string',
                'size:3',
            ],
            'note'         => 'required|string|max:2000',
            'invoice_id'   => 'nullable|string|max:127',
            'address'      => [
                'string',
                'max:300',
                ($offerType === "REFUND_WITH_RETURN" ? 'required' : 'nullable'),
            ],
            'country_code' => [
                'string',
                'size:2',
                ($offerType === "REFUND_WITH_RETURN" ? 'required' : 'nullable'),
                'regex:/^([A-Z]{2}|C2)$/',
            ],
        ]);
    }

    public function escalate(Request $request): RedirectResponse {
        $input = $request->only([
            'paygate_id',
            'dispute_id',
            'dispute_code',
            'note',
        ]);
        // Validate input
        if (empty($input['paygate_id']) || empty($input['dispute_id'])) {
            flash()->error('Missing required information to proceed.');
            return redirect()->back();
        }
        // Find Paygate
        $paygate = \App\Models\Paygate::find($input['paygate_id']);
        if (!$paygate) {
            flash()->error('Payment gateway not found.');
            return redirect()->back();
        }
        // Call PayPal API
        try {
            $paypalApi = new PayPalAPI($paygate);
            $result    = $paypalApi->escalate($input['dispute_id'], $input['note']);
            if (!$result) {
                flash()->error('Failed to escalate the dispute. Please try again later.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            flash()->error('Error while calling API: ' . $e->getMessage());
            return redirect()->back();
        }
        flash()->success('Dispute escalated successfully.');
        return redirect()->route('app.dispute.show', ['id' => $input['dispute_id']]);
    }

    /**
     * Accepts a claim for a given dispute and processes the corresponding refund action.
     *
     * @param Request $request The HTTP request containing claim acceptance details.
     * @param int     $id      The ID of the dispute to be processed.
     *
     * @return RedirectResponse Redirects back to the dispute details page with a success or error message.
     *
     */
    public function acceptClaim(Request $request, $id): RedirectResponse {
        try {
            $acceptClaimType = strtoupper($request->validate(['accept_claim_type' => 'required|string|in:REFUND,REFUND_WITH_RETURN,PARTIAL_REFUND,REFUND_WITH_RETURN_SHIPMENT_LABEL'])['accept_claim_type']);
            $data            = $this->getValidatedAcceptClaimData($request, $acceptClaimType);
            $payPalApi       = $this->getPaypalApiByDisputeId($id);
            $dispute         = \App\Models\Dispute::findOrFail($id);
            $response        = $payPalApi->acceptClaim($dispute->dispute_id, $data['note'], $data['accept_claim_reason'], $acceptClaimType, $data['refund_amount'], $data['invoice_id'], $data['return_shipment_info'], $data['return_shipping_address']);
            flash()->success('Claim accepted successfully!');
        } catch (ValidationException $e) {
            flash()->error($e->getMessage());
            return redirect()->route('app.dispute.show', ['id' => $id]);
        } catch (\Exception $e) {
            return redirect()->route('app.dispute.show', ['id' => $id]);
        }
        return redirect()->route('app.dispute.show', ['id' => $id]);
    }

    /**
     * Get all validated accept_claim data
     *
     * @param Request $request
     * @param         $acceptClaimType
     *
     * @return array
     */
    private function getValidatedAcceptClaimData(Request $request, $acceptClaimType): array {
        $data                            = $request->validate([
            'note'                 => 'required|string|max:2000',
            'accept_claim_reason'  => 'nullable|string|in:DID_NOT_SHIP_ITEM,TOO_TIME_CONSUMING,LOST_IN_MAIL,NOT_ABLE_TO_WIN,COMPANY_POLICY,REASON_NOT_SET',
            'currency_code'        => [
                ($acceptClaimType === "PARTIAL_REFUND" ? 'required' : 'nullable'),
                'string',
                'size:3',
            ],
            'value'                => [
                ($acceptClaimType === "PARTIAL_REFUND" ? 'required' : 'nullable'),
                'numeric',
                'min:0',
            ],
            'invoice_id'           => 'nullable|string|max:127',
            'address'              => [
                ($acceptClaimType === "REFUND_WITH_RETURN" || $acceptClaimType === "REFUND_WITH_RETURN_SHIPMENT_LABEL" ? 'required' : 'nullable'),
                'string',
                'max:300',
            ],
            'country_code'         => [
                ($acceptClaimType === "REFUND_WITH_RETURN" || $acceptClaimType === "REFUND_WITH_RETURN_SHIPMENT_LABEL" ? 'required' : 'nullable'),
                'string',
                'size:2',
                'regex:/^([A-Z]{2}|C2)$/',
            ],
            'return_shipment_info' => 'nullable|array',
        ]);
        $data['refund_amount']           = ($acceptClaimType === 'PARTIAL_REFUND' ? [
            'currency_code' => $data['currency_code'],
            'value'         => $data['value'],
        ] : null);
        $data['return_shipping_address'] = (in_array($acceptClaimType, [
            'REFUND_WITH_RETURN',
            'REFUND_WITH_RETURN_SHIPMENT_LABEL',
        ]) ? [
            'address_line_1' => $data['address'],
            'country_code'   => $data['country_code'],
        ] : null);
        $data['return_shipment_info']    = ($acceptClaimType === 'REFUND_WITH_RETURN_SHIPMENT_LABEL' ? $data['return_shipment_info'] : null);
        return $data;
    }

    /**
     * Provide evidence for a dispute.
     *
     * @param Request $request The request containing tracking info.
     * @param int     $id      The ID of the dispute to be processed.
     *
     * @return RedirectResponse Redirects back to the dispute details page with a success or error message.
     * @throws \Exception
     */
    public function provideEvidence(Request $request, $id): RedirectResponse {

        $validated     = $request->validate([
            'evidence_type'     => 'required|string|max:2000',
            'tracking_number'   => 'nullable|array',
            // Phải có ít nhất một phần tử
            'tracking_number.*' => 'nullable|string|max:2000',
            // Mỗi phần tử phải là string và bắt buộc nhập
            'carrier_name'      => 'nullable|array',
            'carrier_name.*'    => 'nullable|string|max:2000',
            'refund_ids'        => 'nullable|string|max:2000',
            'note'              => 'nullable|string|max:2000',
            'evidence_file'     => 'nullable|array',
            // Kiểm tra mảng file
            'evidence_file.*'   => 'file|mimes:jpg,png,pdf,docx|max:2048',
            // Chỉ nhận JPG, PNG, PDF, DOCX, tối đa 2MB
        ]);
        $dispute       = \App\Models\Dispute::findOrFail($id);
        $paypalApi     = $this->getPaypalApiByDisputeId($id);
        $evidenceType  = $validated['evidence_type'];
        $evidenceInfo  = [];
        $uploadedFiles = [];
        if ($evidenceType === \App\Models\Dispute::EVIDENCE_TYPE_PROOF_OF_FULFILLMENT) {
            $_row = [];
            if (!empty($validated['carrier_name'])) {
                foreach ($validated['carrier_name'] as $_key => $value) {
                    if ($value !== '' && $validated['tracking_number'][$_key] !== '') {
                        $_row[] = [
                            'tracking_number' => $validated['tracking_number'][$_key],
                            'carrier_name'    => $validated['carrier_name'][$_key],
                        ];
                    }
                }
            }
            $evidenceInfo['tracking_info'] = [$_row];
        } elseif ($evidenceType === \App\Models\Dispute::EVIDENCE_TYPE_PROOF_OF_REFUND) {
            $evidenceInfo['refund_ids'] = array_filter([$validated['refund_ids'] ?? '']);
        }
        // Xử lý upload file
        if ($request->hasFile('evidence_file')) {
            foreach ($request->file('evidence_file') as $file) { // Lặp đúng tên input
                $fileName = $file->getClientOriginalName();
                $path     = $file->store('evidence_files', 'public');
                $basePath = 'storage/' . $path;
                $filePath = asset('storage/' . $path);
                $uploadedFiles[] = [
                    'name'      => $fileName,
                    'path'      => $filePath,
                    'base_path' => $basePath,
                ];
            }
        }
        $params = [
            'evidences' => [
                array_filter([
                    'evidence_type' => $evidenceType,
                    'evidence_info' => !empty($evidenceInfo) ? $evidenceInfo : null,
                    'notes'         => $validated['note'] ?? '',
                ]),
            ],
        ];
        if (!empty($params)) {
            if (!empty($uploadedFiles)) {

                $result = $paypalApi->provideEvidenceWithFile($dispute->dispute_id, $params, $uploadedFiles);
                if(!in_array($result['statusCode'],[200,201])){
                    return redirect()->route('app.dispute.show', ['id' => $id])->withErrors(['evidence_file' => $result['error'] ?? 'Error']);
                }
            } else {
                $result = $paypalApi->provideEvidence($dispute->dispute_id, $params);
            }
            if (!empty($result['statusCode']) && $result['statusCode'] === 200) {
                flash()->success('Evidence provided successfully!');
            } else {
                flash()->error('Evidence submission failed: ' . json_encode($result));
            }
        } else {
            flash()->error('Invalid evidence type.');
        }
        return redirect()->route('app.dispute.show', ['id' => $id]);
    }
}
