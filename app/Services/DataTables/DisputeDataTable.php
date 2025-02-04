<?php

declare(strict_types=1);

namespace App\Services\DataTables;

use App\Models\Dispute;
use App\Models\Filters\DisputeFilter;
use App\Services\DataTables\Transformers\DisputeTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

/**
 * Class UserDataTable.
 *
 * This class provides a DataTables integration for the User model.
 */
class DisputeDataTable extends BaseDataTable
{
    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'dispute-table';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'Disputes_';

    /**
     * {@inheritdoc}
     */
    protected array $customButtons = [
        'sync' => 'getSyncBtn',
    ];

    /**
     * Return the query builder instance to be processed by DataTables.
     */
    public function query(Dispute $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function dataTable(): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable(new Dispute))->setTransformer(DisputeTransformer::class)->setRowId('id')->escapeColumns([])->rawColumns(['action'])->filter(function ($query) {
            if (!empty($this->dateToFilter) && !empty($this->minDate) && !empty($this->maxDate)) {
                $query->whereBetween($this->dateToFilter, [$this->minDate, $this->maxDate]);
            }
        });;

        return DisputeFilter::perform($dataTable);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns(): array
    {
        return [
            Column::make([
                'data' => 'id',
                'title' => 'ID',
            ])->addClass('x-id'),
            Column::make('paygate_id')->searchPanes()->addClass('x-searchable'),
            Column::make('dispute_id')->searchPanes()->addClass('x-searchable'),
            Column::make('buyer_transaction_id')->searchPanes()->addClass('x-searchable'),
            Column::make('merchant_id')->searchPanes()->addClass('x-searchable'),
            Column::make('reason')->searchPanes()->addClass('x-searchable'),
            Column::make('status')->searchPanes()->addClass('x-searchable'),
            Column::make('dispute_state')->searchPanes()->addClass('x-searchable'),
            Column::make('dispute_amount_currency')->searchPanes()->addClass('x-searchable'),
            Column::make('dispute_amount_value')->searchPanes()->addClass('x-searchable'),
            Column::make('created_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('updated_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('seller_response_due_date')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            // Column::make('link')->searchPanes()->addClass('x-searchable'),
        ];
    }

    /**
     * Returns the "sync" btn.
     *
     * @return string[]
     */
    public function getSyncBtn(): array
    {
        return [
            'text' => '<i class="fa fa-sync"></i> Sync ',
            'className' => 'btn btn-primary',
            'init' => "function (dt, node, config) {
                $(node).css('background-color', 'rgba(23, 97, 253, 0.75)');
                $(node).css('border-color', 'rgba(23, 97, 253, 0.75)');

                $(node).click(() => {
                    window.location.reload();
                });
            }",
        ];
    }
}
