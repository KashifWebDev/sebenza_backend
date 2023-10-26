<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\Project;
use App\Models\Projectexpense;
use App\Models\Customer;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $projects =Project::with(['users','assigns','customers','projectexpenses'])->where('membership_code',$u->membership_code)->get();
        }else{
            $projects =Project::with(['users','assigns','customers','projectexpenses'])->where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Projects',
            "data"=> [
                'projects'=> $projects,
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
        $projects=new Project();
        $projects->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $projects->membership_code=$u->membership_code;
        }else{
            $projects->membership_code=$u->member_by;
        }

        $projects->projectID=$this->uniqueID();
        $projects->customer_id=$request->customer_id;
        $projects->customer_name=Customer::where('id',$request->customer_id)->first()->name;
        $projects->project_title=$request->project_title;
        $projects->description=$request->description;
        $projects->phases=$request->phases;
        $projects->budget=$request->budget;
        $projects->startDate=$request->startDate;
        $projects->endDate=$request->endDate;
        $projects->progress=$request->progress;
        $projects->assign_to=$request->assign_to;
        $projects->customer_can_view=$request->customer_can_view;
        $projects->customer_can_comment=$request->customer_can_comment;
        $projects->priority=$request->priority;
        $projects->status=$request->status;

        $success=$projects->save();
        if($success){
            if(isset($request->title) && isset($request->amount) && isset($request->spent_by)){
                $payment=new Projectexpense();
                $payment->project_id=$projects->id;
                $payment->title=$request->title;
                $payment->description=$request->description;
                $payment->amount=$request->amount;
                $payment->spent_by=$request->spent_by;
                $payment->date=date('Y-m-d');
                $payment->user_id=$user_id->tokenable_id;
                $payment->save();
            }
        }
        $response=[
            "status"=>true,
            'message' => "Project create successfully",
            "data"=> [
                'projects'=> $projects,
            ]
        ];
        return response()->json($response, 200);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $projects =Project::with(['users','assigns','customers','projectexpenses'])->where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $projects =Project::with(['users','assigns','customers','projectexpenses'])->where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($projects)){
            $response = [
                'status' => true,
                'message'=>'Project By ID',
                "data"=> [
                    'projects'=> $projects,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No project find by this ID',
                "data"=> [
                    'projects'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $projects =Project::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $projects =Project::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($projects)){
            $projects->customer_id=$request->customer_id;
            $projects->customer_name=Customer::where('id',$request->customer_id)->first()->name;
            $projects->project_title=$request->project_title;
            $projects->description=$request->description;
            $projects->phases=$request->phases;
            $projects->budget=$request->budget;
            $projects->startDate=$request->startDate;
            $projects->endDate=$request->endDate;
            $projects->progress=$request->progress;
            $projects->assign_to=$request->assign_to;
            $projects->customer_can_view=$request->customer_can_view;
            $projects->customer_can_comment=$request->customer_can_comment;
            $projects->priority=$request->priority;
            $projects->status=$request->status;
            $success=$projects->update();
            if($success){
                if(isset($request->title) && isset($request->amount) && isset($request->spent_by)){
                    $payment=new Projectexpense();
                    $payment->project_id=$projects->id;
                    $payment->title=$request->title;
                    $payment->description=$request->description;
                    $payment->amount=$request->amount;
                    $payment->spent_by=$request->spent_by;
                    $payment->date=date('Y-m-d');
                    $payment->user_id=$user_id->tokenable_id;
                    $payment->save();
                }
            }
            $response=[
                "status"=>true,
                'message' => "Project update successfully",
                "data"=> [
                    'projects'=> $projects,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No project find by this ID',
                "data"=> [
                    'projects'=> '',
                ]

            ];
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $projects =Project::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $projects =Project::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($projects)){
            $projects->delete();
            $response = [
                'status' => true,
                'message'=> 'Project delete successfully',
                "data"=> [
                    'projects'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No project find by this ID',
                "data"=> [
                    'projects'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }


    public function uniqueID()
    {
        $lastOrder = Project::latest('id')->first();
        if ($lastOrder) {
            $orderID = $lastOrder->id + 1;
        } else {
            $orderID = 1;
        }

        return 'PRM' . $orderID;
    }



}
