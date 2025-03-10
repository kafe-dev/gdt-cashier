<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 09/02/2025
 * @time 13:09
 */

namespace App\Http\Controllers;

use App\Exports\PaypalTransactionDataTableExport;
use App\Services\DataTables\PaypalTransactionDatatable;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * @param int|string $id
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
     * @param int|string $id
     * @param Request $request
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

    /**
     * Exports all open PaypalTransaction records to an Excel file.
     * After exporting, updates sets the exported_at and closed_at timestamp.
     *
     * @return BinaryFileResponse|RedirectResponse
     */
    public function export(Request $request)
    {
        $query = PaypalTransactionModel::where('closed_at', null)->get();
        if ($request->has(['start_date', 'end_date'])) {
            $query = PaypalTransactionModel::whereBetween('datetime', [$request->start_date, $request->end_date])
                ->where('closed_at', null)
                ->get();
        }

        if ($query->isEmpty()) {
            flash()->warning('There are no paypal transactions.');
            return redirect()->route('app.paypal-transaction.index');
        }

        PaypalTransactionModel::whereIn('id', $query->pluck('id'))->update([
            'exported_at' => Carbon::now(),
        ]);

        $fileName = 'paypal_transactions_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        session(['export_records' => $query]);

        return Excel::download(new PaypalTransactionDataTableExport($query), $fileName);
    }

    private function getPaypalTransaction(int $id): PaypalTransactionModel
    {
        return $this->paypalTransactionModel->query()->findOrFail($id);
    }
}
