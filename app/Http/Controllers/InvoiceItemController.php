<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\InvoiceItem;

class InvoiceItemController extends Controller
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

        $invoiceItem = factory(\App\Models\InvoiceItem::class, 10)->make();
        return $invoiceItem;

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
            $invoiceItem = new InvoiceItem;
            $invoiceItem->invoice_id = $request->invoice_id  ;
            $invoiceItem->product_id = $request->product_id ;
            $invoiceItem->employee_id = $request->employee_id ;
            $invoiceItem->model_number = $request->model_number  ;
            $invoiceItem->part_number = $request->part_number ;
            $invoiceItem->brand = $request->brand;
            $invoiceItem->description = $request->description ;

            $invoiceItem->upc = $request->upc;
            $invoiceItem->category = $request->category;
            $invoiceItem->item_class = $request->item_class ;
            $invoiceItem->item_type = $request->item_type ;
            $invoiceItem->serial_number = $request->serial_number ;
            $invoiceItem->invoice_date = $request->invoice_date ;
            $invoiceItem->source_vendor = $request->source_vendor ;
            $invoiceItem->condition = $request->condition ;
            $invoiceItem->tax_code = $request->tax_code ;
            $invoiceItem->tax_amount = $request->tax_amount ;
            $invoiceItem->ext_price = $request->ext_price ;
            $invoiceItem->unit_price = $request->unit_price ;
            $invoiceItem->number_units = $request->number_units ;
            $invoiceItem->standard_gross_profit = $request->standard_gross_profit ;
            $invoiceItem->final_gross_profit = $request->final_gross_profit ;




            $invoiceItem->save();
            return response()->json([
                'data' => $invoiceItem->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store InvoiceItem", 'exceptionMessage' => $e->getMessage())
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
    public function update(Request $request, $id)
    {
        try {
            $invoiceItem = InvoiceItem::find($id);
            $invoiceItem->invoice_id = $request->invoice_id  ;
            $invoiceItem->product_id = $request->product_id ;
            $invoiceItem->employee_id = $request->employee_id ;
            $invoiceItem->model_number = $request->model_number  ;
            $invoiceItem->part_number = $request->part_number ;
            $invoiceItem->brand = $request->brand;
            $invoiceItem->description = $request->description ;

            $invoiceItem->upc = $request->upc;
            $invoiceItem->category = $request->category;
            $invoiceItem->item_class = $request->item_class ;
            $invoiceItem->item_type = $request->item_type ;
            $invoiceItem->serial_number = $request->serial_number ;
            $invoiceItem->invoice_date = $request->invoice_date ;
            $invoiceItem->source_vendor = $request->source_vendor ;
            $invoiceItem->condition = $request->condition ;
            $invoiceItem->tax_code = $request->tax_code ;
            $invoiceItem->tax_amount = $request->tax_amount ;
            $invoiceItem->ext_price = $request->ext_price ;
            $invoiceItem->unit_price = $request->unit_price ;
            $invoiceItem->number_units = $request->number_units ;
            $invoiceItem->standard_gross_profit = $request->standard_gross_profit ;
            $invoiceItem->final_gross_profit = $request->final_gross_profit ;




            $invoiceItem->save();
            return response()->json([
                'data' => $invoiceItem->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Update InvoiceItem", 'exceptionMessage' => $e->getMessage())
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
        return "Destroy";

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
}
