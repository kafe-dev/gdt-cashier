<?php

declare(strict_types=1);

namespace App\Services\DataTables\Transformers;

use App\Models\Paygate;
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

        return [
            'id' => '<span class="fw-bold float-start">'.($paygate->id ?? '').'</span>',
            // 'name'       => $paygate->name ?? '',
            'url' => '<a class="text-primary" href="'.($paygate->url ?? '#').'" target="_blank">'.($paygate->url ?? '').'</a>',
            'api_data' => $paygate->api_data ?? '',
            'vps_data' => $paygate->vps_data ?? '',
            'type' => $paygate->type ?? '',
            'status' => $paygate->status ?? '',
            'limitation' => $paygate->limitation ?? '',
            'mode' => $paygate->mode ?? '',
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

        return '';
    }
}
