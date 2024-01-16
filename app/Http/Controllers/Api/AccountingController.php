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
use App\Models\Estimatequote;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Warehouse;
use App\Models\Asset;
use App\Models\Calender;

class AccountingController extends Controller
{
    public function history(Request $request){
        $token = request()->bearerToken();
        $user_id=PersonalAccessToken::findToken($token);
        $user=User::where('id',$user_id->tokenable_id)->first();

        if(isset($user->membership_code)){
            $response = [
                'status' => true,
                'message'=>'Dashboard all data',
                "data"=> [
                    'total_users'=> User::where('member_by',$user->membership_code)->get()->count(),
                    'my_mettings'=> Meting::where('form_id',$user_id->tokenable_id)->get()->count(),
                    'my_tasks'=> Task::where('form_id',$user_id->tokenable_id)->get()->count(),
                    'my_calender_schedule'=> Calender::where('form_id',$user_id->tokenable_id)->get()->count(),
                    'my_orders'=>Order::where('user_id',$user_id->tokenable_id)->get()->count(),
                    'my_invoices'=> Order::where('user_id',$user_id->tokenable_id)->get()->count(),
                    'total_quotes'=> Estimatequote::where('membership_code',$user->membership_code)->get()->count(),
                    'total_products'=> Product::where('membership_code',$user->membership_code)->get()->count(),
                    'total_stocks'=> Stock::where('membership_code',$user->membership_code)->get()->count(),
                    'total_assets'=> Asset::where('membership_code',$user->membership_code)->get()->count(),
                    'total_warehouses'=> Warehouse::where('membership_code',$user->membership_code)->get()->count(),
                    'total_customers'=> Customer::where('membership_code',$user->membership_code)->get()->count(),
                    'total_projects'=> Project::where('membership_code',$user->membership_code)->get()->count(),
                    'total_files'=> File::where('membership_code',$user->membership_code)->get()->count(),
                    'total_sales'=> Sale::where('membership_code',$user->membership_code)->get()->count(),
                    'total_sales_amount'=> Sale::where('membership_code',$user->membership_code)->get()->sum('payable_amount'),
                    'my_tickets'=> Task::where('form_id',$user_id->tokenable_id)->get()->count(),
                ]

            ];
        }else{
            $response = [
                'status' => true,
                'message'=>'Dashboard all data',
                "data"=> [
                    'total_users'=> User::where('member_by',$user->member_by)->get()->count(),
                    'my_mettings'=> Meting::where('form_id',$user_id->tokenable_id)->get()->count(),
                    'my_tasks'=> Task::where('form_id',$user_id->tokenable_id)->get()->count(),
                    'my_calender_schedule'=> Calender::where('form_id',$user_id->tokenable_id)->get()->count(),
                    'my_orders'=>Order::where('user_id',$user_id->tokenable_id)->get()->count(),
                    'my_invoices'=> Order::where('user_id',$user_id->tokenable_id)->get()->count(),
                    'total_quotes'=> Estimatequote::where('membership_code',$user->member_by)->get()->count(),
                    'total_products'=> Product::where('membership_code',$user->member_by)->get()->count(),
                    'total_stocks'=> Stock::where('membership_code',$user->member_by)->get()->count(),
                    'total_assets'=> Asset::where('membership_code',$user->member_by)->get()->count(),
                    'total_warehouses'=> Warehouse::where('membership_code',$user->member_by)->get()->count(),
                    'total_customers'=> Customer::where('membership_code',$user->member_by)->get()->count(),
                    'total_projects'=> Project::where('membership_code',$user->member_by)->get()->count(),
                    'total_files'=> File::where('membership_code',$user->member_by)->get()->count(),
                    'total_sales'=> Sale::where('membership_code',$user->member_by)->get()->count(),
                    'total_sales_amount'=> Sale::where('membership_code',$user->member_by)->get()->sum('payable_amount'),
                    'my_tickets'=> Task::where('form_id',$user_id->tokenable_id)->get()->count(),
                ]
                ];
        }
        return response()->json($response,200);
    }

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

