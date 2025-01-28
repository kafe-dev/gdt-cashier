<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class OrderTrackingExport implements FromCollection
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Order ID', 'Tracking Number', 'Courier Code', 'Tracking Status', 'Type', 'Created At', 'Updated At'
        ];
    }
}
