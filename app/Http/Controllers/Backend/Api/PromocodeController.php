<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promocodes =Promocode::all();
        $response = [
            'status' => true,
            'message'=>'List of promocodes',
            "data"=> [
                'promocodes'=> $promocodes,
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
        $promocode=new Promocode();
        $promocode->title=$request->title;
        $promocode->promocode=$request->promocode;
        $promocode->expired_date=$request->expired_date;
        $promocode->discount_percent=$request->discount_percent;
        $promocode->save();

        $response=[
            "status"=>true,
            'message' => "Promocode created successfully",
            "data"=> [
                'promocodes'=> $promocode,
            ]
        ];
        return response()->json($response, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function show(Promocode $promocode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promocodes=Promocode::where('id',$id)->first();

        $response=[
            "status"=>true,
            'message' => "Promocode By ID",
            "data"=> [
                'promocodes'=> $promocodes,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $promocode= Promocode::where('id',$id)->first();
        $promocode->title=$request->title;
        $promocode->promocode=$request->promocode;
        $promocode->expired_date=$request->expired_date;
        $promocode->discount_percent=$request->discount_percent;
        $promocode->status=$request->status;
        $promocode->save();

        $response=[
            "status"=>true,
            'message' => "Promocode updated successfully",
            "data"=> [
                'promocodes'=> $promocode,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promocode= Promocode::where('id',$id)->first();
        $promocode->delete();
        $response=[
            "status"=>true,
            'message' => "Promocode Deleted Successfully",
            "data"=> [
                'promocodes'=> [],
            ]
        ];
        return response()->json($response, 200);
    }
}