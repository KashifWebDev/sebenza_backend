<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Stock;
use App\Models\Stockitem;
use App\Models\Product;
use App\Models\Stockpayment;
use Illuminate\Http\Request;

class StockController extends Controller
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
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $stocks =Stock::with(['stockitems','users'])->where('membership_code',$u->membership_code)->get();
        }else{
            $stocks =Stock::with(['stockitems','users'])->where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of My Stocks',
            "data"=> [
                'stocks'=> $stocks,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getproducts()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $products =Product::where('membership_code',$u->membership_code)->where('status',true)->get();
        }else{
            $products =Product::where('membership_code',$u->member_by)->where('status',true)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Products',
            "data"=> [
                'products'=> $products,
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
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $stocks=new Stock();
        $stocks->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $stocks->membership_code=$u->membership_code;
        }else{
            $stocks->membership_code=$u->member_by;
        }
        $stocks->date=date('Y-m-d');
        $stocks->sender_name=$request->sender_name;
        $stocks->sender_cell_number=$request->sender_cell_number;
        $stocks->sender_email=$request->sender_email;
        $stocks->sender_address=$request->sender_address;
        $stocks->receiver_name=$request->receiver_name;
        $stocks->receiver_cell_number=$request->receiver_cell_number;
        $stocks->receiver_email=$request->receiver_email;
        $stocks->receiver_address=$request->receiver_address;
        $stocks->reference=$request->reference;
        $stocks->code=$request->code;
        $stocks->item_value=$request->item_value;
        $stocks->discount=$request->discount;
        $stocks->total_amount=$request->total_amount;
        $stocks->paid_amount=$request->paid_amount;
        $stocks->due_amount=$request->due_amount;

        $time = microtime('.') * 10000;
        $stockImg = $request->invoice_image;
        if ($stockImg) {
            $imgname = $time . $stockImg->getClientOriginalName();
            $imguploadPath = ('public/image/invoice');
            $stockImg->move($imguploadPath, $imgname);
            $stockImgUrl = $imguploadPath . $imgname;
            $stocks->invoice_image = $stockImgUrl;
        }
        $success=$stocks->save();

        if($success){
            foreach(json_decode($request->items) as $item){
                $createitem=new Stockitem();
                $createitem->stock_id=$stocks->id;
                $createitem->stockitem_id=$item->stockitem_id;
                $createitem->item_name=Product::where('id',$item->stockitem_id)->first()->ProductName;
                $createitem->description=$item->description;
                $createitem->color=$item->color;
                $createitem->size=$item->size;
                $createitem->weight=$item->weight;
                $createitem->quantity=$item->quantity;
                $createitem->save();
            }
        }

        $response=[
            "status"=>true,
            'message' => "Stock create successful",
            "data"=> [
                'stocks'=> $stocks,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $stocks =Stock::with(['stockitems','users'])->where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $stocks =Stock::with(['stockitems','users'])->where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($stocks)){
            $response = [
                'status' => true,
                'message'=>'Stock By ID',
                "data"=> [
                    'stocks'=> $stocks,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No stocks find by this ID',
                "data"=> [
                    'stocks'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $stocks =Stock::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $stocks =Stock::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($stocks)){
            $stocks->sender_name=$request->sender_name;
            $stocks->sender_cell_number=$request->sender_cell_number;
            $stocks->sender_email=$request->sender_email;
            $stocks->sender_address=$request->sender_address;
            $stocks->receiver_name=$request->receiver_name;
            $stocks->receiver_cell_number=$request->receiver_cell_number;
            $stocks->receiver_email=$request->receiver_email;
            $stocks->receiver_address=$request->receiver_address;
            $stocks->reference=$request->reference;
            $stocks->code=$request->code;
            $stocks->item_value=$request->item_value;
            $stocks->discount=$request->discount;
            $stocks->total_amount=$request->total_amount;
            $stocks->paid_amount=$request->paid_amount;
            $stocks->due_amount=$request->due_amount;

            $time = microtime('.') * 10000;
            $stockImg = $request->invoice_image;
            if ($stockImg) {
                $imgname = $time . $stockImg->getClientOriginalName();
                $imguploadPath = ('public/image/invoice');
                $stockImg->move($imguploadPath, $imgname);
                $stockImgUrl = $imguploadPath . $imgname;
                $stocks->invoice_image = $stockImgUrl;
            }
            $success=$stocks->save();

            if($success){
                $itemsold=Stockitem::where('stock_id', '=', $stocks->id)->get();
                if(isset($itemsold)){
                    Stockitem::where('stock_id', '=', $stocks->id)->delete();
                }

                foreach(json_decode($request->items) as $item){
                    $createitem=new Stockitem();
                    $createitem->stock_id=$stocks->id;
                    $createitem->stockitem_id=$item->stockitem_id;
                    $createitem->item_name=Product::where('id',$item->stockitem_id)->first()->ProductName;
                    $createitem->description=$item->description;
                    $createitem->color=$item->color;
                    $createitem->size=$item->size;
                    $createitem->weight=$item->weight;
                    $createitem->quantity=$item->quantity;
                    $createitem->save();
                }
            }
            $stocks->update();
            $response=[
                "status"=>true,
                'message' => "Stock update successfully",
                "data"=> [
                    'stocks'=> $stocks,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No stock find by this ID',
                "data"=> [
                    'stocks'=> '',
                ]

            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $stocks =Stock::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $stocks =Stock::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($stocks)){
            $stocks->delete();
            $response = [
                'status' => true,
                'message'=> 'Stock delete successfully',
                "data"=> [
                    'stocks'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No stock find by this ID',
                "data"=> [
                    'stocks'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }

}
