<?php
/**
 * @project gdt-cashier
 * @author  hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date    09/02/2025
 * @time    13:09
 */

namespace App\Http\Controllers;

use App\Exports\PaypalTransactionDataTableExport;
use App\Paygate\PayPalAPI;
use App\Services\DataTables\PaypalTransactionDatatable;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\PaypalTransaction as PaypalTransactionModel;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaypalTransaction extends BaseController
{

    private PaypalTransactionModel $paypalTransactionModel;

    public function __construct(PaypalTransactionModel $paypalTransactionModel)
    {
        parent::__construct();

        $this->paypalTransactionModel = $paypalTransactionModel;
    }

    /**
     * Action 'index'
     */
    public function index(PaypalTransactionDatatable $dataTable)
    {
        $this->filterDateRange($dataTable);
        return $dataTable->render('paypal_transaction.index');
    }

    /**
     * Action 'show'.
     *
     * @param  int|string  $id
     *
     * @return View
     */
    public function show(int|string $id): View
    {
        return view('paypal_transaction.show', [
            'paypalTransaction' => $this->getPaypalTransaction((int)$id),
        ]);
    }

    /**
     * Action 'mark as closed'.
     *
     * @param  int|string  $id
     * @param  Request     $request
     *
     * @return RedirectResponse
     */
    public function markAsClosed(int|string $id, Request $request)
    {
        if ($request->isMethod('POST')) {
            $paypalTransaction = $this->getPaypalTransaction($id);

            $paypalTransaction->closed_at = Carbon::now();
            $paypalTransaction->save();

            flash()->success('Paypal transaction closed.');
        }

        return redirect()->route('app.paypal-transaction.index');
    }

    private function getPaypalTransaction(int $id): PaypalTransactionModel
    {
        return $this->paypalTransactionModel->query()->findOrFail($id);
    }

    /**
     * Processes a PayPal payment refund.
     *
     * @param  Request     $request  The HTTP request containing refund details.
     * @param  int|string  $id       The transaction ID of the PayPal payment to be refunded.
     *
     * @return RedirectResponse Redirects to the PayPal transaction index page with a success or error message.
     */
    public function refundPayment(Request $request, int|string $id): RedirectResponse
    {
        try {
            $refundType = strtoupper($request->validate(['refund_type' => 'required|string'])['refund_type']);
            $amount = $this->getRefundAmountByTransactionId($id);
            $data = $this->getValidatedRefundPaymentData($request, $refundType, $amount['gross']);
            $paypalApi = $this->getPayPalApiByTransactionId($id);

            $paypalApi->issueRefund(
                $id,
                $refundType,
                $data['custom_id'],
                $data['invoice_id'],
                $data['note_to_payer'],
                $amount['currency'],
                $data['amount']
            );

            flash()->success('Refund payment successful!');
        } catch (ValidationException $e) {
            flash()->error($e->getMessage());
            return redirect()->route('app.paypal-transaction.index');
        } catch (\Exception $e) {
            flash()->error('Refund failed');
            return redirect()->route('app.paypal-transaction.index');
        }
        return redirect()->route('app.paypal-transaction.index');
    }

    /**
     * Retrieves the PayPal API instance associated with a given transaction ID.
     *
     * @param  string  $transaction_id  The PayPal transaction ID.
     *
     * @return PayPalAPI The PayPal API instance configured with the corresponding Paygate settings.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the associated Paygate record is not found.
     */
    private function getPayPalApiByTransactionId(string $transaction_id): PayPalAPI
    {
        $paygate_id = $this->paypalTransactionModel->query()->where('transaction_id', $transaction_id)
            ->value('paygate_id');
        $paygate = \App\Models\Paygate::findOrFail($paygate_id);
        return new PayPalAPI($paygate);
    }

    /**
     * Retrieves the refund amount and currency for a given transaction ID.
     *
     * @param  string  $transaction_id  The PayPal transaction ID.
     *
     * @return array An associative array containing:
     *               - 'gross' (int|string): The total transaction amount.
     *               - 'currency' (string): The currency code of the transaction.
     */
    private function getRefundAmountByTransactionId(string $transaction_id): array
    {
        return [
            'gross'    => $this->paypalTransactionModel->query()->where('transaction_id', $transaction_id)
                ->value('gross'),
            'currency' => $this->paypalTransactionModel->query()->where('transaction_id', $transaction_id)
                ->value('currency'),
        ];
    }

    /**
     * Validates and retrieves refund payment data from the request.
     *
     * @param  Request     $request     The HTTP request containing refund details.
     * @param  string      $refundType  The type of refund ('FULL' or 'PARTIAL').
     * @param  int|string  $gross       The total transaction amount (used to validate partial refunds).
     *
     * @return array An associative array containing validated refund data, including:
     *               - 'capture_id' (string): The PayPal capture ID.
     *               - 'amount' (numeric|null): The refund amount (required for partial refunds).
     *               - 'note_to_payer' (string|null): An optional note to the payer.
     *               - 'invoice_id' (string|null): The invoice ID for the refund.
     *               - 'custom_id' (string|null): A custom identifier for the refund.
     *               - 'paypal_request_id' (string|null): An optional PayPal request ID.
     */
    private function getValidatedRefundPaymentData(Request $request, string $refundType, int|string $gross): array
    {
        return $request->validate([
            'capture_id'        => 'required|string',
            'amount'            => [
                'numeric',
                'min:0.01',
                'max:'.$gross,
                ($refundType === 'PARTIAL' ? 'required' : 'nullable'),
            ],
            'note_to_payer'     => 'nullable|string|max:255',
            'invoice_id'        => 'nullable|string|max:127',
            'custom_id'         => 'nullable|string|max:127',
            'paypal_request_id' => 'nullable|string',

        ]);
    }

}
