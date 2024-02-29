<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Complain;
use App\Models\Admin;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class ComplainController extends Controller
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
        $complains =Complain::with('departments')->where('form_id',$user_id->tokenable_id)->get();

        $response = [
            'status' => true,
            'message'=>'List of Complains',
            "data"=> [
                'complains'=> $complains,
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
        $admin = Admin::where('department_id',$request->department_id)->where('status','Active')->inRandomOrder()->first();
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $complains=new Complain();
        $complains->form_id=$user_id->tokenable_id;
        $complains->subject=$request->subject;
        $complains->complain_details=$request->complain_details;
        $complains->date=date('Y-m-d');
        $complains->department_id=$request->department_id;
        if(isset($admin)){
            $complains->admin_id=$admin->admin_id;
        }else{
            $complains->admin_id=Admin::where('status','Active')->first()->id;
        }
        $complains->save();
        $response=[
            "status"=>true,
            'message' => "Complain create successful",
            "data"=> [
                'complains'=> $complains,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $complains =Complain::with('departments')->where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Complain By ID',
            "data"=> [
                'complains'=> $complains,
            ]

        ];
        return response()->json($response,200);
    }

    public function view($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $complains =Complain::with('departments')->where('form_id',$user_id->tokenable_id)->where('id',$id)->get();

        if(isset($complains)){
            $response = [
                'status' => true,
                'message'=>'Complain By ID',
                "data"=> [
                    'complains'=> $complains,
                ]
            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'Something went wrong',
                "data"=> [
                    'complains'=> '',
                ]

            ];
        }
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $complains =Complain::with('complainnotes')->where('id',$id)->first();
        $complains->complain_details=$request->complain_details;
        $complains->feedback=$request->feedback;
        $complains->department_id=$request->department_id;
        if(isset($request->admin_id)){
            $complains->admin_id=$request->admin_id;
        }
        $complains->status=$request->status;
        $complains->update();
        $response=[
            "status"=>true,
            'message' => "Complain update successfully",
            "data"=> [
                'complains'=> $complains,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $complains =Complain::where('id',$id)->first();
        $complains->delete();

        $response = [
            'status' => true,
            'message'=> 'Complain delete successfully',
            "data"=> [
                'complains'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}
