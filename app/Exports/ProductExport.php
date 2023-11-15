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
            $sale->created_at->format('Y-m-d'),
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
        $user=$this->user;
        $user_id=$this->user_id;

        if($user_id->name=='user'){
            $user=$this->user;
            if(isset($user->membership_code)){
                return Product::where('membership_code',$user->membership_code);
            }else{
                return Product::where('membership_code',$user->member_by);
            }
        }else{
            return Product::whereIn('status',['0','1']);
        }

    }


    public function headings(): array
    {
        return ["Date","Membership Code", "Product Name", "Brand Name", "UnitPrice", "SalePrice", "status"];
    }



}
