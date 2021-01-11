<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\QuoteItem;
use App\Models\TaxInfo;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\WorkOrderLaborLine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Quote;
use App\Models\QuoteNote;

class QuoteController extends Controller
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

        $quote = Quote::where('id', '=', 162117)->take(1)->get();

        return response()->json([
            'data' => $quote->toArray()
        ], 200);

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   //echo "hit me baby one more time";

        try {
            $quote = new Quote;
            // $quote->sold_contact_id = $request->sold_contact_id;
            // $quote->ship_contact_id = $request->ship_contact_id;
            // $quote->bill_contact_id = $request->bill_contact_id;
            // $quote->sold_account_id = $request->sold_account_id;
            // $quote->quote_type = $request->quote_type;
            // $quote->quote_class = $request->quote_class;
            // $quote->quote_status = $request->quote_status;
            // $quote->primary_sales_id = $request->primary_sales_id;
            // $quote->second_sales_id = $request->second_sales_id;
            // $quote->third_sales_id = $request->third_sales_id;
            // $quote->product_total = $request->product_total;
            // $quote->labor_total = $request->labor_total;
            // $quote->shipping_total = $request->shipping_total;
            // $quote->tax_total = $request->tax_total;
            // $quote->total = $request->total;
            // $quote->notes = $request->notes;
            // $quote->lead_source = $request->lead_source;
            // $quote->primary_development = $request->primary_development;
            // $quote->primary_product_interest = $request->primary_product_interest;
            // $quote->primary_feature_interest = $request->primary_feature_interest;
            // $quote->demo_affinity = $request->demo_affinity;
            // $quote->approval_status = $request->approval_status;
            // $quote->approval_status_notes = $request->approval_status_notes;



                  $quote->primary_sales_id = Auth::id();
                  $quote->sold_account_id = $request->sold_account_id;
                  $quote->sold_contact_id = $request->sold_contact_id;
                  $quote->order_type = $request->order_type;
                  $quote->order_class = $request->order_class;
                  $quote->status = $request->status;
                  $quote->lead_source = $request->lead_source;
                  $quote->bill_contact_id = $request->sold_contact_id;
                  $quote->second_sales_id = Auth::id();
                  $quote->third_sales_id = Auth::id();
                  $quote->ship_contact_id = $request->sold_contact_id;
                  $quote->product_total = 0.00;
                  $quote->labor_total = 0.00;
                  $quote->shipping_total = 0.00;
                  $quote->tax_total = 0.00;
                  $quote->total = 0.00;
                  $quote->notes = $request->notes;
                  $quote->primary_development = '';
                  $quote->primary_product_interest = '';
                  $quote->primary_feature_interest = '';
                  $quote->demo_affinity = '';
                  $quote->approval_status = '';
                  $quote->approval_status_notes = '';

            //print_r($quote);
            $quote->save();

            return response()->json([
                'data' => $quote->toArray()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Store Quote", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    /**
     * Force Update Totals for Quote
     */


    public function updateTotals($id)
    {

        try {
            $quote = Quote::findOrFail($id);

            $quote->updateTotals();

            return response()->json([
                'data' => $quote->toArray()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => array('error' => "No Quote", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }


    public function getFull($id)
    {

        try {
            $quote = Quote::findOrFail($id);

            $quoteItems = $quote->quoteItems; 
            $quote->shipTo;
            $quote->soldTo;
            $quote->billTo;
            $quote->primarySalesId;
            $quote->quoteLaborLines;

            foreach($quote->quoteNote as $note){
                $note->user;
                $note->to;
                $note->note_type;
            }

            $fullQuote["body"] = $quote->toArray();
            $fullQuote["lines"] = $quoteItems;
            foreach($quote->quoteLaborLines as $line){
                // $line->quote;
                $line->user;
                $line->product;
            }
            $fullQuote["laborLines"] = $quote->quoteLaborLines;
            return response()->json([
                'data' => $fullQuote
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => array('error' => "No Quote", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }
    /*
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $quote = Quote::where('id', '=',$id)
                ->take(1)
                ->get();
            return response()->json([
                'data' => $quote->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Quote", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

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
    public function update(Request $request)
    {
        try {
            $quote = Quote::findOrFail($request->get('id'));
            $quote->sold_contact_id = $request->sold_contact_id  ;
            $quote->ship_contact_id = $request->ship_contact_id ;
            $quote->bill_contact_id = $request->bill_contact_id ;
            $quote->quote_type = $request->quote_type ;
            $quote->quote_class = $request->quote_class;
            $quote->quote_status = $request->quote_status ;
            $quote->primary_sales_id = $request->primary_sales_id ;
            $quote->second_sales_id = $request->second_sales_id ;
            $quote->third_sales_id = $request->third_sales_id ;
            $quote->product_total = $request->product_total ;
            $quote->labor_total = $request->labor_total ;
            $quote->shipping_total = $request->shipping_total ;
            $quote->tax_total = $request->tax_total ;
            $quote->total = $request->total ;
            $quote->notes = $request->notes ;
            $quote->lead_source = $request->lead_source ;
            $quote->primary_development = $request->primary_development ;
            $quote->primary_product_interest = $request->primary_product_interest ;
            $quote->primary_feature_interest = $request->primary_feature_interest ;
            $quote->demo_affinity = $request->demo_affinity ;
            $quote->approval_status = $request->approval_status ;
            $quote->approval_status_notes = $request->approval_status_notes ;



            $quote->save();

            return response()->json([
                'data' => $quote->toArray()
            ], 200);
        }
        catch(\exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to update Quote", 'exceptionMessage' => $e->getMessage())
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
            $quotes = Quote::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            foreach($quotes as $quote){
                $quote->shipTo;
                $quote->soldTo;
                $quote->billTo;
                $quote->quoteItems;
            }

            return response()->json([
                'data' => $quotes->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }    
    }
    
    public function convertToOrder($id)
    {

        try {
            $quote = Quote::findOrFail($id);
            if($quote->quote_status != 'Converted') {
                // todo: fix quote to call updateTotals in quote
                $quote->updateTotals();
                $order = new WorkOrder();
                $order->sold_contact_id = $quote->sold_contact_id;
                $order->ship_contact_id = $quote->ship_contact_id;
                $order->bill_contact_id = $quote->bill_contact_id;
                $order->sold_account_id = $quote->sold_account_id;

                $order->order_type = $quote->order_type;
                $order->order_class = $quote->order_class;
                $order->status = "New";
                $order->requirement_status = 'none';
                $order->quote_id = $quote->id;
                $order->primary_sales_id = $quote->primary_sales_id;
                $order->second_sales_id = $quote->second_sales_id;
                $order->third_sales_id = $quote->third_sales_id;
                $order->product_total = $quote->product_total;
                $order->labor_total = $quote->labor_total;
                $order->shipping_total = $quote->shipping_total;
                $order->tax_total = $quote->tax_total;
                $order->total = $quote->total;
                $order->lead_source = $quote->lead_source;
                $order->primary_development = $quote->primary_development;
                $order->primary_product_interest = $quote->primary_product_interest;
                $order->primary_feature_interest = $quote->primary_feature_interest;
                $order->demo_affinity = $quote->demo_affinity;
                $order->tax_zone = $quote->tax_zone;
                $order->is_taxable = $quote->taxable;
                $order->platform_order_id = "walts-quote-" . $quote->id;
                $order->platform = 'WPOS3';
                $order->expected_delivery_date = '0000-00-00 00:00:00';
               // $order->ship_method_requested = '';

                $order->save();

                $quoteItems = $quote->quoteItems()->get();
                foreach ($quoteItems as $item) {

                    $detailLine = new WorkOrderItem();
                    $detailLine->workorder_id = $order->id;
                    $detailLine->product_id = $item->product_id;
                    // todo: need to current user id as employee
                    $detailLine->employee_id = Auth::id();
                    $detailLine->model_number = $item->model_number;
                    $detailLine->part_number = $item->part_number;
                    $detailLine->fk_brand_id = $item->fk_brand_id;
                    $detailLine->description = $item->description;
                    $detailLine->upc = $item->upc;
                    $detailLine->fk_category_id = $item->fk_category_id;
                    $detailLine->item_class = $item->item_class;
                    $detailLine->item_type = $item->item_type;
                    $detailLine->serial_number_tracked = $item->serial_number;
                    $detailLine->source_vendor = $item->condition;
                    $detailLine->condition = $item->condition;
                    $detailLine->tax_code = $item->tax_code;
                    $detailLine->tax_amount = $item->tax_amount;
                    $detailLine->ext_price = $item->ext_price;
                    $detailLine->unit_price = $item->unit_price;
                    $detailLine->number_units = $item->number_units;
                    $detailLine->standard_gross_profit = $item->standard_gross_profit;
                    $detailLine->final_gross_profit = $item->final_gross_profit;
                    $detailLine->platform_order_item_id = "walts-quote-" . $item->id;
                    $detailLine->ship_method_requested = 'LOCAL DELIVERY';
                    $detailLine->save();
                }

                foreach($quote->quoteLaborLines as $line){
                    $newWorkorderLine = new WorkOrderLaborLine();
                    $newWorkorderLine->fk_workorder_id = $order->id;
                    $newWorkorderLine->sku = $line->sku;
                    $newWorkorderLine->fk_products_id = $line->fk_products_id;
                    $newWorkorderLine->description = $line->description;
                    $newWorkorderLine->fk_technician_id = $line->fk_technician_id;
                    $newWorkorderLine->rate = $line->rate;
                    $newWorkorderLine->hours = $line->hours;
                    $newWorkorderLine->save();
                }

                $quote->workorder_id = $order->id;
                $quote->status = "Converted";
                $quote->save();
            }
            else{
                throw new \Exception("Quote Already Converted");
            }
            return response()->json([
                'data' => $order->id
            ], 200);
        } catch (\Exception $e) {
            //Need to create cleanup to destroy what was partially created.
            return response()->json([
                'data' => array('error' => "Could Not Covert to Order", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }
}
