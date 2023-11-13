<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleExport implements FromQuery,WithHeadings,WithMapping
{


    use Exportable;
    private $startDate;
    private $endDate;

    public function __construct($startDate,$endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function map($sale): array
    {
        return [
            $sale->orderDate,
            $sale->invoiceID,
            $sale->customer_name,
            $sale->customer_phone,
            $sale->customer_address,
            $sale->amount_total,
            $sale->discount,
            $sale->payable_amount,
            $sale->paid_amount,
            $sale->due,
            implode(', ', $sale->saleitems->pluck('item_name')->toArray()),

        ];
    }

    public function query()
    {
        $startDate=$this->startDate;
        $endDate=$this->endDate;
        return Sale::whereBetween('orderDate', [$startDate, $endDate]);
    }


    public function headings(): array
    {
        return ["Date","Invoice", "Customer Name", "Contact No.", "Customer Address", "Price", "Discount", "Payable Amount", "Paid", "Due", "Item Info"];
    }



}
