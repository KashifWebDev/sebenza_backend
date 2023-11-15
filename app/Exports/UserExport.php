<?php

namespace App\Exports;

use App\Models\User;
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
    private $user;
    private $user_id;

    public function __construct($user,$user_id)
    {
        $this->user = $user;
        $this->user_id = $user_id;
    }


    public function map($userss): array
    {
        if(isset($userss->membership_code)){
            return [
                $userss->company_name,
                $userss->created_at->format('Y-m-d'),
                $userss->account_type,
                $userss->membership_code,
                $userss->first_name,
                $userss->last_name,
                $userss->phone,
                $userss->email,
                $userss->address,
                $userss->city,
                $userss->country,
            ];
        }else{
            return [
                $userss->company_name,
                $userss->created_at->format('Y-m-d'),
                $userss->account_type,
                $userss->member_by,
                $userss->first_name,
                $userss->last_name,
                $userss->phone,
                $userss->email,
                $userss->address,
                $userss->city,
                $userss->country,
            ];
        }

    }

    public function query()
    {
        $user=$this->user;
        $user_id=$this->user_id;
        if($user_id->name=='user'){
            $user=$this->user;
            if(isset($user->membership_code)){
                return User::where('membership_code',$user->membership_code);
            }else{
                return User::where('membership_code',$user->member_by);
            }
        }else{
            return User::whereIn('status',['0','1']);
        }

    }


    public function headings(): array
    {
        return ["Company Name" ,"Join Date","Account Type","Membership Code", "First Name", "Last Name", "Phone", "Email" , "Address", "City", "Country"];
    }



}
