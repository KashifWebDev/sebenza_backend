<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

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
            if($row[0]=='Email'){

            }else{
                $token = request()->bearerToken();
                $user_id=PersonalAccessToken::findToken($token);
                $memberof=User::where('id', $user_id->tokenable_id)->first();
                $count=User::where('member_by', $memberof->membership_code)->get()->count();

                if($count<$memberof->user_limit_id){

                    $user=new User();
                    $user->email=$row[0];
                    $user->member_by=$memberof->membership_code;
                    $user->assignRole('user');
                    $user->save();

                    $details = [
                        'title' => 'Join '.env('APP_NAME').' - Empower Your Business Together',
                        "user"=>$user,
                    ];

                    \Mail::to($user->email)->send(new \App\Mail\SendMailInvitation($details));

                }else{
                    return $user;
                }

                return $user;
            }
        }

    }
}
