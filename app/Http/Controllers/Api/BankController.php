<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $user=User::where('id',$user_id->tokenable_id)->first();
        $banks=Bank::where('user_id',$user->id)->get();

        $response = [
            'status' => true,
            'message'=>'List of my Bank',
            "data"=> [
                'banks'=> $banks,
            ]

        ];
        return response()->json($response,200);
    }

    public function getCompanyUserBank()
    {
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $user=User::where('id',$user_id->tokenable_id)->first();
            if(isset($user->membership_code)){
                $wits=Bank::where('membership_id',$user->membership_code)->get();
            }else{
                $wits=Bank::where('membership_id',$user->member_by)->get();
            }

            if(isset($wits)){
                foreach($wits as $wit){
                    $w=Bank::where('id',$wit->id)->first();
                    $u=User::where('id',$w->user_id)->first();
                    $w->full_name=$u->first_name . ' ' .$u->last_name;
                    $banks[]=$w;
                }
            }else{
                $banks=[];
            }


            $response = [
                'status' => true,
                'message'=>'Company User Bank Info',
                "data"=> [
                    'banks'=> $banks,
                ]

            ];
            return response()->json($response,200);

        } catch (\Exception $e) {

            $response = [
                "status"=>false,
                'message' => "Something went wrong. Please try again.",
                "data"=> [
                    'banks'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $banks=new Bank();
            $user=User::where('id',$user_id->tokenable_id)->first();
            $banks->user_id=$user->id;
            if(isset($user->membership_code)){
                $banks->membership_id=$user->membership_code;
            }else{
                $banks->membership_id=$user->member_by;
            }
            $banks->payment_method=$request->payment_method;
            $banks->account_name=$request->account_name;
            $banks->account_number=$request->account_number;
            $banks->additional_info=$request->additional_info;
            $banks->status=$request->status;
            $banks->save();
            $response=[
                "status"=>true,
                'message' => "Bank create successful",
                "data"=> [
                    'banks'=> $banks,
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response=[
                "status"=>false,
                'message' => "Can not create bank. Something went wrong",
                "data"=> [
                    'banks'=> '',
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $banks =Bank::where('id',$id)->first();
            $response = [
                'status' => true,
                'message'=>'Bank view by ID',
                "data"=> [
                    'banks'=> $banks,
                ]
            ];
            return response()->json($response,200);

        } catch (\Exception $e) {
            $response=[
                "status"=>false,
                'message' => "Something went wrong please try again !",
                "data"=> [
                    'banks'=> '',
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $banks=Bank::where('id',$id)->first();
            if(isset($banks)){
                $banks->payment_method=$request->payment_method;
                $banks->account_name=$request->account_name;
                $banks->additional_info=$request->additional_info;
                $banks->status=$request->status;
                $banks->update();
                $response=[
                    "status"=>true,
                    'message' => "Bank update successful",
                    "data"=> [
                        'banks'=> $banks,
                    ]
                ];
                return response()->json($response, 200);
            }else{
                $response=[
                    "status"=>false,
                    'message' => "Nothing found with this ID",
                    "data"=> [
                        'banks'=> '',
                    ]
                ];
                return response()->json($response, 200);
            }

        } catch (\Exception $e) {
            $response=[
                "status"=>false,
                'message' => "Can not update bank. Something went wrong",
                "data"=> [
                    'banks'=> '',
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */

}
