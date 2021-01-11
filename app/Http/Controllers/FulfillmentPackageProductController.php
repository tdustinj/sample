<?php

namespace App\Http\Controllers;

use App\Models\FulfillmentPackageProduct;
use App\Models\WorkOrderItem;
use App\Models\Fulfillment;
use Illuminate\Http\Request;

class FulfillmentPackageProductController extends Controller
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
            $fulfillmentPackageProduct = new FulfillmentPackageProduct();

            $fulfillmentPackageProduct->fk_fulfillment_package_id = $request->fulfillmentPackageId;
            $fulfillmentPackageProduct->fk_workorder_item_id = $request->workorderItemId;
            $fulfillmentPackageProduct->fk_fulfillment_id = $request->fulfillmentId;
            $fulfillmentPackageProduct->fk_product_id = $request->productId ;
            $fulfillmentPackageProduct->qty = $request->qty ;
            $workOrderItem = WorkOrderItem::find($request->workorderItemId);
            if($workOrderItem) {
                $fulfillment = Fulfillment::find($request->fulfillmentId);
                if($fulfillment) {
                    //$fulfillment->vendor_tracking_number = $workOrderItem->tracking_url;
                    $fulfillment->expected_delivery_date = $workOrderItem->expected_delivery_date;
                    $fulfillment->fulfillment_partner_expected_delivery_date = $workOrderItem->expected_delivery_date;
                    $fulfillment->vendor_promise_delivery_date = $workOrderItem->expected_delivery_date;
                    $fulfillment->vendor_shipping_date = $workOrderItem->expected_ship_date;
                    $fulfillment->vendor_promise_shipping_date = $workOrderItem->expected_ship_date;
                    $fulfillment->expected_ship_date = $workOrderItem->expected_ship_date;
                    //$fulfillment->vendor_shipping_company = $workOrderItem->tracking_url; // ???
                    //$fulfillment->purchase_order_number = $workOrderItem->tracking_url; ???
                    $fulfillment->vendor_shipping_status = "Shipped"; // change this to come from dropdown
                    $fulfillment->save();
                }                
                $workOrderItem->number_units_fulfilled =  $workOrderItem->number_units_fulfilled + $request->qty;
                $workOrderItem->save();
            }

            $fulfillmentPackageProduct->save();


            return response()->json([
                'data' => $fulfillmentPackageProduct->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Create New Fulfillment Packages", 'exceptionMessage' => $e->getMessage())
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
            $item = FulfillmentPackageProduct::findOrFail($id);
            return response()->json([
                'data' => $item->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Fulfillment Packages info not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
    public function update(Request $request, $id)
    {


        try {

            $fulfillmentPackageProduct = FulfillmentPackageProduct::findOrFail($id);

            $fulfillmentPackageProduct->fk_fulfillment_package_id = $request->fulfillmentPackageId;
            $fulfillmentPackageProduct->fk_workorder_item_id = $request->workorderItemId;

            $fulfillmentPackageProduct->fk_product_id = $request->productId ;
            $fulfillmentPackageProduct->qty = $request->totalQty ;
            $workOrderItem = WorkOrderItem::where('id', '=',$request->workorderItemId)->get();
            if(sizeof($workOrderItem) ) {
               if($workOrderItem[0]->number_units_fulfilled + $request->qty <= $workOrderItem[0]->number_units) {
                   $workOrderItem[0]->number_units_fulfilled = $workOrderItem[0]->number_units_fulfilled + $request->qty;
                   $workOrderItem[0]->save();
               }
               else{
                   throw new \Exception('Can not fulfill more items then qty sold, Per Peter!') ;
               }
            }



            $fulfillmentPackageProduct->save();

            return response()->json([
                'data' => $fulfillmentPackageProduct->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Update Fulfillment", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $item = fulfillmentPackageProduct::findOrFail($id);

            $workOrderItem = WorkOrderItem::where('id', '=', $request->workorderItemId)->get();
            if(sizeof($workOrderItem) ) {
               if($workOrderItem[0]->number_units_fulfilled - $item->qty >= 0) {
                   $workOrderItem[0]->number_units_fulfilled = $workOrderItem[0]->number_units_fulfilled - $item->qty;
                   $workOrderItem[0]->save();
               }
               else{
                   throw new \Exception('Can not fulfill more items then qty sold, Per Peter!') ;
               }
            }


            $item->delete();
            return response()->json([
                'data' => array('success')
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Fulfillment Package Could Not Be Deleted: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }
    public function search(Request $request)

    {
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        try {
            $product = FulfillmentPackageProduct::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            return response()->json([
                'data' => $product->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Fulfillment Packages Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }
}
