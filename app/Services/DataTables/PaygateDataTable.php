<?php

declare(strict_types=1);

namespace App\Services\DataTables;

use App\Models\Filters\PaygateFilter;
use App\Models\Paygate;
use App\Services\DataTables\Transformers\PaygateTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

/**
 * Class UserDataTable.
 *
 * This class provides a DataTables integration for the User model.
 */
class PaygateDataTable extends BaseDataTable
{
    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'paygate-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.paygate.create';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'Paygates_';

    /**
     * Return the query builder instance to be processed by DataTables.
     */
    public function query(Paygate $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function dataTable(): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable(new Paygate))->setTransformer(PaygateTransformer::class)->setRowId('id')->escapeColumns([])->rawColumns(['action'])->filter(function ($query) {
            if (!empty($this->dateToFilter) && !empty($this->minDate) && !empty($this->maxDate)) {
                $query->whereBetween($this->dateToFilter, [$this->minDate, $this->maxDate]);
            }
        });;

        return PaygateFilter::perform($dataTable);
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
            ])->searchPanes(false)->addClass('x-id'),
            Column::make('name')->searchPanes()->addClass('x-searchable'),
            Column::make('url')->searchPanes()->addClass('x-searchable'),
            // Column::make('api_data')->addClass('x-searchable'),
            // Column::make('vps_data')->addClass('x-searchable'),PaygateDataTable.php
            Column::make('type')->searchPanes()->addClass('x-searchable'),
            Column::make('status')->searchPanes()->addClass('x-searchable'),
//            Column::make('limitation')->searchPanes()->addClass('x-searchable'),
            Column::make('mode')->searchPanes()->addClass('x-searchable'),
            Column::make('created_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('updated_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
        ];
    }
}