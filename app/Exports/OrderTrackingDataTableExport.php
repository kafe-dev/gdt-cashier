<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderTrackingDataTableExport implements FromCollection, WithHeadings
{
    /**
     * The collection of order tracking records to be exported.
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

    /**
     * Returns the collection of records formatted for export.
     *
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->records->map(function ($record) {
            return [
                'ID' => $record->id,
                'Paygate ID' => $record->paygate_id,
                'Invoice Number' => $record->invoice_number,
                'Transaction ID' => $record->transaction_id,
                'Tracking Number' => $record->tracking_number,
                'Courier Code' => $record->courier_code,
                'Tracking Status' => $record->tracking_status,
                'Type' => $record->type == 0 ? 'Open' : 'Closed',
                'Ordered At' => $record->ordered_at,
                'Closed At' => $record->closed_at,
                'Last Checked At' => $record->last_checked_at,
                'Exported At' => $record->exported_at,
                'Created At' => $record->created_at,
                'Updated At' => $record->updated_at,
            ];
        });
    }

    /**
     * Defines the column headings for the Excel export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Paygate ID',
            'Invoice Number',
            'Transaction ID',
            'Tracking Number',
            'Courier Code',
            'Tracking Status',
            'Type',
            'Ordered At',
            'Closed At',
            'Last Checked At',
            'Exported At',
            'Created At',
            'Updated At'
        ];
    }
}
