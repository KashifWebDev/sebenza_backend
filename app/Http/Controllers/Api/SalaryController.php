<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Paymentfrequency;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
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
            $sals=Salary::where('membership_id',$user->membership_code)->get();
        }else{
            $sals=Salary::where('membership_id',$user->member_by)->get();
        }

        foreach($sals as $sal){
            $u=User::where('id',$sal->user_id)->first();
            $sal->full_name=$u->first_name . '' .$u->last_name;
            $salarys[]=$sal;
        }
        $response = [
            'status' => true,
            'message'=>'List of Salary',
            "data"=> [
                'salarys'=> $salarys,
            ]

        ];
        return response()->json($response,200);
    }

    public function getMySalary()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $user=User::where('id',$user_id->tokenable_id)->first();
        if(isset($user->membership_code)){
            $salarys=Salary::where('user_id',$user->id)->where('membership_id',$user->membership_code)->first();
        }else{
            $salarys=Salary::where('user_id',$user->id)->where('membership_id',$user->member_by)->first();
        }

        $response = [
            'status' => true,
            'message'=>'My Salary Info',
            "data"=> [
                'salarys'=> $salarys,
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
        $exist =Salary::where('user_id',$request->user_id)->first();

        if(isset($exist)){
            $response = [
                'status' => true,
                'message'=>'Already have a salary with this account. Please change user or update its salary.',
                "data"=> [
                    'salarys'=> '',
                ]
            ];
            return response()->json($response,200);
        }else{
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $salarys=new Salary();
            $user=User::where('id',$user_id->tokenable_id)->first();
            $salarys->created_by=$user->id;
            $salarys->user_id=$request->user_id;
            $salarys->payment_frequency_id=$request->payment_frequency_id;
            $salarys->payment_frequency=Paymentfrequency::where('id',$request->payment_frequency_id)->first()->frequecy_name;
                if(isset($user->membership_code)){
                    $salarys->membership_id=$user->membership_code;
                }else{
                    $salarys->membership_id=$user->member_by;
                }
            $salarys->basic_salaray=$request->basic_salaray;
            $salarys->hourly_rate=$request->hourly_rate;
            $salarys->working_hour=$request->working_hour;
            $salarys->save();
            $response=[
                "status"=>true,
                'message' => "Salary create successful",
                "data"=> [
                    'salarys'=> $salarys,
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Salary $salary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salarys =Salary::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Salary By ID',
            "data"=> [
                'salarys'=> $salarys,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $salarys=Salary::where('id',$id)->first();
        $user=User::where('id',$user_id->tokenable_id)->first();
        $salarys->created_by=$user->id;
        $salarys->user_id=$request->user_id;
        $salarys->payment_frequency_id=$request->payment_frequency_id;
        $salarys->payment_frequency=Paymentfrequency::where('id',$request->payment_frequency_id)->first()->frequecy_name;
            if(isset($user->membership_code)){
                $salarys->membership_id=$user->membership_code;
            }else{
                $salarys->membership_id=$user->member_by;
            }
        $salarys->basic_salaray=$request->basic_salaray;
        $salarys->hourly_rate=$request->hourly_rate;
        $salarys->working_hour=$request->working_hour;
        $salarys->update();
        $response=[
            "status"=>true,
            'message' => "Salary update successful",
            "data"=> [
                'salarys'=> $salarys,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salarys =Salary::where('id',$id)->first();
        $salarys->delete();

        $response = [
            'status' => true,
            'message'=> 'Salary delete successfully',
            "data"=> [
                'salarys'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}
