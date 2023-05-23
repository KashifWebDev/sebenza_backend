<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Helpcenter;
use Illuminate\Http\Request;

class HelpcenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $helpcenter=Helpcenter::first();
        $helpcenter->image=env('PROD_URL').$helpcenter->image;
        $helpcenter->image_two=env('PROD_URL').$helpcenter->image_two;
        $response = [
            'status' => true,
            'message'=>'Help center page data',
            "data"=> [
                'helpcenter'=> $helpcenter,
            ]
        ];
        return response()->json($response,200);
    }

    public function gethelpcenterinfo()
    {
        $helpcenter=Helpcenter::first();
        $helpcenter->image=env('PROD_URL').$helpcenter->image;
        $helpcenter->image_two=env('PROD_URL').$helpcenter->image_two;
        $response = [
            'status' => true,
            'message'=>'Help center page data',
            "data"=> [
                'helpcenter'=> $helpcenter,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $time = microtime('.') * 10000;
        $helpcenter=Helpcenter::first();
        $helpcenter->title=$request->title;
        $helpcenter->text=$request->text;
        $helpcenter->youtube_link=$request->youtube_link;
        $helpcenter->youtube_link_two=$request->youtube_link_two;

        $helpcenterImg = $request->file('image');
        if($helpcenterImg){
            if($helpcenter->image=='public/test.jpg'){

            }else{
                unlink($helpcenter->image);
            }
            $imgname = $time . $helpcenterImg->getClientOriginalName();
            $imguploadPath = ('public/images/helpcenter/image/');
            $helpcenterImg->move($imguploadPath, $imgname);
            $helpcenterImgUrl = $imguploadPath . $imgname;
            $helpcenter->image = $helpcenterImgUrl;
        }

        $banner_imageImg = $request->file('image_two');

        if($banner_imageImg){
            if($helpcenter->image_two=='public/test.jpg'){

            }else{
                unlink($helpcenter->image_two);
            }
            $aimgname = $time . $banner_imageImg->getClientOriginalName();
            $aimguploadPath = ('public/images/helpcenter/image/');
            $banner_imageImg->move($aimguploadPath, $aimgname);
            $banner_imageImgUrl = $aimguploadPath . $aimgname;
            $helpcenter->image_two = $banner_imageImgUrl;
        }


        $helpcenter->save();
        $helpcenter->image=env('PROD_URL').$helpcenter->image;
        $helpcenter->image_two=env('PROD_URL').$helpcenter->image_two;

        $response=[
            "status"=>true,
            'message' => "Help center info updated successfully",
            "data"=> [
                'helpcenter'=> $helpcenter,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Helpcenter  $helpcenter
     * @return \Illuminate\Http\Response
     */
    public function show(Helpcenter $helpcenter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Helpcenter  $helpcenter
     * @return \Illuminate\Http\Response
     */
    public function edit(Helpcenter $helpcenter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Helpcenter  $helpcenter
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Helpcenter  $helpcenter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Helpcenter $helpcenter)
    {
        //
    }
}