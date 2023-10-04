<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\Estimatequote;
use App\Models\Estimatepayment;
use App\Models\Estimatetermscondition;
use App\Models\Item;
use Illuminate\Http\Request;

class EstimatequoteController extends Controller
{
    public function index()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $estimatequotes =Estimatequote::with(['users','payments','items','termsconditions'])->where('membership_code',$u->membership_code)->get();
        }else{
            $estimatequotes =Estimatequote::with(['users','payments','items','termsconditions'])->where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Estimatequotes',
            "data"=> [
                'estimatequotes'=> $estimatequotes,
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
        $estimatequotes=new Estimatequote();
        $estimatequotes->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $estimatequotes->membership_code=$u->membership_code;
        }else{
            $estimatequotes->membership_code=$u->member_by;
        }

        $estimatequotes->estimateID=$this->uniqueID();
        $estimatequotes->customer_name=$request->customer_name;
        $estimatequotes->customer_phone=$request->customer_phone;
        $estimatequotes->customer_email=$request->customer_email;
        $estimatequotes->shipping_country=$request->shipping_country;
        $estimatequotes->shipping_city=$request->shipping_city;
        $estimatequotes->shipping_zone=$request->shipping_zone;
        $estimatequotes->shipping_address=$request->shipping_address;
        $estimatequotes->title=$request->title;
        $estimatequotes->description=$request->description;
        $estimatequotes->notes=$request->notes;
        $estimatequotes->entryDate=date('Y-m-d');
        $estimatequotes->last_updated=date('Y-m-d');
        $estimatequotes->paymentDate=$request->paymentDate;
        $estimatequotes->status=$request->status;
        $time = microtime('.') * 10000;
        $productImg = $request->file('customer_e_signature');
        if($productImg){
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/estimate/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $product->customer_e_signature = $productImgUrl;
        }

        $estimatequotes->subTotal=$request->subTotal;
        $estimatequotes->discountCharge=$request->discountCharge;
        $estimatequotes->vat=$request->vat;
        $estimatequotes->total=$request->subTotal+$request->vat-$request->discountCharge;

        $success=$estimatequotes->save();
        if($success){
            foreach($request->items as $item){
                $createitem=new Item();
                $createitem->estimate_id=$estimatequotes->id;
                $createitem->itemName=$item->itemName;
                $createitem->color=$item->color;
                $createitem->size=$item->size;
                $createitem->weight=$item->weight;
                $createitem->quantity=$item->quantity;
                $createitem->itemPrice=$item->itemPrice;
                $createitem->totalPrice=$item->quantity*$item->itemPrice;
                $createitem->save();
            }
            foreach($request->termsconditions as $terms){
                $createterms=new Estimatetermscondition();
                $createterms->estimate_id=$estimatequotes->id;
                $createterms->termscondition_id=$terms->terms_id;
                $createterms->save();
            }

            if(isset($request->payment_methood) && isset($request->amount) ){
                $payment=new Estimatepayment();
                $payment->estimate_id=$estimatequotes->id;
                $payment->payment_methood=$request->payment_methood;
                $payment->amount=$request->amount;
                $payment->trx_id=$request->trx_id;
                $payment->date=date('Y-m-d');
                $payment->userID=$user_id->tokenable_id;
                $payment->status='Done';
                $payment->save();
            }
        }
        $response=[
            "status"=>true,
            'message' => "Estimatequote create successful",
            "data"=> [
                'estimatequotes'=> $estimatequotes,
            ]
        ];
        return response()->json($response, 200);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estimatequote  $estimatequote
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $estimatequotes =Estimatequote::with(['users','payments','items','termsconditions'])->where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $estimatequotes =Estimatequote::with(['users','payments','items','termsconditions'])->where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($estimatequotes)){
            $response = [
                'status' => true,
                'message'=>'Estimatequote By ID',
                "data"=> [
                    'estimatequotes'=> $estimatequotes,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No estimatequote find by this ID',
                "data"=> [
                    'estimatequotes'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estimatequote  $estimatequote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $estimatequotes =Estimatequote::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $estimatequotes =Estimatequote::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($estimatequotes)){
            $estimatequotes->customer_name=$request->customer_name;
            $estimatequotes->customer_phone=$request->customer_phone;
            $estimatequotes->customer_email=$request->customer_email;
            $estimatequotes->shipping_country=$request->shipping_country;
            $estimatequotes->shipping_city=$request->shipping_city;
            $estimatequotes->shipping_zone=$request->shipping_zone;
            $estimatequotes->shipping_address=$request->shipping_address;
            $estimatequotes->title=$request->title;
            $estimatequotes->description=$request->description;
            $estimatequotes->notes=$request->notes;
            $estimatequotes->entryDate=date('Y-m-d');
            $estimatequotes->last_updated=date('Y-m-d');
            $estimatequotes->paymentDate=$request->paymentDate;
            $estimatequotes->status=$request->status;
            $time = microtime('.') * 10000;
            $productImg = $request->file('customer_e_signature');
            if($productImg){
                $imgname = $time . $productImg->getClientOriginalName();
                $imguploadPath = ('public/images/estimate/');
                $productImg->move($imguploadPath, $imgname);
                $productImgUrl = $imguploadPath . $imgname;
                $product->customer_e_signature = $productImgUrl;
            }

            $estimatequotes->subTotal=$request->subTotal;
            $estimatequotes->discountCharge=$request->discountCharge;
            $estimatequotes->vat=$request->vat;
            $estimatequotes->total=$request->subTotal+$request->vat-$request->discountCharge;

            $success=$estimatequotes->save();
            if($success){
                $itemsold=Item::where('estimate_id', '=', $estimatequotes->id)->get();
                if(isset($itemsold)){
                    Item::where('estimate_id', '=', $estimatequotes->id)->delete();
                }
                foreach($request->items as $item){
                    $createitem=new Item();
                    $createitem->estimate_id=$estimatequotes->id;
                    $createitem->itemName=$item->itemName;
                    $createitem->color=$item->color;
                    $createitem->size=$item->size;
                    $createitem->weight=$item->weight;
                    $createitem->quantity=$item->quantity;
                    $createitem->itemPrice=$item->itemPrice;
                    $createitem->totalPrice=$item->quantity*$item->itemPrice;
                    $createitem->save();
                }

                $ordert=Estimatetermscondition::where('estimate_id', '=', $estimatequotes->id)->get();
                if(isset($ordert)){
                    Estimatetermscondition::where('estimate_id', '=', $estimatequotes->id)->delete();
                }

                foreach($request->termsconditions as $terms){
                    $createterms=new Estimatetermscondition();
                    $createterms->estimate_id=$estimatequotes->id;
                    $createterms->termscondition_id=$terms->terms_id;
                    $createterms->save();
                }

                if(isset($request->payment_methood) && isset($request->amount) ){
                    $payment=new Estimatepayment();
                    $payment->estimate_id=$estimatequotes->id;
                    $payment->payment_methood=$request->payment_methood;
                    $payment->amount=$request->amount;
                    $payment->trx_id=$request->trx_id;
                    $payment->date=date('Y-m-d');
                    $payment->userID=$user_id->tokenable_id;
                    $payment->status='Done';
                    $payment->save();
                }
            }
            $response=[
                "status"=>true,
                'message' => "Estimatequote update successfully",
                "data"=> [
                    'estimatequotes'=> $estimatequotes,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No estimatequote find by this ID',
                "data"=> [
                    'estimatequotes'=> '',
                ]

            ];
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estimatequote  $estimatequote
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $estimatequotes =Estimatequote::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $estimatequotes =Estimatequote::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($estimatequotes)){
            $estimatequotes->delete();
            $response = [
                'status' => true,
                'message'=> 'Estimatequote delete successfully',
                "data"=> [
                    'estimatequotes'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No estimatequote find by this ID',
                "data"=> [
                    'estimatequotes'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }


    public function uniqueID()
    {
        $lastOrder = Estimatequote::latest('id')->first();
        if ($lastOrder) {
            $orderID = $lastOrder->id + 1;
        } else {
            $orderID = 1;
        }

        return 'EQ' . $orderID;
    }



}
