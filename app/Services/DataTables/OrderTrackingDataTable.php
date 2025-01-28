<?php

namespace App\Services\DataTables;

use App\Exports\OrderTrackingExport;
use App\Models\Filters\OrderTrackingFilter;
use App\Models\OrderTracking;
use App\Services\DataTables\Transformers\OrderTrackingTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class OrderTrackingDataTable extends BaseDataTable
{
    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'order_tracking-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.tracking.index';

    /**
     * actions array
     *
     * @var array|string[]
     */
    protected array $actions = ['print', 'excel', 'exportAndClose'];

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'Users_';

    /**
     * Return the query builder instance to be processed by DataTables.
     */
    public function query(OrderTracking $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * @inheritDoc
     */
    public function dataTable(): EloquentDataTable
    {
        $datatable = (new EloquentDataTable(new OrderTracking()))
            ->setTransformer(OrderTrackingTransformer::class)
            ->setRowId('id')
            ->escapeColumns([])
            ->rawColumns(['action'])
            ->filter(function ($query) {
                $query->where('type', '=', '0');
            });

        return OrderTrackingFilter::perform($datatable);
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return [
            Column::make(['data' => 'id', 'title' => 'ID'])->addClass('x-id'),
            Column::make('order_id')->searchPanes()->addClass('x-searchable'),
            Column::make('tracking_number')->searchPanes()->addClass('x-searchable'),
            Column::make('courier_code')->searchPanes()->addClass('x-searchable'),
            Column::make('tracking_status')->searchPanes(),
//            Column::make('tracking_data')->searchPanes(),
            Column::make('type')->searchPanes(),
            Column::make('closed_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('last_checked_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('exported_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('created_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('updated_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
        ];
    }

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
                            $this->renderButtons(),
                            'print', 'exportAndClose', 'reset',
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
                            timeConverter();

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

    public function exportAndClose(): \Illuminate\Http\JsonResponse
    {
        $query = OrderTracking::query();

        Excel::download(new OrderTrackingExport($query), 'order_tracking_data.xlsx');

        $query->update(['type' => OrderTracking::TYPE_CLOSED]);

        return response()->json(['status' => 'success', 'message' => 'Data exported and records updated to closed']);
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
}
