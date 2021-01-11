<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\WorkOrderLaborLine;
use Illuminate\Support\Facades\Auth;
class WorkOrderLaborLineController extends Controller
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
            $workorderLaborLine = WorkOrderLaborLine::create($data);
            $workorderLaborLine->user;
            $workorderLaborLine->product;
            return response()->json([
                'data' => $workorderLaborLine->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store Workorder Labor Line", 'exceptionMessage' => $e->getMessage())
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
            $workorderLaborLine = WorkOrderLaborLine::find($request->id);
            $workorderLaborLine->fk_workorder_id = $request->fk_workorder_id;
            $workorderLaborLine->sku = $request->sku;
            $workorderLaborLine->fk_products_id = $request->fk_products_id;
            $workorderLaborLine->description = $request->description;
            $workorderLaborLine->fk_technician_id = $request->fk_technician_id;
            $workorderLaborLine->rate = $request->rate;
            $workorderLaborLine->hours = $request->hours;
            $workorderLaborLine->save();
            return response()->json([
                'data' => $workorderLaborLine->toArray()
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
        WorkOrderLaborLine::destroy($id);
        return response()->json([
            'data' => array('Deleted'=> "$id")
        ], 200);

    }

}
