<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Termscategory;
use Illuminate\Http\Request;

class TermscategoryController extends Controller
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
            $termscategorys =Termscategory::where('membership_code',$u->membership_code)->get();
        }else{
            $termscategorys =Termscategory::where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Termscategorys',
            "data"=> [
                'termscategorys'=> $termscategorys,
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
        $termscategorys=new Termscategory();
        $termscategorys->user_id=$user_id->tokenable_id;
        $termscategorys->category_name=$request->category_name;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $termscategorys->membership_code=$u->membership_code;
        }else{
            $termscategorys->membership_code=$u->member_by;
        }
        $termscategorys->status=$request->status;
        $termscategorys->save();
        $response=[
            "status"=>true,
            'message' => "Termscategory create successful",
            "data"=> [
                'termscategorys'=> $termscategorys,
            ]
        ];
        return response()->json($response, 200);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Termscategory  $termscategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $termscategorys =Termscategory::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $termscategorys =Termscategory::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($termscategorys)){
            $response = [
                'status' => true,
                'message'=>'Termscategory By ID',
                "data"=> [
                    'termscategorys'=> $termscategorys,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No termscategory find by this ID',
                "data"=> [
                    'termscategorys'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Termscategory  $termscategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $termscategorys =Termscategory::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $termscategorys =Termscategory::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($termscategorys)){
            $termscategorys->user_id=$user_id->tokenable_id;
            $termscategorys->category_name=$request->category_name;
            $termscategorys->status=$request->status;
            $termscategorys->update();
            $response=[
                "status"=>true,
                'message' => "Termscategory update successfully",
                "data"=> [
                    'termscategorys'=> $termscategorys,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No termscategory find by this ID',
                "data"=> [
                    'termscategorys'=> '',
                ]

            ];
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Termscategory  $termscategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $termscategorys =Termscategory::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $termscategorys =Termscategory::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($termscategorys)){
            $termscategorys->delete();
            $response = [
                'status' => true,
                'message'=> 'Termscategory delete successfully',
                "data"=> [
                    'termscategorys'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No termscategory find by this ID',
                "data"=> [
                    'termscategorys'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }
}
