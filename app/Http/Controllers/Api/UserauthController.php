<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Accounttype;
use App\Models\Accountpackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use App\Imports\UserImport;
use Excel;

class UserauthController extends Controller
{

    public function userImport(Request $request){
        Excel::import(new UserImport, $request->file);
        $response = [
            'status' =>true,
            'message' => "Unique user Import Successfull",
        ];
        return response()->json($response,201);
    }

    public function userstore(Request $request){
        $email=User::where('email', $request->email)->first();
        $phonenumber=User::where('phone', $request->phone)->first();
        if($email){
            $response = [
                'status' =>false,
                'message' => "Email Already Taken",
                "data"=> [
                    "token"=> '',
                    "user"=>[],
                ]
            ];
            return response()->json($response,201);
        }elseif($phonenumber){
                $response = [
                    'status' =>false,
                    'message' => "Phone number has Already Taken",
                    "data"=> [
                        "token"=> '',
                        "user"=>[],
                    ]
                ];
            return response()->json($response,201);
        }else{
            $user=new User();
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->phone=$request->phone;
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->membership_code=$this->uniqueID();
            $user->company_name=$request->company_name;
            $user->account_type_id=$request->account_type_id;
            if(isset($request->account_type_id)){
                $type=Accounttype::where('id',$request->account_type_id)->first();
                $user->account_type=$type->account_type;
            }
            $user->country=$request->country;
            $user->city=$request->city;
            $user->address=$request->address;

            $user->user_limit_id=$request->user_limit_id;
            // if(isset($request->user_limit_id)){
            //     $package=Accountpackage::where('id',$request->user_limit_id)->first();
            //     $user->user_limit=$package->account_package;
            // }
            $user->assignRole(5);
            $user->save();

            $token = $user->createToken('user')->plainTextToken;



            $response=[
                "status"=>true,
                "message"=>"User Create Successfully",
                "data"=> [
                    "token"=> $token,
                    "user"=>$user,
                ]
            ];
            return response()->json($response, 200);
        }
    }

    public function usercreate(Request $request,$code){
        $email=User::where('email', $request->email)->first();
        $phonenumber=User::where('phone', $request->phone)->first();
        if($email){
            $response = [
                'status' =>false,
                'message' => "Email Already Taken",
                "data"=> [
                    "token"=> '',
                    "user"=>[],
                ]
            ];
            return response()->json($response,201);
        }elseif($phonenumber){
                $response = [
                    'status' =>false,
                    'message' => "Phone number has Already Taken",
                    "data"=> [
                        "token"=> '',
                        "user"=>[],
                    ]
                ];
            return response()->json($response,201);
        }else{
            $memby=User::where('membership_code', $code)->first();
            $user=new User();
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->phone=$request->phone;
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->member_by=$code;
            $user->company_name=$memby->company_name;
            $user->country=$request->country;
            $user->city=$request->city;
            $user->address=$request->address;
            $user->assignRole($request->role);
            $user->save();


            $response=[
                "status"=>true,
                "message"=>"User Create Successfully",
                "data"=> [
                    "user"=>$user,
                ]
            ];
            return response()->json($response, 200);
        }
    }


    public function uniqueID()
    {
        $lastmember = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'superuser');
                }
            )->first();
        if ($lastmember) {
            $menberID = $lastmember->id + 1;
        } else {
            $menberID = 1;
        }

        return 'SEBENZA00' . $menberID;
    }


    public function userlogin(Request $request){
        $user = User::where('email', $request->email)
                    ->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            $error = [
                    "status"=>false,
                    "message"=>"Login failed",
                    "data"=> [
                        "token"=> '',
                        "user"=>[],
                    ]
            ];
            return response()->json($error);
        }


        $user = User::with(['roles'=>function ($query) { $query->select('id','name','guard_name');}])->where('id', $user->id)->first();

        $token = $user->createToken('user')->plainTextToken;

        $response = [
            "status"=>true,
            "message"=>"Login Successfully",
            "data"=>[
                'token' => $token,
                'user'=>$user,
            ],
        ];

        return response($response, 201);
    }

    public function userdetails($id){

        $user = User::with('roles')->where('id', $id)->first();

        $response = [
            "status"=>true,
            "message"=>"User Details",
            "data"=> [
                "user"=>$user,
            ]
        ];

        return response($response, 201);
    }

    public function userlogout(Request $request){
        $token = $request->token;
        $usertoken=PersonalAccessToken::findToken($token);
        $utoken = PersonalAccessToken::where('name',$usertoken->name)->where('tokenable_id', $usertoken->tokenable_id);
        $utoken->delete();
        $error = [
            'status'=>true,
            'message' => 'Logout Successfully',
            "data"=> [
                "user"=>[],
            ]
        ];
        return response()->json($error);
    }


}
