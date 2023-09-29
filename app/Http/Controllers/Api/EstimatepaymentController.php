<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\Estimatepayment;
use Illuminate\Http\Request;

class EstimatepaymentController extends Controller
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
     * @param  \App\Models\Estimatepayment  $estimatepayment
     * @return \Illuminate\Http\Response
     */
    public function show(Estimatepayment $estimatepayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estimatepayment  $estimatepayment
     * @return \Illuminate\Http\Response
     */
    public function edit(Estimatepayment $estimatepayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estimatepayment  $estimatepayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estimatepayment $estimatepayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estimatepayment  $estimatepayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estimatepayment $estimatepayment)
    {
        //
    }
}
