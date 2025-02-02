<?php
declare(strict_types=1);

namespace App\Services\DataTables;

use App\Models\Filters\OrderFilter;
use App\Models\Filters\PaygateFilter;
use App\Models\Order;
use App\Models\Paygate;
use App\Services\DataTables\Transformers\OrderTransformer;
use App\Services\DataTables\Transformers\PaygateTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

/**
 * Class UserDataTable.
 *
 * This class provides a DataTables integration for the User model.
 */
class OrderDataTable extends BaseDataTable {

    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'order-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.paygate.create';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'Orders_';

    /**
     * Return the query builder instance to be processed by DataTables.
     */
    public function query(Paygate $model): QueryBuilder {
        return $model->newQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function dataTable(): EloquentDataTable {
        $dataTable = (new EloquentDataTable(new Order()))->setTransformer(OrderTransformer::class)->setRowId('id')->escapeColumns([])->rawColumns(['action']);
        return OrderFilter::perform($dataTable);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns(): array {
        return [
            Column::make([
                'data'  => 'id',
                'title' => 'ID',
            ])->addClass('x-id'),
            Column::make('code')->searchPanes()->addClass('x-searchable'),
            Column::make('paygate_id')->searchPanes()->addClass('x-searchable'),
            Column::make('status')->searchPanes()->addClass('x-searchable'),
            Column::make('invoicer_email_address')->searchPanes()->addClass('x-searchable'),
            Column::make('billing_info')->searchPanes()->addClass('x-searchable'),
            Column::make('amount')->searchPanes()->addClass('x-searchable'),
            Column::make('currency_code')->searchPanes()->addClass('x-searchable'),
            Column::make('paid_amount')->searchPanes()->addClass('x-searchable'),
            Column::make('paid_currency_code')->searchPanes()->addClass('x-searchable'),
            Column::make('link')->searchPanes()->addClass('x-searchable'),
            Column::make('created_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('updated_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
        ];
    }
}
