<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Invoice;

class InvoiceController extends Controller
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

        $invoice = factory(\App\Models\Invoice::class, 10)->make();
        return $invoice;

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
            $invoice = new Invoice;
            $invoice->sold_contact_id = $request->sold_contact_id  ;
            $invoice->ship_contact_id = $request->ship_contact_id ;
            $invoice->bill_contact_id = $request->bill_contact_id ;
            $invoice->sold_account_id = $request->sold_account_id  ;
            $invoice->invoice_type = $request->invoice_type ;
            $invoice->invoice_class = $request->invoice_class;
            $invoice->invoice_status = $request->invoice_status ;

            $invoice->workorder_id = $request->workorder_id;
            $invoice->quote_id = $request->quote_id;
            $invoice->primary_sales_id = $request->primary_sales_id ;
            $invoice->second_sales_id = $request->second_sales_id ;
            $invoice->third_sales_id = $request->third_sales_id ;
            $invoice->product_total = $request->product_total ;
            $invoice->labor_total = $request->labor_total ;
            $invoice->shipping_total = $request->shipping_total ;
            $invoice->tax_total = $request->tax_total ;
            $invoice->total = $request->total ;
            $invoice->notes = $request->notes ;
            $invoice->lead_source = $request->lead_source ;
            $invoice->primary_development = $request->primary_development ;
            $invoice->primary_product_interest = $request->primary_product_interest ;
            $invoice->primary_feature_interest = $request->primary_feature_interest ;
            $invoice->demo_affinity = $request->demo_affinity ;



            $invoice->save();
            return response()->json([
                'data' => $invoice->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store Invoice", 'exceptionMessage' => $e->getMessage())
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
            $invoice = Invoice::find($id);
            $invoice->sold_contact_id = $request->sold_contact_id  ;
            $invoice->ship_contact_id = $request->ship_contact_id ;
            $invoice->bill_contact_id = $request->bill_contact_id ;
            $invoice->invoice_type = $request->invoice_type ;
            $invoice->invoice_class = $request->invoice_class;
            $invoice->invoice_status = $request->invoice_status ;

            $invoice->workorder_id = $request->workorder_id;
            $invoice->quote_id = $request->quote_id;

            $invoice->primary_sales_id = $request->primary_sales_id ;
            $invoice->second_sales_id = $request->second_sales_id ;
            $invoice->third_sales_id = $request->third_sales_id ;
            $invoice->product_total = $request->product_total ;
            $invoice->labor_total = $request->labor_total ;
            $invoice->shipping_total = $request->shipping_total ;
            $invoice->tax_total = $request->tax_total ;
            $invoice->total = $request->total ;
            $invoice->notes = $request->notes ;
            $invoice->lead_source = $request->lead_source ;
            $invoice->primary_development = $request->primary_development ;
            $invoice->primary_product_interest = $request->primary_product_interest ;
            $invoice->primary_feature_interest = $request->primary_feature_interest ;
            $invoice->demo_affinity = $request->demo_affinity ;



            $invoice->save();
            return response()->json([
                'data' => $invoice->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store Invoice", 'exceptionMessage' => $e->getMessage())
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
        

       $invoice = Invoice::where('id', '!=', '')->get();
        print_r($invoice);
        exit;

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
