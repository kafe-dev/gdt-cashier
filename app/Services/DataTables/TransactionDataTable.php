<?php

declare(strict_types=1);

namespace App\Services\DataTables;

use App\Models\Filters\PaygateFilter;
use App\Models\Filters\TransactionFilter;
use App\Models\Paygate;
use App\Models\Transaction;
use App\Services\DataTables\Transformers\PaygateTransformer;
use App\Services\DataTables\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

/**
 * Class UserDataTable.
 *
 * This class provides a DataTables integration for the User model.
 */
class TransactionDataTable extends BaseDataTable
{
    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'transaction-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.transaction.create';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'Transactions_';

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
        $dataTable = (new EloquentDataTable(new Transaction()))->setTransformer(TransactionTransformer::class)->setRowId('id')->escapeColumns([])->rawColumns(['action']);

        return TransactionFilter::perform($dataTable);
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
            Column::make('url')->searchPanes()->addClass('x-searchable'),
            // Column::make('api_data')->addClass('x-searchable'),
            // Column::make('vps_data')->addClass('x-searchable'),PaygateDataTable.php
            Column::make('type')->searchPanes()->addClass('x-searchable'),
            Column::make('status')->searchPanes()->addClass('x-searchable'),
            Column::make('limitation')->searchPanes()->addClass('x-searchable'),
            Column::make('mode')->searchPanes()->addClass('x-searchable'),
            Column::make('created_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('updated_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
        ];
    }
}
