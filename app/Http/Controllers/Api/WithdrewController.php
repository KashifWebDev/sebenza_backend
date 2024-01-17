<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Paymentfrequency;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Withdrew;
use App\Models\Salary;
use App\Models\Bank;
use Illuminate\Http\Request;

class WithdrewController extends Controller
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
            $wits=Withdrew::where('membership_id',$user->membership_code)->get();
        }else{
            $wits=Withdrew::where('membership_id',$user->member_by)->get();
        }

        if(count($wits)>0){
            foreach($wits as $wit){
                $w=Withdrew::where('id',$wit->id)->first();
                $u=User::where('id',$w->user_id)->first();
                $w->full_name=$u->first_name . ' ' .$u->last_name;
                $withdrews[]=$w;
            }
        }else{
            $withdrews=[];
        }

        $response = [
            'status' => true,
            'message'=>'List of Withdrew',
            "data"=> [
                'withdrews'=> $withdrews,
            ]

        ];
        return response()->json($response,200);
    }

    public function getMywithdrew()
    {
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $user=User::where('id',$user_id->tokenable_id)->first();
            if(isset($user->membership_code)){
                $wits=Withdrew::where('user_id',$user->id)->where('membership_id',$user->membership_code)->get();
            }else{
                $wits=Withdrew::where('user_id',$user->id)->where('membership_id',$user->member_by)->get();
            }

            if(count($wits)>0){
                foreach($wits as $wit){
                    $w=Withdrew::where('id',$wit->id)->first();
                    $u=User::where('id',$w->user_id)->first();
                    $w->full_name=$u->first_name . ' ' .$u->last_name;
                    $withdrews[]=$w;
                }
            }else{
                $withdrews=[];
            }


            $response = [
                'status' => true,
                'message'=>'My Withdrew Info',
                "data"=> [
                    'withdrews'=> $withdrews,
                ]

            ];
            return response()->json($response,200);

        } catch (\Exception $e) {

            $response = [
                'status' => false,
                'message'=>$e->getMessage(),
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
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $withdrews=new Withdrew();
        $user=User::where('id',$user_id->tokenable_id)->first();
        $withdrews->user_id=$user->id;
        if(isset($user->membership_code)){
            $withdrews->membership_id=$user->membership_code;
        }else{
            $withdrews->membership_id=$user->member_by;
        }
        $bank=Bank::where('user_id',$user->id)->where('status','Active')->first();
        $withdrews->payment_method=$bank->payment_method;
        $withdrews->account_name=$bank->account_name;
        $withdrews->account_number=$bank->account_number;
        $withdrews->additional_info=$bank->additional_info;
        $withdrews->amount=$request->amount;
        $salary=Salary::where('user_id',$user->id)->first();
        if($salary->account_balance>=$request->amount){
            $success=$withdrews->save();
        }else{
            $response=[
                "status"=>false,
                'message' => "Not enough balance in your account.",
                "data"=> [
                    'withdrews'=> '',
                ]
            ];
            return response()->json($response, 200);
        }
        if($success){
            $salary->account_balance=$salary->account_balance-$request->amount;
            $salary->pending_withdrew=$salary->pending_withdrew+$request->amount;
            $salary->update();
            $response=[
                "status"=>true,
                'message' => "Withdrew request create successful",
                "data"=> [
                    'withdrews'=> $withdrews,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $withdrews->delete();
            $response=[
                "status"=>false,
                'message' => "Can not create withdrew request",
                "data"=> [
                    'withdrews'=> '',
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdrew  $withdrew
     * @return \Illuminate\Http\Response
     */
    public function show(Withdrew $withdrew)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdrew  $withdrew
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $withdrews =Withdrew::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Withdrew By ID',
            "data"=> [
                'withdrews'=> $withdrews,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdrew  $withdrew
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $withdrews=Withdrew::where('id',$id)->first();
        $user=User::where('id',$user_id->tokenable_id)->first();

        if($request->status=='Paid'){
            $withdrews->status=$request->status;
            $salary=Salary::where('user_id',$user->id)->first();
            $salary->withdrew_balance=$salary->withdrew_balance+$withdrews->amount;
            $salary->pending_withdrew=$salary->pending_withdrew-$withdrews->amount;
            $salary->update();
            $withdrews->save();
            $response=[
                "status"=>true,
                'message' => "Payment send successfully",
                "data"=> [
                    'withdrews'=> $withdrews,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $withdrews->status=$request->status;

            $salary=Salary::where('user_id',$user->id)->first();
            $salary->account_balance=$salary->account_balance+$withdrews->amount;
            $salary->pending_withdrew=$salary->pending_withdrew-$withdrews->amount;
            $salary->update();
            $withdrews->save();
            $response=[
                "status"=>true,
                'message' => "Payment canceled and refound to your account.",
                "data"=> [
                    'withdrews'=> $withdrews,
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdrew  $withdrew
     * @return \Illuminate\Http\Response
     */

}
