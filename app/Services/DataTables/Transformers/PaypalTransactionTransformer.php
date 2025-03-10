<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 09/02/2025
 * @time 16:43
 */

namespace App\Services\DataTables\Transformers;

use App\Models\PaypalTransaction;
use App\Utils\ActionWidget;
use League\Fractal\TransformerAbstract;

class PaypalTransactionTransformer extends TransformerAbstract
{
    /**
     * Data transformer.
     */
    public function transform(PaypalTransaction $paypalTransaction): array
    {
        $status = match ($paypalTransaction->status) {
            'D' => '<span class="badge badge-soft-danger">Denied</span>',
            'P' => '<span class="badge badge-soft-info">Pending</span>',
            'S' => '<span class="badge badge-soft-success">Completed</span>',
            'V' => '<span class="badge badge-soft-purple">Refunded</span>',
            default => '<span class="badge badge-soft-primary">Unknown</span>',
        };

        return [
            'id' => '<span class="fw-bold float-start">' . $paypalTransaction->id . '</span>',
            'datetime' => ! empty($paypalTransaction->datetime) ? '<span class="x-has-time-converter">' . $paypalTransaction->datetime->format(config('app.date_format')) . '</span>' : '-',
            'paygate_id' => $paypalTransaction->paygate_id,
            'paygate_name' => $paypalTransaction->paygate_name ?? '-',
            'name' => $paypalTransaction->name,
            'type' => $paypalTransaction->type,
            'event_code' => $paypalTransaction->event_code,
            'status' => $status,
            'currency' => $paypalTransaction->currency,
            'gross' => $paypalTransaction->gross,
            'fee' => $paypalTransaction->fee,
            'net' => $paypalTransaction->net,
            'from_email_address' => $paypalTransaction->from_email_address,
            'to_email_address' => $paypalTransaction->to_email_address,
            'transaction_id' => $paypalTransaction->transaction_id,
            'shipping_address' => $paypalTransaction->shipping_address,
            'address_status' => $paypalTransaction->address_status,
            'item_title' => $paypalTransaction->item_title,
            'item_id' => $paypalTransaction->item_id,
            'shipping_and_handling_amount' => $paypalTransaction->shipping_and_handling_amount,
            'insurance_amount' => $paypalTransaction->insurance_amount,
            'sales_tax' => $paypalTransaction->sales_tax,
            'option_1_name' => $paypalTransaction->option_1_name,
            'option_1_value' => $paypalTransaction->option_1_value,
            'option_2_name' => $paypalTransaction->option_2_name,
            'option_2_value' => $paypalTransaction->option_2_value,
            'reference_txn_id' => $paypalTransaction->reference_txn_id,
            'invoice_number' => $paypalTransaction->invoice_number,
            'custom_number' => $paypalTransaction->custom_number,
            'quantity' => $paypalTransaction->quantity,
            'receipt_id' => $paypalTransaction->receipt_id,
            'balance' => $paypalTransaction->balance,
            'address_line_1' => $paypalTransaction->address_line_1,
            'address_line_2' => $paypalTransaction->address_line_2,
            'town_city' => $paypalTransaction->town_city,
            'state_province' => $paypalTransaction->state_province,
            'zip_postal_code' => $paypalTransaction->zip_postal_code,
            'country' => $paypalTransaction->country,
            'contact_phone_number' => $paypalTransaction->contact_phone_number,
            'subject' => $paypalTransaction->subject,
            'note' => $paypalTransaction->note,
            'country_code' => $paypalTransaction->country_code,
            'balance_impact' => $paypalTransaction->balance_impact,
            'closed_at' => !empty($paypalTransaction->closed_at) ? '<span class="x-has-time-converter">' . $paypalTransaction->closed_at->format(
                    config('app.date_format')
                ) . '</span>' : '-',
            'last_checked_at' => !empty($paypalTransaction->last_checked_at) ? '<span class="x-has-time-converter">' . $paypalTransaction->last_checked_at->format(
                    config('app.date_format')
                ) . '</span>' : '-',
            'exported_at' => !empty($paypalTransaction->exported_at) ? '<span class="x-has-time-converter">' . $paypalTransaction->exported_at->format(
                    config('app.date_format')
                ) . '</span>' : '-',
            'created_at' => !empty($paypalTransaction->created_at) ? '<span class="x-has-time-converter">' . $paypalTransaction->created_at->format(
                    config('app.date_format')
                ) . '</span>' : '-',
            'updated_at' => !empty($paypalTransaction->updated_at) ? '<span class="x-has-time-converter">' . $paypalTransaction->updated_at->format(
                    config('app.date_format')
                ) . '</span>' : '-',
            'action' => $this->renderActions($paypalTransaction),
        ];
    }

    /**
     * Render action columns.
     */
    public function renderActions(PaypalTransaction $paypalTransaction): string
    {
        return '
            ' . ActionWidget::renderShowBtn(route('app.paypal-transaction.show', ['id' => $paypalTransaction->id])) . '
            ' . ActionWidget::renderMarkClosedBtn(
                $paypalTransaction->id,
                route('app.paypal-transaction.markclosed', ['id' => $paypalTransaction->id])
            ) . '
        ';
    }
}
