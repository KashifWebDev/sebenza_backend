<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Stockitem;
use Illuminate\Http\Request;

class StockitemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\Stockitem  $stockitem
     * @return \Illuminate\Http\Response
     */
    public function show(Stockitem $stockitem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stockitem  $stockitem
     * @return \Illuminate\Http\Response
     */
    public function edit(Stockitem $stockitem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stockitem  $stockitem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stockitem $stockitem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stockitem  $stockitem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stockitem $stockitem)
    {
        //
    }
}
