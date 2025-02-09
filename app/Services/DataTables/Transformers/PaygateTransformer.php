<?php

declare(strict_types=1);

namespace App\Services\DataTables\Transformers;

use App\Models\Paygate;
use App\Utils\ActionWidget;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 *
 * This class is responsible for transforming user data for DataTables.
 */
class PaygateTransformer extends TransformerAbstract
{
    /**
     * Data transformer.
     */
    public function transform(Paygate $paygate): array
    {
        $status = match ($paygate->status) {
            Paygate::STATUS_INACTIVE => '<span class="badge badge-soft-secondary">'.Paygate::STATUS[$paygate->status].'</span>',
            Paygate::STATUS_DRAFT => '<span class="badge badge-soft-danger">'.Paygate::STATUS[$paygate->status].'</span>',
            default => '<span class="badge badge-soft-success">'.Paygate::STATUS[$paygate->status].'</span>',
        };
        $type = match ($paygate->type) {
            Paygate::TYPE_STRIPE => '<span class="badge badge-soft-success">'.Paygate::TYPE[$paygate->type].'</span>',
            default => '<span class="badge badge-soft-primary">'.Paygate::TYPE[$paygate->type].'</span>',
        };
        $mode = match ($paygate->mode) {
            Paygate::MODE_LIVE => '<span class="badge badge-soft-success">'.Paygate::MODES[$paygate->mode].'</span>',
            default => '<span class="badge badge-soft-secondary">'.Paygate::MODES[$paygate->mode].'</span>',
        };

        return [
            'id' => '<span class="fw-bold float-start">'.($paygate->id ?? '-').'</span>',
            'name'       => $paygate->name ?? '-',
            'url' => '<a class="text-primary" href="'.($paygate->url ?? '#').'" target="_blank">'.($paygate->url ?? '-').'</a>',
            'api_data' => $paygate->api_data ?? '-',
            'vps_data' => $paygate->vps_data ?? '-',
            'type' => $type,
            'status' => $status,
//            'limitation' => $paygate->limitation ?? '-',
            'mode' => $mode,
            'created_at' => ! empty($paygate->created_at) ? '<span class="x-has-time-converter">'.$paygate->created_at->format(config('app.date_format')).'</span>' : '-',
            'updated_at' => ! empty($paygate->updated_at) ? '<span class="x-has-time-converter">'.$paygate->updated_at->format(config('app.date_format')).'</span>' : '-',
            'action' => $this->renderActions($paygate),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(Paygate $paygate): string
    {
        $modify = match ($paygate->status) {
            Paygate::STATUS_ACTIVE => '<a href="'.route('app.paygate.changeStatus', ['id' => $paygate->id]).'" class="btn btn-sm btn-secondary" title="Deactive"><i class="fa fa-ban"></i></a>',
            Paygate::STATUS_INACTIVE, Paygate::STATUS_DRAFT => '<a href="'.route('app.paygate.changeStatus', ['id' => $paygate->id]).'" class="btn btn-sm btn-success" title="Active"><i class="fa fa-check"></i></a>',
        };

        return '
            '.$modify.'
            '.ActionWidget::renderShowBtn(route('app.paygate.show', ['id' => $paygate->id])).'
            '.ActionWidget::renderUpdateBtn(route('app.paygate.update', ['id' => $paygate->id])).'
            '.ActionWidget::renderDeleteBtn($paygate->id, route('app.paygate.delete', ['id' => $paygate->id])).'
        ';
    }
}
