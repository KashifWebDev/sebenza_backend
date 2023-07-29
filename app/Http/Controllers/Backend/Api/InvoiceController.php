<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices =Invoice::with(['orders','orders.users.roles'])->get();

        $response = [
            'status' => true,
            'message'=>'Invoice Lists',
            "data"=> [
                'invoices'=> $invoices,
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
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices =Invoice::with(['orders','orders.users.roles'])->where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Invoice by invoice ID',
            "data"=> [
                'invoices'=> $invoices,
            ]
        ];

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice =Invoice::where('id',$id)->first();
        $invoice->discount=$request->discount;
        $invoice->payable_amount=$invoice->amount_total-$request->discount;
        $invoice->paid_amount=0;
        $invoice->invoiceDate=date('Y-m-d');
        $invoice->save();



        $response = [
            'status' => true,
            'message'=>'Update Invoice by invoice ID',
            "data"=> [
                'invoices'=> $invoices,
            ]
        ];

        return response()->json($response,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoices =Invoice::where('id',$id)->first();
        $invoices->delete();

        $response=[
            "status"=>true,
            'message' => "Invoice Deleted Successfully",
            "data"=> [
                'invoicess'=> [],
            ]
        ];
        return response()->json($response, 200);
    }
}
