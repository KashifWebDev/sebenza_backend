<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Accountpackage;
use Illuminate\Http\Request;

class AccountpackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accountpackages=Accountpackage::all();
        $response = [
            'status' => true,
            'message'=>'List of account packages',
            "data"=> [
                'accountpackages'=> $accountpackages,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getpackage()
    {
        $accountpackages=Accountpackage::where('status','Active')->get();
        $response = [
            'status' => true,
            'message'=>'List of account packages',
            "data"=> [
                'accountpackages'=>$accountpackages,
            ],
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
        $accountpackage=new Accountpackage();
        $accountpackage->account_package=$request->account_package;
        $accountpackage->max_user=$request->max_user;
        $accountpackage->status=$request->status;
        $accountpackage->save();
        $response=[
            "status"=>true,
            'message' => "Account package create successful",
            "data"=> [
                'accountpackage'=> $accountpackage,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accountpackage  $accountpackage
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accountpackage=Accountpackage::where('id',$id)->first();
        $response = [
            'status' => true,
            'message'=>'Account package by Id',
            "data"=> [
                'accountpackage'=> $accountpackage,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accountpackage  $accountpackage
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accountpackage  $accountpackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $accountpackage=Accountpackage::where('id',$request->account_package_id)->first();
        $accountpackage->account_package=$request->account_package;
        $accountpackage->max_user=$request->max_user;
        $accountpackage->status=$request->status;
        $accountpackage->update();
        $response=[
            "status"=>true,
            'message' => "Account type update successful",
            "data"=> [
                'accountpackage'=> $accountpackage,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accountpackage  $accountpackage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accountpackage=Accountpackage::where('id',$id)->first();
        $accountpackage->delete();
        $response = [
            'status' => true,
            'message'=> 'Account Package delete successfully',
            "data"=> [
                'accountpackage'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}
