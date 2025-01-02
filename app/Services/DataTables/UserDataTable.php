<?php

declare(strict_types=1);

namespace App\Services\DataTables;

use App\Models\Filters\UserFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use App\Services\DataTables\Transformers\UserTransformer;

/**
 * Class UserDataTable.
 *
 * This class provides a DataTables integration for the User model.
 */
class UserDataTable extends BaseDataTable
{

    protected string $tableId = 'users-table';
    protected string $exportFileName = 'Users_';
    protected bool $enableDateRange = true;

    /**
     * Return the query builder instance to be processed by DataTables.
     *
     * @param  User  $model
     *
     * @return QueryBuilder
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
        $dataTable = (new EloquentDataTable(new User()))
            ->setTransformer(UserTransformer::class)
            ->setRowId('id')
            ->escapeColumns(['username', 'email'])
            ->rawColumns(['action']);

        return UserFilter::perform($dataTable);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('username')->searchPanes()->addClass('x-searchable'),
            Column::make('email')->searchPanes()->addClass('x-searchable'),
            Column::make('role')->searchPanes()->addClass('x-searchable'),
            Column::make('registration_ip')->searchPanes()->addClass('x-searchable'),
            Column::make('status')->searchPanes(),
            Column::make('last_login_at'),
            Column::make('blocked_at'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

}
