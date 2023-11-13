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

class SaleExport implements FromQuery, WithHeadings,WithMapping
{




    use Exportable;
    private $curierid;

    public function __construct($startDate,$endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function map($order): array
    {
        return [
            $order->orderDate,
            $order->invoiceID,
            $order->customer_name,
            $order->customer_phone,
            $order->customer_address,
            $order->amount_total,
            $order->discount,
            $order->payable_amount,
            $order->paid_amount,
            $order->due,
            implode(', ', $order->saleitems->pluck('item_name')->toArray()),

        ];
    }

    public function query()
    {
        $startDate=$this->startDate;
        $endDate=$this->endDate;
        return Sale::with(['saleitems'])->get();
    }


    public function headings(): array
    {
        return ["Date","Invoice", "Customer Name", "Contact No.", "Customer Address", "Price", "Discount", "Payable Amount", "Paid", "Due", "Item Info"];
    }



}
