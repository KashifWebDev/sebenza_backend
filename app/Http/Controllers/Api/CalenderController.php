<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Calender;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class CalenderController extends Controller
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
        $calenders =Calender::where('form_id',$user_id->tokenable_id)->get();

        $response = [
            'status' => true,
            'message'=>'List of Calender Schedule',
            "data"=> [
                'calenders'=> $calenders,
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
        $calenders=new Calender();
        $calenders->form_id=$user_id->tokenable_id;
        $calenders->title=$request->title;
        $calenders->details=$request->details;
        $calenders->bgColor=$request->bgColor;
        $calenders->startDate=$request->startDate;
        $calenders->startTime=$request->startTime;
        $calenders->endDate=$request->endDate;
        $calenders->endTime=$request->endTime;
        $calenders->save();
        $response=[
            "status"=>true,
            'message' => "Calender schedule create successful",
            "data"=> [
                'calenders'=> $calenders,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Calender  $calender
     * @return \Illuminate\Http\Response
     */
    public function show(Calender $calender)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Calender  $calender
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $calenders =Calender::where('id',$id)->first();
        $response = [
            'status' => true,
            'message'=>'Calender schedule By ID',
            "data"=> [
                'calenders'=>$calenders,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calender  $calender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $calenders =Calender::where('id',$id)->first();
        $calenders->form_id=$user_id->tokenable_id;
        $calenders->title=$request->title;
        $calenders->details=$request->details;
        $calenders->bgColor=$request->bgColor;
        $calenders->startDate=$request->startDate;
        $calenders->startTime=$request->startTime;
        $calenders->endDate=$request->endDate;
        $calenders->endTime=$request->endTime;
        $calenders->status=$request->status;
        $calenders->save();
        $response=[
            "status"=>true,
            'message' => "Calenders schedule update successful",
            "data"=> [
                'calenders'=> $calenders,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calender  $calender
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $calenders =Calender::where('id',$id)->first();
        $calenders->delete();
        $response = [
            'status' => true,
            'message'=> 'Calender schedule delete successfully',
            "data"=> [
                'calenders'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}