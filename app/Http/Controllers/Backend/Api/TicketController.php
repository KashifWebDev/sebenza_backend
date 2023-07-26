<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Ticket;
use App\Models\Replay;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = request()->bearerToken();
        if(isset($token)){
            $user_id=PersonalAccessToken::findToken($token);
            if($user_id->name=='admin'){
          	    $tickets=Ticket::with('users')->get();
            }else{
          	    $tickets=Ticket::with('users')->where('from_id',$user_id->tokenable_id)->get();
            }
        	$response = [
                'status' => true,
                'message'=>'Supporttickets by user id',
                "data"=> [
                    'supporttickets'=> $tickets,
                ]
            ];
            return response()->json($response,200);
        }else{
        	$response = [
                'status' => false,
                'message'=>'Token is not valid',
                "data"=> [
                    'supporttickets'=>[],
                ]
            ];
            return response()->json($response,200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $token = request()->bearerToken();
        if(isset($token)){
            $user_id=PersonalAccessToken::findToken($token);
            $tts=Ticket::with('users')->where('from_id',$user_id->tokenable_id)->get();
            foreach($tts as $tt){
                $t=Ticket::where('id',$tt->id)->first();
                $t->status='Closed';
                $t->update();
            }
            $ticket=new Ticket();
            $ticket->from_id=$user_id->tokenable_id;
            $ticket->name=User::where('id',$user_id->tokenable_id)->first()->first_name .''. User::where('id',$user_id->tokenable_id)->first()->last_name;
            $ticket->email=User::where('id',$user_id->tokenable_id)->first()->email;
            $ticket->subject=$request->subject;
            $ticket->department=$request->department;
            $ticket->priority=$request->priority;
            $ticket->message=$request->message;

            $time = microtime('.') * 10000;
            $productImg = $request->file('attachment');
            if($productImg){
                $imgname = $time . $productImg->getClientOriginalName();
                $imguploadPath = ('public/images/ticket/');
                $productImg->move($imguploadPath, $imgname);
                $productImgUrl = $imguploadPath . $imgname;
                $ticket->attachment = $productImgUrl;
            }
            $ticket->save();
            $response = [
                'status' => true,
                'message'=>'Supporttickets created successfully',
                "data"=> [
                    'supporttickets'=> $ticket,
                ]
            ];
            return response()->json($response,200);
        }else{
        	$response = [
                'status' => false,
                'message'=>'Token is not valid',
                "data"=> [
                    'supporttickets'=>[],
                ]
            ];
            return response()->json($response,200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket=Ticket::with('users')->where('id',$id)->first();
        $res=Replay::where('ticket_id',$id)->get();
        if(count($res)>0){
            foreach($res as $re){
                $r=Replay::where('id',$re->id)->first();
                if($r->type=='User'){
                    $r['users']=User::where('id',$r->from_user_id)->first();
                }else{
                    $r['users']=Admin::where('id',$r->from_user_id)->first();
                }
                $replays[]=$r;
            }
        }else{
            $replays=[];
        }
        $adm=Replay::where('ticket_id',$id)->where('type','Admin')->first();
        if(isset($adm)){
            $user=Admin::where('id',$adm->from_user_id)->first();
            $user['from']='Admin';
        }else{
            $user=[];
        }
        $response = [
            'status' => true,
            'message'=>'View support tikit by id',
            "data"=> [
                'supporttickets'=> $ticket,
                'replays'=> $replays,
                'replied_from'=> $user,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */

    public function admindex()
    {
        $tickets=Ticket::with('users')->get();
        $response = [
            'status' => true,
            'message'=>'All Supporttickets List',
            "data"=> [
                'supporttickets'=> $tickets,
            ]
        ];
        return response()->json($response,200);
    }

    public function edit($id)
    {
        $ticket=Ticket::with('users')->where('id',$id)->first();
        $res=Replay::where('ticket_id',$id)->get();
        if(count($res)>0){
            foreach($res as $re){
                $r=Replay::where('id',$re->id)->first();
                if($r->type=='User'){
                    $r['users']=User::where('id',$r->from_user_id)->first();
                }else{
                    $r['users']=Admin::where('id',$r->from_user_id)->first();
                }
                $replays[]=$r;
            }
        }else{
            $replays=[];
        }
        $response = [
            'status' => true,
            'message'=>'View support tikit by id',
            "data"=> [
                'supporttickets'=> $ticket,
                'replays'=> $replays,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ticket=Ticket::where('id',$id)->first();
        $ticket->status=$request->status;
        $ticket->save();
        $response = [
            'status' => true,
            'message'=>'Supporttickets Status Update successully',
            "data"=> [
                'supporttickets'=> $ticket,
            ]
        ];
        return response()->json($response,200);
    }

    public function replay(Request $request, $id)
    {
        $ticket=Ticket::where('id',$id)->first();
        if($request->type=='User'){
            $ticket->status='Answered';
            $ticket->update();
        }else{
            $ticket->status='Answered';
            $ticket->update();
        }
        $token = request()->bearerToken();

        $replay=new Replay();
        $replay->ticket_id=$id;
        $replay->replay=$request->replay;
        if($request->type=='User'){
            $replay->type ='User';

            if(isset($token)){
                $user_id=PersonalAccessToken::findToken($token);
            }

            $replay->from_user_id=$user_id->tokenable_id;
            $replay->status='Customer-Replay';
        }else{
            if(isset($token)){
                $user_id=PersonalAccessToken::findToken($token);
            }
            $replay->from_user_id=$user_id->tokenable_id;
            $replay->type='Admin';
            $replay->status='Answered';
        }
        $time = microtime('.') * 10000;
        $productImg = $request->file('replayatt');
        if($productImg){
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/ticket/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $replay->replayatt = $productImgUrl;
        }

        $replay->save();
        $response = [
            'status' => true,
            'message'=>'Ticket replaed Successfully',
            "data"=> [
                'supporttickets'=> $ticket,
                'replay'=>$replay,
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
