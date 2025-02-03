<?php
declare(strict_types=1);

namespace App\Services\DataTables\Transformers;

use App\Models\Dispute;
use App\Models\Paygate;
use App\Utils\ActionWidget;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 *
 * This class is responsible for transforming user data for DataTables.
 */
class DisputeTransformer extends TransformerAbstract {

    /**
     * Data transformer.
     */
    public function transform(Dispute $dispute): array {
        $paygate = Paygate::find($dispute->paygate_id);
        return [
            'id'                       => '<span class="fw-bold float-start">' . ($dispute->id ?? '') . '</span>',
            'paygate_id'               => $paygate->name ?? '',
            'dispute_id'               => $dispute->dispute_id ?? '',
            'create_time'              => !empty($dispute->create_time) ? '<span class="x-has-time-converter">' . $dispute->create_time->format(config('app.date_format')) . '</span>' : '-',
            'update_time'              => !empty($dispute->update_time) ? '<span class="x-has-time-converter">' . $dispute->update_time->format(config('app.date_format')) . '</span>' : '-',
            'buyer_transaction_id'     => $dispute->buyer_transaction_id ?? '',
            'merchant_id'              => $dispute->merchant_id ?? '',
            'reason'                   => $dispute->reason ?? '',
            'status'                   => $dispute->status ?? '',
            'dispute_state'            => $dispute->dispute_state ?? '',
            'dispute_amount_currency'  => $dispute->dispute_amount_currency ?? '',
            'dispute_amount_value'     => $dispute->dispute_amount_value ?? '',
            'dispute_life_cycle_stage' => $dispute->dispute_life_cycle_stage ?? '',
            'dispute_channel'          => $dispute->dispute_channel ?? '',
            'seller_response_due_date' => !empty($dispute->seller_response_due_date) ? '<span class="x-has-time-converter">' . $dispute->seller_response_due_date->format(config('app.date_format')) . '</span>' : '-',
            'link'                     => !empty($dispute->link) ? '<a class="text-primary" href="' . $dispute->link . '" target="_blank">' . $dispute->link . '</a>' : '-',
            'created_at'               => !empty($dispute->created_at) ? '<span class="x-has-time-converter">' . $dispute->created_at->format(config('app.date_format')) . '</span>' : '-',
            'updated_at'               => !empty($dispute->updated_at) ? '<span class="x-has-time-converter">' . $dispute->updated_at->format(config('app.date_format')) . '</span>' : '-',
            'action'                   => $this->renderActions($dispute),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(Dispute $dispute) {
        $paygate = \App\Models\Paygate::find($dispute->paygate_id);
        $data_vps = $paygate? json_encode($paygate->vps_data) : '';
        $btnView           = '<a href="' . route('app.dispute.show', ['id' => $dispute->id]) . '" class="btn btn-sm btn-info m-1" title="View"><i class="fa fa-eye"></i></a>';
        $btnRedirectPaypal = '<a href="#" class="btn btn-sm btn-primary m-1" title="View" target="_blank" data-bs-toggle="modal" data-bs-target="#dispute-info-paypal" data-vps="' . htmlspecialchars($data_vps). '" data-link="' . htmlspecialchars($dispute->link) . '"><i class="fab fa-paypal"></i></a>';
        return $btnView . ' ' . $btnRedirectPaypal;
    }
}
