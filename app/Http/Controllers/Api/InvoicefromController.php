<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Invoicefrom;
use App\Models\User;
use App\Models\Basicinfo;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class InvoicefromController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $Invoicefors =Invoicefrom::where('membership_code',$u->membership_code)->where('invoice_for',$request->invoice_for)->get();
        }else{
            $Invoicefors =Invoicefrom::where('membership_code',$u->member_by)->where('invoice_for',$request->invoice_for)->get();
        }

        // 0==me and 1==to me

        $response = [
            'status' => true,
            'message'=>'My Invoicefors',
            "data"=> [
                'Invoicefors'=> $Invoicefors,
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

        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();

        $Invoicefors =new Invoicefrom();
        if(isset($u->membership_code)){
            $invoicefors->membership_code='dasdasd';
        }else{
            $invoicefors->membership_code=$u->member_by;
        }
        return $request;
        $user=User::where('email',$request->email)->first();
        $invoicefors->user_id=$user->id;
        $invoicefors->invoice_for=$request->invoice_for;
        $invoicefors->invoiceID=$this->uniqueID();
        $invoicefors->invoiceDate=$request->invoiceDate;

        if($request->logo){
            $logo = $request->file('logo');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/logo/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $invoicefors->file = $logoImgUrl;
        }
        $invoicefors->name=$request->name;
        $invoicefors->company_name=$request->company_name;
        $invoicefors->address=$request->address;
        $invoicefors->invoice_details=$request->invoice_details;

        $invoicefors->amount_total=$request->amount_total;
        $invoicefors->discount=$request->discount;
        $invoicefors->payable_amount=$request->payable_amount;
        $invoicefors->paid_amount=$invoicefors->paid_amount;
        $invoicefors->status=$request->status;
        $success=$invoicefors->save();

        if($request->status=='Send'){
            $details = [
                'title' => 'Invoice From -'. $user->company_name,
                "user"=>$user,
                'invoicefors'=>$invoicefors,
            ];

            \Mail::to($request->email)->send(new \App\Mail\SendMailInvoicefor($details));
        }

        $response = [
            'status' => true,
            'message'=>'Invoices Created Successfully',
            "data"=> [
                'invoiceforss'=> $invoicefors,
            ]
        ];

        return response()->json($response,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoicefrom  $invoicefors
     * @return \Illuminate\Http\Response
     */
    public function show(Invoicefrom $invoicefors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoicefrom  $invoicefors
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $invoicefors =Invoicefrom::where('id',$id)->first();

            $response = [
                'status' => true,
                'message'=>'invoicefors by invoicefors ID',
                "data"=> [
                    'invoicefors'=> $invoicefors,
                ]
            ];

            return response()->json($response,200);
        } catch (\Exception $e) {

            $response = [
                'status' => false,
                'message'=>$e->getMessage(),
            ];
            return response()->json($response,200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoicefrom  $invoicefors
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request ,$id)
    {
        $Invoicefors =Invoicefrom::where('id',$id)->first();
        $user=User::where('email',$request->email)->first();
        $invoicefors->user_id=$user->id;
        $invoicefors->invoice_for=$request->invoice_for;
        $invoicefors->invoiceDate=$request->invoiceDate;

        if($request->logo){
            $logo = $request->file('logo');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/logo/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $invoicefors->file = $logoImgUrl;
        }
        $invoicefors->name=$request->name;
        $invoicefors->company_name=$request->company_name;
        $invoicefors->address=$request->address;
        $invoicefors->invoice_details=$request->invoice_details;

        $invoicefors->amount_total=$request->amount_total;
        $invoicefors->discount=$request->discount;
        $invoicefors->payable_amount=$request->payable_amount;
        $invoicefors->paid_amount=$invoicefors->paid_amount;
        $invoicefors->status=$request->status;
        $success=$invoicefors->update();

        if($request->status=='Send'){
            $details = [
                'title' => 'Invoice From -'. $user->company_name,
                "user"=>$user,
                'invoicefors'=>$invoicefors,
            ];

            \Mail::to($request->email)->send(new \App\Mail\SendMailInvoicefor($details));
        }

        $response = [
            'status' => true,
            'message'=>'Invoices Update Successfully',
            "data"=> [
                'invoiceforss'=> $invoicefors,
            ]
        ];

        return response()->json($response,200);
    }


    public function updatepayment(Request $request)
    {
        $invoicefors =Invoicefrom::where('invoiceforsID',$request->invoicefors_id)->first();
        $invoicefors->paid_amount=$invoicefors->payable_amount;
        $invoicefors->payable_amount=0;
        $invoicefors->paymentDate=date('Y-m-d');
        $invoicefors->status=$request->status;
        $success=$invoicefors->update();
        $user=User::where('id',$order->user_id)->first();
        $details = [
            'title' => 'Payment Confirmation -'. env('APP_NAME'),
            "user"=>$user,
            'invoicefors'=>$invoicefors,
        ];

        \Mail::to($user->email)->send(new \App\Mail\SendMailPayment($details));

        $response = [
            'status' => true,
            'message'=>'Payment Give Successfully',
            "data"=> [
                'invoiceforss'=> $invoicefors,
            ]
        ];

        return response()->json($response,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoicefrom  $invoicefors
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoicefrom $invoicefors)
    {
        //
    }

    public function uniqueID()
    {
        $lastOrder = Invoicefrom::latest('id')->first();
        if ($lastOrder) {
            $orderID = $lastOrder->id + 1;
        } else {
            $orderID = 1;
        }

        return 'INVF#90' . $orderID;
    }

}
