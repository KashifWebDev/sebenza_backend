<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Department;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments =Department::all();

        $response = [
            'status' => true,
            'message'=>'List of Departments',
            "data"=> [
                'departments'=> $departments,
            ]

        ];
        return response()->json($response,200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getdepartments()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $departments =Department::where('status','Active')->get();

        $response = [
            'status' => true,
            'message'=>'List of Departments',
            "data"=> [
                'departments'=> $departments,
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
        $departments=new Department();
        $departments->department_name=$request->department_name;
        $departments->status=$request->status;
        $departments->save();
        $response=[
            "status"=>true,
            'message' => "Department create successful",
            "data"=> [
                'departments'=> $departments,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departments =Department::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Department By ID',
            "data"=> [
                'departments'=> $departments,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $departments =Department::where('id',$id)->first();
        $departments->department_name=$request->department_name;
        $departments->status=$request->status;
        $departments->save();
        $response=[
            "status"=>true,
            'message' => "Department update successful",
            "data"=> [
                'departments'=> $departments,
            ]
        ];
        return response()->json($response, 200);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departments =Department::where('id',$id)->first();
        // $departments->delete();
        $response = [
            'status' => true,
            'message'=> 'Department delete successfully',
            "data"=> [
                'departments'=> [],
            ]
        ];
        return response()->json($response,200);
    }


}
