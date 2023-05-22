<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Aboutus;
use Illuminate\Http\Request;

class AboutusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aboutus=Aboutus::first();
        $response = [
            'status' => true,
            'message'=>'About Us data',
            "data"=> [
                'aboutus'=> $aboutus,
            ]
        ];
        return response()->json($response,200);
    }

    public function getaboutinfo()
    {
        $aboutus=Aboutus::first();
        $response = [
            'status' => true,
            'message'=>'About Us data',
            "data"=> [
                'aboutus'=> $aboutus,
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Aboutus  $aboutus
     * @return \Illuminate\Http\Response
     */
    public function show(Aboutus $aboutus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aboutus  $aboutus
     * @return \Illuminate\Http\Response
     */
    public function edit(Aboutus $aboutus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Aboutus  $aboutus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $time = microtime('.') * 10000;
        $aboutus=Aboutus::first();
        $aboutus->title=$request->title;
        $aboutus->text=$request->text;
        $aboutus->short_description=$request->short_description;
        $aboutus->short_title=$request->short_title;

        $aboutusImg = $request->file('image');
        if($aboutusImg){
            if($aboutus->image=='public/test.jpg'){

            }else{
                unlink($aboutus->image);
            }
            $imgname = $time . $aboutusImg->getClientOriginalName();
            $imguploadPath = ('public/images/aboutus/image/');
            $aboutusImg->move($imguploadPath, $imgname);
            $aboutusImgUrl = $imguploadPath . $imgname;
            $aboutus->image = $aboutusImgUrl;
        }

        $banner_imageImg = $request->file('banner_image');

        if($banner_imageImg){
            if($aboutus->banner_image=='public/test.jpg'){

            }else{
                unlink($aboutus->banner_image);
            }
            $aimgname = $time . $banner_imageImg->getClientOriginalName();
            $aimguploadPath = ('public/images/aboutus/image/');
            $banner_imageImg->move($aimguploadPath, $aimgname);
            $banner_imageImgUrl = $aimguploadPath . $aimgname;
            $aboutus->banner_image = $banner_imageImgUrl;
        }

        $aboutus->m_title=$request->m_title;
        $aboutus->m_text=$request->m_text;
        $aboutus->m_text_two=$request->m_text_two;

        $aboutus->title_one=$request->title_one;
        $aboutus->text_one=$request->text_one;

        $aboutus->title_two=$request->title_two;
        $aboutus->text_two=$request->text_two;

        $aboutus->title_three=$request->title_three;
        $aboutus->text_three=$request->text_three;

        $aboutus->title_four=$request->title_four;
        $aboutus->text_four=$request->text_four;

        $aboutus->save();

        $response=[
            "status"=>true,
            'message' => "About info updated successfully",
            "data"=> [
                'aboutus'=> $aboutus,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Aboutus  $aboutus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aboutus $aboutus)
    {
        //
    }
}