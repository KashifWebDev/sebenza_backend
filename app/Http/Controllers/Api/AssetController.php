<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
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
            $assets =Asset::where('membership_code',$u->membership_code)->get();
        }else{
            $assets =Asset::where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of My Assets',
            "data"=> [
                'assets'=> $assets,
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
        $assets=new Asset();
        $assets->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $assets->membership_code=$u->membership_code;
        }else{
            $assets->membership_code=$u->member_by;
        }
        $assets->asset_name=$request->asset_name;
        $assets->asset_description=$request->asset_description;
        $assets->quantity=$request->quantity;
        $assets->purchese_date=$request->purchese_date;
        $assets->purchese_value=$request->purchese_value;
        $assets->currency=$request->currency;
        $assets->capture_date=date('Y-m-d');
        $assets->capture_name=$u->name;

        $time = microtime('.') * 10000;
        $productImg = $request->attachment;
        if ($productImg) {
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/image/attachment');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $assets->attachment = $productImgUrl;
        }

        $assets->save();
        $response=[
            "status"=>true,
            'message' => "Asset create successful",
            "data"=> [
                'assets'=> $assets,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $assets =Asset::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $assets =Asset::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($assets)){
            $response = [
                'status' => true,
                'message'=>'Asset By ID',
                "data"=> [
                    'assets'=> $assets,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No assets find by this ID',
                "data"=> [
                    'assets'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $assets =Asset::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $assets =Asset::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($assets)){
            $assets->asset_name=$request->asset_name;
            $assets->asset_description=$request->asset_description;
            $assets->quantity=$request->quantity;
            $assets->purchese_date=$request->purchese_date;
            $assets->purchese_value=$request->purchese_value;
            $assets->currency=$request->currency;

            $time = microtime('.') * 10000;
            $productImg = $request->attachment;
            if ($productImg) {
                $imgname = $time . $productImg->getClientOriginalName();
                $imguploadPath = ('public/image/attachment');
                $productImg->move($imguploadPath, $imgname);
                $productImgUrl = $imguploadPath . $imgname;
                $assets->attachment = $productImgUrl;
            }

            $assets->update();
            $response=[
                "status"=>true,
                'message' => "Asset update successfully",
                "data"=> [
                    'assets'=> $assets,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No asset find by this ID',
                "data"=> [
                    'assets'=> '',
                ]

            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $assets =Asset::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $assets =Asset::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($assets)){
            $assets->delete();
            $response = [
                'status' => true,
                'message'=> 'Asset delete successfully',
                "data"=> [
                    'assets'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No asset find by this ID',
                "data"=> [
                    'assets'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }
}
