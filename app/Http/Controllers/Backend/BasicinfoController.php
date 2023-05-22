<?php

namespace App\Http\Controllers\Backend;

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
        return view('backend.content.basicinfo.index',['webinfo'=>$webinfo]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Basicinfo  $basicinfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $webinfo =Basicinfo::where('id',$id)->first();
        $webinfo->email=$request-> email;
        $webinfo->phone_one=$request-> phone_one;
        $webinfo->phone_two=$request-> phone_two;
        $webinfo->address=$request-> address;
        if($request->logo){
            if($webinfo->logo =='public/webview/assets/images/logo.png'){
            }else{
                unlink($webinfo->logo);
            }
            $logo = $request->file('logo');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/categorybanner/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $webinfo->logo = $logoImgUrl;
        }
        $webinfo->save();
        return redirect()->back()->with('message','Info updated successfully');
    }

    public function pixelanalytics(Request $request, $id)
    {
        $webinfo =Basicinfo::where('id',$id)->first();
        $webinfo->facebook_pixel=$request->facebook_pixel;
        $webinfo->google_analytics=$request->google_analytics;
        $webinfo->update();
        return redirect()->back()->with('message','Pixel & Analytics updated successfully');
    }

    public function sociallink(Request $request, $id)
    {
        $webinfo =Basicinfo::where('id',$id)->first();
        if(isset($request->facebook)){
            $webinfo->facebook=$request->facebook;
        }else{
            $webinfo->facebook=null;
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
        if(isset($request->pinterest)){
            $webinfo->pinterest=$request->pinterest;
        }else{
            $webinfo->pinterest=null;
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
        return redirect()->back()->with('message','Social Links updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Basicinfo  $basicinfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Basicinfo $basicinfo)
    {
        //
    }
}
