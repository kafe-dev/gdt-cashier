<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 10/02/2025
 * @time 17:24
 */

namespace App\Exports;

use App\Models\Paygate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaypalTransactionDataTableExport implements FromCollection, WithHeadings
{
    /**
     * The collection of paypal transaction records to be exported.
     *
     * @var Collection
     */
    protected Collection $records;

    /**
     * Constructor to initialize the records.
     *
     * @param Collection $records The collection of order tracking records.
     */
    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records->map(function ($record) {
            return [
                'ID' => $record->id,
                'Date' => $record->date,
                'Time' => $record->time,
                'Timezone' => $record->timezone,
                'Paygate' => Paygate::findOrFail($record->paygate_id)->name ?? "",
                'Name' => $record->name,
                'Type' => $record->type,
                'Event Code' => $record->event_code,
                'Status' => $record->status,
                'Currency' => $record->currency,
                'Gross' => $record->gross,
                'Fee' => $record->fee,
                'Net' => $record->net,
                'From Email' => $record->from_email_address,
                'To Email' => $record->to_email_address,
                'Transaction ID' => $record->transaction_id,
                'Shipping Address' => $record->shipping_address,
                'Address Status' => $record->address_status,
                'Item Title' => $record->item_title,
                'Item ID' => $record->item_id,
                'Shipping & Handling' => $record->shipping_and_handling_amount,
                'Insurance Amount' => $record->insurance_amount,
                'Sales Tax' => $record->sales_tax,
                'Option 1 Name' => $record->option_1_name,
                'Option 1 Value' => $record->option_1_value,
                'Option 2 Name' => $record->option_2_name,
                'Option 2 Value' => $record->option_2_value,
                'Reference Transaction ID' => $record->reference_txn_id,
//                'Invoice ID' => $record->invoice_id,
                'Invoice Number' => $record->invoice_number,
                'Custom Number' => $record->custom_number,
                'Quantity' => $record->quantity,
                'Receipt ID' => $record->receipt_id,
                'Balance' => $record->balance,
                'Address Line 1' => $record->address_line_1,
                'Address Line 2' => $record->address_line_2,
                'Town/City' => $record->town_city,
                'State/Province' => $record->state_province,
                'ZIP/Postal Code' => $record->zip_postal_code,
                'Country' => $record->country,
                'Contact Phone' => $record->contact_phone_number,
                'Subject' => $record->subject,
                'Note' => $record->note,
                'Country Code' => $record->country_code,
                'Balance Impact' => $record->balance_impact,
                'Closed At' => $record->closed_at,
                'Last Checked At' => $record->last_checked_at,
                'Exported At' => $record->exported_at,
                'Created At' => $record->created_at,
                'Updated At' => $record->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Time',
            'Timezone',
            'Paygate',
            'Name',
            'Type',
            'Event Code',
            'Status',
            'Currency',
            'Gross',
            'Fee',
            'Net',
            'From Email',
            'To Email',
            'Transaction ID',
            'Shipping Address',
            'Address Status',
            'Item Title',
            'Item ID',
            'Shipping & Handling',
            'Insurance Amount',
            'Sales Tax',
            'Option 1 Name',
            'Option 1 Value',
            'Option 2 Name',
            'Option 2 Value',
            'Reference Transaction ID',
//            'Invoice ID',
            'Invoice Number',
            'Custom Number',
            'Quantity',
            'Receipt ID',
            'Balance',
            'Address Line 1',
            'Address Line 2',
            'Town/City',
            'State/Province',
            'ZIP/Postal Code',
            'Country',
            'Contact Phone',
            'Subject',
            'Note',
            'Country Code',
            'Balance Impact',
            'Closed At',
            'Last Checked At',
            'Exported At',
            'Created At',
            'Updated At'
        ];
    }
}
