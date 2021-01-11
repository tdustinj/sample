<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\WorkOrderItem;

class WorkOrderItemController extends Controller
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

        $workOrderItem = factory(\App\Models\WorkOrderItem::class, 10)->make();
        return $workOrderItem;

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


        try {
            //$today = date("Y-m-d H:i:s");

            $workOrderItem = new WorkOrderItem;
            $workOrderItem->workorder_id = $request->workorder_id  ;
            $workOrderItem->product_id = $request->product_id ;
            $workOrderItem->employee_id = $request->employee_id ;
            $workOrderItem->model_number = $request->model_number  ;
            $workOrderItem->part_number = $request->part_number ;
            $workOrderItem->fk_brand_id = $request->brand;
            $workOrderItem->description = $request->description ;
            $workOrderItem->upc = $request->upc;
            $workOrderItem->fk_category_id = $request->category;
            $workOrderItem->item_class = $request->item_class ;
            $workOrderItem->item_type = $request->item_type ;
            $workOrderItem->serial_number_tracked = $request->serial_number_tracked ;
            //$workOrderItem->workorder_date = $today;
            $workOrderItem->source_vendor = $request->source_vendor ;
            $workOrderItem->condition = $request->condition ;
            $workOrderItem->tax_code = $request->tax_code ;
            $workOrderItem->tax_amount = $request->tax_amount ;
            $workOrderItem->ext_price = $request->ext_price ;
            $workOrderItem->unit_price = $request->unit_price ;
            $workOrderItem->number_units = $request->number_units ;
            $workOrderItem->standard_gross_profit = $request->standard_gross_profit ;
            $workOrderItem->final_gross_profit = $request->final_gross_profit ;




            $workOrderItem->save();
            return response()->json([
                'data' => $workOrderItem->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store WorkOrderItem", 'exceptionMessage' => $e->getMessage())
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
        $workorderItem = WorkOrderItem::where('id', '=', $id)->get();

        return response()->json([
                'data' => $workorderItem->toArray()
            ], 200);



        //return "Show";
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
    public function update(Request $request, $id)
    {


        try {

            $workOrderItem = WorkOrderItem::find($id);
            // $workOrderItem->workorder_id = $request->workorder_id  ;
            // $workOrderItem->product_id = $request->product_id ;
            // $workOrderItem->employee_id = $request->employee_id ;
            // $workOrderItem->model = $request->model  ;
            // $workOrderItem->part_number = $request->part_number ;
            // $workOrderItem->brand = $request->brand;
            // $workOrderItem->description = $request->description ;

            // $workOrderItem->upc = $request->upc;
            // $workOrderItem->category = $request->category;
            // $workOrderItem->item_class = $request->item_class ;
            // $workOrderItem->item_type = $request->item_type ;
            // $workOrderItem->serial_number = $request->serial_number ;
            // $workOrderItem->workorder_date = $request->workorder_date ;
            // $workOrderItem->source_vendor = $request->source_vendor ;
            $workOrderItem->condition = $request->condition ;
            // $workOrderItem->tax_code = $request->tax_code ;
            // $workOrderItem->tax_amount = $request->tax_amount ;
            // $workOrderItem->ext_price = $request->ext_price ;
            $workOrderItem->unit_price = $request->unit_price ;
            $workOrderItem->number_units = $request->number_units ;
            // $workOrderItem->standard_gross_profit = $request->standard_gross_profit ;
            // $workOrderItem->final_gross_profit = $request->final_gross_profit ;




            $workOrderItem->save();

            return response()->json([
                'data' => $workOrderItem->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Update WorkOrderItem", 'exceptionMessage' => $e->getMessage())
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
       
        WorkOrderItem::where('id', 'like', $id)->delete();

        return response()->json([
                'data' => array('Deleted'=> "$id")
            ], 200);

        //return "Destroy";

    }
    public function search(Request $request)

    {
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        try {
            $account = Account::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            return response()->json([
                'data' => $account->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    public static function getCommitted($productId){
        /*
         *  THis method fetches a total qty of products that have not had a serial assigned
         *
         */
        $committedQty = 0;
        try {
            $workOrderItems = WorkOrderItem::where('product_id', '=', $productId)
                ->where('number_serial_numbers_assigned' , '<', 'number_units')
                ->get();
            foreach($workOrderItems as $item){
                $committedQty += $item->number_units - $item->number_serial_numbers_assigned;
            }
            return  $committedQty;
        }

        catch(exception $e)
        {
            return 0;
        }


    }
}
