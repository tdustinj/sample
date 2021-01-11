<?php

namespace App\Http\Controllers;

use App\Models\Fulfillment;
use App\Models\FulfillmentType;
use Illuminate\Http\Request;

class FulfillmentTypeController extends Controller
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

        try {
            $fulfillmentTypes = FulfillmentType::all();
            return response()->json([
                'data' => $fulfillmentTypes->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Find any Fulfillment Types", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

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
        try {
            $fulfillmentType = new FulfillmentType();
            $fulfillmentType->fulfillment_name = $data['fulfillment_name'];
            $fulfillmentType->active = 1;
            $fulfillmentType->save();
            return response()->json([
                'data' => $fulfillmentType->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Create Fulfillment", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try {
            $item = FulfillmentType::findOrFail($id);
            return response()->json([
                'data' => $item->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Fulfillment type not found for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
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
        $data = $request->all();

        $fulfillmentType = FulfillmentType::where('id', $data['id'])->first();
        $fulfillmentType->fulfillment_name = $data['fulfillment_name'];
        $fulfillmentType->active = ($data['active'] == "true" || $data['active'] == 1 || $data['active'] == true ? 1 : 0 );
        $fulfillmentType->save();

        return response()->json([
            'data' => $fulfillmentType->toArray()
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
        $fulfillmentType = FulfillmentType::where('id', $id)->first();
        $fulfillmentType->delete();
        return response()->json([
            'data' => "Succesfully Deleted record"
        ], 200);
    }
    public function search(Request $request)

    {
       // not needed

    }
}
