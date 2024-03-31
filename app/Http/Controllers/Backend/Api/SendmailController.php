<?php

namespace App\Http\Controllers\Backend\Api;
use App\Http\Controllers\Controller;

use App\Models\Sendmail;
use Illuminate\Http\Request;

class SendmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendemail(Request $request)
    {
        $message=$request->message;
        $subject=$request->subject;
        if($request->files){
            $logo = $request->file('files');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/files/');
            $logo->move($uploadPath, $name);
            $filepath = $uploadPath . $name;
        }

        $sendmails= new Sendmail();
        $sendmail->to=$request->email;
        $sendmail->subject=$subject;
        $sendmail->message=$message;
        $sendmail->file=$filepath;
        $sendmail->date=date('Y-m-d');
        $sendmail->save();
        $details = [
            'title' => env('APP_NAME') . 'Sebenza General Information !',
            "details"=>$details,
        ];

        \Mail::to($request->email)->send(new \App\Mail\SendMail($details));

        if($sendmail){
            $response = [
                'status' => true,
                'message'=>'Mail send successfully',
            ];
        }else{
            $response = [
                'status' => false,
                'message'=>'Something went wrong',
            ];
        }
        return response()->json($response,200);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ids=$request->user_ids;
        $message=$request->message;
        $subject=$request->subject;
        if($request->files){
            $logo = $request->file('files');
            $name = time() . "_" . $logo->getClientOriginalName();
            $uploadPath = ('public/images/files/');
            $logo->move($uploadPath, $name);
            $filepath = $uploadPath . $name;
        }

        foreach($ids as $id){
            $user=User::where('id',$id)->first();
            if(isset($user)){
                $sendmails= new Sendmail();
                $sendmail->to=$user->email;
                $sendmail->subject=$subject;
                $sendmail->message=$message;
                $sendmail->file=$filepath;
                $sendmail->date=date('Y-m-d');
                $sendmail->save();
            }
        }

        $response = [
            'status' => true,
            'message'=>'Mail sending job set successfully',
        ];

        return response()->json($response,200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sendmail  $sendmail
     * @return \Illuminate\Http\Response
     */
    public function show(Sendmail $sendmail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sendmail  $sendmail
     * @return \Illuminate\Http\Response
     */
    public function edit(Sendmail $sendmail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sendmail  $sendmail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sendmail $sendmail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sendmail  $sendmail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sendmail $sendmail)
    {
        //
    }
}
