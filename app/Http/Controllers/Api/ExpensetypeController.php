<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use App\Models\Expensetype;

class ExpensetypeController extends Controller
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
            $expensetypes =Expensetype::where('membership_id',$u->membership_code)->get();
        }else{
            $expensetypes =Expensetype::where('membership_id',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Expensetypes',
            "data"=> [
                'expensetypes'=> $expensetypes,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getexpencetype()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $expensetypes =Expensetype::where('membership_id',$u->membership_code)->where('status','Active')->get();
        }else{
            $expensetypes =Expensetype::where('membership_id',$u->member_by)->where('status','Active')->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Expensetypes',
            "data"=> [
                'expensetypes'=> $expensetypes,
            ]

        ];
        return response()->json($response,200);
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
        $expensetypes=new Expensetype();
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $expensetypes->membership_id=$u->membership_code;
        }else{
            $expensetypes->membership_id=$u->member_by;
        }
        $expensetypes->expence_type=$request->expence_type;
        $expensetypes->status=$request->status;
        $expensetypes->save();
        $response=[
            "status"=>true,
            'message' => "Expensetypes create successful",
            "data"=> [
                'expensetypes'=> $expensetypes,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expensetype  $expensetype
     * @return \Illuminate\Http\Response
     */
    public function show(Expensetype $expensetype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expensetype  $expensetype
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expensetypes =Expensetype::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Expensetype By ID',
            "data"=> [
                'expensetypes'=> $expensetypes,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expensetype  $expensetype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $expensetypes =Expensetype::where('id',$id)->first();
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $expensetypes->membership_id=$u->membership_code;
        }else{
            $expensetypes->membership_id=$u->member_by;
        }
        $expensetypes->expence_type=$request->expence_type;
        $expensetypes->status=$request->status;
        $expensetypes->update();
        $response=[
            "status"=>true,
            'message' => "Expensetype update successfully",
            "data"=> [
                'expensetypes'=> $expensetypes,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expensetype  $expensetype
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expensetypes =Expensetype::where('id',$id)->first();
        $expensetypes->delete();

        $response = [
            'status' => true,
            'message'=> 'Expensetype delete successfully',
            "data"=> [
                'expensetypes'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}
