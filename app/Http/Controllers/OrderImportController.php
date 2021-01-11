<?php

namespace App\Http\Controllers;
use App\Models\OrderImport;
use App\Models\WorkOrder;
use App\Services\OrderManager\OrderManagerClientContract; 
use Illuminate\Http\Request;

class OrderImportController extends Controller
{
    protected $orderManagerClient;

    public function __construct(OrderManagerClientContract $orderManagerClient)
    {
        $this->orderManagerClient = $orderManagerClient;

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
        //
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
        //
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
    public function update(Request $request, $id)
    {
        //
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
    }

    public function orderImportIssuesCount()
    {
        $orderImportIssuesCount = OrderImport::where('imported', '=', true)->where('import_failed', '=', true)->count();
        return response()->json([
                    'data' => $orderImportIssuesCount
                ], 200);
    }

    public function orderImportIssues()
    {
        $orderImportIssues = OrderImport::where('imported', '=', true)->where('import_failed', '=', true)->get();
        return response()->json([
                    'data' => $orderImportIssues->toArray()
                ], 200);
    }

    public function getOrderManagerOrder($id)
    {
        $orderImport = WorkOrder::where('order_manager_id', '=', $id)->first();

        list($orderExists, $fullorder) = $this->orderManagerClient->getFullOrder($id);
        // $fullorder = json_decode($fullorder);
        // print_r($fullorder);
        return response()->json([
                    'data' => $fullorder->data,
                    'workorder' => $orderImport
                ], 200);

    }
}
