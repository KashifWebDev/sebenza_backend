<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Paymentfrequency;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class PaymentfrequencyController extends Controller
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
        if(isset($user->membership_code)){
            $paymentfrequencys =Paymentfrequency::where('membership_id',$user->membership_code)->get();
        }else{
            $paymentfrequencys =Paymentfrequency::where('membership_id',$user->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Paymentfrequency',
            "data"=> [
                'paymentfrequencys'=> $paymentfrequencys,
            ]

        ];
        return response()->json($response,200);
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
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $paymentfrequencys=new Paymentfrequency();

        $user=User::where('id',$user_id->tokenable_id)->first();
        if(isset($user->membership_code)){
            $paymentfrequencys->membership_id=$user->membership_code;
        }else{
            $paymentfrequencys->membership_id=$user->member_by;
        }

        $paymentfrequencys->user_id=$user->id;
        $paymentfrequencys->frequecy_name=$request->frequecy_name;
        $paymentfrequencys->save();
        $response=[
            "status"=>true,
            'message' => "Paymentfrequencys create successful",
            "data"=> [
                'paymentfrequencys'=> $paymentfrequencys,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paymentfrequency  $paymentfrequency
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paymentfrequencys =Paymentfrequency::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Paymentfrequencys By ID',
            "data"=> [
                'paymentfrequencys'=> $paymentfrequencys,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paymentfrequency  $paymentfrequency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $paymentfrequencys =Paymentfrequency::where('id',$id)->first();
        $user=User::where('id',$user_id->tokenable_id)->first();
        if(isset($user->membership_code)){
            $paymentfrequencys->membership_id=$user->membership_code;
        }else{
            $paymentfrequencys->membership_id=$user->member_by;
        }
        $paymentfrequencys->user_id=$user->id;
        $paymentfrequencys->frequecy_name=$request->frequecy_name;
        $paymentfrequencys->status=$request->status;
        $paymentfrequencys->update();
        $response=[
            "status"=>true,
            'message' => "Paymentfrequency update successfully",
            "data"=> [
                'paymentfrequencys'=> $paymentfrequencys,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paymentfrequency  $paymentfrequency
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentfrequencys =Paymentfrequency::where('id',$id)->first();
        $paymentfrequencys->delete();

        $response = [
            'status' => true,
            'message'=> 'Paymentfrequency delete successfully',
            "data"=> [
                'paymentfrequencys'=> [],
            ]
        ];
        return response()->json($response,200);
    }

}
