<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Accounttype;
use App\Models\Basicinfo;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Accountpackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use App\Imports\UserImport;
use Excel;
use Carbon\Carbon;
use App\Models\Userexcel;
use App\Exports\UserExport;
use Illuminate\Support\Facades\Storage;

class UserauthController extends Controller
{

    public function fileExport(Request $request)
    {
        $startDate =$request->startDate;
        $endDate =$request->endDate;
        $time = microtime('.') * 10000;
        $fileName=$time.'user.xlsx';
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $user=User::where('id',$user_id->tokenable_id)->first();

        if(isset($startDate) && isset($endDate)){

            $file= Excel::store(new UserExport($startDate,$endDate,$user), $fileName);

            $userexcel=new Userexcel();
            $u=User::where('id',$user_id->tokenable_id)->first();
            $userexcel->user_id=$u->id;
            if(isset($u->membership_code)){
                $userexcel->membership_code=$u->membership_code;
            }else{
                $userexcel->membership_code=$u->member_by;
            }
            if ($file) {
                $userexcel->data_file = 'storage/app/'.$fileName;
            }
            $userexcel->startDate=$startDate;
            $userexcel->endDate=$endDate;
            $userexcel->date=date('Y-m-d');
            $userexcel->save();
            $response = [
                'status' => true,
                'message'=>'User Data Report File',
                "data"=> [
                    'userexcel'=> $userexcel,
                ]

            ];

        }else{
            $response = [
                'status' => false,
                'message'=>'Please Select Any Date',
                "data"=> [
                    'userexcel'=> '',
                ]

            ];
        }
        return response()->json($response,200);
    }
    public function userslist()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $userexcel =Userexcel::where('membership_code',$u->membership_code)->get();
        }else{
            $userexcel =Userexcel::where('membership_code',$u->member_by)->get();
        }

        if(isset($userexcel)){
            $response = [
                'status' => true,
                'message'=>'Users report data By Membership ID',
                "data"=> [
                    'userexcel'=> $userexcel,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No users report data found',
                "data"=> [
                    'userexcel'=> '',
                ]

            ];
        }
        return response()->json($response,200);
    }

    public function userImport(Request $request){

        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $memberof=User::where('id', $user_id->tokenable_id)->first();
        $count=User::where('member_by', $memberof->membership_code)->get()->count();

        if($count<$memberof->user_limit_id){
            Excel::import(new UserImport, $request->file);
            $response = [
                'status' =>true,
                'message' => "Unique user Import Successful",
            ];
            return response()->json($response,201);
        }else{
            $response = [
                'status' =>true,
                'message' => "You do not have user limit. Please remove some user from file.",
            ];
            return response()->json($response,201);
        }

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

            $user->assignRole(5);
            $result=$user->save();

            if($result){
                $webinfo =Basicinfo::first();
                $order=new Order();
                $order->user_id=$user->id;
                $order->membership_id=$user->membership_code;
                $order->account_total_user=$request->user_limit_id;
                $order->cost_per_user=$webinfo->cost_per_user;
                $amounttotal=($request->user_limit_id*$webinfo->cost_per_user);
                $order->amount_total=$amounttotal;
                $order->orderDate=date('Y-m-d');
                $order->account_type_id=$request->account_type_id;
                if(isset($request->account_type_id)){
                    $type=Accounttype::where('id',$request->account_type_id)->first();
                    $order->account_type=$type->account_type;
                }
                $successorder=$order->save();

                if($successorder){
                    $invoice=new Invoice();
                    $invoice->invoiceID=$this->invoiceID();
                    $invoice->order_id=$order->id;
                    $invoice->account_total_user=$request->user_limit_id;
                    $invoice->cost_per_user=$webinfo->cost_per_user;
                    $amounttotal=($request->user_limit_id*$webinfo->cost_per_user);
                    $invoice->amount_total=$amounttotal;
                    $invoice->payable_amount=$amounttotal;
                    $invoice->paid_amount=0;
                    $invoice->invoiceDate=date('Y-m-d');
                    $invoice->save();
                }

                $token = $user->createToken('user')->plainTextToken;


                $details = [
                    'title' => env('APP_NAME') . 'Registration Successful !',
                    "user"=>$user,
                ];

                \Mail::to($user->email)->send(new \App\Mail\SendMailReg($details));

                $invdetails = [
                    'title' => env('APP_NAME') . 'Subscription Invoice',
                    "user"=>$user,
                    "invoice"=>$invoice,
                ];

                \Mail::to($user->email)->send(new \App\Mail\SendMailInvoice($invdetails));

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
    }

    public function usercreate(Request $request){
        $email=User::where('email', $request->email)->first();
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
        }else{
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);

            $memby=User::where('id', $user_id->tokenable_id)->first();
            $count=User::where('member_by', $memby->membership_code)->get()->count();
            if($count<$memby->user_limit_id){
                $user=new User();
                $user->email=$request->email;
                $user->member_by=$memby->membership_code;
                $user->company_name=$memby->company_name;
                $user->assignRole($request->role);
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

    public function invoiceID()
    {
        $lastmember = Invoice::latest()->first();
        if ($lastmember) {
            $menberID = $lastmember->id + 1;
        } else {
            $menberID = 1;
        }

        return '#INV00' . $menberID;
    }

    public function uniqueID()
    {
        $lastmember = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'superuser');
                }
            )->latest()->first();
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

    public function userprofile(Request $request){

        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $user=User::where('id', $user_id->tokenable_id)->first();

        $response = [
            "status"=>true,
            "message"=>"My Profile Details",
            "data"=> [
                "user"=>$user,
            ]
        ];

        return response($response, 201);
    }

    public function userprofileupdate(Request $request){

        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $user=User::where('id', $user_id->tokenable_id)->first();
        $user->first_name=$request->firstName;
        $user->last_name=$request->lastName;
        $user->phone=$request->mobile;
        $user->address=$request->address;
        $user->postcode=$request->postcode;
        $user->state=$request->state;
        $user->country=$request->country;
        $user->city=$request->city;
        $time = microtime('.') * 10000;
        $productImg = $request->file('img');
        if($productImg){
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/backend/profile/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $user->profile = $productImgUrl;
        }

        $user->update();

        $response = [
            "status"=>true,
            "message"=>"Profile update successfully",
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


    public function memberjoininfo(Request $request){

        $user=User::where('email',$request->email)->first();

        $user->first_name=$request->firstName;
        $user->last_name=$request->lastName;
        $user->phone=$request->mobile;
        $user->address=$request->address;
        $user->postcode=$request->postcode;
        $user->state=$request->state;
        $user->country=$request->country;
        $user->city=$request->city;
        $time = microtime('.') * 10000;
        $productImg = $request->file('img');
        if($productImg){
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/backend/profile/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $user->profile = $productImgUrl;
        }
        $user->update();
        return $user;

        $usernew = User::with(['roles'=>function ($query) { $query->select('id','name','guard_name');}])->where('id', $user->id)->first();

        $token = $usernew->createToken('user')->plainTextToken;

        $response = [
            "status"=>true,
            "message"=>"Member Join successfully",
            "data"=>[
                'token'=>$token,
                'user'=>$usernew,
            ],
        ];

        return response($response, 201);
    }

}
