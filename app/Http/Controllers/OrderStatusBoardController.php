<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrder;
use Illuminate\Http\Response;

class OrderStatusBoardController extends Controller
{


    public function index(Request $request) {

        
        $workorders = WorkOrder::
                  with([    // Get only the fields we needs for the mouseover info
                   'workOrderItems' => function($q)
                   {
                    $q->select('id', 'workorder_id', 'model_number', 'item_type', 'source_vendor', 'condition', 'unit_price', 'number_units', 'shipping_service', 'item_shipping_cost', 'ship_method_requested', 'expected_delivery_date', 'expected_ship_date');
                   },
                   'soldTo' => function($q)
                   {
                    $q->select('id', 'first_name', 'last_name', 'address', 'address2', 'city', 'state', 'zip');
                   }
                  ])->select('id', 'sold_contact_id', 'customer_contacted', 'expected_delivery_date', 'is_amazon_prime', 'order_placed_date', 'platform', 'platform_order_id', 'product_total', 'shipping_confirmed', 'status', 'total', 'viewed')->where('platform', '!=', 'AmazonFBA')->limit(300)->get();        

        $results = $workorders->toArray();

		$inv_group = array();
		// organize by status
		foreach($results AS $workorder)
		{
		    $inv_group[$workorder["status"]][] = $workorder; 	    
		}
    ksort($inv_group);
    	//return json_encode($inv_group);
            return response()->json([
                'data' => $inv_group
            ], 200);

    }


    public function toggleViewed(Request $request) {

    	$id = $request['id'];

        try {
            $workOrder = WorkOrder::find($id);          
            $workOrder->viewed = !$workOrder->viewed;
            $workOrder->save();

        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store WorkOrder", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

        $workorders = WorkOrder::
              with([    // Get only the fields we needs for the mouseover info
               'workOrderItems' => function($q)
               {
                $q->select('id', 'workorder_id', 'model_number', 'item_type', 'source_vendor', 'condition', 'unit_price', 'number_units', 'shipping_service', 'item_shipping_cost', 'ship_method_requested', 'expected_delivery_date', 'expected_ship_date');
               },
               'soldTo' => function($q)
               {
                $q->select('id', 'first_name', 'last_name', 'address', 'address2', 'city', 'state', 'zip');
               }
              ])->select('id', 'sold_contact_id', 'customer_contacted', 'expected_delivery_date', 'is_amazon_prime', 'order_placed_date', 'platform', 'platform_order_id', 'product_total', 'shipping_confirmed', 'status', 'total', 'viewed')->where('platform', '!=', 'AmazonFBA')->limit(300)->get();        


        $results = $workorders->toArray();

        $inv_group = array();
        // organize by status
        foreach($results AS $workorder)
        {
            $inv_group[$workorder["status"]][] = $workorder;        
        }
        ksort($inv_group);
        //return json_encode($inv_group);
        return response()->json([
            'data' => $inv_group
        ], 200);

    }


}
