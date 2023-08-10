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
        $rows= ++$this->currentRow;
        $email=User::where('email', $row[0])->first();
        if($email){

        }else{
            if($row[0]=='Email'){

            }else{
                $token = request()->bearerToken();
                $user_id=PersonalAccessToken::findToken($token);
                $memberof=User::where('id', $user_id->tokenable_id)->first();
                $count=User::where('member_by', $memberof->membership_code)->get()->count();

                if(($memberof->user_limit_id-$count)>=$rows){

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
                    $response = [
                        'status' =>false,
                        'message' => "You don not have limit to add user. Please update your limit.",
                        "data"=> [
                            "user"=>[],
                        ]
                    ];
                    return response()->json($response,201);
                }

                return $user;
            }
        }

    }
}
