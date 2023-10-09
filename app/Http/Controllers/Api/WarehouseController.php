<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
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
            $warehouses =Warehouse::where('membership_code',$u->membership_code)->get();
        }else{
            $warehouses =Warehouse::where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of My Warehouses',
            "data"=> [
                'warehouses'=> $warehouses,
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
        $warehouses=new Warehouse();
        $warehouses->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $warehouses->membership_code=$u->membership_code;
        }else{
            $warehouses->membership_code=$u->member_by;
        }
        $warehouses->title=$request->title;
        $warehouses->country=$request->country;
        $warehouses->city=$request->city;
        $warehouses->location=$request->location;
        $warehouses->status=$request->status;
        $warehouses->save();
        $response=[
            "status"=>true,
            'message' => "Warehouse create successful",
            "data"=> [
                'warehouses'=> $warehouses,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warehouse  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warehouse  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $warehouses =Warehouse::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $warehouses =Warehouse::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($warehouses)){
            $response = [
                'status' => true,
                'message'=>'Warehouse By ID',
                "data"=> [
                    'warehouses'=> $warehouses,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No warehouses find by this ID',
                "data"=> [
                    'warehouses'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $warehouses =Warehouse::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $warehouses =Warehouse::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($warehouses)){
            $warehouses->title=$request->title;
            $warehouses->country=$request->country;
            $warehouses->city=$request->city;
            $warehouses->location=$request->location;
            $warehouses->status=$request->status;
            $warehouses->total_qty=$request->total_qty;
            $warehouses->total_transfer=$request->total_transfer;
            $warehouses->available_qty=$request->available_qty;
            $warehouses->update();
            $response=[
                "status"=>true,
                'message' => "Warehouse update successfully",
                "data"=> [
                    'warehouses'=> $warehouses,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No product find by this ID',
                "data"=> [
                    'warehouses'=> '',
                ]

            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $warehouses =Warehouse::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $warehouses =Warehouse::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($warehouses)){
            $warehouses->delete();
            $response = [
                'status' => true,
                'message'=> 'Warehouse delete successfully',
                "data"=> [
                    'warehouses'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No product find by this ID',
                "data"=> [
                    'warehouses'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }
}
