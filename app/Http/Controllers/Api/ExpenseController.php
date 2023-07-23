<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
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
        $expenses =Expense::where('membership_id',$user_id->tokenable_id)->get();

        $response = [
            'status' => true,
            'message'=>'List of Expense',
            "data"=> [
                'expenses'=> $expenses,
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
        $expense=new Expense();
        $expense->membership_id=$user_id->tokenable_id;
        $expense->expensetype_id=$user_id->expensetype_id;
        $expense->amount=$request->amount;
        $expense->notes=$request->notes;

        if($request->image){
            $logo = $request->file('image');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/expense/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $expense->image = $logoImgUrl;
        }

        $expense->save();
        $response=[
            "status"=>true,
            'message' => "Expenses create successful",
            "data"=> [
                'expense'=> $expense,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense =Expense::where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Expense By ID',
            "data"=> [
                'expense'=> $expense,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $expense =Expense::where('id',$id)->first();
        $expense->membership_id=$user_id->tokenable_id;
        $expense->expensetype_id=$user_id->expensetype_id;
        $expense->amount=$request->amount;
        $expense->notes=$request->notes;

        if($request->image){
            unlink($expense->image);
            $logo = $request->file('image');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/expense/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $expense->image = $logoImgUrl;
        }
        $expense->update();
        $response=[
            "status"=>true,
            'message' => "Expense update successfully",
            "data"=> [
                'expense'=> $expense,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense =Expense::where('id',$id)->first();
        $expense->delete();

        $response = [
            'status' => true,
            'message'=> 'Expense delete successfully',
            "data"=> [
                'tasks'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}
