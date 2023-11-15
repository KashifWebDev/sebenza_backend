<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class CustomerExport implements FromQuery,WithHeadings,WithMapping
{


    use Exportable;
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function map($customer): array
    {
        return [
            $customer->created_at->format('Y-m-d'),
            $customer->membership_code,
            $customer->name,
            $customer->email,
            $customer->company_name,
            $customer->status,
        ];
    }

    public function query()
    {
            $user=$this->user;
            if(isset($user->membership_code)){
                return Customer::where('membership_code',$user->membership_code);
            }else{
                return Customer::where('membership_code',$user->member_by);
            }

    }


    public function headings(): array
    {
        return ["Created Date","Membership Code", "Name", "Email", "Company Name", "status"];
    }



}
