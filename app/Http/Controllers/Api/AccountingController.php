<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use App\Models\Meting;

class AccountingController extends Controller
{
    public function getmettings(){
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
}
