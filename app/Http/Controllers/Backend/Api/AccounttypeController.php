<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Accounttype;
use Illuminate\Http\Request;

class AccounttypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->search)){
            $accounttypes=Accounttype::where('account_type','LIKE', "%$request->search%")->get();
        }else{
            $accounttypes=Accounttype::all();
        }

        $response = [
            'status' => true,
            'message'=>'List of account types',
            "data"=> [
                'accounttypes'=> $accounttypes,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gettype()
    {
        $accounttypes=Accounttype::where('status','Active')->get();
        $response = [
            'status' => true,
            'message'=>'List of account types',
            "data"=>[
                'accounttypes'=>$accounttypes,
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
        $accounttype=new Accounttype();
        $accounttype->account_type=$request->account_type;
        $accounttype->status=$request->status;
        $accounttype->save();
        $response=[
            "status"=>true,
            'message' => "Account type create successful",
            "data"=> [
                'accounttype'=> $accounttype,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accounttype  $accounttype
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accounttype=Accounttype::where('id',$id)->first();
        $response = [
            'status' => true,
            'message' => 'Account Type By Id',
            "data"=> [
                'accounttype'=> $accounttype,
            ]
        ];
        return response()->json($response,200);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accounttype  $accounttype
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accounttype  $accounttype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $accounttype=Accounttype::where('id',$request->account_type_id)->first();
        $accounttype->account_type=$request->account_type;
        $accounttype->status=$request->status;
        $accounttype->update();
        $response=[
            "status"=>true,
            'message' => "Account type update successful",
            "data"=> [
                'accounttype'=> $accounttype,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accounttype  $accounttype
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accounttype=Accounttype::where('id',$id)->first();
        $accounttype->delete();
        $response = [
            'status' => true,
            'message'=> 'Account Type delete successfully',
            "data"=> [
                'accounttype'=> [],
            ]
        ];
        return response()->json($response,200);
    }

}