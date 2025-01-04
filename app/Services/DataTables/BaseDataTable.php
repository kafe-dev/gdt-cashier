<?php

declare(strict_types=1);

namespace App\Services\DataTables;

use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

/**
 * Class BaseDataTable.
 *
 * This is the base class for all DataTables service.
 * Make sure to extend this class when creating new DataTables service.
 */
abstract class BaseDataTable extends DataTable
{
    /**
     * @var string Table ID
     */
    protected string $tableId;

    /**
     * @var string Export file name
     */
    protected string $exportFileName;

    /**
     * @var bool Trigger to enable date range filter
     */
    protected bool $enableDateRange = false;

    /**
     * DataTable handler.
     */
    abstract public function dataTable(): EloquentDataTable;

    /**
     * DataTables columns definition.
     */
    abstract public function getColumns(): array;

    /**
     * DataTable builder configuration.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->tableId)
            ->setTableHeadClass('x-searchable-wrapper')
            ->columns($this->getColumns())
            ->scrollX()
            ->scrollY()
            ->responsive()
            ->fixedHeader()
            ->fixedColumns(['start' => 1, 'end' => 1])
            ->scrollCollapse(true)
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->parameters([
                'layout' => [
                    'topStart' => [
                        'buttons' => [
                            'add', 'excel', 'csv', 'pdf', 'reset',
                            [
                                'extend' => 'searchPanes',
                                'cascadePanes' => true,
                                'attr' => [
                                    'id' => 'filter-btn',
                                ],
                                'config' => [
                                    'responsive' => true,
                                    'layouts' => [
                                        'columns-sm-1', 'columns-md-2', 'columns-3',
                                    ],
                                    'initCollapsed' => true,
                                    'select' => [
                                        'style' => 'multi',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'topEnd' => 'search',
                    'bottomStart' => 'pageLength',
                    'bottomEnd' => ['info', 'paging'],
                ],
                'language' => [
                    'searchPanes' => [
                        'collapse' => [
                            0 => '<i class="fas fa-filter"></i> Filters',
                            '_' => '<i class="fas fa-filter"></i> Filters (%d)',
                        ],
                    ],
                ],
            ])
            ->addAction();
    }

    /**
     * Get filename for export.
     */
    public function filename(): string
    {
        return $this->exportFileName.date('YmdHis');
    }
}
