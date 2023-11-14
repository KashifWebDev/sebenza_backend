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

class UserExport implements FromQuery,WithHeadings,WithMapping
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


    public function map($user): array
    {
        if(isset($user->membership_code)){
            return [
                $user->company_name,
                $user->created_at->format('Y-m-d'),
                $user->account_type,
                $user->membership_code,
                $user->first_name,
                $user->last_name,
                $user->phone,
                $user->email,
                $user->address,
                $user->city,
                $user->country,
            ];
        }else{
            return [
                $user->company_name,
                $user->created_at->format('Y-m-d'),
                $user->account_type,
                $user->member_by,
                $user->first_name,
                $user->last_name,
                $user->phone,
                $user->email,
                $user->address,
                $user->city,
                $user->country,
            ];
        }

    }

    public function query()
    {
        $startDate=$this->startDate;
        $endDate=$this->endDate;
        $user=$this->user;
        if(isset($user->membership_code)){
            return User::where('membership_code',$user->membership_code)->whereBetween('created_at', [$startDate, $endDate]);
        }else{
            return User::where('membership_code',$user->member_by)->whereBetween('created_at', [$startDate, $endDate]);
        }
    }


    public function headings(): array
    {
        return ["Company Name" ,"Join Date","Account Type","Membership Code", "First Name", "Last Name", "Phone", "Email" , "Address", "City", "Country"];
    }



}
