<?php

declare(strict_types=1);

namespace App\Services\DataTables\Transformers;

use App\Models\Store;
use App\Utils\ActionWidget;
use League\Fractal\TransformerAbstract;

/**
 * Class StoreTransformer.
 *
 * This class is responsible for transforming store data for DataTables.
 */
class StoreTransformer extends TransformerAbstract
{
    /**
     * Data transformer.
     */
    public function transform(Store $store): array
    {
        $status = match ($store->status) {
            Store::STATUS_DRAFT => '<span class="badge badge-soft-secondary">'.Store::STATUSES[$store->status].'</span>',
            Store::STATUS_INACTIVE => '<span class="badge badge-soft-danger">'.Store::STATUSES[$store->status].'</span>',
            default => '<span class="badge badge-soft-success">'.Store::STATUSES[$store->status].'</span>',
        };

        return [
            'id' => '<span class="fw-bold float-start">'.$store->id.'</span>',
            'user_id' => '<span class="fw-bold float-start">'.$store->user_id.'</span>',
            'name' => $store->name,
            'url' => '<a class="text-primary" href="'.$store->url.'">'.$store->url.'</a>',
            'description' => $store->description,
            'status' => $status,
            'api_data' => $store->api_data,
            'created_at' => ! empty($store->created_at) ? '<span class="x-has-time-converter">'.$store->created_at->format(config('app.date_format')).'</span>' : '-',
            'updated_at' => ! empty($store->updated_at) ? '<span class="x-has-time-converter">'.$store->updated_at->format(config('app.date_format')).'</span>' : '-',
            'action' => $this->renderActions($store),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(Store $store): string
    {
        $modify = match ($store->status) {
            Store::STATUS_ACTIVE => '<a href="' . route("app.store.changeStatus", ['id' => $store->id]) .'" class="btn btn-sm btn-secondary" title="Deactivate"><i class="fa fa-ban"></i></a>',
            Store::STATUS_INACTIVE, Store::STATUS_DRAFT => '<a href="' . route("app.store.changeStatus", ['id' => $store->id]) .'" class="btn btn-sm btn-success" title="Active"><i class="fa fa-check"></i></a>',
        };

        return '
            <div style="display: grid; gap: 5px">
                '.$modify.'
                '.ActionWidget::renderShowBtn(route('app.store.show', ['id' => $store->id])).'
                '.ActionWidget::renderUpdateBtn(route('app.store.update', ['id' => $store->id])).'
                '.ActionWidget::renderDeleteBtn($store->id, route('app.store.delete', ['id' => $store->id])).'
            </div>
        ';
    }
}
