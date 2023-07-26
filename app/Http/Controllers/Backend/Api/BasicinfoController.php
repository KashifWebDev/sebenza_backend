<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Basicinfo;
use Illuminate\Http\Request;

class BasicinfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $webinfo =Basicinfo::first();
        $response = [
            'status' => true,
            'message'=>'Software basic infos',
            "data"=> [
                'basicinfo'=> $webinfo,
            ]

        ];
        return response()->json($response,200);


    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Basicinfo  $basicinfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $webinfo =Basicinfo::first();
        $webinfo->title=$request->title;
        $webinfo->email=$request->email;
        $webinfo->contact=$request->contact;
        $webinfo->cost_per_user=$request->cost_per_user;
        $webinfo->address=$request->address;
        if($request->logo){
            if($webinfo->logo =='public/webview/assets/images/logo.png'){
            }else{
                unlink($webinfo->logo);
            }
            $logo = $request->file('logo');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/logo/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $webinfo->logo = $logoImgUrl;
        }
        $webinfo->update();
        $response = [
            'status' => true,
            'message'=>'Basic info update successfully',
            "data"=> [
                'basicinfo'=> $webinfo,
            ]

        ];
        return response()->json($response,200);
    }

    public function pixelanalytics(Request $request)
    {
        $webinfo =Basicinfo::first();
        $webinfo->facebook_pixel=$request->facebook_pixel;
        $webinfo->google_analytics=$request->google_analytics;
        $webinfo->update();
        $response = [
            'status' => true,
            'message'=>'Pixel & Analytics updated successfully',
            "data"=> [
                'basicinfo'=> $webinfo,
            ]

        ];
        return response()->json($response,200);
    }

    public function sociallink(Request $request)
    {
        $webinfo =Basicinfo::first();
        if(isset($request->facebook)){
            $webinfo->facebook=$request->facebook;
        }else{
            $webinfo->facebook=null;
        }
        if(isset($request->instagram)){
            $webinfo->instagram=$request->instagram;
        }else{
            $webinfo->instagram=null;
        }
        if(isset($request->tiktok)){
            $webinfo->tiktok=$request->tiktok;
        }else{
            $webinfo->tiktok=null;
        }
        if(isset($request->pinterest)){
            $webinfo->pinterest=$request->pinterest;
        }else{
            $webinfo->pinterest=null;
        }
        if(isset($request->twitter)){
            $webinfo->twitter=$request->twitter;
        }else{
            $webinfo->twitter=null;
        }
        if(isset($request->google)){
            $webinfo->google=$request->google;
        }else{
            $webinfo->google=null;
        }
        if(isset($request->rss)){
            $webinfo->rss=$request->rss;
        }else{
            $webinfo->rss=null;
        }
        if(isset($request->linkedin)){
            $webinfo->linkedin=$request->linkedin;
        }else{
            $webinfo->linkedin=null;
        }
        if(isset($request->youtube)){
            $webinfo->youtube=$request->youtube;
        }else{
            $webinfo->youtube=null;
        }
        $webinfo->update();

        $response = [
            'status' => true,
            'message'=>'Social Links updated successfully',
            "data"=> [
                'basicinfo'=> $webinfo,
            ]

        ];
        return response()->json($response,200);

    }

     public function seometa(Request $request)
    {
        $webinfo =Basicinfo::first();
        $webinfo->site_name=$request->site_name;
        $webinfo->meta_description=$request->meta_description;
        $webinfo->meta_keyword=$request->meta_keyword;
        $webinfo->update();
        $response = [
            'status' => true,
            'message'=>'Seo meta info update successfully',
            "data"=> [
                'basicinfo'=> $webinfo,
            ]

        ];
        return response()->json($response,200);
    }

}