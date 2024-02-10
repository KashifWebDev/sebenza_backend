<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Serveice;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ServeiceController extends Controller
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
            $pros =Serveice::where('membership_code',$u->membership_code)->get();
        }else{
            $pros =Serveice::where('membership_code',$u->member_by)->get();
        }

        if(count($pros)>0){
            $response = [
                'status' => true,
                'message'=>'List of My Serveices',
                "data"=> [
                    'serveices'=> $serveices,
                ]

            ];
        }else{
            $response = [
                'status' => true,
                'message'=>'List of My Serveices',
                "data"=> [
                    'serveices'=> [],
                ]

            ];
        }


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
        $serveices=new Serveice();
        $serveices->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $serveices->membership_code=$u->membership_code;
        }else{
            $serveices->membership_code=$u->member_by;
        }
        $serveices->title=$request->title;
        $serveices->regular_price=$request->regular_price;
        $serveices->discount=$request->discount;
        $serveices->net_price=$request->regular_price-$request->discount;
        $serveices->status=$request->status;

        $time = microtime('.') * 10000;
        $serveiceImg = $request->image;
        if ($serveiceImg) {
            $imgname = $time . $serveiceImg->getClientOriginalName();
            $imguploadPath = ('public/image/serveiceimage');
            $serveiceImg->move($imguploadPath, $imgname);
            $serveiceImgUrl = $imguploadPath . $imgname;
            $serveices->image = $serveiceImgUrl;
        }

        $serveices->save();
        $response=[
            "status"=>true,
            'message' => "Serveice create successful",
            "data"=> [
                'serveices'=> $serveices,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Serveice  $serveice
     * @return \Illuminate\Http\Response
     */
    public function show(Serveice $serveice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Serveice  $serveice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $serveices =Serveice::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $serveices =Serveice::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($serveices)){
            $response = [
                'status' => true,
                'message'=>'Serveice By ID',
                "data"=> [
                    'serveices'=> $serveices,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No serveices find by this ID',
                "data"=> [
                    'serveices'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Serveice  $serveice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $serveices =Serveice::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $serveices =Serveice::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($serveices)){
            $serveices->title=$request->title;
            $serveices->regular_price=$request->regular_price;
            $serveices->discount=$request->discount;
            $serveices->net_price=$request->regular_price-$request->discount;
            $serveices->status=$request->status;

            $time = microtime('.') * 10000;
            $serveiceImg = $request->image;
            if ($serveiceImg) {
                $imgname = $time . $serveiceImg->getClientOriginalName();
                $imguploadPath = ('public/image/serveiceimage');
                $serveiceImg->move($imguploadPath, $imgname);
                $serveiceImgUrl = $imguploadPath . $imgname;
                $serveices->image = $serveiceImgUrl;
            }
            $serveices->update();
            $response=[
                "status"=>true,
                'message' => "Serveice update successfully",
                "data"=> [
                    'serveices'=> $serveices,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No serveice find by this ID',
                "data"=> [
                    'serveices'=> '',
                ]

            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Serveice  $serveice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $serveices =Serveice::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $serveices =Serveice::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($serveices)){
            $serveices->delete();
            $response = [
                'status' => true,
                'message'=> 'Serveice delete successfully',
                "data"=> [
                    'serveices'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No serveice find by this ID',
                "data"=> [
                    'serveices'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }
}
