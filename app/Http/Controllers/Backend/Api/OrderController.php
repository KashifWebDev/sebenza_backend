<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Basicinfo;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders =Order::with('users')->get();

        $response = [
            'status' => true,
            'message'=>'Order Lists',
            "data"=> [
                'orders'=> $orders,
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order =Order::with('users')->where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Order By order ID',
            "data"=> [
                'order'=> $order,
            ]
        ];

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $webinfo =Basicinfo::first();
        $order =Order::with('users')->where('id',$id)->first();
        $order->new_user=$request->new_user;
        $successorder=$order->update();

        if($successorder){

            $fdate=$order->expireDate;
            $tdate=date('Y-m-d');

            $start = Carbon::parse($fdate);
            $end =  Carbon::parse($tdate);
            $days = $end->diffInDays($start);

            $invoice=new Invoice();
            $invoice->invoiceID=$this->invoiceID();
            $invoice->order_id=$order->id;
            $invoice->account_total_user=$request->new_user;
            $invoice->cost_per_user=$webinfo->cost_per_user;
            $everydaypayment=$webinfo->cost_per_user/30;
            $availabledayamount=($everydaypayment*($day-1))*$request->new_user;
            $amounttotal=$availabledayamount;
            $invoice->amount_total=$amounttotal;
            $invoice->payable_amount=$amounttotal;
            $invoice->paid_amount=0;
            $invoice->invoiceDate=date('Y-m-d');
            $invoice->save();
        }

        $response = [
            'status' => true,
            'message'=>'New invoice created successfully',
            "data"=> [
                'invoice'=> $invoice,
            ]
        ];

        return response()->json($response,200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order= Order::where('id',$id)->first();
        $invoices =Invoice::where('order_id',$id)->get();
        foreach($invoices as $invoice){
            $invoice->delete();
        }
        $order->delete();

        $response=[
            "status"=>true,
            'message' => "Orders Deleted Successfully",
            "data"=> [
                'orders'=> [],
            ]
        ];
        return response()->json($response, 200);
    }
}