    public function getquotes(Request $request){
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $u=User::where('id',$user_id->tokenable_id)->first();
            if(isset($u->membership_code)){
                $estimatequotes =Estimatequote::with(['users','payments','items','termsconditions'])->where('membership_code',$u->membership_code)->get();
            }else{
                $estimatequotes =Estimatequote::with(['users','payments','items','termsconditions'])->where('membership_code',$u->member_by)->get();
            }

            $startDate=$request->startDate;
            $endDate=$request->endDate;

            if ($startDate != '' && $endDate != '') {
                $estimatequotes = $estimatequotes->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            $response = [
                'status' => true,
                'message'=>'List of Estimatequotes',
                "data"=> [
                    'estimatequotes'=> $estimatequotes,
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

    public function getproducts(Request $request){
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $u=User::where('id',$user_id->tokenable_id)->first();
            if(isset($u->membership_code)){
                $pros =Product::where('membership_code',$u->membership_code)->get();
            }else{
                $pros =Product::where('membership_code',$u->member_by)->get();
            }

            $startDate=$request->startDate;
            $endDate=$request->endDate;

            if ($startDate != '' && $endDate != '') {
                $pros = $pros->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            if(count($pros)>0){
                foreach($pros as $us){
                    $use=$us;
                    if(isset($use->ProductImage)){
                        $use->ProductImage=env('PROD_URL').$use->ProductImage;
                    }else{

                    }
                    $products[]=$use;
                }

                $response = [
                    'status' => true,
                    'message'=>'List of My Products',
                    "data"=> [
                        'products'=> $products,
                    ]

                ];
            }else{
                $response = [
                    'status' => true,
                    'message'=>'List of My Products By Date',
                    "data"=> [
                        'products'=> [],
                    ]

                ];
            }


            return response()->json($response,200);

        } catch (\Exception $e) {

            $response = [
                'status' => false,
                'message'=>$e->getMessage(),
            ];
            return response()->json($response,200);
        }


    }

    public function getstocks(Request $request){
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $u=User::where('id',$user_id->tokenable_id)->first();
            if(isset($u->membership_code)){
                $stocks =Stock::with(['stockitems','users'])->where('membership_code',$u->membership_code)->get();
            }else{
                $stocks =Stock::with(['stockitems','users'])->where('membership_code',$u->member_by)->get();
            }

            $startDate=$request->startDate;
            $endDate=$request->endDate;

            if ($startDate != '' && $endDate != '') {
                $stocks = $stocks->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            $response = [
                'status' => true,
                'message'=>'List of My Stocks By Date',
                "data"=> [
                    'stocks'=> $stocks,
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

    public function getcustomers(Request $request){
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $u=User::where('id',$user_id->tokenable_id)->first();
            if(isset($u->membership_code)){
                $customers =Customer::where('membership_code',$u->membership_code)->get();
            }else{
                $customers =Customer::where('membership_code',$u->member_by)->get();
            }

            $startDate=$request->startDate;
            $endDate=$request->endDate;
            $status=$request->status;

            if ($startDate != '' && $endDate != '') {
                $customers = $customers->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
            if ($status != '') {
                $customers = $customers->where('status', $status);
            }


            $response = [
                'status' => true,
                'message'=>'List of Customer',
                "data"=> [
                    'customers'=> $customers,
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

    public function getprojects(Request $request){
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $u=User::where('id',$user_id->tokenable_id)->first();
            if(isset($u->membership_code)){
                $projects =Project::with(['users','assigns','customers','projectexpenses'])->where('membership_code',$u->membership_code)->get();
            }else{
                $projects =Project::with(['users','assigns','customers','projectexpenses'])->where('membership_code',$u->member_by)->get();
            }

            $startDate=$request->startDate;
            $endDate=$request->endDate;
            $status=$request->status;

            if ($startDate != '' && $endDate != '') {
                $projects = $projects->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
            if ($status != '') {
                $projects = $projects->where('status', $status);
            }


            $response = [
                'status' => true,
                'message'=>'List of Projects',
                "data"=> [
                    'projects'=> $projects,
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

    public function getsales(Request $request){
        try {
            $token = request()->bearerToken();
            $user_id=PersonalAccessToken::findToken($token);
            $u=User::where('id',$user_id->tokenable_id)->first();
            if(isset($u->membership_code)){
                $sales =Sale::with(['saleitems'])->where('membership_code',$u->membership_code)->get();
            }else{
                $sales =Sale::with(['saleitems'])->where('membership_code',$u->member_by)->get();
            }

            $startDate=$request->startDate;
            $endDate=$request->endDate;
            $status=$request->status;

            if ($startDate != '' && $endDate != '') {
                $sales = $sales->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            if(isset($sales)){
                $response = [
                    'status' => true,
                    'message'=>'Sales By Membership ID',
                    "data"=> [
                        'sales'=> $sales,
                    ]

                ];
            }else{
                $response = [
                    'status' => false,
                    'message'=>'No sales find by this Membership ID',
                    "data"=> [
                        'sales'=> '',
                    ]

                ];
            }
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
