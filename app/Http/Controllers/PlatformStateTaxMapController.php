<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PlatformStateTaxMap;

class PlatformStateTaxMapController extends Controller
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
    public function update($platform_code, $state_code, $collectAndRemitTax)
    {
       try {
          $platformStateTaxToUpdate = PlatformStateTaxMap::where('platform_code','=', $platform_code)->where('state_code', '=', $state_code)->first();
          $platformStateTaxToUpdate->platform_code = $platform_code;
          $platformStateTaxToUpdate->state_code = $state_code;
          $platformStateTaxToUpdate->collectAndRemitTax = ($collectAndRemitTax == "true" ? 1 : 0);
          $platformStateTaxToUpdate->save();

          return response()->json([
              'data' => $platformStateTaxToUpdate,
              'sent' => $collectAndRemitTax
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

    public function getAllTaxInfo()
    {
        try {
            $stateTaxes = PlatformStateTaxMap::all();

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

            if (empty($stateTaxes)) {
                throw new Exception('Error retrieving State Taxes');
            }

            //we need to load up all the relationships
            return response()->json([
                'data' => $stateTaxes
            ], 200);
        }
        catch(Exception $e) {
            return response()->json([
                'data' => array('error'=> "Unable to load state taxes.", 'exceptionMessage' => $e->getMessage())
            ], 500);
        }
    }

    public function getAllTaxInfoByState()
    {
        try {
            $stateTaxes = PlatformStateTaxMap::all()->groupBy('state_code');

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

            if (empty($stateTaxes)) {
                throw new Exception('Error retrieving State Taxes');
            }

            //we need to load up all the relationships
            return response()->json([
                'data' => $stateTaxes
            ], 200);
        }
        catch(Exception $e) {
            return response()->json([
                'data' => array('error'=> "Unable to load state taxes.", 'exceptionMessage' => $e->getMessage())
            ], 500);
        }
    }

    public function getAllTaxInfoByPlatform()
    {
        try {
            $platformTaxes = PlatformStateTaxMap::all()->groupBy('platform_code');

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

            if (empty($platformTaxes)) {
                throw new Exception('Error retrieving Platform Taxes');
            }

            //we need to load up all the relationships
            return response()->json([
                'data' => $platformTaxes
            ], 200);
        }
        catch(Exception $e) {
            return response()->json([
                'data' => array('error'=> "Unable to load state taxes.", 'exceptionMessage' => $e->getMessage())
            ], 500);
        }
    }

}
