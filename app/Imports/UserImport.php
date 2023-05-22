<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;

class UserImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $email=User::where('email', $row[0])->first();
        $phonenumber=User::where('phone', $row[0])->first();
        if($email){

        }elseif($phonenumber){

        }else{


            return new User([
                'first_name'=>$row[1],
                'last_name'=>$row[2],
                'phone'=>$row[3],
                'email'=>$row[4],
                'password'=>Hash::make($row[9]),
                'member_by'=>$row[0],
                'company_name'=>$row[10],
                'country'=>$row[5],
                'city'=>$row[6],
                'address'=>$row[7],
            ]);

        }

    }
}
