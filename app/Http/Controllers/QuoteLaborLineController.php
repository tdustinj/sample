<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\QuoteLaborLine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuoteLaborLineController extends Controller
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
        return "index";

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return "Create";
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
        $data['fk_technician_id'] = Auth::id();
        try {
            $quoteLaborLine = QuoteLaborLine::create($data);
            $quoteLaborLine->user;
            $quoteLaborLine->product;
            return response()->json([
                'data' => $quoteLaborLine->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store quote", 'exceptionMessage' => $e->getMessage())
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
        //
        return "Show";
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
        return "Edit";
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
        try {
            $quoteLaborLine = QuoteLaborLine::find($request->id);
            $quoteLaborLine->fk_quote_id = $request->fk_quote_id;
            $quoteLaborLine->sku = $request->sku;
            $quoteLaborLine->fk_products_id = $request->fk_products_id;
            $quoteLaborLine->description = $request->description;
            $quoteLaborLine->fk_technician_id = $request->fk_technician_id;
            $quoteLaborLine->rate = $request->rate;
            $quoteLaborLine->hours = $request->hours;
            $quoteLaborLine->save();
            return response()->json([
                'data' => $quoteLaborLine->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store WorkOrder", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
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
        QuoteLaborLine::destroy($id);
        return response()->json([
            'data' => array('Deleted'=> "$id")
        ], 200);

    }
}