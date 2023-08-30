<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Vattex;
use Illuminate\Http\Request;

class VattexController extends Controller
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
            $vattexs=Vattex::where('membership_id',$user->membership_code)->first();
        }else{
            $vattexs=Vattex::where('membership_id',$user->member_by)->first();
        }

        $response = [
            'status' => true,
            'message'=>'My Company Vattax',
            "data"=> [
                'vattaxs'=> $vattexs,
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
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $user=User::where('id',$user_id->tokenable_id)->first();

            if(isset($user->membership_code)){
                $vattexs=Vattex::where('membership_id',$user->membership_code)->first();
            }else{
                $vattexs=Vattex::where('membership_id',$user->member_by)->first();
            }
            if(isset($vattexs)){
                $vattexs=new Vattex();
                $user=User::where('id',$user_id->tokenable_id)->first();
                $vattexs->user_id=$user->id;
                if(isset($user->membership_code)){
                    $vattexs->membership_id=$user->membership_code;
                }else{
                    $vattexs->membership_id=$user->member_by;
                }
                $vattexs->vat=$request->vat;
                $vattexs->tax=$request->tax;
                $vattexs->save();
                $response=[
                    "status"=>true,
                    'message' => "Vattax create successful",
                    "data"=> [
                        'vattaxs'=> $vattexs,
                    ]
                ];
                return response()->json($response, 200);
            }else{
                $response=[
                    "status"=>false,
                    'message' => "Already have vat tax info for your company.",
                    "data"=> [
                        'vattaxs'=> '',
                    ]
                ];
                return response()->json($response, 200);
            }

        } catch (\Exception $e) {
            $response=[
                "status"=>false,
                'message' => "Can not create vattex. Something went wrong",
                "data"=> [
                    'vattaxs'=> '',
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vattex  $vattex
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $vattexs =Vattex::where('id',$id)->first();
            $response = [
                'status' => true,
                'message'=>'Vattax view by ID',
                "data"=> [
                    'vattaxs'=> $vattexs,
                ]
            ];
            return response()->json($response,200);

        } catch (\Exception $e) {
            $response=[
                "status"=>false,
                'message' => "Something went wrong please try again !",
                "data"=> [
                    'vattaxs'=> '',
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vattex  $vattex
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $user=User::where('id',$user_id->tokenable_id)->first();

            if(isset($user->membership_code)){
                $vattexs=Vattex::where('membership_id',$user->membership_code)->first();
            }else{
                $vattexs=Vattex::where('membership_id',$user->member_by)->first();
            }

            $vattexs->vat=$request->vat;
            $vattexs->tax=$request->tax;
            $vattexs->update();

            $response=[
                "status"=>true,
                'message' => "Vattax update successful",
                "data"=> [
                    'vattaxs'=> $vattexs,
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response=[
                "status"=>false,
                'message' => "Can not update vattax. Something went wrong",
                "data"=> [
                    'vattaxs'=> '',
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vattex  $vattex
     * @return \Illuminate\Http\Response
     */

}
