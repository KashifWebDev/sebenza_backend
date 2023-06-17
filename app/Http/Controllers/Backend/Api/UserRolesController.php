<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DataTables;

class UserRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->search)){
            $roles =Role::where('guard_name','web')->where('name','LIKE', "%$request->search%")->get();
        }else{
            $roles =Role::where('guard_name','web')->get();
        }

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $exist =Role::where('name',$request->roleName)->where('guard_name','web')->first();
        if($exist){
            $response=[
                "status"=>false,
                'message' => "Role already exist",
                "data"=> [
                    'role'=> [],
                ]
            ];
            return response()->json($response, 200);
        }else{
            $role = Role::create(['name' => $request->roleName,'guard_name' => 'web']);
            if(empty($role)){
                $response=[
                    "status"=>false,
                    'message' => "Something went wrong",
                    "data"=> [
                        'role'=> [],
                    ]
                ];
                return response()->json($response, 200);
            }else{
                $response=[
                    "status"=>true,
                    'message' => "Role created successfully",
                    "data"=> [
                        'role'=> $role,
                    ]
                ];
                return response()->json($response, 200);
            }
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role =Role::where('id',$id)->where('guard_name','web')->first();
        $response=[
            "status"=>true,
            'message' => "Role By Role ID",
            "data"=> [
                'role'=>$role,
            ]
        ];
        return response()->json($response, 200);
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
        $role =Role::where('id',$id)->where('guard_name','web')->first();
        $role->name=$request->roleName;
        $role->update();

        if(empty($role)){
            $response=[
                "status"=>false,
                'message' => "Something went wrong",
                "data"=> [
                    'role'=> [],
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response=[
                "status"=>true,
                'message' => "Role update successfully",
                "data"=> [
                    'role'=> $role,
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
        $role =Role::findById($id,'web');
        if(is_null($role)){
            $response=[
                "status"=>true,
                'message' => "Something went wrong",
                "data"=> [
                    'role'=> [],
                ]
            ];
            return response()->json($response, 200);
        }else{
            $role->delete();
            $response=[
                "status"=>true,
                'message' => "Role Deleted Successfully",
                "data"=> [
                    'role'=> [],
                ]
            ];
            return response()->json($response, 200);
        }
    }

}
