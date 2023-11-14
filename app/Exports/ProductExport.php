<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromQuery,WithHeadings,WithMapping
{


    use Exportable;
    private $startDate;
    private $endDate;
    private $user;

    public function __construct($startDate,$endDate,$user)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->user = $user;
    }


    public function map($sale): array
    {
        return [
            $sale->created_at_>format('Y-m-d'),
            $sale->membership_code,
            $sale->ProductName,
            $sale->BrandName,
            $sale->UnitPrice,
            $sale->SalePrice,
            $sale->status,
        ];
    }

    public function query()
    {
        $startDate=$this->startDate;
        $endDate=$this->endDate;
        $user=$this->user;
        if(isset($user->membership_code)){
            return Product::where('membership_code',$user->membership_code)->whereBetween('created_at', [$startDate, $endDate]);
        }else{
            return Product::where('membership_code',$user->member_by)->whereBetween('created_at', [$startDate, $endDate]);
        }
    }


    public function headings(): array
    {
        return ["Date","Membership Code", "Product Name", "Brand Name", "UnitPrice", "SalePrice", "status"];
    }



}
