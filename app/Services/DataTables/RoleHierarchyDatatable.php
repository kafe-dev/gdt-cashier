<?php
/**
 * @project gdt-cashier
 * @author hoep
 * @email hiepnguyen3624@gmail.com
 * @date 2025-03-31
 * @time 7:27 AM
 */

namespace App\Services\DataTables;

use App\Models\Filters\RoleHierarchyFilter;
use App\Models\RoleHierarchy;
use App\Services\DataTables\Transformers\RoleHierarchyTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class RoleHierarchyDatatable extends BaseDataTable
{

    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'role_hierarchies-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.user.role.hierarchy.create';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'Role_Hierarchies_';

    public function query(RoleHierarchy $model): QueryBuilder
    {
        return $model->newQuery()->groupBy('parent_role');
    }

    /**
     * @inheritDoc
     */
    public function dataTable(): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable(new RoleHierarchy()))
            ->setTransformer(RoleHierarchyTransformer::class)
            ->setRowId('id')
            ->rawColumns(['action']);
        return RoleHierarchyFilter::perform($dataTable);
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return [
            Column::make(['data' => 'id', 'title' => 'ID'])->addClass('x-id'),
            Column::make('parent_role')->searchPanes()->addClass('x-searchable'),
            Column::make('child_role')->searchPanes()->addClass('x-searchable'),
        ];
    }

}
