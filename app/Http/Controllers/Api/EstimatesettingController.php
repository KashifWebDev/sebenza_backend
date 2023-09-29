<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\Estimatesetting;
use Illuminate\Http\Request;

class EstimatesettingController extends Controller
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
            $estimatesettings =Estimatesetting::where('membership_code',$u->membership_code)->get();
        }else{
            $estimatesettings =Estimatesetting::where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Estimatesettings',
            "data"=> [
                'estimatesettings'=> $estimatesettings,
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
        $estimatesettings=new Estimatesetting();
        $estimatesettings->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $estimatesettings->membership_code=$u->membership_code;
        }else{
            $estimatesettings->membership_code=$u->member_by;
        }
        $estimatesettings->template_id=$request->template_id;
        $estimatesettings->color_code=$request->color_code;
        $estimatesettings->font_name=$request->font_name;
        $estimatesettings->status=$request->status;

        if($request->logo){
            $logo = $request->file('logo');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/estimate/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $estimatesettings->logo = $logoImgUrl;
        }

        if($request->e_signature){
            $e_signature = $request->file('e_signature');
            $e_signaturename = time() . "_" . $e_signature->getClientOriginalName();
            $e_signatureuploadPath = ('public/images/estimate/');
            $e_signature->move($e_signatureuploadPath, $e_signaturename);
            $e_signatureImgUrl = $e_signatureuploadPath . $e_signaturename;
            $estimatesettings->e_signature = $e_signatureImgUrl;
        }

        $estimatesettings->save();
        $response=[
            "status"=>true,
            'message' => "Estimatesetting create successful",
            "data"=> [
                'estimatesettings'=> $estimatesettings,
            ]
        ];
        return response()->json($response, 200);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estimatesetting  $estimatesetting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $estimatesettings =Estimatesetting::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $estimatesettings =Estimatesetting::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($estimatesettings)){
            $response = [
                'status' => true,
                'message'=>'Estimatesetting By ID',
                "data"=> [
                    'estimatesettings'=> $estimatesettings,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No estimatesetting find by this ID',
                "data"=> [
                    'estimatesettings'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estimatesetting  $estimatesetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $estimatesettings =Estimatesetting::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $estimatesettings =Estimatesetting::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($estimatesettings)){
            $estimatesettings->user_id=$user_id->tokenable_id;
            $estimatesettings->template_id=$request->template_id;
            $estimatesettings->color_code=$request->color_code;
            $estimatesettings->font_name=$request->font_name;
            $estimatesettings->status=$request->status;

            if($request->logo){
                $logo = $request->file('logo');
                $name = time() . "_" . $logo->getClientOriginalName();
                $uploadPath = ('public/images/estimate/');
                $logo->move($uploadPath, $name);
                $logoImgUrl = $uploadPath . $name;
                $estimatesettings->logo = $logoImgUrl;
            }

            if($request->e_signature){
                $e_signature = $request->file('e_signature');
                $e_signaturename = time() . "_" . $e_signature->getClientOriginalName();
                $e_signatureuploadPath = ('public/images/estimate/');
                $e_signature->move($e_signatureuploadPath, $e_signaturename);
                $e_signatureImgUrl = $e_signatureuploadPath . $e_signaturename;
                $estimatesettings->e_signature = $e_signatureImgUrl;
            }
            $estimatesettings->update();
            $response=[
                "status"=>true,
                'message' => "Estimatesetting update successfully",
                "data"=> [
                    'estimatesettings'=> $estimatesettings,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No estimatesetting find by this ID',
                "data"=> [
                    'estimatesettings'=> '',
                ]

            ];
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estimatesetting  $estimatesetting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $estimatesettings =Estimatesetting::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $estimatesettings =Estimatesetting::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($estimatesettings)){
            $estimatesettings->delete();
            $response = [
                'status' => true,
                'message'=> 'Estimatesetting delete successfully',
                "data"=> [
                    'estimatesettings'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No estimatesetting find by this ID',
                "data"=> [
                    'estimatesettings'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }

}
