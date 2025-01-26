<?php

declare(strict_types=1);

namespace App\Services\DataTables;

use App\Models\Filters\StoreFilter;
use App\Models\Store;
use App\Services\DataTables\Transformers\StoreTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

/**
 * Class StoreDataTable.
 *
 * This class provides a DataTables integration for the Store model.
 */
class StoreDataTable extends BaseDataTable
{
    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'stores-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.store.create';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'Stores_';

    /**
     * Return the query builder instance to be processed by DataTables.
     */
    public function query(Store $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function dataTable(): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable(new Store))
            ->setTransformer(StoreTransformer::class)
            ->setRowId('id')
            ->escapeColumns(['name', 'url', 'description', 'api_data'])
            ->rawColumns(['action']);

        return StoreFilter::perform($dataTable);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns(): array
    {
        return [
            Column::make(['data' => 'id', 'title' => 'ID'])->Width('8px')->addClass('x-id'),
            Column::make(['data' => 'user_id', 'title' => 'Owner'])->Width('50px')->searchPanes()->addClass('x-searchable'),
            Column::make('name')->searchPanes()->addClass('x-searchable'),
            Column::make('url')->Width('150px')->searchPanes()->addClass('x-searchable'),
            Column::make('description')->Width('250px')->addClass('x-searchable'),
            Column::make('status')->searchPanes(),
            Column::make('created_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('updated_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
        ];
    }

}
