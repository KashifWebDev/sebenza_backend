<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Task;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
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
        $tasks =Task::where('form_id',$user_id->tokenable_id)->get();

        $response = [
            'status' => true,
            'message'=>'List of Tasks',
            "data"=> [
                'tasks'=> $tasks,
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
        $tasks=new Task();
        $tasks->form_id=$user_id->tokenable_id;
        $tasks->name=$request->name;
        $tasks->details=$request->details;
        $tasks->date=$request->date;
        $tasks->time=$request->time;
        $tasks->save();
        $response=[
            "status"=>true,
            'message' => "Task create successful",
            "data"=> [
                'tasks'=> $tasks,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tasks =Task::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Task By ID',
            "data"=> [
                'tasks'=> $tasks,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $tasks =Task::where('id',$id)->first();
        $tasks->form_id=$user_id->tokenable_id;
        $tasks->name=$request->name;
        $tasks->details=$request->details;
        $tasks->date=$request->date;
        $tasks->time=$request->time;
        $tasks->status=$request->status;
        $tasks->save();
        $response=[
            "status"=>true,
            'message' => "Task update successfully",
            "data"=> [
                'tasks'=> $tasks,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tasks =Task::where('id',$id)->first();
        $tasks->delete();
        $response = [
            'status' => true,
            'message'=> 'Task delete successfully',
            "data"=> [
                'tasks'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}