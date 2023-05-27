<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Newsupdate;
use DB;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use App\Models\Seennewsupdate;

class NewsController extends Controller
{
    public function getnews($id)
    {
        $uss =Newsupdate::where('status','Active')->get();

        if(count($uss)>0){
            foreach($uss as $us){
                $use=$us;
                if(isset($use->postImage)){
                    $use->postImage=env('PROD_URL').$use->postImage;
                }else{

                }
                $se=Seennewsupdate::where('news_id',$use->id)->where('user_id',$id)->first();
                if(isset($se)){
                    if($se->seen==1){
                        $use->seen=true;
                    }else{
                        $use->seen=false;
                    }
                }else{
                    $use->seen=false;
                }
                $use->total_view=Seennewsupdate::where('news_id',$use->id)->get()->count();
                $news[]=$use;
            }
        }else{
            $news=[];
        }

        $response = [
            'status' => true,
            'message'=>'List of active news & updates',
            "data"=> [
                'news'=> $news,
            ]

        ];
        return response()->json($response,200);
    }

    public function getnewsbyid(Request $request, $slug)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $news =Newsupdate::where('slug',$request->slug)->where('status','Active')->first();
        if(isset($news->postImage)){
            $news->postImage=env('PROD_URL').$news->postImage;
        }else{

        }

        $se=Seennewsupdate::where('news_id',$news->id)->where('user_id',$user_id->tokenable_id)->first();
        if(isset($se)){
        }else{
            $seen=new Seennewsupdate();
            $seen->seen=true;
            $seen->news_id=$news->id;
            $seen->user_id=$user_id->tokenable_id;
            $seen->save();
        }

        $news->seen=true;
        $news->total_view=Seennewsupdate::where('news_id',$news->id)->get()->count();

        $response = [
            'status' => true,
            'message'=>'News & updates view by ID',
            "data"=> [
                'news'=> $news,
            ]

        ];
        return response()->json($response,200);
    }


    public function getpubnews()
    {
        $uss =Newsupdate::where('status','Active')->get();
        foreach($uss as $us){
            $use=$us;
            if(isset($use->postImage)){
                $use->postImage=env('PROD_URL').$use->postImage;
            }else{

            }
            $use->total_view=Seennewsupdate::where('news_id',$use->id)->get()->count();

            $news[]=$use;
        }

        $response = [
            'status' => true,
            'message'=>'List of active news & updates',
            "data"=> [
                'news'=> $news,
            ]

        ];
        return response()->json($response,200);
    }

    public function getpubnewsbyid($slug)
    {
        $news =Newsupdate::where('slug',$slug)->where('status','Active')->first();
        if(isset($news->postImage)){
            $news->postImage=env('PROD_URL').$news->postImage;
        }else{

        }
        $news->total_view=Seennewsupdate::where('news_id',$news->id)->get()->count();

        $response = [
            'status' => true,
            'message'=>'News & updates by id',
            "data"=> [
                'news'=> $news,
            ]

        ];
        return response()->json($response,200);
    }


}