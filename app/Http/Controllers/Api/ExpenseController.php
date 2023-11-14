<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Expense;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\ExpenseExport;
use App\Models\Expenseexcel;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function fileExport(Request $request)
    {
        $startDate =$request->startDate;
        $endDate =$request->endDate;
        $time = microtime('.') * 10000;
        $fileName=$time.'expense.xlsx';
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $user=User::where('id',$user_id->tokenable_id)->first();

        if(isset($startDate) && isset($endDate)){

            $file= Excel::store(new ExpenseExport($startDate,$endDate,$user), $fileName);

            $saleexcel=new Expenseexcel();
            $u=User::where('id',$user_id->tokenable_id)->first();
            $saleexcel->user_id=$u->id;
            if(isset($u->membership_code)){
                $saleexcel->membership_code=$u->membership_code;
            }else{
                $saleexcel->membership_code=$u->member_by;
            }
            if ($file) {
                $saleexcel->data_file = 'storage/app/'.$fileName;
            }
            $saleexcel->startDate=$startDate;
            $saleexcel->endDate=$endDate;
            $saleexcel->date=date('Y-m-d');
            $saleexcel->save();
            $response = [
                'status' => true,
                'message'=>'Expense Data Report File',
                "data"=> [
                    'saleexcel'=> $saleexcel,
                ]

            ];

        }else{
            $response = [
                'status' => false,
                'message'=>'Please Select Any Date',
                "data"=> [
                    'saleexcel'=> '',
                ]

            ];
        }
        return response()->json($response,200);
    }
    public function expenselist()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $u=User::where('id',$user_id->tokenable_id)->first();
        if(isset($u->membership_code)){
            $expenseexcel =Expenseexcel::where('membership_code',$u->membership_code)->get();
        }else{
            $expenseexcel =Expenseexcel::where('membership_code',$u->member_by)->get();
        }

        if(isset($expenseexcel)){
            $response = [
                'status' => true,
                'message'=>'Expense report data By Membership ID',
                "data"=> [
                    'expenseexcel'=> $expenseexcel,
                ]

            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'No expense report data found',
                "data"=> [
                    'expenseexcel'=> '',
                ]

            ];
        }
        return response()->json($response,200);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $expenses =Expense::with('expensetypes')->where('membership_id',$user_id->tokenable_id)->get();

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
        $expenses=new Expense();
        $expenses->membership_id=$user_id->tokenable_id;
        $expenses->expensetype_id=$request->expensetype_id;
        $expenses->amount=$request->amount;
        $expenses->notes=$request->notes;

        if($request->image){
            $logo = $request->file('image');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/expenses/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $expenses->image = $logoImgUrl;
        }

        $expenses->save();
        $response=[
            "status"=>true,
            'message' => "Expenses create successful",
            "data"=> [
                'expenses'=> $expenses,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expenses
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expenses
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expenses =Expense::with('expensetypes')->where('id',$id)->first();

        $response = [
            'status' => true,
            'message'=>'Expense By ID',
            "data"=> [
                'expenses'=> $expenses,
            ]

        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);

        $expenses =Expense::where('id',$id)->first();
        $expenses->membership_id=$user_id->tokenable_id;
        $expenses->expensetype_id=$request->expensetype_id;
        $expenses->amount=$request->amount;
        $expenses->notes=$request->notes;

        if($request->image){
            $logo = $request->file('image');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/expenses/');
            $logo->move($uploadPath, $name);
            $logoImgUrl = $uploadPath . $name;
            $expenses->image = $logoImgUrl;
        }
        $expenses->update();
        $response=[
            "status"=>true,
            'message' => "Expense update successfully",
            "data"=> [
                'expenses'=> $expenses,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expenses
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expenses =Expense::where('id',$id)->first();
        $expenses->delete();

        $response = [
            'status' => true,
            'message'=> 'Expense delete successfully',
            "data"=> [
                'expenses'=> [],
            ]
        ];
        return response()->json($response,200);
    }
}
