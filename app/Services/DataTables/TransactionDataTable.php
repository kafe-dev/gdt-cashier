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
            Column::make('transaction_id')->searchPanes()->addClass('x-searchable'),
            Column::make('transaction_event_code')->searchPanes()->addClass('x-searchable'),
            Column::make('transaction_initiation_date')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('transaction_updated_date')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('transaction_amount_currency')->searchPanes()->addClass('x-searchable'),
            Column::make('transaction_amount_value')->searchPanes()->addClass('x-searchable'),
            Column::make('transaction_status')->searchPanes()->addClass('x-searchable'),
            //Column::make('transaction_subject')->searchPanes()->addClass('x-searchable'),
//            Column::make('ending_balance_currency')->searchPanes()->addClass('x-searchable'),
//            Column::make('ending_balance_value')->searchPanes()->addClass('x-searchable'),
//            Column::make('available_balance_currency')->searchPanes()->addClass('x-searchable'),
//            Column::make('available_balance_value')->searchPanes()->addClass('x-searchable'),
            //Column::make('protection_eligibility')->searchPanes()->addClass('x-searchable'),
            //Column::make('created_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            //Column::make('updated_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
        ];
    }
}
