<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VoucherSalesExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $sales = Payment::with('patient', 'voucherHeader.paketVoucher')->get();
        
        $data = [];
        foreach ($sales as $sale) {
            $data[] = [
                'Patient Name' => $sale->patient->name,
                'Voucher Name' => $sale->voucherHeader->paketVoucher->name,
                'Price' => number_format($sale->amount),
                'Payment Method' => $sale->payment_method,
                'Payment Date' => $sale->created_at->format('Y-m-d'),
            ];
        }

        return $data;
    }

    // Define the headings
    public function headings(): array
    {
        return [
            'Patient Name',
            'Voucher Name',
            'Price',
            'Payment Method',
            'Payment Date',
        ];
    }
}
