<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->search)){
            $users =User::with('roles')->where('first_name','LIKE', "%$request->search%")->orWhere('last_name','LIKE', "%$request->search%")->get();
        }else{
            $users =User::with('roles')->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of users',
            "data"=> [
                'users'=> $users,
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
        $email=User::where('email', $request->email)->first();
        $phonenumber=User::where('phone', $request->phone)->first();
        if($email){
            $response = [
                'status' =>false,
                'message' => "Email Already Taken",
                "data"=> [
                    "token"=> '',
                    "user"=>[],
                ]
            ];
            return response()->json($response,201);
        }elseif($phonenumber){
                $response = [
                    'status' =>false,
                    'message' => "Phone number has Already Taken",
                    "data"=> [
                        "token"=> '',
                        "user"=>[],
                    ]
                ];
            return response()->json($response,201);
        }else{
            $user=new User();
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->company_name=$request->company_name;
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->phone=$request->phone;
            $user->membership_code=$this->uniqueID();
            $user->country=$request->country;
            $user->city=$request->city;
            $user->address=$request->address;
            $user->save();
            if($request->roles){
                $user->assignRole($request->roles);
            }

            $response=[
                "status"=>true,
                'message' => "User created successfully",
                "data"=> [
                    'user'=> $user,
                ]
            ];
            return response()->json($response, 200);
        }

    }

    public function uniqueID()
    {
        $lastmember = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'superuser');
                }
            )->first();
        if ($lastmember) {
            $menberID = $lastmember->id + 1;
        } else {
            $menberID = 1;
        }

        return 'SEBENZA00' . $menberID;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getuserroles()
    {
        $roles =Role::where('guard_name','web')->get();
        $response = [
            'status' => true,
            'message'=>'List of user roles',
            "data"=> [
                'roles'=> $roles,
            ]

        ];
        return response()->json($response,200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles =Role::where('guard_name','web')->get();
        $user =User::with('roles')->where('id',$id)->first();


        $response = [
            'status' => true,
            'message'=>'User By ID',
            "data"=> [
                'user'=> $user,
                'roles'=> $roles,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $user=User::where('id',$id)->first();


        if(isset($user)){
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->company_name=$request->company_name;
            if($request->password){
                $user->password=Hash::make($request->password);
            }
            $user->phone=$request->phone;
            $user->country=$request->country;
            $user->city=$request->city;
            $user->address=$request->address;
            $user->update();
            $user->roles()->detach();
            if($request->roles){
                $user->assignRole($request->roles);
            }


            $response=[
                "status"=>true,
                'message' => "User updated successfully",
                "data"=> [
                    'user'=> $user,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response=[
                "status"=>false,
                'message' => "User not found",
                "data"=> [
                    'user'=> [],
                ]
            ];
            return response()->json($response, 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id',$id)->first();
        if(is_null($user)){
            $response=[
                "status"=>false,
                'message' => "Something went wrong",
                "data"=> [
                    'user'=> [],
                ]
            ];
            return response()->json($response, 200);
        }else{
            $user->delete();
            $response=[
                "status"=>true,
                'message' => "User Deleted Successfully",
                "data"=> [
                    'user'=> [],
                ]
            ];
            return response()->json($response, 200);
        }
    }

}
