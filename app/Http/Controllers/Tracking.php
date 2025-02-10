<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\OrderTrackingDataTableExport;
use App\Facades\TrackingMore;
use App\Models\OrderTracking as OrderTrackingModel;
use App\Services\DataTables\OrderTrackingDataTable;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Trackingmore\TrackingMoreException;

/**
 * Class Tracking.
 *
 * This controller is responsible for managing tracking-related operations.
 */
class Tracking extends BaseController
{
    private OrderTrackingModel $orderTrackingModel;

    public function __construct(OrderTrackingModel $orderTrackingModel)
    {
        parent::__construct();

        $this->orderTrackingModel = $orderTrackingModel;
    }

    /**
     * Action `index`.
     *
     * @throws TrackingMoreException
     */
    public function index(OrderTrackingDataTable $dataTable)
    {
        foreach (OrderTrackingModel::all() as $orderTracking) {
            if (is_null($orderTracking->courier_code || $orderTracking->tracking_number != null)) {
                $this->detectCouriers($orderTracking->id, $orderTracking->tracking_number);
            }
        }

        $this->filterDateRange($dataTable);

        return $dataTable->render('tracking.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  The tracking ID
     */
    public function show(int|string $id): View
    {
        return view('tracking.show', [
            'orderTracking' => $this->getOrderTracking((int) $id),
            'json_tracking_data' => json_decode($this->getOrderTracking((int) $id)->tracking_data),
        ]);
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  The tracking ID
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        if ($request->isMethod('POST')) {
            $tracking = $this->getOrderTracking((int) $id);

            if ($tracking->delete()) {
                flash()->success('Tracking has been deleted.');
            }
        }

        return redirect()->route('app.tracking.index');
    }

    /**
     * Action 'mark as closed'
     *
     * @return RedirectResponse
     */
    public function markAsClosed(int|string $id, Request $request)
    {
        if ($request->isMethod('POST')) {
            $orderTracking = OrderTrackingModel::findOrFail($id);

            $orderTracking->type = OrderTrackingModel::TYPE_CLOSED;
            $orderTracking->closed_at = now();
            $orderTracking->save();

            flash()->success('Tracking has been marked as closed.');
        }

        return redirect()->route('app.tracking.index');
    }

    /**
     * Exports all open OrderTracking records to an Excel file.
     * After exporting, updates the type to closed and sets the exported_at timestamp.
     *
     * @return BinaryFileResponse|RedirectResponse
     */
    public function export(): BinaryFileResponse|RedirectResponse
    {
        $records = OrderTrackingModel::where('type', OrderTrackingModel::TYPE_OPEN)->get();

        if ($records->isEmpty()) {
            flash()->warning('There are no tracking records.');
            return redirect()->route('app.tracking.index');
        }

        OrderTrackingModel::whereIn('id', $records->pluck('id'))->update([
            'type' => OrderTrackingModel::TYPE_CLOSED,
            'exported_at' => Carbon::now(),
            'closed_at' => Carbon::now(),
        ]);

        $fileName = 'order_tracking_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        session(['export_records' => $records]);

        return Excel::download(new OrderTrackingDataTableExport($records), $fileName);
    }

    /**
     * Returns the specific order tracking based on the given ID.
     */
    private function getOrderTracking(int $id): OrderTrackingModel
    {
        return $this->orderTrackingModel->query()->findOrFail($id);
    }

    /**
     * Action for detect insufficient courier code.
     *
     * @throws TrackingMoreException
     */
    private function detectCouriers(int $id, ?string $trackingNumber)
    {
        $response = TrackingMore::courier()->detect(['tracking_number' => $trackingNumber]);

        $code = $response['data'][0]['courier_code'];

        OrderTrackingModel::find($id)->update(['courier_code' => $code]);
    }
}
