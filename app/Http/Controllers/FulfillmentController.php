<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Fulfillment;
use App\Models\FulfillmentPackage;
use App\Services\FulfillmentServiceFactory\FulfillmentServiceFactoryContract;

class FulfillmentController extends Controller
{
    protected $fulfillmentServiceFactory;

    public function __construct(FulfillmentServiceFactoryContract $fulfillmentServiceFactory)
    {
        $this->fulfillmentServiceFactory = $fulfillmentServiceFactory;

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
            $fulfillment = new Fulfillment();
            $fulfillment->vendor_id = 1;
            $fulfillment->vendor_name = "Vendor Name";
            $fulfillment->fk_fulfillment_type_id  = $request->fulfillmentType;
            $fulfillment->fk_workorder_id = $request->workorderId;

            $fulfillment->workorder_item_id = 1591; // do we need this?
            $fulfillment->part_number = "55UJ7700"; // do we need this?
            $fulfillment->product_id = 1762; // do we need this?

            $fulfillment->fk_ship_to_contact_id = $request->contactId;
            $fulfillment->save();

            $package = new FulfillmentPackage();
            $package->fk_fulfillment_id = $fulfillment->id;

            $package->save();

            $fulfillment->load(['package','package.items']);
            return response()->json([
                'data' => $fulfillment
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
            $item = Fulfillment::findOrFail($id);
            $item->load(['package', 'package.items']);
            return response()->json([
                'data' => $item->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Fulfillment info not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
        try {
            $fieldName = $request->get('fieldName');
            $fieldValue = $request->get('fieldValue');
            $fulfillment = Fulfillment::findOrFail($request->get('fulfillmentId'));
            //$fulfillment->total_items_in_fulfillment = $request->totalItemsInFulfillment ;
            $fulfillment->$fieldName = $fieldValue;
            $fulfillment->save();
            $fulfillment->load(['package', 'package.items']);
            return response()->json([
                'data' => $fulfillment->toArray()
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
            $item = Fulfillment::findOrFail($id);
            $item->delete();
            return response()->json([
                'data' => array('success')
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Fulfillment could not be deleted for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function search(Request $request)
    {
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        try {
            $fulfillment = Fulfillment::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            return response()->json([
                'data' => $fulfillment->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Fulfillments Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    public function getFull($id)
    {
        try {
            $item = Fulfillment::findOrFail($id);
            $item->load(['package','package.items']);

            return response()->json([
                'data' => $item
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Fulfillment could not be found for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    public function getFulfillments($workorderId)
    {
        try {
            $item = Fulfillment::where('fk_workorder_id', '=',$workorderId)->with(['fulfillmentType:id,fulfillment_name', 'package.items.workorderItem:id,model_number'])->get();
            return response()->json([
                'data' => $item
            ], 200);
        }

        catch(\Exception $e) {
            return response()->json([
                'data' => array('error'=> "Fulfillment could not be found for id: $workorderId", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function getFulfillment(Request $request)
    {
        $validatedData = $request->validate([
            // makes sure that fulfillmentType in the request is one of the fulfillment_code's in the fulfillment_type table.
            'fulfillmentType' => 'required|string|exists:fulfillment_type,fulfillment_code',
        ]);

        $fulfillmentMethod = $this->fulfillmentServiceFactory->getFulfillmentService($validatedData['fulfillmentType']);

        $data = array();

        $fulfillmentMethod->configShipment($request);

        $response = $fulfillmentMethod->getQuote();

        return response()->json([
                'data' => $response
            ], 200);
    }

    public function createFulfillment(Request $request)
    {
        $validatedData = $request->validate([
            // makes sure that fulfillmentType in the request is one of the fulfillment_code's in the fulfillment_type table.
            'fulfillmentType' => 'required|string|exists:fulfillment_type,fulfillment_code',
        ]);

        $fulfillmentMethod = $this->fulfillmentServiceFactory->getFulfillmentService($validatedData['fulfillmentType']);

        $data = array();

        $packageType = $fulfillmentMethod->configShipment($request, false);
        if($packageType == 'multiple'){
            $response = $fulfillmentMethod->confirmMultipleFulfillment();
        }else{
            $response = $fulfillmentMethod->confirmFulfillment();
        }

        return response()->json([
                'data' => $response
            ], 200);


    }
}
