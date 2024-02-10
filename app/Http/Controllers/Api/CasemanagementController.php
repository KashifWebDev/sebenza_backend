<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\Casemanagement;
use App\Models\Customer;
use Illuminate\Http\Request;

class CasemanagementController extends Controller
{
    public function index()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $casemanagements =Casemanagement::with(['users','assigns','customers'])->where('membership_code',$u->membership_code)->get();
        }else{
            $casemanagements =Casemanagement::with(['users','assigns','customers'])->where('membership_code',$u->member_by)->get();
        }

        $response = [
            'status' => true,
            'message'=>'List of Casemanagements',
            "data"=> [
                'casemanagements'=> $casemanagements,
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
        $casemanagements=new Casemanagement();
        $casemanagements->user_id=$user_id->tokenable_id;
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $casemanagements->membership_code=$u->membership_code;
        }else{
            $casemanagements->membership_code=$u->member_by;
        }

        $casemanagements->caseID=$this->uniqueID();
        $casemanagements->customer_id=$request->customer_id;
        $casemanagements->customer_name=Customer::where('id',$request->customer_id)->first()->name;
        $casemanagements->title=$request->title;
        $casemanagements->description=$request->description;
        $casemanagements->phases=$request->phases;
        $casemanagements->notes=$request->notes;

        $time = microtime('.') * 10000;
        $productImg = $request->file('customer_e_signature');
        if($productImg){
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/estimate/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $casemanagements->customer_e_signature = $productImgUrl;
        }

        $casemanagements->subTotal=$request->subTotal;
        $casemanagements->discountCharge=$request->discountCharge;
        $casemanagements->vat=$request->vat;
        $casemanagements->total=$request->subTotal+$request->vat-$request->discountCharge;

        $casemanagements->entryDate=$request->entryDate;
        $casemanagements->doneDate=$request->doneDate;
        $casemanagements->last_updated=date('Y-m-d');
        $casemanagements->paymentDate=$request->paymentDate;
        $casemanagements->progress=$request->progress;
        $casemanagements->assign_to=$request->assign_to;
        $casemanagements->customer_can_view=$request->customer_can_view;
        $casemanagements->customer_can_comment=$request->customer_can_comment;
        $casemanagements->priority=$request->priority;
        $casemanagements->status=$request->status;

        $success=$casemanagements->save();

        if(isset($success)){
            $response=[
                "status"=>true,
                'message' => "Casemanagement create successfully",
                "data"=> [
                    'casemanagements'=> $casemanagements,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response=[
                "status"=>false,
                'message' => "Something went wrong. Please try again !",
                "data"=> [
                    'casemanagements'=> '',
                ]
            ];
            return response()->json($response, 200);
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Casemanagement  $casemanagement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $casemanagements =Casemanagement::with(['users','assigns','customers'])->where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $casemanagements =Casemanagement::with(['users','assigns','customers'])->where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($casemanagements)){
            $response = [
                'status' => true,
                'message'=>'Casemanagement By ID',
                "data"=> [
                    'casemanagements'=> $casemanagements,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No casemanagement find by this ID',
                "data"=> [
                    'casemanagements'=> '',
                ]

            ];
        }

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Casemanagement  $casemanagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $casemanagements =Casemanagement::with(['users','assigns','customers'])->where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $casemanagements =Casemanagement::with(['users','assigns','customers'])->where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($casemanagements)){
            $casemanagements->customer_id=$request->customer_id;
            $casemanagements->customer_name=Customer::where('id',$request->customer_id)->first()->name;
            $casemanagements->title=$request->title;
            $casemanagements->description=$request->description;
            $casemanagements->phases=$request->phases;
            $casemanagements->notes=$request->notes;

            $time = microtime('.') * 10000;
            $productImg = $request->file('customer_e_signature');
            if($productImg){
                $imgname = $time . $productImg->getClientOriginalName();
                $imguploadPath = ('public/images/estimate/');
                $productImg->move($imguploadPath, $imgname);
                $productImgUrl = $imguploadPath . $imgname;
                $casemanagements->customer_e_signature = $productImgUrl;
            }

            $casemanagements->subTotal=$request->subTotal;
            $casemanagements->discountCharge=$request->discountCharge;
            $casemanagements->vat=$request->vat;
            $casemanagements->total=$request->subTotal+$request->vat-$request->discountCharge;

            $casemanagements->entryDate=$request->entryDate;
            $casemanagements->doneDate=$request->doneDate;
            $casemanagements->last_updated=$request->last_updated;
            $casemanagements->paymentDate=$request->paymentDate;
            $casemanagements->progress=$request->progress;
            $casemanagements->assign_to=$request->assign_to;
            $casemanagements->customer_can_view=$request->customer_can_view;
            $casemanagements->customer_can_comment=$request->customer_can_comment;
            $casemanagements->priority=$request->priority;
            $casemanagements->status=$request->status;
            $success=$casemanagements->update();

            $response=[
                "status"=>true,
                'message' => "Casemanagement update successfully",
                "data"=> [
                    'casemanagements'=> $casemanagements,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No casemanagement find by this ID',
                "data"=> [
                    'casemanagements'=> '',
                ]

            ];
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Casemanagement  $casemanagement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $casemanagements =Casemanagement::where('id',$id)->where('membership_code',$u->membership_code)->first();
        }else{
            $casemanagements =Casemanagement::where('id',$id)->where('membership_code',$u->member_by)->first();
        }

        if(isset($casemanagements)){
            $casemanagements->delete();
            $response = [
                'status' => true,
                'message'=> 'Casemanagement delete successfully',
                "data"=> [
                    'casemanagements'=> [],
                ]
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'status' => false,
                'message'=>'No casemanagement find by this ID',
                "data"=> [
                    'casemanagements'=> '',
                ]
            ];
            return response()->json($response,200);
        }
    }


    public function uniqueID()
    {
        $lastOrder = Casemanagement::latest('id')->first();
        if ($lastOrder) {
            $orderID = $lastOrder->id + 1;
        } else {
            $orderID = 1;
        }

        return 'CS99' . $orderID;
    }



}
