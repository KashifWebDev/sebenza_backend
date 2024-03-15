<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use App\Models\Currencyrate;
use Illuminate\Http\Request;

class CurrencyrateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencyrates =Currencyrate::all();
        $response = [
            'status' => true,
            'message'=>'List of currencyrates',
            "data"=> [
                'currencyrates'=> $currencyrates,
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
        $currencyrate=new Currencyrate();
        $currencyrate->from=$request->from;
        $currencyrate->to=$request->to;
        $currencyrate->rate=$request->rate;
        $currencyrate->save();

        $response=[
            "status"=>true,
            'message' => "Currencyrate created successfully",
            "data"=> [
                'currencyrates'=> $currencyrate,
            ]
        ];
        return response()->json($response, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currencyrate  $currencyrate
     * @return \Illuminate\Http\Response
     */
    public function show(Currencyrate $currencyrate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currencyrate  $currencyrate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currencyrates=Currencyrate::where('id',$id)->first();

        $response=[
            "status"=>true,
            'message' => "Currencyrate By ID",
            "data"=> [
                'currencyrates'=> $currencyrates,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currencyrate  $currencyrate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currencyrate= Currencyrate::where('id',$id)->first();
        $currencyrate->from=$request->from;
        $currencyrate->to=$request->to;
        $currencyrate->rate=$request->rate;
        $currencyrate->save();

        $response=[
            "status"=>true,
            'message' => "Currencyrate updated successfully",
            "data"=> [
                'currencyrates'=> $currencyrate,
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currencyrate  $currencyrate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currencyrate= Currencyrate::where('id',$id)->first();
        $currencyrate->delete();
        $response=[
            "status"=>true,
            'message' => "Currencyrate Deleted Successfully",
            "data"=> [
                'currencyrates'=> [],
            ]
        ];
        return response()->json($response, 200);
    }
}
