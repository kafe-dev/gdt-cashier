<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 09/02/2025
 * @time 16:34
 */

namespace App\Services\DataTables;

use App\Models\Filters\PaypalTransactionFilter;
use App\Models\PaypalTransaction;
use App\Services\DataTables\Transformers\PaypalTransactionTransformer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class PaypalTransactionDatatable extends BaseDataTable
{
    /**
     * {@inheritdoc}
     */
    protected string $tableId = 'paypal_transactions-table';

    /**
     * {@inheritdoc}
     */
    protected string $createUrl = 'app.paypal_transaction.index';

    /**
     * {@inheritdoc}
     */
    protected string $exportFileName = 'PayPal_Transactions_';

    /**
     * {@inheritdoc}
     */
    protected array $customButtons = [
        'sync' => 'getSyncBtn',
        'exportExcel' => 'getExportExcelBtn',
    ];

    /**
     * Return the query builder instance to be processed by DataTables.
     */
    public function query(PaypalTransaction $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function dataTable(): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable(new PaypalTransaction()))
            ->setTransformer(PaypalTransactionTransformer::class)
            ->setRowId('id')
            ->rawColumns(['action'])
            ->filter(function ($query) {
                $query->where('closed_at', '=', null);

                if (!empty($this->dateToFilter) && !empty($this->minDate) && !empty($this->maxDate)) {
                    $query->whereBetween($this->dateToFilter, [$this->minDate, $this->maxDate]);
                }
            });
        return PaypalTransactionFilter::perform($dataTable);
    }

    public function getColumns(): array
    {
        return [
            Column::make(['data' => 'id', 'title' => 'ID'])->addClass('x-id'),
            Column::make('date')->searchPanes()->addClass('x-searchable'),
            Column::make('time')->searchPanes()->addClass('x-searchable'),
            Column::make('timezone')->searchPanes()->addClass('x-searchable'),
            Column::make('paygate_id')->searchPanes()->addClass('x-searchable'),
            Column::make('name')->searchPanes()->addClass('x-searchable'),
//            Column::make('type')->searchPanes()->addClass('x-searchable'),
//            Column::make('event_code')->searchPanes()->addClass('x-searchable'),
            Column::make('status')->searchPanes()->addClass('x-searchable'),
            Column::make('currency')->searchPanes()->addClass('x-searchable'),
            Column::make('gross')->searchPanes()->addClass('x-searchable'),
            Column::make('fee')->searchPanes()->addClass('x-searchable'),
            Column::make('net')->searchPanes()->addClass('x-searchable'),
//            Column::make('from_email_address')->searchPanes()->addClass('x-searchable'),
//            Column::make('to_email_address')->searchPanes()->addClass('x-searchable'),
            Column::make('transaction_id')->searchPanes()->addClass('x-searchable'),
//            Column::make('shipping_address')->searchPanes()->addClass('x-searchable'),
//            Column::make('address_status')->searchPanes()->addClass('x-searchable'),
//            Column::make('item_title')->searchPanes()->addClass('x-searchable'),
//            Column::make('item_id')->searchPanes()->addClass('x-searchable'),
//            Column::make('shipping_and_handling_amount')->searchPanes()->addClass('x-searchable'),
//            Column::make('insurance_amount')->searchPanes()->addClass('x-searchable'),
//            Column::make('sales_tax')->searchPanes()->addClass('x-searchable'),
//            Column::make('option_1_name')->searchPanes()->addClass('x-searchable'),
//            Column::make('option_1_value')->searchPanes()->addClass('x-searchable'),
//            Column::make('option_2_name')->searchPanes()->addClass('x-searchable'),
//            Column::make('option_2_value')->searchPanes()->addClass('x-searchable'),
//            Column::make('reference_txn_id')->searchPanes()->addClass('x-searchable'),
//            Column::make('invoice_id')->searchPanes()->addClass('x-searchable'),
            Column::make('invoice_number')->searchPanes()->addClass('x-searchable'),
//            Column::make('custom_number')->searchPanes()->addClass('x-searchable'),
//            Column::make('quantity')->searchPanes()->addClass('x-searchable'),
//            Column::make('receipt_id')->searchPanes()->addClass('x-searchable'),
            Column::make('balance')->searchPanes()->addClass('x-searchable'),
//            Column::make('address_line_1')->searchPanes()->addClass('x-searchable'),
//            Column::make('address_line_2')->searchPanes()->addClass('x-searchable'),
//            Column::make('town_city')->searchPanes()->addClass('x-searchable'),
//            Column::make('state_province')->searchPanes()->addClass('x-searchable'),
//            Column::make('zip_postal_code')->searchPanes()->addClass('x-searchable'),
//            Column::make('country')->searchPanes()->addClass('x-searchable'),
//            Column::make('contact_phone_number')->searchPanes()->addClass('x-searchable'),
//            Column::make('subject')->searchPanes()->addClass('x-searchable'),
//            Column::make('note')->searchPanes()->addClass('x-searchable'),
//            Column::make('country_code')->searchPanes()->addClass('x-searchable'),
//            Column::make('balance_impact')->searchPanes()->addClass('x-searchable'),
            Column::make('closed_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('last_checked_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('exported_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('created_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
            Column::make('updated_at')->searchPanes()->addClass('x-has-date-filter')->orderable(false),
        ];
    }

    /**
     * Returns the "sync" btn.
     *
     * @return string[]
     */
    public function getSyncBtn(): array
    {
        return [
            'text' => '<i class="fa fa-sync"></i> Sync ',
            'className' => 'btn btn-primary',
            'init' => "function (dt, node, config) {
                $(node).css('background-color', 'rgba(23, 97, 253, 0.75)');
                $(node).css('border-color', 'rgba(23, 97, 253, 0.75)');

                $(node).click(() => {
                    window.location.reload();
                });
            }",
        ];
    }

    /**
     * Returns the "exportExcel" button.
     *
     * @return string[]
     */
    public function getExportExcelBtn(): array
    {
        return [
            'text' => '<i class="fa fa-file-excel"></i> Export',
            'className' => 'btn btn-success',
            'init' => "function (dt, node, config) {
                $(node).click(() => {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '" . route('app.paypal-transaction.export') . "';

                    var csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '" . csrf_token() . "';

                    form.appendChild(csrfToken);

                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'key';
                    input.value = 'value';
                    form.appendChild(input);

                    document.body.appendChild(form);
                    form.submit();

                    $.ajax({
                        type: 'POST',
                        url: form.action,
                        data: $(form).serialize(),
                        success: function(response) {
                            alert('Exported successfully');
                            $('#reset-btn').click();
                        },
                        error: function() {
                        }
                    });
                });
            }",
        ];
    }
}
