<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
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
            $customers =Customer::where('membership_code',$u->membership_code)->get();
        }else{
            $customers =Customer::where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Customer',
            "data"=> [
                'customers'=> $customers,
            ]

        ];
        return response()->json($response,200);
    }

    public function getcustomer()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $customers =Customer::where('membership_code',$u->membership_code)->get();
        }else{
            $customers =Customer::where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Customer',
            "data"=> [
                'customers'=> $customers,
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
        $customers=new Customer();
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $customers->membership_code=$u->membership_code;
        }else{
            $customers->membership_code=$u->member_by;
        }

        $customers->user_id=$user_id->tokenable_id;
        $customers->name=$request->name;
        $customers->email=$request->email;
        $customers->password=Hash::make($request->password);
        $customers->company_name=$request->company_name;
        $customers->status=$request->status;
        $customers->save();
        $response=[
            "status"=>true,
            'message' => "Customers create successfully",
            "data"=> [
                'customers'=> $customers,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customers
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customers
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customers =Customer::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Customer By ID',
            "data"=> [
                'customers'=> $customers,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $customers =Customer::where('id',$id)->first();
        $customers->name=$request->name;
        $customers->email=$request->email;
        $customers->password=Hash::make($request->password);
        $customers->company_name=$request->company_name;
        $customers->status=$request->status;
        $customers->update();
        $response=[
            "status"=>true,
            'message' => "Customer update successfully",
            "data"=> [
                'customers'=> $customers,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customers =Customer::where('id',$id)->first();
        $customers->delete();

        $response = [
            'status' => true,
            'message'=> 'Customer delete successfully',
            "data"=> [
                'customers'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}
