<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function fileExport(Request $request)
    {
        $startDate =$request->startDate;
        $endDate =$request->endDate;
        $time = microtime('.') * 10000;
        $fileName=$time.'products.xlsx';
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $user=User::where('id',$user_id->tokenable_id)->first();

        if(isset($startDate) && isset($endDate)){

            $file= Excel::store(new ProductExport($startDate,$endDate,$user), $fileName);

            $saleexcel=new Productexcel();
            $u=User::where('id',$user_id->tokenable_id)->first();
            $saleexcel->user_id=$u->id;
            if(isset($u->membership_code)){
                $saleexcel->membership_code=$u->membership_code;
            }else{
                $saleexcel->membership_code=$u->member_by;
            }
            if ($file) {
                $saleexcel->data_file = 'storage/app/'.$fileName;
            }
            $saleexcel->startDate=$startDate;
            $saleexcel->endDate=$endDate;
            $saleexcel->date=date('Y-m-d');
            $saleexcel->save();
            $response = [
                'status' => true,
                'message'=>'Products Data Report File',
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
    public function productlist()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $salesreport =Productexcel::where('membership_code',$u->membership_code)->get();
        }else{
            $salesreport =Productexcel::where('membership_code',$u->member_by)->get();
        }

        if(isset($salesreport)){
            $response = [
                'status' => true,
                'message'=>'Products report data By Membership ID',
                "data"=> [
                    'salesreport'=> $salesreport,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No sales report data found',
                "data"=> [
                    'salesreport'=> '',
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
            $pros =Product::where('membership_code',$u->membership_code)->get();
        }else{
            $pros =Product::where('membership_code',$u->member_by)->get();
        }

        if(count($pros)>0){
            foreach($pros as $us){
                $use=$us;
                if(isset($use->ProductImage)){
                    $use->ProductImage=env('PROD_URL').$use->ProductImage;
                }else{

                }
                $products[]=$use;
            }

            $response = [
                'status' => true,
                'message'=>'List of My Products',
                "data"=> [
                    'products'=> $products,
                ]

            ];
        }else{
            $response = [
                'status' => true,
                'message'=>'List of My Products',
                "data"=> [
                    'products'=> [],
                ]

            ];
        }


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
        $products=new Product();
        $products->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $products->membership_code=$u->membership_code;
        }else{
            $products->membership_code=$u->member_by;
        }
        $products->ProductName=$request->ProductName;
        $products->BrandName=$request->BrandName;
        $products->UnitPrice=$request->UnitPrice;
        $products->SalePrice=$request->SalePrice;

        $time = microtime('.') * 10000;
        $productImg = $request->ProductImage;
        if ($productImg) {
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/image/productimage');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $products->ProductImage = $productImgUrl;
        }

        $products->status=$request->status;
        $products->save();
        $response=[
            "status"=>true,
            'message' => "Product create successful",
            "data"=> [
                'products'=> $products,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $products =Product::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $products =Product::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($products->ProductImage)){
            $products->ProductImage=env('PROD_URL').$products->ProductImage;
        }

        if(isset($products)){
            $response = [
                'status' => true,
                'message'=>'Product By ID',
                "data"=> [
                    'products'=> $products,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No products find by this ID',
                "data"=> [
                    'products'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $products =Product::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $products =Product::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($products)){
            $products->ProductName=$request->ProductName;
            $products->BrandName=$request->BrandName;
            $products->UnitPrice=$request->UnitPrice;
            $products->SalePrice=$request->SalePrice;

            $time = microtime('.') * 10000;
            $productImg = $request->ProductImage;
            if ($productImg) {
                $imgname = $time . $productImg->getClientOriginalName();
                $imguploadPath = ('public/image/productimage');
                $productImg->move($imguploadPath, $imgname);
                $productImgUrl = $imguploadPath . $imgname;
                $products->ProductImage = $productImgUrl;
            }

            $products->status=$request->status;
            $products->update();
            $response=[
                "status"=>true,
                'message' => "Product update successfully",
                "data"=> [
                    'products'=> $products,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No product find by this ID',
                "data"=> [
                    'products'=> '',
                ]

            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $products =Product::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $products =Product::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($products)){
            $products->delete();
            $response = [
                'status' => true,
                'message'=> 'Product delete successfully',
                "data"=> [
                    'products'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No product find by this ID',
                "data"=> [
                    'products'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }
}
