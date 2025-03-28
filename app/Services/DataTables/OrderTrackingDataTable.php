<?php

namespace App\Services\DataTables;

use App\Models\Filters\OrderTrackingFilter;
use App\Models\OrderTracking;
use App\Services\DataTables\Transformers\OrderTrackingTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class OrderTrackingDataTable extends BaseDataTable
{

    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'order_tracking-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.tracking.index';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'OrderTracking_';

    /**
     * {@inheritdoc}
     */
    protected array $customButtons = [
        'sync'        => 'getSyncBtn',
        'exportExcel' => 'getExportExcelBtn',
    ];

    /**
     * Return the query builder instance to be processed by DataTables.
     */
    public function query(OrderTracking $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * {@inheritDoc}
     */
    public function dataTable(): EloquentDataTable
    {
        $datatable = (new EloquentDataTable(new OrderTracking))
            ->setTransformer(OrderTrackingTransformer::class)
            ->setRowId('id')
            ->escapeColumns([])
            ->rawColumns(['action'])
            ->filter(function ($query) {
                $query->where('type', '=', '0');

                if (!empty($this->dateToFilter) && !empty($this->minDate) && !empty($this->maxDate)) {
                    $query->whereBetween($this->dateToFilter, [$this->minDate, $this->maxDate]);
                }
            });

        return OrderTrackingFilter::perform($datatable);
    }

    /**
     * {@inheritDoc}
     */
    public function getColumns(): array
    {
        return [
            Column::make(['data' => 'id', 'title' => 'ID'])->addClass('x-id'),
            //            Column::make('paygate_id')->searchPanes()->addClass('x-searchable'),
            Column::make('paygate_name')->searchPanes()->addClass('x-searchable'),
            Column::make('invoice_number')->searchPanes()->addClass('x-searchable'),
            Column::make('transaction_id')->searchPanes()->addClass('x-searchable'),
            Column::make(['data' => 'has_tracking_number', 'title' => 'Tracking Number'])->searchPanes()->addClass(
                'x-searchable'
            ),
            Column::make('courier_code')->searchPanes()->addClass('x-searchable'),
            Column::make('tracking_status')->searchPanes(),
            //            Column::make('tracking_data')->searchPanes(),
            Column::make('type')->searchPanes(),
            Column::make('ordered_at')->searchPanes(false)->addClass('x-has-date-filter')->orderable(false),
            Column::make('closed_at')->searchPanes(false)->addClass('x-has-date-filter')->orderable(false),
            Column::make('last_checked_at')->searchPanes(false)->addClass('x-has-date-filter')->orderable(false),
            Column::make('exported_at')->searchPanes(false)->addClass('x-has-date-filter')->orderable(false),
            Column::make('created_at')->searchPanes(false)->addClass('x-has-date-filter')->orderable(false),
            Column::make('updated_at')->searchPanes(false)->addClass('x-has-date-filter')->orderable(false),
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
            'text'      => '<i class="fa fa-sync"></i> Sync ',
            'className' => 'btn btn-primary',
            'init'      => "function (dt, node, config) {
                $(node).css('background-color', 'rgba(23, 97, 253, 0.75)');
                $(node).css('border-color', 'rgba(23, 97, 253, 0.75)');

                $(node).click(() => {
                    window.location.reload();
                });
            }",
        ];
    }

    /**
     * Returns the "exportExcel" button.
     *
     * @return string[]
     */
    public function getExportExcelBtn(): array
    {
        return [
            'text'          => '<i class="fa fa-file-excel"></i> Export',
            'extend'        => 'excel',
            'exportOptions' => [
                'modifier' => [
                    'filter' => 'applied',
                ],
            ],
            'className'     => 'btn btn-success',

        ];
    }

}
