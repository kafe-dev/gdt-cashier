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
    public string|null $dateToFilter = null;
    public string|null $minDate = null;
    public string|null $maxDate = null;

    /**
     * @var string Table ID
     */
    protected string $tableId;

    /**
     * @var string Url to the create page
     */
    protected string $createUrl;

    /**
     * @var string Export file name
     */
    protected string $exportFileName;

    /**
     * @var array|string[] Defines the custom buttons for the DataTable
     */
    protected array $customButtons = [
        'create' => 'getCreateBtn',
    ];

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
            ->serverSide()
            ->parameters([
                'layout' => [
                    'topStart' => [
                        'buttons' => [
                            $this->renderButtons(),
                            'print', 'pdf',
                            [
                                'extend' => 'reset',
                                'attr' => [
                                    'id' => 'reset-btn',
                                ],
                            ],
                            $this->getReloadBtn(),
//                            [
//                                'extend' => 'searchPanes',
//                                'cascadePanes' => true,
//                                'attr' => [
//                                    'id' => 'filter-btn',
//                                ],
//                                'config' => [
//                                    'responsive' => true,
//                                    'layouts' => [
//                                        'columns-sm-1', 'columns-md-2', 'columns-3',
//                                    ],
//                                    'initCollapsed' => true,
//                                    'select' => [
//                                        'style' => 'multi',
//                                    ],
//                                ],
//                            ],
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
                    timeConverter();
                    ".$this->initScript()."
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

    /**
     * Returns the "create" btn.
     *
     * @return string[]
     */
    public function getCreateBtn(): array
    {
        return [
            'text' => '<i class="fa fa-plus"></i> New ',
            'className' => 'btn btn-success',
            'init' => "function (dt, node, config) {
                $(node).click(() => {
                    window.location.href = '".route($this->createUrl)."';
                });
            }",
        ];
    }

    public function getReloadBtn(): array {
        return [
            'text' => '<i class="fa fa-refresh" style=" height: 12px; width: auto"><svg fill="white" height="100%" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
	 viewBox="0 0 489.698 489.698" xml:space="preserve">
<g>
	<g>
		<path d="M468.999,227.774c-11.4,0-20.8,8.3-20.8,19.8c-1,74.9-44.2,142.6-110.3,178.9c-99.6,54.7-216,5.6-260.6-61l62.9,13.1
			c10.4,2.1,21.8-4.2,23.9-15.6c2.1-10.4-4.2-21.8-15.6-23.9l-123.7-26c-7.2-1.7-26.1,3.5-23.9,22.9l15.6,124.8
			c1,10.4,9.4,17.7,19.8,17.7c15.5,0,21.8-11.4,20.8-22.9l-7.3-60.9c101.1,121.3,229.4,104.4,306.8,69.3
			c80.1-42.7,131.1-124.8,132.1-215.4C488.799,237.174,480.399,227.774,468.999,227.774z"/>
		<path d="M20.599,261.874c11.4,0,20.8-8.3,20.8-19.8c1-74.9,44.2-142.6,110.3-178.9c99.6-54.7,216-5.6,260.6,61l-62.9-13.1
			c-10.4-2.1-21.8,4.2-23.9,15.6c-2.1,10.4,4.2,21.8,15.6,23.9l123.8,26c7.2,1.7,26.1-3.5,23.9-22.9l-15.6-124.8
			c-1-10.4-9.4-17.7-19.8-17.7c-15.5,0-21.8,11.4-20.8,22.9l7.2,60.9c-101.1-121.2-229.4-104.4-306.8-69.2
			c-80.1,42.6-131.1,124.8-132.2,215.3C0.799,252.574,9.199,261.874,20.599,261.874z"/>
	</g>
</g>
</svg></i> Reload ',
            'className' => 'btn btn-danger',
            'init' => "function (dt, node, config) {
                $(node).click(() => {
                    let url = window.location.href;

                    url = new URL(url).origin + new URL(url).pathname;
                    window.location.href = url;
                });
            }",
        ];
    }

    /**
     * Render all the custom buttons.
     */
    private function renderButtons(): array
    {
        $output = [];

        if (! empty($this->customButtons)) {
            foreach ($this->customButtons as $key => $callback) {
                $output[] = $this->$callback();
            }
        }

        return array_values($output);
    }

    /**
     * DataTable init script.
     *
     * @return string
     */
    private function initScript(): string
    {
        return "
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

                                    dateFilterSpan.setAttribute('id', header.getAttribute('data-dt-column') + '_filter_span');

                                    let minDate;
                                    let maxDate;
                                    let url = window.location.href;

                                    if (url.includes('dateToFilter') && url.includes('minDate') && url.includes('maxDate')) {
                                        let urlParams = new URLSearchParams(new URL(url).search);
                                        let dateToFilter = urlParams.get('dateToFilter');
                                        let currentMinDate = urlParams.get('minDate');
                                        let currentMaxDate = urlParams.get('maxDate');

                                        $(dateFilterSpan).each(function() {
                                            if (this.getAttribute('id') === dateToFilter + '_filter_span') {
                                                this.textContent = currentMinDate + ' - ' + currentMaxDate;
                                            }
                                        });
                                    }

                                    $(calendar).daterangepicker({
                                        startDate: startDate,
                                        endDate: endDate,
                                        locale: {
                                            format: 'YYYY-MM-DD'
                                        }
                                    }, function (startDate, endDate) {
                                        minDate = moment(startDate).format('YYYY-MM-DD');
                                        maxDate = moment(endDate).format('YYYY-MM-DD');
                                        url = new URL(url).origin + new URL(url).pathname;
                                        url += '?dateToFilter=' + header.getAttribute('data-dt-column') + '&minDate=' + minDate + '&maxDate=' + maxDate;

                                        window.location.href = url;
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
        ";
    }
}
