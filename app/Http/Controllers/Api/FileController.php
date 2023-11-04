<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FileController extends Controller
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
            $files =File::where('membership_code',$u->membership_code)->get();
        }else{
            $files =File::where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Files',
            "data"=> [
                'files'=> $files,
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
        $files=new File();
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $files->membership_code=$u->membership_code;
        }else{
            $files->membership_code=$u->member_by;
        }
        $files->user_id=$user_id->tokenable_id;
        $files->title=$request->title;
        $files->text=$request->text;

        if($request->file){
            $logo = $request->file('file');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/file/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $files->file = $logoImgUrl;
        }

        $files->save();
        $response=[
            "status"=>true,
            'message' => "Files create successfully",
            "data"=> [
                'files'=> $files,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $files
     * @return \Illuminate\Http\Response
     */
    public function show(File $files)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $files
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $files =File::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'File By ID',
            "data"=> [
                'files'=> $files,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $files
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $files =File::where('id',$id)->first();
        $files->user_id=$user_id->tokenable_id;
        $files->title=$request->title;
        $files->text=$request->text;

        if($request->file){
            $logo = $request->file('file');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/file/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $files->file = $logoImgUrl;
        }
        $files->update();
        $response=[
            "status"=>true,
            'message' => "File update successfully",
            "data"=> [
                'files'=> $files,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $files
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $files =File::where('id',$id)->first();
        $files->delete();

        $response = [
            'status' => true,
            'message'=> 'File delete successfully',
            "data"=> [
                'files'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}
