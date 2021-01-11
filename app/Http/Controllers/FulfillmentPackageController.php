<?php

namespace App\Http\Controllers;

use App\Models\FulfillmentPackage;
use App\Models\FulfillmentPackageProduct;
use Illuminate\Http\Request;

class FulfillmentPackageController extends Controller
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
            $fulfillmentPackage = new FulfillmentPackage();

            $fulfillmentPackage->fk_fulfillment_id = $request->fulfillmentId;
            $fulfillmentPackage->package_length = $request->packageLength;
            $fulfillmentPackage->package_width = $request->packageWidth;
            $fulfillmentPackage->package_height = $request->packageHeight;
            $fulfillmentPackage->package_weight = $request->packageWeight;
            $fulfillmentPackage->tracking_number = $request->trackingNumber;
            $fulfillmentPackage->shipping_quote = $request->shippingQuote;
            $fulfillmentPackage->shipping_actual = $request->shippingActual;

            $fulfillmentPackage->save();
            $fulfillmentPackage->load(['items']);
            return response()->json([
                'data' => $fulfillmentPackage
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
            $item = FulfillmentPackage::findOrFail($id);
            $item->load(['items']);
            return response()->json([
                'data' => $item
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

            $fulfillmentPackage = FulfillmentPackage::findOrFail($id);

            $fulfillmentPackage->package_length = $request->packageLength;
            $fulfillmentPackage->package_width = $request->packageWidth;
            $fulfillmentPackage->package_height = $request->packageHeight;
            $fulfillmentPackage->package_weight = $request->packageWeight;

            $fulfillmentPackage->tracking_number = $request->trackingNumber;
            $fulfillmentPackage->shipping_quote = $request->shippingQuote;
            $fulfillmentPackage->shipping_actual = $request->shippingActual;

            $fulfillmentPackage->save();
            $fulfillmentPackage->load(['items']);
            return response()->json([
                'data' => $fulfillmentPackage
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
    public function destroy($id)
    {
        try {
            $item = FulfillmentPackage::findOrFail($id);
            $products = FulfillmentPackageProduct::where('fk_fulfillment_package_id', '=', $item->id)->get();
            if(sizeof($products ) < 1) {
                $item->delete();
            }
            else{
                throw( new \Exception);
            }
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
            $package = FulfillmentPackage::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            return response()->json([
                'data' => $package->toArray()
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
