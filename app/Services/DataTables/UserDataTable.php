<?php

declare(strict_types=1);

namespace App\Services\DataTables;

use App\Models\Filters\UserFilter;
use App\Models\User;
use App\Services\DataTables\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

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
            Column::make('username')->searchPanes(),
            Column::make('email')->searchPanes(),
            Column::make('role')->searchPanes(),
            Column::make('registration_ip'),
            Column::make('status')->searchPanes(),
            Column::make('last_login_at')->searchPanes(),
            Column::make('blocked_at')->searchPanes(),
            Column::make('created_at')->searchPanes(),
            Column::make('updated_at')->searchPanes(),
        ];
    }
}
