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
        if($email){

        }else{
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $memberof=User::where('id', $user_id->tokenable_id)->first();

            $user=new User();
            $user->email=$row[0];
            $user->member_by=$memberof->membership_code;
            $user->assignRole('user');
            $user->save();
            return $user;
        }

    }
}
