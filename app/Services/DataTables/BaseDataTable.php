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
     * @var string $tableId Table ID
     */
    protected string $tableId;

    /**
     * @var string $exportFileName Export file name
     */
    protected string $exportFileName;

    /**
     * @var bool $enableDateRange Trigger to enable date range filter
     */
    protected bool $enableDateRange = false;

    /**
     * DataTable handler.
     *
     * @return EloquentDataTable
     */
    abstract public function dataTable(): EloquentDataTable;

    /**
     * DataTables columns definition.
     *
     * @return array
     */
    abstract public function getColumns(): array;

    /**
     * DataTable builder configuration.
     *
     * @return HtmlBuilder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId($this->tableId)
                    ->setTableHeadClass('x-searchable-wrapper')
                    ->columns($this->getColumns())
                    ->scrollX(true)
                    ->responsive(true)
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->parameters([
                        'layout'       => [
                            'topStart'    => [
                                'buttons' => [
                                    'add', 'excel', 'csv', 'pdf', 'reset',
                                    [
                                        'extend'       => 'searchPanes',
                                        'cascadePanes' => true,
                                        'attr'         => [
                                            'id' => 'filter-btn',
                                        ],
                                        'config'       => [
                                            'responsive'    => true,
                                            'layouts'       => [
                                                'columns-sm-1', 'columns-md-2', 'columns-3',
                                            ],
                                            'initCollapsed' => true,
                                            'select'        => [
                                                'style' => 'multi',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'topEnd'      => 'search',
                            'bottomStart' => 'pageLength',
                            'bottomEnd'   => ['info', 'paging'],
                        ],
                        'language'     => [
                            'searchPanes' => [
                                'collapse' => [
                                    0   => '<i class="fas fa-filter"></i> Filters',
                                    '_' => '<i class="fas fa-filter"></i> Filters (%d)',
                                ],
                            ],
                        ],
                        'initComplete' => "function () {
                            this.api().columns().every(function () {
                                let column = this;
                                let header = column.header();
                                let input = document.createElement('input');

                                input.setAttribute('class', 'form-control');
                                header.appendChild(input);
                                input.setAttribute('readonly', 'true');

                                if (header.classList.contains('x-searchable')) {
                                    input.removeAttribute('readonly');
                                }

                                input.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                });

                                input.addEventListener('keyup', () => {
                                    if (column.search() !== this.value) {
                                        column.search(input.value).draw();
                                    }
                                });
                            });
                        }",
                    ])
                    ->addAction();
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    public function filename(): string
    {
        return $this->exportFileName.date('YmdHis');
    }

}
