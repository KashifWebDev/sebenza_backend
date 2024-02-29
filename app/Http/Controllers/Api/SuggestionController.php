<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Suggestion;
use App\Models\Admin;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends Controller
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
        $suggestions =Suggestion::with('departments')->where('form_id',$user_id->tokenable_id)->get();

        $response = [
            'status' => true,
            'message'=>'List of Suggestions',
            "data"=> [
                'suggestions'=> $suggestions,
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
        $suggestions=new Suggestion();
        $suggestions->form_id=$user_id->tokenable_id;
        $suggestions->title=$request->title;
        $suggestions->suggestions=$request->suggestions;
        $suggestions->date=date('Y-m-d');
        $suggestions->department_id=$request->department_id;
        if(isset($admin)){
            $suggestions->admin_id=$admin->id;
        }else{
            $suggestions->admin_id=Admin::where('status','Active')->first()->id;
        }
        $suggestions->save();
        $response=[
            "status"=>true,
            'message' => "Suggestion create successfully",
            "data"=> [
                'suggestions'=> $suggestions,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Suggestion  $suggestion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $suggestions =Suggestion::with('departments')->where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Suggestion By ID',
            "data"=> [
                'suggestions'=> $suggestions,
            ]

        ];
        return response()->json($response,200);
    }

    public function view($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $suggestions =Suggestion::with('departments')->where('form_id',$user_id->tokenable_id)->where('id',$id)->get();

        if(isset($suggestions)){
            $response = [
                'status' => true,
                'message'=>'Suggestion By ID',
                "data"=> [
                    'suggestions'=> $suggestions,
                ]
            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'Something went wrong',
                "data"=> [
                    'suggestions'=> '',
                ]

            ];
        }
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Suggestion  $suggestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $suggestions =Suggestion::with('suggestionnotes')->where('id',$id)->first();
        $suggestions->suggestions=$request->suggestions;
        $suggestions->feedback=$request->feedback;
        $suggestions->department_id=$request->department_id;
        if(isset($request->admin_id)){
            $suggestions->admin_id=$request->admin_id;
        }
        $suggestions->status=$request->status;
        $suggestions->update();
        $response=[
            "status"=>true,
            'message' => "Suggestion update successfully",
            "data"=> [
                'suggestions'=> $suggestions,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Suggestion  $suggestion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $suggestions =Suggestion::where('id',$id)->first();
        $suggestions->delete();

        $response = [
            'status' => true,
            'message'=> 'Suggestion delete successfully',
            "data"=> [
                'suggestions'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}
