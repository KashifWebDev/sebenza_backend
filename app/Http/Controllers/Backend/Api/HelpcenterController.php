<?php

namespace App\Http\Controllers;

use App\Models\Helpcenter;
use Illuminate\Http\Request;

class HelpcenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aboutus=Aboutus::first();
        $response = [
            'status' => true,
            'message'=>'About Us data',
            "data"=> [
                'aboutus'=> $aboutus,
            ]
        ];
        return response()->json($response,200);
    }

    public function getaboutinfo()
    {
        $aboutus=Aboutus::first();
        $response = [
            'status' => true,
            'message'=>'About Us data',
            "data"=> [
                'aboutus'=> $aboutus,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Helpcenter  $helpcenter
     * @return \Illuminate\Http\Response
     */
    public function show(Helpcenter $helpcenter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Helpcenter  $helpcenter
     * @return \Illuminate\Http\Response
     */
    public function edit(Helpcenter $helpcenter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Helpcenter  $helpcenter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Helpcenter $helpcenter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Helpcenter  $helpcenter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Helpcenter $helpcenter)
    {
        //
    }
}