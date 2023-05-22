<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsupdate;
use Illuminate\Http\Request;

class NewsupdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uss =Newsupdate::all();

        foreach($uss as $us){
            $use=$us;
            $use->postImage=env('PROD_URL').$use->postImage;
            $news[]=$use;
        }

        $response = [
            'status' => true,
            'message'=>'List of news & updates',
            "data"=> [
                'news'=> $news,
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
        $news=new Newsupdate();
        $news->title=$request->title;
        $news->news=$request->news;

        $newsImg = $request->file('postImage');
        if($newsImg){
            $imgname = $time . $newsImg->getClientOriginalName();
            $imguploadPath = ('public/images/news/');
            $newsImg->move($imguploadPath, $imgname);
            $newsImgUrl = $imguploadPath . $imgname;
            $news->postImage = $newsImgUrl;
        }

        // if ($request->hasFile('postImage')) {
        //     foreach ($request->file('postImage') as $imgfiles) {
        //         $name = time() . "_" . $imgfiles->getClientOriginalName();
        //         $imgfiles->move(public_path() . '/images/news/', $name);
        //         $imageData[] = $name;
        //     }
        //     $news->postImage = json_encode($imageData);
        // };
        $news->save();
        $news->postImage=env('PROD_URL').$news->postImage;

        $response=[
            "status"=>true,
            'message' => "News & updates created successfully",
            "data"=> [
                'news'=> $news,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Newsupdate  $newsupdate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $news=Newsupdate::where('id',$id)->first();
        $news->postImage=env('PROD_URL').$news->postImage;
        $response=[
            "status"=>true,
            'message' => "News & updates By ID",
            "data"=> [
                'news'=> $news,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Newsupdate  $newsupdate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $time = microtime('.') * 10000;
        $news=Newsupdate::where('id',$id)->first();;
        $news->title=$request->title;
        $news->news=$request->news;
        $news->status=$request->status;
        $news->slug=$request->slug;

        $newsImg = $request->file('postImage');

        if($newsImg){
            if($news->postImage=='public/test.jpg'){

            }else{
                unlink($news->postImage);
            }
            $imgname = $time . $newsImg->getClientOriginalName();
            $imguploadPath = ('public/images/news/image/');
            $newsImg->move($imguploadPath, $imgname);
            $newsImgUrl = $imguploadPath . $imgname;
            $news->postImage = $newsImgUrl;
        }

        // if ($request->hasFile('postImage')) {
        //     if($news->postImage){
        //         foreach (json_decode($news->postImage) as $postimg) {
        //            unlink('public/images/news/' . $postimg);
        //         }
        //     }
        //     foreach ($request->file('postImage') as $imgfiles) {
        //         $name = time() . "_" . $imgfiles->getClientOriginalName();
        //         $imgfiles->move(public_path() . '/images/news/', $name);
        //         $imageData[] = $name;
        //     }
        //     $news->postImage = json_encode($imageData);
        // }
        $news->save();
        $news->postImage=env('PROD_URL').$news->postImage;

        $response=[
            "status"=>true,
            'message' => "News & updates updated successfully",
            "data"=> [
                'news'=> $news,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Newsupdate  $newsupdate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news=Newsupdate::where('id',$id)->first();
        $news->delete();
        $response=[
            "status"=>true,
            'message' => "News & updates Deleted Successfully",
            "data"=> [
                'news'=> [],
            ]
        ];
        return response()->json($response, 200);
    }
}
