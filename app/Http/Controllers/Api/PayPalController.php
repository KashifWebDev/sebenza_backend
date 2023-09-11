<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    public function payment(Request $request){
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('success.payment'),
                "cancel_url" => route('cancel.payment'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "invoiceID" => $request->invoiceID,
                        "value" => $request->price,
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    $invoice =Invoice::where('id',$request->invoiceID)->first();
                    $invoice->payment_id=$response['id'];
                    $invoice->payment_type='PayPal';
                    $invoice->update();
                    $response = [
                        'status' => true,
                        'message'=>'Paypal Payment Url',
                        "data"=> [
                            'url'=> $links['href'],
                        ]
                    ];
                    return response()->json($response,200);
                }
            }
            $response = [
                'status' => false,
                'message'=>'Payment cancel ! Something went wrong.',
                "data"=> [
                    'url'=>'',
                ]
            ];
            return response()->json($response,200);
        } else {
            $response = [
                'status' => false,
                'message'=>'Something went wrong.Please try again.',
                "data"=> [
                    'url'=>'',
                ]
            ];
            return response()->json($response,200);
        }

    }

    public function paymentCancel(Request $request)
    {
        $invoice =Invoice::where('payment_id',$request->token)->first();
        $invoice->payment_id='';
        $invoice->payment_type='';
        $invoice->update();
        $response = [
            'status' => false,
            'message'=>'Payment cancel ! Something went wrong.',
            "data"=> [
                'url'=>'',
            ]
        ];
        return response()->json($response,200);
    }

    public function paymentSuccess(Request $request)
    {

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $invoice =Invoice::where('payment_id',$response['id'])->first();
            $invoice->payment_id=$response['id'];
            $invoice->status=$response['status'];
            $invoice->payment_response=$response;
            $invoice->update();
            $response = [
                'status' => true,
                'message'=>'Transaction complete.',
            ];
            return response()->json($response,200);
        } else {
            $invoice =Invoice::where('payment_id',$response['id'])->first();
            $invoice->payment_id='';
            $invoice->payment_type='';
            $invoice->update();
            $response = [
                'status' => false,
                'message'=>'Payment cancel ! Something went wrong.',
            ];
            return response()->json($response,200);
        }
    }

}
