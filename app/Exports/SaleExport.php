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
    private $user;
    private $user_id;

    public function __construct($user,$user_id)
    {
        $this->user = $user;
        $this->user_id = $user_id;
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
        $user=$this->user;
        $user_id=$this->user_id;

        if($user_id->name=='user'){
            $user=$this->user;
            if(isset($user->membership_code)){
                return Sale::where('membership_code',$user->membership_code);
            }else{
                return Sale::where('membership_code',$user->member_by);
            }
        }else{
            return Sale::whereIn('status',['0','1']);
        }

    }

    public function headings(): array
    {
        return ["Date","Invoice", "Customer Name", "Contact No.", "Customer Address", "Price", "Discount", "Payable Amount", "Paid", "Due", "Item Info"];
    }



}
