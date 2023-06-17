<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Whatsapp;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requests)
    {
        if(isset($request->search)){
            $whatsapp=Whatsapp::where('user_name',$request->search)->orWhere('whatsapp_number',$request->search)->get();
        }else{
            $whatsapp=Whatsapp::all();
        }

        $response = [
            'status' => true,
            'message'=>'All whatsapp number infos',
            "data"=> [
                'whatsapp'=> $whatsapp,
            ]
        ];
        return response()->json($response,200);
    }

    public function getwhatsappinfo()
    {
        $whatsapps=Whatsapp::where('status','Active')->inRandomOrder()->first();

        $response = [
            'status' => true,
            'message'=>'Random whatsapp number',
            "data"=> [
                'whatsapp'=> $whatsapps,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $whatsapp=new Whatsapp();
        $whatsapp->user_name=$request->user_name;
        $whatsapp->whatsapp_number=$request->whatsapp_number;

        $whatsapp->save();
        $response=[
            "status"=>true,
            'message' => "Whatsapp info create successful",
            "data"=> [
                'whatsapp'=> $whatsapp,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Whatsapp  $whatsapp
     * @return \Illuminate\Http\Response
     */
    public function show(Whatsapp $whatsapp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Whatsapp  $whatsapp
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $whatsapp=Whatsapp::findOrfail($id);

        $response=[
            "status"=>true,
            'message' => "Whatsapp info by ID",
            "data"=> [
                'whatsapp'=> $whatsapp,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Whatsapp  $whatsapp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $whatsapp=Whatsapp::findOrfail($id);
        $whatsapp->user_name=$request->user_name;
        $whatsapp->whatsapp_number=$request->whatsapp_number;

        $whatsapp->update();
        $response=[
            "status"=>true,
            'message' => "Whatsapp info update successfull",
            "data"=> [
                'whatsapp'=> $whatsapp,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Whatsapp  $whatsapp
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $whatsapp=Whatsapp::findOrfail($id);
        $whatsapp->delete();

        $response=[
            "status"=>true,
            'message' => "Whatsapp info delete successfull",
            "data"=> [
                'whatsapp'=> [],
            ]
        ];
        return response()->json($response, 200);
    }
}