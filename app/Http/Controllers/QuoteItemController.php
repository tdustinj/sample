<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\QuoteItem;
use App\Models\Product;

class QuoteItemController extends Controller
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

        $quoteItem = factory(\App\Models\QuoteItem::class, 10)->make();
        return $quoteItem;

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
        $today = date("Y-m-d H:i:s");
        $newQuoteItem = Product::find($request->productId);
        // return $newQuoteItem;
        try {
            $quoteItem = new QuoteItem;
            $quoteItem->quote_id = $request->quote_id;
            $quoteItem->product_id = $newQuoteItem->id;
            $quoteItem->employee_id = Auth::id();
            $quoteItem->model_number = $newQuoteItem->model_number;
            $quoteItem->part_number = $newQuoteItem->part_number;
            $quoteItem->fk_brand_id = $newQuoteItem->brand->id;
            $quoteItem->description = $newQuoteItem->description;
            $quoteItem->upc = $newQuoteItem->upc;
            $quoteItem->fk_category_id = $newQuoteItem->category->id;
            $quoteItem->item_class = $newQuoteItem->item_class;
            $quoteItem->item_type = $newQuoteItem->item_type;
            $quoteItem->serial_number = $newQuoteItem->serial_number;
            $quoteItem->quote_date = $today;
            $quoteItem->source_vendor = "WAREHOUSE";
            $quoteItem->condition = "new";
            $quoteItem->tax_code = "TBD";
            $quoteItem->tax_amount = "0.00";
            $quoteItem->ext_price = "0.00";
            $quoteItem->unit_price = $newQuoteItem->msrp;
            $quoteItem->number_units = 1;
            $quoteItem->standard_gross_profit = "0.00";
            $quoteItem->final_gross_profit = "0.00";




            $quoteItem->save();
            return response()->json([
                'data' => $quoteItem->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store QuoteItem", 'exceptionMessage' => $e->getMessage())
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
    public function update(Request $request)
    {
        try {
            $quoteItem = QuoteItem::find($request->get('id'));
            $quoteItem->quote_id = $request->quote_id  ;
            // $quoteItem->product_id = $request->product_id ;
            // $quoteItem->employee_id = $request->employee_id ;
            // $quoteItem->model = $request->model  ;
            // $quoteItem->part_number = $request->part_number ;
            // $quoteItem->brand = $request->brand;
            // $quoteItem->description = $request->description ;

            // $quoteItem->upc = $request->upc;
            // $quoteItem->category = $request->category;
            // $quoteItem->item_class = $request->item_class ;
            // $quoteItem->item_type = $request->item_type ;
            // $quoteItem->serial_number = $request->serial_number ;
            // $quoteItem->quote_date = $request->quote_date ;
            // $quoteItem->source_vendor = $request->source_vendor ;
            $quoteItem->condition = $request->condition;
            // $quoteItem->tax_code = $request->tax_code ;
            // $quoteItem->tax_amount = $request->tax_amount ;
            // $quoteItem->ext_price = $request->ext_price ;
            $quoteItem->unit_price = $request->unit_price ;
            $quoteItem->number_units = $request->number_units ;
            // $quoteItem->standard_gross_profit = $request->standard_gross_profit ;
            // $quoteItem->final_gross_profit = $request->final_gross_profit ;




            $quoteItem->save();
            return response()->json([
                'data' => $quoteItem->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Update QuoteItem", 'exceptionMessage' => $e->getMessage())
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
        //print_r($id);
        QuoteItem::where('id', 'like', $id)->delete();

        //return "Destroy";
        return response()->json([
                'data' => array('Deleted'=> "$id")
            ], 200);

    }
    public function search(Request $request)

    {
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        try {
            $quoteItem = QuoteItem::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            return response()->json([
                'data' => $quoteItem->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No QuoteItem Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }
}
