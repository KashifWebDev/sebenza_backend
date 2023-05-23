<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Teammember;
use Illuminate\Http\Request;

class TeammemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uss=Teammember::all();
        foreach($uss as $us){
            $use=$us;
            $use->image=env('PROD_URL').$use->image;
            $teammember[]=$use;
        }
        $response = [
            'status' => true,
            'message'=>'All teammembers infos',
            "data"=> [
                'teammembers'=> $teammember,
            ]
        ];
        return response()->json($response,200);
    }

    public function getteammemberinfo()
    {
        $uss=Teammember::where('status','Active')->get();
        foreach($uss as $us){
            $use=$us;
            $use->image=env('PROD_URL').$use->image;
            $teammember[]=$use;
        }
        $response = [
            'status' => true,
            'message'=>'Active teammembers data',
            "data"=> [
                'teammembers'=> $teammember,
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
        $time = microtime('.') * 10000;
        $teammember=new Teammember();
        $teammember->name=$request->name;
        $teammember->title=$request->title;

        $teammemberImg = $request->file('image');
        if($teammemberImg){
            if($teammember->image=='public/backend/img/user.jpg'){

            }else{
                unlink($teammember->image);
            }
            $imgname = $time . $teammemberImg->getClientOriginalName();
            $imguploadPath = ('public/images/teammember/image/');
            $teammemberImg->move($imguploadPath, $imgname);
            $teammemberImgUrl = $imguploadPath . $imgname;
            $teammember->image = $teammemberImgUrl;
        }

        $teammember->save();
        $teammember->image=env('PROD_URL').$teammember->image;
        $response=[
            "status"=>true,
            'message' => "Teammember create successful",
            "data"=> [
                'teammember'=> $teammember,
            ]
        ];
        return response()->json($response, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teammember  $teammember
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teammember  $teammember
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $teammember=Teammember::where('id',$id)->first();
        $teammember->image=env('PROD_URL').$teammember->image;
        $response=[
            "status"=>true,
            'message' => "Teammembers By ID",
            "data"=> [
                'teammember'=> $teammember,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teammember  $teammember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $time = microtime('.') * 10000;
        $teammember=Teammember::findOrfail($id);
        $teammember->name=$request->name;
        $teammember->title=$request->title;

        $teammemberImg = $request->file('image');
        if($teammemberImg){
            if($teammember->image=='public/backend/img/user.jpg'){

            }else{
                unlink($teammember->image);
            }
            $imgname = $time . $teammemberImg->getClientOriginalName();
            $imguploadPath = ('public/images/teammember/image/');
            $teammemberImg->move($imguploadPath, $imgname);
            $teammemberImgUrl = $imguploadPath . $imgname;
            $teammember->image = $teammemberImgUrl;
        }

        $teammember->save();
        $teammember->image=env('PROD_URL').$teammember->image;
        $response=[
            "status"=>true,
            'message' => "Teammember update successful",
            "data"=> [
                'teammember'=> $teammember,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teammember  $teammember
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $teammember=Teammember::findOrfail($id);
        $teammember->delete();

        $response=[
            "status"=>true,
            'message' => "Teammember delete successful",
            "data"=> [
                'teammember'=> [],
            ]
        ];
        return response()->json($response, 200);
    }
}