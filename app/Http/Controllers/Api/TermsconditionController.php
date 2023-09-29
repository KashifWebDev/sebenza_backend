<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Termscondition;
use Illuminate\Http\Request;

class TermsconditionController extends Controller
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
            $termsconditions =Termscondition::where('membership_code',$u->membership_code)->get();
        }else{
            $termsconditions =Termscondition::where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Termsconditions',
            "data"=> [
                'termsconditions'=> $termsconditions,
            ]

        ];
        return response()->json($response,200);
    }

    public function getdata()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $termscategorys =Termscategory::where('membership_code',$u->membership_code)->where('status','Active')->get();
        }else{
            $termscategorys =Termscategory::where('membership_code',$u->member_by)->where('status','Active')->get();
        }

        $response = [
            'status' => true,
            'message'=>'Active list of Termscategorys',
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
        $termsconditions=new Termscondition();
        $termsconditions->user_id=$user_id->tokenable_id;
        $termsconditions->category_id=$request->category_id;
        $termsconditions->termscondition=$request->termscondition;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $termsconditions->membership_code=$u->membership_code;
        }else{
            $termsconditions->membership_code=$u->member_by;
        }
        $termsconditions->status=$request->status;
        $termsconditions->save();
        $response=[
            "status"=>true,
            'message' => "Termscondition create successful",
            "data"=> [
                'termsconditions'=> $termsconditions,
            ]
        ];
        return response()->json($response, 200);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Termscondition  $termscondition
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $termsconditions =Termscondition::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $termsconditions =Termscondition::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($termsconditions)){
            $response = [
                'status' => true,
                'message'=>'Termscondition By ID',
                "data"=> [
                    'termsconditions'=> $termsconditions,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No termscondition find by this ID',
                "data"=> [
                    'termsconditions'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Termscondition  $termscondition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $termsconditions =Termscondition::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $termsconditions =Termscondition::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($termsconditions)){
            $termsconditions->user_id=$user_id->tokenable_id;
            $termsconditions->category_id=$request->category_id;
            $termsconditions->termscondition=$request->termscondition;
            $termsconditions->status=$request->status;
            $termsconditions->update();
            $response=[
                "status"=>true,
                'message' => "Termscondition update successfully",
                "data"=> [
                    'termsconditions'=> $termsconditions,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No termscondition find by this ID',
                "data"=> [
                    'termsconditions'=> '',
                ]

            ];
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Termscondition  $termscondition
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $termsconditions =Termscondition::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $termsconditions =Termscondition::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($termsconditions)){
            $termsconditions->delete();
            $response = [
                'status' => true,
                'message'=> 'Termscondition delete successfully',
                "data"=> [
                    'termsconditions'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No termscondition find by this ID',
                "data"=> [
                    'termsconditions'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }

}
