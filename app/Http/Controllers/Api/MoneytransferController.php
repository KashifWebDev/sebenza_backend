<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Moneytransfer;
use Illuminate\Http\Request;

class MoneytransferController extends Controller
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
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $moneytransfers =Moneytransfer::where('membership_code',$u->membership_code)->get();
        }else{
            $moneytransfers =Moneytransfer::where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of My Moneytransfers',
            "data"=> [
                'moneytransfers'=> $moneytransfers,
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
        $moneytransfers=new Moneytransfer();
        $moneytransfers->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $moneytransfers->membership_code=$u->membership_code;
        }else{
            $moneytransfers->membership_code=$u->member_by;
        }
        $moneytransfers->transfer_date=$request->transfer_date;
        $moneytransfers->from=$request->from;
        $moneytransfers->to=$request->to;
        $moneytransfers->sender_name=$request->sender_name;
        $moneytransfers->paid_amount=$request->paid_amount;
        $moneytransfers->currency=$request->currency;
        $moneytransfers->transfer_rate=$request->transfer_rate;

        $moneytransfers->collected_amount=$request->paid_amount-($request->paid_amount*($request->transfer_rate/100));
        $moneytransfers->receiver_name=$request->receiver_name;
        $moneytransfers->reference_code=$request->reference_code;
        $moneytransfers->collected_status=$request->collected_status;
        $moneytransfers->save();
        $response=[
            "status"=>true,
            'message' => "Moneytransfer successful",
            "data"=> [
                'moneytransfers'=> $moneytransfers,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Moneytransfer  $moneytransfer
     * @return \Illuminate\Http\Response
     */
    public function show(Moneytransfer $moneytransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Moneytransfer  $moneytransfer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $moneytransfers =Moneytransfer::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $moneytransfers =Moneytransfer::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($moneytransfers)){
            $response = [
                'status' => true,
                'message'=>'Moneytransfer By ID',
                "data"=> [
                    'moneytransfers'=> $moneytransfers,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No moneytransfers find by this ID',
                "data"=> [
                    'moneytransfers'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Moneytransfer  $moneytransfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $moneytransfers =Moneytransfer::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $moneytransfers =Moneytransfer::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($moneytransfers)){
            $moneytransfers->transfer_date=$request->transfer_date;
            $moneytransfers->from=$request->from;
            $moneytransfers->to=$request->to;
            $moneytransfers->sender_name=$request->sender_name;
            $moneytransfers->paid_amount=$request->paid_amount;
            $moneytransfers->currency=$request->currency;
            $moneytransfers->transfer_rate=$request->transfer_rate;

            $moneytransfers->collected_amount=$request->paid_amount-($request->paid_amount*($request->transfer_rate/100));
            $moneytransfers->receiver_name=$request->receiver_name;
            $moneytransfers->reference_code=$request->reference_code;
            $moneytransfers->collected_status=$request->collected_status;

            $moneytransfers->update();
            $response=[
                "status"=>true,
                'message' => "Moneytransfer update successfully",
                "data"=> [
                    'moneytransfers'=> $moneytransfers,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No moneytransfer find by this ID',
                "data"=> [
                    'moneytransfers'=> '',
                ]

            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Moneytransfer  $moneytransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $moneytransfers =Moneytransfer::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $moneytransfers =Moneytransfer::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($moneytransfers)){
            $moneytransfers->delete();
            $response = [
                'status' => true,
                'message'=> 'Moneytransfer delete successfully',
                "data"=> [
                    'moneytransfers'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No moneytransfer find by this ID',
                "data"=> [
                    'moneytransfers'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }
}
