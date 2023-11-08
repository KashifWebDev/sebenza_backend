<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use App\Models\Saleexcel;
use App\Models\Saleitem;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;
use App\Exports\SaleExport;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    public function fileExport(Request $request)
    {
        $startDate =$request->startDate;
        $endDate =$request->endDate;
        $fileName='public/'.date('Ymd').'order.xlsx';
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        if(isset($startDate) && isset($endDate)){
            $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');

            $file= Excel::store(new SaleExport($startDate,$endDate), $tempFilePath, 'local.xlsx');
            // Move the file to the public path
            $publicPath = public_path('exports/example.xlsx');
            File::move($tempFilePath, $publicPath);

            // Return a response with the public URL of the file
            return response()->file($publicPath);
            $saleexcel=new Saleexcel();
            $u=User::where('id',$user_id->tokenable_id)->first();
            $saleexcel->user_id=$u->id;
            if(isset($u->membership_code)){
                $saleexcel->membership_code=$u->membership_code;
            }else{
                $saleexcel->membership_code=$u->member_by;
            }
            if ($file) {
                $imgname = $time . $file->getClientOriginalName();
                $imguploadPath = ('public/sales');
                $file->move($imguploadPath, $imgname);
                $salesUrl = $imguploadPath . $imgname;
                $saleexcel->data_file = $salesUrl;
            }
            $saleexcel->startDate=$startDate;
            $saleexcel->endDate=$endDate;
            $saleexcel->date=date('Y-m-d');
            $saleexcel->save();
            $response = [
                'status' => true,
                'message'=>'Sales Data Report File',
                "data"=> [
                    'saleexcel'=> $saleexcel,
                ]

            ];

        }else{
            $response = [
                'status' => false,
                'message'=>'Please Select Any Date',
                "data"=> [
                    'saleexcel'=> '',
                ]

            ];
        }
        return response()->json($response,200);
    }

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
            $sales =Sale::with(['saleitems'])->where('membership_code',$u->membership_code)->get();
        }else{
            $sales =Sale::with(['saleitems'])->where('membership_code',$u->member_by)->get();
        }

        if(isset($sales)){
            $response = [
                'status' => true,
                'message'=>'Sales By Membership ID',
                "data"=> [
                    'sales'=> $sales,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No sales find by this Membership ID',
                "data"=> [
                    'sales'=> '',
                ]

            ];
        }
        return response()->json($response,200);
    }

    public function store(Request $request)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $sale=new Sale();
        $sale->user_id=$user_id->id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $sale->membership_code=$u->membership_code;
        }else{
            $sale->membership_code=$u->member_by;
        }
        $sale->invoiceID=$this->invoiceID();

        $sale->customer_name=$request->customer_name;
        $sale->customer_phone=$request->customer_phone;
        $sale->customer_address=$request->customer_address;
        $sale->amount_total=$request->amount_total;
        $sale->discount=$request->discount;
        $sale->payable_amount=$request->amount_total-$request->discount;

        $pauableamount=$request->amount_total-$request->discount;
        if(isset($request->payment_type) && isset($request->paid_amount)){
            $sale->payment_type=$request->payment_type;
            $sale->trx_id=$request->trx_id;
            $sale->payment_date=date('Y-m-d');
            $sale->paid_amount=$request->paid_amount;
            $sale->due=$pauableamount-$request->paid_amount;
        }
        $sale->orderDate=date('Y-m-d');
        $sale->comment=$request->comment;
        $sale->status=$request->status;
        $successsale=$sale->save();

        if($successsale){
            foreach(json_decode($request->items) as $item){
                $createitem=new Saleitem();
                $createitem->sale_id=$sale->id;
                $createitem->product_id=$item->product_id;
                $createitem->item_name=Product::where('id',$item->product_id)->first()->ProductName;
                $createitem->color=$item->color;
                $createitem->size=$item->size;
                $createitem->weight=$item->weight;
                $createitem->quantity=$item->quantity;
                $createitem->unit_price=$item->UnitPrice;
                $createitem->save();
            }
        }

        $response=[
            "status"=>true,
            "message"=>"Sales Create Successfully",
            "data"=> [
                "sale"=>$sale,
            ]
        ];
        return response()->json($response, 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale=Sale::with(['saleitems'])->where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Sale By Sale ID',
            "data"=> [
                'sale'=>$sale,
            ]
        ];

        return response()->json($response,200);
    }

    public function saledata()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $sales =Sale::with(['saleitems'])->where('membership_code',$u->membership_code)->get();
            $salemonthly =Sale::with(['saleitems'])->where('membership_code',$u->membership_code)->whereMonth('created_at', Carbon::now()->month)->get();
            $saletoday =Sale::with(['saleitems'])->where('membership_code',$u->membership_code)->whereDate('created_at', Carbon::today())->get();
        }else{
            $sales =Sale::with(['saleitems'])->where('membership_code',$u->member_by)->get();
            $salemonthly =Sale::with(['saleitems'])->where('membership_code',$u->member_by)->whereMonth('created_at', Carbon::now()->month)->get();
            $saletoday =Sale::with(['saleitems'])->where('membership_code',$u->member_by)->whereDate('created_at', Carbon::today())->get();
        }

        if(isset($sales)){
            $response = [
                'status' => true,
                'message'=>'Sales Data By Membership ID',
                "data"=> [
                    'totalInvoice'=> $sales->count(),
                    'totalPrice'=> $sales->sum('payable_amount'),
                    'totalPaidAmount'=> $sales->sum('paid_amount'),
                    'totalDueAmount'=> $sales->sum('due'),
                    'monthlyInvoice'=> $salemonthly->count(),
                    'monthlyPrice'=> $salemonthly->sum('payable_amount'),
                    'monthlyPaidAmount'=> $salemonthly->sum('paid_amount'),
                    'monthlyDueAmount'=> $salemonthly->sum('due'),
                    'todayInvoice'=> $saletoday->count(),
                    'todayPrice'=> $saletoday->sum('payable_amount'),
                    'todayPaidAmount'=> $saletoday->sum('paid_amount'),
                    'todayDueAmount'=> $saletoday->sum('due'),
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No sales find by this Membership ID',
                "data"=> [
                    'totalInvoice'=> 0,
                    'totalPrice'=> 0,
                    'totalPaidAmount'=> 0,
                    'totalDueAmount'=> 0,
                    'monthlyInvoice'=> 0,
                    'monthlyPrice'=> 0,
                    'monthlyPaidAmount'=> 0,
                    'monthlyDueAmount'=> 0,
                    'todayInvoice'=> 0,
                    'todayPrice'=> 0,
                    'todayPaidAmount'=> 0,
                    'todayDueAmount'=> 0,
                ]

            ];
        }
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

        $sale =Sale::with('saleitems')->where('id',$id)->first();
        $sale->amount_total=$request->amount_total;
        $sale->discount=$request->discount;
        $sale->payable_amount=$request->amount_total-$request->discount;

        $pauableamount=$request->amount_total-$request->discount;
        if(isset($request->payment_type) && isset($request->paid_amount)){
            $sale->payment_type=$request->payment_type;
            $sale->trx_id=$request->trx_id;
            $sale->payment_date=date('Y-m-d');
            $sale->paid_amount=$request->paid_amount;
            $sale->due=$pauableamount-$request->paid_amount;
        }

        $sale->orderDate=date('Y-m-d');
        $sale->comment=$request->comment;
        $sale->status=$request->status;
        $successsale=$sale->update();

        if($successsale){
            $itemsold=Saleitem::where('sale_id', '=', $sale->id)->get();
            if(isset($itemsold)){
                Saleitem::where('sale_id', '=', $sale->id)->delete();
            }
            foreach(json_decode($request->items) as $item){
                $createitem=new Saleitem();
                $createitem->sale_id=$sale->id;
                $createitem->product_id=$item->product_id;
                $createitem->item_name=Product::where('id',$item->product_id)->first()->ProductName;
                $createitem->color=$item->color;
                $createitem->size=$item->size;
                $createitem->weight=$item->weight;
                $createitem->quantity=$item->quantity;
                $createitem->unit_price=$item->UnitPrice;
                $createitem->save();
            }
        }

        $response = [
            'status' => true,
            'message'=>'Sales update successfully',
            "data"=> [
                'sale'=> $sale,
            ]
        ];
        return response()->json($response,200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function invoiceID()
    {
        $lastmember = Sale::latest()->first();
        if ($lastmember) {
            $menberID = $lastmember->id + 1;
        } else {
            $menberID = 1;
        }

        return 'SL00' . $menberID;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
