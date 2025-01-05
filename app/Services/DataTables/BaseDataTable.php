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
            ->addAction()
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
                    'search' => 'Search All:',
                ],
                'initComplete' => "function () {
                            this.api().columns().every(function () {
                                let column = this;
                                let header = column.header();
                                let input = document.createElement('input');
                                let inputWrapper = document.createElement('div');

                                inputWrapper.setAttribute('class', 'form-inline');
                                inputWrapper.appendChild(input);

                                input.setAttribute('class', 'form-control');
                                header.appendChild(input);
                                input.setAttribute('disabled', 'true');

                                if (header.classList.contains('x-searchable')) {
                                    input.removeAttribute('disabled');
                                }

                                if (header.classList.contains('x-has-date-filter')) {
                                    let startDate = moment().subtract(1, 'M');
                                    let endDate = moment();

                                    let calendar = document.createElement('label');
                                    let calendarIcon = document.createElement('i');
                                    let dateFilterSpan = document.createElement('span');
                                    let caretDownIcon = document.createElement('i');
                                    let datePickerWrapper = document.createElement('span');

                                    datePickerWrapper.setAttribute('class', 'date-picker-wrapper');

                                    calendar.setAttribute('for', header.getAttribute('data-dt-column') + '_filter');
                                    calendar.setAttribute('class', 'input-group-text bg-soft-primary form-control d-flex justify-content-between');
                                    calendar.style.cursor = 'pointer';
                                    calendar.style.height = '33.5px';

                                    calendarIcon.setAttribute('class', 'fas fa-calendar pl-3');
                                    caretDownIcon.setAttribute('class', 'fas fa-caret-down');

                                    calendar.append(calendarIcon);
                                    calendar.append(dateFilterSpan);
                                    calendar.append(caretDownIcon);

                                    header.append(calendar);
                                    header.append(datePickerWrapper);

                                    input.setAttribute('id', header.getAttribute('data-dt-column') + '_filter');
                                    input.style.display = 'none';

                                    $(calendar).daterangepicker({
                                        startDate: startDate,
                                        endDate: endDate,
                                        locale: {
                                            format: 'YYYY-MM-DD'
                                        }
                                    }, function (startDate, endDate) {
                                        console.log(moment(startDate).format('YYYY-MM-DD'))
                                        console.log(moment(endDate).format('YYYY-MM-DD'))

                                        column.search(moment(startDate).format('YYYY-MM-DD'), moment(endDate).format('YYYY-MM-DD')).draw();
                                    });
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
            ]);
    }

    /**
     * Get filename for export.
     */
    public function filename(): string
    {
        return $this->exportFileName.date('YmdHis');
    }
}
