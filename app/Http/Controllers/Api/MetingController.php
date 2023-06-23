<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Meting;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class MetingController extends Controller
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
        $metings =Meting::where('form_id',$user_id->tokenable_id)->get();

        $response = [
            'status' => true,
            'message'=>'List of Metings',
            "data"=> [
                'metings'=> $metings,
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
        $metings=new Meting();
        $metings->form_id=$user_id->tokenable_id;
        $metings->title=$request->title;
        $metings->place=$request->place;
        $metings->description=$request->description;
        $metings->link=$request->link;
        $metings->recipients=$request->recipients;
        $metings->date=$request->date;
        $metings->time=$request->time;
        $metings->save();
        $response=[
            "status"=>true,
            'message' => "Meting create successful",
            "data"=> [
                'metings'=> $metings,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meting  $meting
     * @return \Illuminate\Http\Response
     */
    public function show(Meting $meting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meting  $meting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $metings =Meting::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Meting By ID',
            "data"=> [
                'metings'=> $metings,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meting  $meting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $metings =Meting::where('id',$id)->first();
        $metings->form_id=$user_id->tokenable_id;
        $metings->title=$request->title;
        $metings->place=$request->place;
        $metings->description=$request->description;
        $metings->link=$request->link;
        $metings->recipients=$request->recipients;
        $metings->date=$request->date;
        $metings->time=$request->time;
        $metings->status=$request->status;
        $metings->save();
        $response=[
            "status"=>true,
            'message' => "Meting update successful",
            "data"=> [
                'metings'=> $metings,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meting  $meting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $metings =Meting::where('id',$id)->first();
        $metings->delete();
        $response = [
            'status' => true,
            'message'=> 'Meting delete successfully',
            "data"=> [
                'metings'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}