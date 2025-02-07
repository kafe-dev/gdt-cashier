<?php

namespace App\Services\DataTables;

use App\Models\Filters\PermissionFilter;
use App\Models\Permission;
use App\Services\DataTables\Transformers\PermissionTransformer;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Column;

class PermissionDatatable extends BaseDataTable
{
    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'permissions-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.user.roleManage.index';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'Permissions_';

    public function query(Permission $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * @inheritDoc
     */
    public function dataTable(): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable(new Permission()))
            ->setTransformer(PermissionTransformer::class)
            ->setRowId('id')
            ->rawColumns(['action']);
        return PermissionFilter::perform($dataTable);
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return [
            Column::make(['data' => 'id', 'title' => 'ID'])->addClass('x-id'),
            Column::make('role')->searchPanes()->addClass('x-searchable'),
            Column::make('routes')->searchPanes()->addClass('x-searchable'),
        ];
    }
}
