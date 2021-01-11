<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        //$this->middleware('log')->only('index');

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
            $platformStateTax = new PlatformStateTaxMap();
                $platformStateTax->platform_code = $request->platform_code;
                $platformStateTax->state_code = $request->state_code;
                $platformStateTax->collectAndRemitTax = $request->collectAndRemitTax;

            $platformStateTax->save();

            return response()->json([
                'data' => $platformStateTax
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Create Platform State Tax Mapping", 'exceptionMessage' => $e->getMessage())
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

        // try {
        //     $item = Fulfillment::findOrFail($id);
        //     $item->load(['package', 'package.items']);
        //     return response()->json([
        //         'data' => $item->toArray()
        //     ], 200);
        // }

        // catch(\Exception $e)
        // {
        //     return response()->json([
        //         'data' => array('error'=> "Fulfillment info not found for id: $id", 'exceptionMessage' => $e->getMessage())
        //     ], 404);
        // }
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

        // try {

        //     $fulfillment = Fulfillment::findOrFail($id);
        //     $fulfillment->total_items_in_fulfillment = $request->totalItemsInFulfillment ;

        //     $fulfillment->save();
        //     $fulfillment->load(['package', 'package.items']);
        //     return response()->json([
        //         'data' => $fulfillment->toArray()
        //     ], 200);
        // }
        // catch(exception $e)
        // {
        //     return response()->json([
        //         'data' => array('error'=> "Unable to Update Fulfillment", 'exceptionMessage' => $e->getMessage())
        //     ], 404);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // try {
        //     $item = Fulfillment::findOrFail($id);
        //     $item->delete();
        //     return response()->json([
        //         'data' => array('success')
        //     ], 200);
        // }

        // catch(\Exception $e)
        // {
        //     return response()->json([
        //         'data' => array('error'=> "Fulfillment could not be deleted for id: $id", 'exceptionMessage' => $e->getMessage())
        //     ], 404);
        // }
    }

    public function getAllStates()
    {
        try {
            $states = State::all();

                                    // ->with([
                                    //     'orderPlacingCustomer.payWithAmazonDetail', 'shippingDetails.shipToCustomer',
                                    //     'shippingDetails.orderItems', 'platformOrderTypes', 'promotions', 'refunds',
                                    //     'billingDetails.billToCustomer.payWithAmazonDetail', 'taxReference', 'orderItems.giftInfos',
                                    //     'giftInfos.orderItems', 'miscFees', 'orderItems.miscFees',
                                    //     'orderItems.promotions', 'orderItems.refunds',
                                    //     'fulfillments.orderItemMembers', 'orderItems.members.fulfillment',
                                    //     'orderItems.shippingDetails', 'payments.holds',
                                    // ])
                                    // ->first();

            if (empty($states)) {
                throw new Exception('Error retrieving States');
            }

            //we need to load up all the relationships
            return response()->json([
                'data' => $states
            ], 200);
        }
        catch(Exception $e) {
            return response()->json([
                'data' => array('error'=> "Unable to load States.", 'exceptionMessage' => $e->getMessage())
            ], 500);
        }
    }
}
