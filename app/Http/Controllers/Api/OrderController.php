<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\User;
use App\Models\Basicinfo;
use App\Models\Promocode;
use App\Models\Invoice;
use App\Models\Accounttype;
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
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $order =Order::with(['users.roles'])->where('user_id',$user_id->tokenable_id)->get();

        $response = [
            'status' => true,
            'message'=>'My order details',
            "data"=> [
                'order'=> $order,
            ]

        ];
        return response()->json($response,200);
    }

    public function store(Request $request)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $user=User::where('id', $user_id->tokenable_id)->first();
        $webinfo =Basicinfo::first();
        $order=new Order();
        $order->user_id=$user->id;
        $order->membership_id=$user->membership_code;
        $order->account_total_user=$request->user_limit_id;
        $order->cost_per_user=$webinfo->cost_per_user;
        $amounttotal=($request->user_limit_id*$webinfo->cost_per_user);
        $order->amount_total=$amounttotal;
        $order->orderDate=date('Y-m-d');
        $order->account_type_id=$request->account_type_id;
        if(isset($request->account_type_id)){
            $type=Accounttype::where('id',$request->account_type_id)->first();
            $order->account_type=$type->account_type;
        }
        $successorder=$order->save();

        if($successorder){
            $invoice=new Invoice();
            $invoice->invoiceID=$this->invoiceID();
            $invoice->order_id=$order->id;
            $invoice->account_total_user=$request->user_limit_id;
            $invoice->cost_per_user=$webinfo->cost_per_user;
            $amounttotal=($request->user_limit_id*$webinfo->cost_per_user);
            $invoice->amount_total=$amounttotal;
            $invoice->payable_amount=$amounttotal;
            $invoice->paid_amount=0;
            $invoice->invoiceDate=date('Y-m-d');
            $invoice->save();
        }

        $invdetails = [
            'title' => env('APP_NAME') . 'Subscription Invoice',
            "user"=>$user,
            "invoice"=>$invoice,
        ];

        \Mail::to($user->email)->send(new \App\Mail\SendMailInvoice($invdetails));

        $response=[
            "status"=>true,
            "message"=>"New Package Create Successfully",
            "data"=> [
                "invoice"=>$invoice,
            ]
        ];
        return response()->json($response, 200);
    }

    public function usepromo(Request $request){

        $disc= Promocode::where('promocode',$request->promocode)->where('status','Active')->first();
        $invo =Invoice::where('invoiceID',$request->invoiceID)->where('status','Unpaid')->first();
        if(isset($disc)){
            if($invo->discount>0){
                $response=[
                    "status"=>true,
                    "message"=>"Already Have discount. Promo can not apply",
                    "data"=> [
                        "invoice"=>$invo,
                    ]
                ];
                return response()->json($response, 200);
            }else{
                $discountamount =$invo->payable_amount*($disc->discount_percent/100);
                $invo->discount=$discountamount;
                $invo->payable_amount=$invo->payable_amount-$discountamount;
                $invo->update();
                $response=[
                    "status"=>true,
                    "message"=>"Promocode apply successfully.",
                    "data"=> [
                        "invoice"=>$invo,
                    ]
                ];
                return response()->json($response, 200);
            }
        }else{
            $response=[
                "status"=>true,
                "message"=>"Promocode is not valid. Please enter a valid promocode",
                "data"=> [
                    "invoice"=>$invo,
                ]
            ];
            return response()->json($response, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order =Order::with('users.roles')->where('id',$id)->first();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $webinfo =Basicinfo::first();
        $order =Order::with('users')->where('id',$id)->where('user_id',$user_id->tokenable_id)->first();
        if($order->expireDate==''){
            $response = [
                'status' => true,
                'message'=>'Please paid your previous invoice.Then try to update',
                "data"=> [
                    'order'=> $order,
                ]
            ];

            return response()->json($response,200);
        }else{
            $invo =Invoice::where('order_id',$order->id)->where('status','Unpaid')->first();

            if(isset($invo)){
                $response = [
                    'status' => true,
                    'message'=>'Please paid your previous invoice.Then try to update',
                    "data"=> [
                        'order'=> $order,
                    ]
                ];

                return response()->json($response,200);
            }else{
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
                    $availabledayamount=($everydaypayment*($days-1))*$request->new_user;

                    $amounttotal=$availabledayamount;
                    $invoice->amount_total=$amounttotal;
                    $invoice->payable_amount=$amounttotal;
                    $invoice->paid_amount=0;
                    $invoice->invoiceDate=date('Y-m-d');
                    $invoice->save();
                }

                $user = User::where('id', $order->user_id)->first();
                $invdetails = [
                    'title' => env('APP_NAME') . 'Subscription Invoice',
                    "user"=>$user,
                    "invoice"=>$invoice,
                ];

                \Mail::to($user->email)->send(new \App\Mail\SendMailInvoice($invdetails));


                $response = [
                    'status' => true,
                    'message'=>'New invoice created successfully',
                    "data"=> [
                        'invoice'=> $invoice,
                    ]
                ];

                return response()->json($response,200);
            }
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function invoiceID()
    {
        $lastmember = Invoice::latest()->first();
        if ($lastmember) {
            $menberID = $lastmember->id + 1;
        } else {
            $menberID = 1;
        }

        return '#INV00' . $menberID;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
