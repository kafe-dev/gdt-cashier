<?php

namespace App\Services\DataTables;

use App\Models\Filters\UserFilter;
use App\Models\User;
use App\Services\DataTables\Transformers\UserRoleTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class UserRoleDataTable extends BaseDataTable
{
    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'users-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.user.roleManage.index';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'Users_Role_';

    /**
     * Return the query builder instance to be processed by DataTables.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function dataTable(): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable(new User))
            ->setTransformer(UserRoleTransformer::class)
            ->setRowId('id')
            ->escapeColumns(['username', 'email'])
            ->rawColumns(['action'])
            ->filter(function ($query) {
                $query->where('role', '!=', 1)
                    ->where('id', '!=', auth()->id());

                if (!empty($this->dateToFilter) && !empty($this->minDate) && !empty($this->maxDate)) {
                    $query->whereBetween($this->dateToFilter, [$this->minDate, $this->maxDate]);
                }
            });
        return UserFilter::perform($dataTable);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns(): array
    {
        return [
            Column::make(['data' => 'id', 'title' => 'ID'])->addClass('x-id'),
            Column::make('username')->searchPanes()->addClass('x-searchable'),
            Column::make('email')->searchPanes()->addClass('x-searchable'),
            Column::make('role')->searchPanes()->addClass('x-searchable'),
            Column::make('created_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('updated_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
        ];
    }
}
