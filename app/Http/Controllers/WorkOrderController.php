<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\WorkOrder;
use App\Models\Quote;
use App\Models\WorkorderNotes;
use Exception;

class WorkOrderController extends Controller
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
        return Workorder::all();
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
            $workOrder = new WorkOrder;
            $workOrder->sold_contact_id = $request->sold_contact_id;
            $workOrder->ship_contact_id = $request->ship_contact_id;
            $workOrder->bill_contact_id = $request->bill_contact_id;
            $workOrder->sold_account_id = $request->sold_account_id;
            $workOrder->workorder_type = $request->workorder_type;
            $workOrder->workorder_class = $request->workorder_class;
            $workOrder->workorder_status = $request->workorder_status;
            $workOrder->requirement_status = $request->requirement_status;
            $workOrder->quote_id = 0;
            $workOrder->invoice_id = $request->invoice_id;
            $workOrder->primary_sales_id = $request->primary_sales_id;
            $workOrder->second_sales_id = $request->second_sales_id;
            $workOrder->third_sales_id = $request->third_sales_id;
            $workOrder->product_total = $request->product_total;
            $workOrder->labor_total = $request->labor_total;
            $workOrder->shipping_total = $request->shipping_total;
            $workOrder->tax_total = $request->tax_total;
            $workOrder->total = $request->total;
            $workOrder->notes = $request->notes;
            $workOrder->lead_source = $request->lead_source;
            $workOrder->primary_development = $request->primary_development;
            $workOrder->primary_product_interest = $request->primary_product_interest;
            $workOrder->primary_feature_interest = $request->primary_feature_interest;
            $workOrder->demo_affinity = $request->demo_affinity;

            $workOrder->save();
            return response()->json([
                'data' => $workOrder->toArray()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Store WorkOrder", 'exceptionMessage' => $e->getMessage())
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
        return WorkOrder::findOrFail($id);
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
            $workOrder = WorkOrder::find($request->id);

            $workOrder->sold_contact_id = $request->sold_contact_id;
            $workOrder->ship_contact_id = $request->ship_contact_id;
            $workOrder->bill_contact_id = $request->bill_contact_id;
            $workOrder->workorder_type = $request->workorder_type;
            $workOrder->workorder_class = $request->workorder_class;
            $workOrder->workorder_status = $request->workorder_status;
            $workOrder->requirement_status = $request->requirement_status;
            $workOrder->primary_sales_id = $request->primary_sales_id;
            $workOrder->second_sales_id = $request->second_sales_id;
            $workOrder->third_sales_id = $request->third_sales_id;
            $workOrder->product_total = $request->product_total;
            $workOrder->labor_total = $request->labor_total;
            $workOrder->shipping_total = $request->shipping_total;
            $workOrder->tax_total = $request->tax_total;
            $workOrder->total = $request->total;
            $workOrder->notes = $request->notes;
            $workOrder->lead_source = $request->lead_source;
            $workOrder->primary_development = $request->primary_development;
            $workOrder->primary_product_interest = $request->primary_product_interest;
            $workOrder->primary_feature_interest = $request->primary_feature_interest;
            $workOrder->demo_affinity = $request->demo_affinity;
            $workOrder->invoice_id = $request->invoice_id;
            $workOrder->quote_id = $request->quote_id;
            $workOrder->sold_account_id = $request->sold_account_id;
            $workOrder->tax_zone = $request->tax_zone;
            $workOrder->taxable = $request->taxable;
            //created_at
            //updated_at
            $workOrder->save();
            return response()->json([
                'data' => $workOrder->toArray()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Store WorkOrder", 'exceptionMessage' => $e->getMessage())
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
            $workOrders = WorkOrder::where($searchKey, 'like', $searchValue . '%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            foreach ($workOrders as $workOrder) {
                $workOrder->shipTo;
                $workOrder->soldTo;
                $workOrder->billTo;
                $workOrder->workOrderItems;
            }

            return response()->json([
                'data' => $workOrders->toArray()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "No Accounts Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }
    public function convertToInvoice($id)
    {

        try {
            $order = WorkOrder::findOrFail($id);
            if ($order->order_status != 'Invoiced') {
                // todo: fix order to call updateTotals in order
                $order->updateTotals();

                $invoice = new Invoice();
                $invoice->sold_contact_id = $order->sold_contact_id;
                $invoice->ship_contact_id = $order->ship_contact_id;
                $invoice->bill_contact_id = $order->bill_contact_id;
                $invoice->sold_account_id = $order->sold_account_id;

                $invoice->invoice_type = $order->order_type;
                $invoice->invoice_class = $order->order_class;
                $invoice->invoice_status = "New";
                $invoice->requirement_status = 'none';
                $invoice->order_id = $order->id;
                $invoice->quote_id = $order->quote_id;
                $invoice->primary_sales_id = $order->primary_sales_id;
                $invoice->second_sales_id = $order->second_sales_id;
                $invoice->third_sales_id = $order->third_sales_id;
                $invoice->product_total = $order->product_total;
                $invoice->labor_total = $order->labor_total;
                $invoice->shipping_total = $order->shipping_total;
                $invoice->tax_total = $order->tax_total;
                $invoice->total = $order->total;
                $invoice->lead_source = $order->lead_source;
                $invoice->primary_development = $order->primary_development;
                $invoice->primary_product_interest = $order->primary_product_interest;
                $invoice->primary_feature_interest = $order->primary_feature_interest;
                $invoice->demo_affinity = $order->demo_affinity;
                $invoice->tax_zone = $order->tax_zone;
                $invoice->taxable = $order->taxable;

                $invoice->save();

                $invoiceItems = $order->orderItems()->get();
                foreach ($invoiceItems as $item) {

                    $detailLine = new InvoiceItem();

                    $detailLine->fk_workorder_id = $order->id;
                    $detailLine->fk_product_id = $item->fk_product_id;
                    // todo: need to current user id as employee
                    $detailLine->fk_employee_id = 0;
                    $detailLine->model_number = $item->model_number;
                    $detailLine->part_number = $item->part_number;
                    $detailLine->brand = $item->brand;
                    $detailLine->description = $item->description;
                    $detailLine->upc = $item->upc;
                    $detailLine->category = $item->category;
                    $detailLine->item_class = $item->item_class;
                    $detailLine->item_type = $item->item_type;
                    $detailLine->serial_number = $item->serial_number;
                    $detailLine->source_vendor = $item->condition;
                    $detailLine->condition = $item->condition;
                    $detailLine->tax_code = $item->tax_code;
                    $detailLine->tax_amount = $item->tax_amount;
                    $detailLine->ext_price = $item->ext_price;
                    $detailLine->unit_price = $item->unit_price;
                    $detailLine->number_units = $item->number_units;
                    $detailLine->standard_gross_profit = $item->standard_gross_profit;
                    $detailLine->final_gross_profit = $item->final_gross_profit;

                    $detailLine->save();
                }

                $order->invoice_id = $invoice->id;
                $order->invoice_status = "Invoiced";
                $order->save();
            } else {
                throw new Exception("Order Already Invoiced");
            }
            return response()->json([
                'data' => $order->id
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "Could Not Invoice Order", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function updateTotals($id)
    {

        try {
            $workOrder = WorkOrder::findOrFail($id);

            $workOrder->updateTotals();

            return response()->json([
                'data' => $workOrder->toArray()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "No Order Found", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }


    public function getFull($id)
    {

        try {
            $workOrder = WorkOrder::findOrFail($id);
            $workOrder->updateTotals();
            $workOrder->billTo;
            $workOrder->shipTo;
            $workOrder->soldTo;
            $workOrder->load(['workOrderItems.inventory', 'primarySales']);
            foreach ($workOrder->payments as $payment) {
                $payment->user;
                $payment->paymentMethod;
                $payment->paymentClass;
                $payment->paymentTerminal;
                $payment->paymentBatch;
            }
            foreach ($workOrder->workorderNotes as $note) {
                $note->user;
                $note->to;
                $note->note_type;
            }

            foreach ($workOrder->workorderLaborLines as $line) {
                // $line->quote;
                $line->user;
                // $line->product;
            }

            return response()->json([
                'data' => $workOrder
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "No Order Found", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }
}
