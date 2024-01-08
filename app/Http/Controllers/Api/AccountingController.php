<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use App\Models\Meting;
use App\Models\Task;
use App\Models\User;
use App\Models\Withdrew;
use App\Models\Expense;

class AccountingController extends Controller
{
    public function getmettings(Request $request){
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $metings =Meting::with('notes')->where('form_id',$user_id->tokenable_id)->get();
        $startDate=$request->startDate;
        $endDate=$request->endDate;

        if ($startDate != '' && $endDate != '') {
            $metings = $metings->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        $response = [
            'status' => true,
            'message'=>'Date Wise Metting List',
            "data"=> [
                'metings'=> $metings,
            ]

        ];
        return response()->json($response,200);
    }

    public function gettasks(Request $request){
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $startDate=$request->startDate;
        $endDate=$request->endDate;

        if ($startDate != '' && $endDate != '') {
            $tasks=Task::with('tasknotes')->where('form_id',$user_id->tokenable_id)->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->get();
        }else{
            $tasks=Task::with('tasknotes')->where('form_id',$user_id->tokenable_id)->get();
        }
        $response = [
            'status' => true,
            'message'=>'Date Wise Task List',
            "data"=> [
                'tasks'=> $tasks,
            ]

        ];
        return response()->json($response,200);
    }

    public function getwithdraws(Request $request){
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $user=User::where('id',$user_id->tokenable_id)->first();
            $startDate=$request->startDate;
            $endDate=$request->endDate;
            $status=$request->status;

            if(isset($user->membership_code)){
                $wits=Withdrew::where('user_id',$user->id)->where('membership_id',$user->membership_code)->get();
            }else{
                $wits=Withdrew::where('user_id',$user->id)->where('membership_id',$user->member_by)->get();
            }

            if ($startDate != '' && $endDate != '') {
                $wits = $wits->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
            if ($status != '') {
                $wits = $wits->where('status', $status);
            }


            if(count($wits)>0){
                foreach($wits as $wit){
                    $w=Withdrew::where('id',$wit->id)->first();
                    $u=User::where('id',$w->user_id)->first();
                    $w->full_name=$u->first_name . ' ' .$u->last_name;
                    $withdrews[]=$w;
                }
            }else{
                $withdrews=[];
            }


            $response = [
                'status' => true,
                'message'=>'My Withdrew info By Date',
                "data"=> [
                    'withdrews'=> $withdrews,
                ]

            ];
            return response()->json($response,200);

        } catch (\Exception $e) {

            $response = [
                'status' => false,
                'message'=>$e->getMessage(),
            ];
            return response()->json($response,200);
        }
    }

    public function getexpenses(Request $request){
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $u=User::where('id',$user_id->tokenable_id)->first();
            if(isset($u->membership_code)){
                $expenses =Expense::with('expensetypes')->where('membership_id',$u->membership_code)->get();
            }else{
                $expenses =Expense::with('expensetypes')->where('membership_id',$u->member_by)->get();
            }
            $startDate=$request->startDate;
            $endDate=$request->endDate;
            $expensetype_id=$request->expensetype_id;

            if ($startDate != '' && $endDate != '') {
                $expenses = $expenses->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
            if ($expensetype_id != '') {
                $expenses = $expenses->where('expensetype_id', $expensetype_id);
            }

            $response = [
                'status' => true,
                'message'=>'Date wise Expense List',
                "data"=> [
                    'expenses'=> $expenses,
                ]

            ];
            return response()->json($response,200);

        } catch (\Exception $e) {

            $response = [
                'status' => false,
                'message'=>$e->getMessage(),
            ];
            return response()->json($response,200);
        }


    }


}
