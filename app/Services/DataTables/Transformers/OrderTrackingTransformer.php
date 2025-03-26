<?php

namespace App\Services\DataTables\Transformers;

use App\Models\OrderTracking;
use App\Models\Paygate;
use App\Utils\ActionWidget;
use League\Fractal\TransformerAbstract;

class OrderTrackingTransformer extends TransformerAbstract
{

    /**
     * Data transformer.
     */
    public function transform(OrderTracking $orderTracking): array
    {
        $status = match ($orderTracking->tracking_status) {
            'inforeceived' => '<span class="badge badge-soft-info">Info Received</span>',
            'transit' => '<span class="badge badge-soft-primary">In Transit</span>',
            'pickup' => '<span class="badge badge-soft-primary">Out for Delivery</span>',
            'undelivered' => '<span class="badge badge-soft-warning">Failed Attempt</span>',
            'delivered' => '<span class="badge badge-soft-success">Delivered</span>',
            'exception' => '<span class="badge badge-soft-warning">Exception</span>',
            'expired' => '<span class="badge badge-soft-dark">Expired</span>',
            'notfound' => '<span class="badge badge-soft-secondary">Not Found</span>',
            'pending' => '<span class="badge badge-soft-purple">Pending</span>',
            default => '<span class="badge badge-soft-info">Unknown</span>',
        };

        $hasTracking = match ($orderTracking->has_tracking_number) {
            1 => $orderTracking->tracking_number,
            default => '',
        };

        $type = match ($orderTracking->type) {
            OrderTracking::TYPE_OPEN => '<span class="badge badge-soft-primary">'.OrderTracking::TYPES[$orderTracking->type].'</span>',
            OrderTracking::TYPE_CLOSED => '<span class="badge badge-soft-dark">'.OrderTracking::TYPES[$orderTracking->type].'</span>',
            default => '<span class="badge badge-soft-secondary">'.OrderTracking::TYPES[$orderTracking->type].'</span>',
        };

        return [
            'id'                  => '<span class="fw-bold float-start">'.$orderTracking->id.'</span>',
            'paygate_id'          => $orderTracking->paygate_id,
            'paygate_name'        => $orderTracking->paygate_name,
            'invoice_number'      => $orderTracking->invoice_number ?? '',
            'transaction_id'      => $orderTracking->transaction_id ?? '',
            'has_tracking_number' => $hasTracking,
            'courier_code'        => $orderTracking->courier_code ?? '',
            'tracking_status'     => $status,
            'tracking_data'       => ''.ActionWidget::renderShowBtn(
                    route('app.tracking.show', ['id' => $orderTracking->id])
                ) ?? '',
            'type'                => $type,
            'ordered_at'          => !empty($orderTracking->ordered_at) ? '<span class="x-has-time-converter">'.$orderTracking->ordered_at->format(
                    config('app.date_format')
                ).'</span>' : '-',
            'closed_at'           => !empty($orderTracking->closed_at) ? '<span class="x-has-time-converter">'.$orderTracking->closed_at->format(
                    config('app.date_format')
                ).'</span>' : '-',
            'last_checked_at'     => !empty($orderTracking->last_checked_at) ? '<span class="x-has-time-converter">'.$orderTracking->last_checked_at->format(
                    config('app.date_format')
                ).'</span>' : '-',
            'exported_at'         => !empty($orderTracking->exported_at) ? '<span class="x-has-time-converter">'.$orderTracking->exported_at->format(
                    config('app.date_format')
                ).'</span>' : '-',
            'created_at'          => !empty($orderTracking->created_at) ? '<span class="x-has-time-converter">'.$orderTracking->created_at->format(
                    config('app.date_format')
                ).'</span>' : '-',
            'updated_at'          => !empty($orderTracking->updated_at) ? '<span class="x-has-time-converter">'.$orderTracking->updated_at->format(
                    config('app.date_format')
                ).'</span>' : '-',
            'action'              => $this->renderActions($orderTracking),
        ];
    }

    /**
     * Render action columns.
     */
    public function renderActions(OrderTracking $orderTracking): string
    {
        $addTrackingInfoButton = '<a class="btn btn-sm btn-warning text-white" href="'.route(
                'app.tracking.addTrackingView',
                ['id' => $orderTracking->id]
            ).'" title="Add Tracking Info"><i class="fa fa-plus"></i></a>';

        return '
            '.$addTrackingInfoButton.'
            '.ActionWidget::renderShowBtn(route('app.tracking.show', ['id' => $orderTracking->id])).'
            '.ActionWidget::renderDeleteBtn(
                $orderTracking->id,
                route('app.tracking.delete', ['id' => $orderTracking->id])
            ).'
            '.ActionWidget::renderMarkClosedBtn(
                $orderTracking->id,
                route('app.tracking.markclosed', ['id' => $orderTracking->id])
            ).'
        ';
    }

}
