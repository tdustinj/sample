<?php

namespace App\Http\Controllers;
use App\Models\OrderType;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        //  $this->middleware('log')->only('index');

        //$this->middleware('subscribed')->except('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        try{
            $orderTypes = OrderType::all();
            if (empty($orderTypes)) {
                throw new Exception('Error retrieving States');
            }

            return response()->json([
                'data' => $orderTypes
                ], 200);
        }
        catch(Exception $e) {
            return response()->json([
                    'data' => array('error'=> "Unable to load Order Types.", 'exceptionMessage' => $e->getMessage())
                ], 500);
        }
        
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
        $data = $request->all();
        $orderType = new OrderType();
        $orderType->order_type_name = $data['order_type_name'];
        $orderType->active =  ($data['active'] ? 1 : 0 );
        $orderType->save();

        return response()->json([
            'data' => $orderType->toArray()
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $data = $request->all();

        $orderType = OrderType::where('id', $data['id'])->first();
        $orderType->order_type_name = $data['order_type_name'];
        $orderType->active = ($data['active'] ? 1 : 0 );
        $orderType->save();

        return response()->json([
            'data' => $orderType
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $orderType = OrderType::where('id', $id)->first();
        $orderType->active = false;
        $orderType->save();
        return response()->json([
            'data' => $orderType
        ], 200);
    }
}
