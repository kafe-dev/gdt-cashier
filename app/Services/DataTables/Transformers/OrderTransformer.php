<?php
declare(strict_types=1);

namespace App\Services\DataTables\Transformers;

use App\Models\Dispute;
use App\Models\Order;
use App\Models\Paygate;
use App\Utils\ActionWidget;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 *
 * This class is responsible for transforming user data for DataTables.
 */
class OrderTransformer extends TransformerAbstract {

    /**
     * Data transformer.
     */
    public function transform(Order $order): array {
        $paygate = Paygate::find($order->paygate_id);
        return [
            'id'             => '<span class="fw-bold float-start">' . ($order->id ?? '') . '</span>',
            'code'           => $order->code ?? '',
            'paygate_id'     => $paygate->name ?? '',
            'status'         => $order->status ?? '',
            'invoicer_email' => $order->invoicer_email_address ?? '',
            'billing_info'   => $order->billing_info ?? '-',
            'amount'         => is_numeric($order->amount) ? number_format((float) $order->amount, 2) . ' ' . ($order->currency_code ?? '') : '-',
            'paid_amount'    => is_numeric($order->paid_amount) ? number_format((float) $order->paid_amount, 2) . ' ' . ($order->paid_currency_code ?? '') : '-',
            'link'           => !empty($order->link) ? '<a class="text-primary" href="' . $order->link . '" target="_blank">' . $order->link . '</a>' : '-',
            'created_at'     => !empty($order->created_at) ? '<span class="x-has-time-converter">' . $order->created_at->format(config('app.date_format')) . '</span>' : '-',
            'updated_at'     => !empty($order->updated_at) ? '<span class="x-has-time-converter">' . $order->updated_at->format(config('app.date_format')) . '</span>' : '-',
            'action'         => $this->renderActions($order),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(Order $order) {

        $btnView           = '<a href="' . route('app.dispute.show', ['id' => $order->id]) . '" class="btn btn-sm btn-info m-1" title="View"><i class="fa fa-eye"></i></a>';
        return $btnView;
    }
}
