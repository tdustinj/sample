<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\WorkOrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Inventory;
use \DB;

class InventoryController extends Controller
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
    return DB::table('inventory')
      ->join('product', 'inventory.fk_products_id', '=', 'product.id')
      ->select('inventory.model_number', 'product.description', DB::raw('COUNT(*) as count'))
      ->groupby('inventory.model_number', 'description')
      ->get();
  }
  /**
   * Display a formatted list of inventory
   * that is filtered by Model Number
   * @param int $modelNumber 
   * @return \Illuminate\Http\Response
   */
  public function list($modelNumber, Request $request)
  {
    /*
     * Validate URL Parameters
     * Set them to defaults if they are null
     */
    $validated = $request->validate([
      'limit' => 'nullable|integer',
      'offset' => 'nullable|integer'
    ]);
    $limit = $validated['limit'] ?? 100;
    $offset = $validated['offset'] ?? 0;

    $page = DB::table('inventory')
      ->join('product', 'inventory.fk_products_id', '=', 'product.id')
      ->select('inventory.model_number', 'product.description', 'inventory.serial_number')
      ->where('inventory.model_number', '=', $modelNumber)
      ->limit($limit)
      ->offset($offset)
      ->get();
    $total = Inventory::where('model_number', '=', $modelNumber)->count();
    return response()->json(['page' => $page, 'total' => $total]);
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
  public static function store(Request $request)
  {
    try {
      $product = Product::findOrFail($request->products_id);
      $inventory = new Inventory;
      $inventory->fk_products_id = $request->products_id;
      $inventory->model_number = $request->product_model;
      $inventory->fk_brand_id = $product->fk_brand_id;
      $inventory->fk_category_id = $product->fk_category_id;
      $inventory->part_number = $request->part_number;
      $inventory->upc = $request->product_upc;
      $inventory->box_code = $request->box_code;
      $inventory->model_bar_code = $request->model_bar_code;
      $inventory->ean = $request->ean;
      $inventory->asin = $request->asin;
      $inventory->serial_tracked = $request->serial_tracked;
      $inventory->serial_number = $request->serial_number;
      $inventory->alternate_serial_number = $request->alternate_serial_number;
      $inventory->bundle_products_id = $request->bundle_products_id;
      $inventory->fk_location_id = $request->location_id;
      $inventory->initial_purchase_condition = $request->initial_purchase_condition;
      $inventory->current_condition = $request->current_condition;
      $inventory->current_condition_notes = $request->current_condition_notes;
      $inventory->selling_status = $request->selling_status;
      $inventory->assigned_to_invoice = $request->assigned_to_invoice;
      $inventory->sold_at = $request->sold_at;
      $inventory->rma_number = $request->rma_number;
      $inventory->rma_tracking_number = $request->rma_tracking_number;
      $inventory->rma_status = $request->rma_status;
      $inventory->rma_credit_amount_rec = $request->rma_credit_amount_rec;
      $inventory->rma_credit_rec_at = $request->rma_credit_rec_at;
      $inventory->fk_vendor_id = $request->vendor_id;
      $inventory->fk_purchase_order_id = $request->purchase_order_id;
      $inventory->ordered_at = $request->created_at;
      $inventory->received_at = Date('Y-m-d h:i:s', $request->received_at);
      $inventory->received_by = $request->received_by;
      $inventory->invoice_cost = $request->invoice_cost;
      $inventory->program_cost = $request->program_cost;
      $inventory->billed_amount = $request->billed_amount;
      $inventory->purchase_shipping_cost = $request->purchase_shipping_cost;
      $inventory->fulfillment_type = $request->fulfillment_type;
      $inventory->fulfillment_cost = $request->fulfillment_cost;
      $inventory->commission_paid = $request->commission_paid;
      $inventory->spiff_paid = $request->spiff_paid;
      $inventory->other_costs = $request->other_costs;
      $inventory->spa = $request->spa;
      $inventory->mdf = $request->mdf;
      $inventory->vir = $request->vir;
      $inventory->payment_discount = $request->payment_discount;
      $inventory->trailing_credit_program = $request->trailing_credit_program;
      $inventory->trailing_credit_program_notes = $request->trailing_credit_program_notes;
      $inventory->trailing_credit_submission_status = $request->trailing_credit_submission_status;
      $inventory->trailing_credit_claimed_at = $request->trailing_credit_claimed_at;
      $inventory->trailing_credit_received_at = $request->trailing_credit_received_at;
      $inventory->trailing_credit_amount = $request->trailing_credit_amount;
      $inventory->pre_tc_gross_margin = $request->pre_tc_gross_margin;
      $inventory->post_tc_gross_margin = $request->post_tc_gross_margin;
      $inventory->initial_gross_margin = $request->initial_gross_margin;
      $inventory->program_cost_gross_margin = $request->program_cost_gross_margin;
      $inventory->gross_profit_after_commission_spiff = $request->gross_profit_after_commission_spiff;
      $inventory->final_gross_profit = $request->final_gross_profit;
      $inventory->save();
      return response()->json([
        'data' => $inventory->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Unable to Store Inventory", 'exceptionMessage' => $e->getMessage())
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
      $item = Inventory::findOrFail($id);
      return response()->json([
        'data' => $item->toArray()
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Inventory not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
      $inventory = Inventory::find($id);
      $inventory->fk_products_id = $request->products_id;
      $inventory->model_number = $request->model_number;
      $inventory->fk_brand_id = $request->brand;
      $inventory->fk_category_id = $request->category;
      $inventory->part_number = $request->part_number;
      $inventory->upc = $request->upc;
      $inventory->box_code = $request->box_code;
      $inventory->model_bar_code = $request->model_bar_code;
      $inventory->ean = $request->ean;
      $inventory->asin = $request->asin;
      $inventory->serial_tracked = $request->serial_tracked;
      $inventory->serial_number = $request->serial_number;
      $inventory->alternate_serial_number = $request->alternate_serial_number;
      $inventory->bundle_products_id = $request->bundle_products_id;
      $inventory->fk_location_id = $request->location_id;
      $inventory->initial_purchase_condition = $request->initial_purchase_condition;
      $inventory->current_condition = $request->current_condition;
      $inventory->current_condition_notes = $request->current_condition_notes;
      $inventory->selling_status = $request->selling_status;
      $inventory->assigned_to_invoice = $request->assigned_to_invoice;
      $inventory->sold_at = $request->sold_at;
      $inventory->rma_number = $request->rma_number;
      $inventory->rma_tracking_number = $request->rma_tracking_number;
      $inventory->rma_status = $request->rma_status;
      $inventory->rma_credit_amount_rec = $request->rma_credit_amount_rec;
      $inventory->rma_credit_rec_at = $request->rma_credit_rec_at;
      $inventory->fk_vendor_id = $request->vendor_id;
      $inventory->fk_purchase_order_id = $request->purchase_order_id;
      $inventory->ordered_at = $request->ordered_at;
      $inventory->received_at = $request->received_at;
      $inventory->received_by = $request->received_by;
      $inventory->invoice_cost = $request->invoice_cost;
      $inventory->program_cost = $request->program_cost;
      $inventory->billed_amount = $request->billed_amount;
      $inventory->purchase_shipping_cost = $request->purchase_shipping_cost;
      $inventory->fulfillment_type = $request->fulfillment_type;
      $inventory->fulfillment_cost = $request->fulfillment_cost;
      $inventory->commission_paid = $request->commission_paid;
      $inventory->spiff_paid = $request->spiff_paid;
      $inventory->other_costs = $request->other_costs;
      $inventory->spa = $request->spa;
      $inventory->mdf = $request->mdf;
      $inventory->vir = $request->vir;
      $inventory->payment_discount = $request->payment_discount;
      $inventory->trailing_credit_program = $request->trailing_credit_program;
      $inventory->trailing_credit_program_notes = $request->trailing_credit_program_notes;
      $inventory->trailing_credit_submission_status = $request->trailing_credit_submission_status;
      $inventory->trailing_credit_claimed_at = $request->trailing_credit_claimed_at;
      $inventory->trailing_credit_received_at = $request->trailing_credit_received_at;
      $inventory->trailing_credit_amount = $request->trailing_credit_amount;
      $inventory->pre_tc_gross_margin = $request->pre_tc_gross_margin;
      $inventory->post_tc_gross_margin = $request->post_tc_gross_margin;
      $inventory->initial_gross_margin = $request->initial_gross_margin;
      $inventory->program_cost_gross_margin = $request->program_cost_gross_margin;
      $inventory->gross_profit_after_commission_spiff = $request->gross_profit_after_commission_spiff;
      $inventory->final_gross_profit = $request->final_gross_profit;
      $inventory->save();
      return response()->json([
        'data' => $inventory->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Unable to Store Inventory", 'exceptionMessage' => $e->getMessage())
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
      $item = Inventory::where($searchKey, 'like', $searchValue . '%')->orderBy($searchKey, 'desc')
        ->take(10)
        ->get();
      return response()->json([
        'data' => $item->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "No Inventory Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
  public function getAssignable($productId)
  {
    try {
      $item = Inventory::where('fk_products_id', '=', $productId)->whereNull('assigned_to_invoice')->whereNull('fk_workorder_item_id')->get();
      return response()->json([
        'data' => $item->toArray()
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Inventory not found for productId: $productId", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
  public function getAssigned($productId)
  {
    try {
      $item = Inventory::where('fk_products_id', '=', $productId)->where('assigned_to_invoice', '!=', '')->orWhere('fk_workorder_item_id', '!=', '')->get();
      return response()->json([
        'data' => $item->toArray()
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Unable to find unassigned Inventory  for productId: $productId", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
  public function releaseInventory($inventoryId, $workOrderId, $workOrderItemId)
  {
    // remove
    try {
      $item = Inventory::where('id', '=', $inventoryId)->first();
      $item->release();
      $line = WorkOrderItem::where('id', '=', $workOrderItemId)->get();
      return response()->json([
        'data' => $item->toArray()
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Unable to release inventory item id: $inventoryId", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
  /*
   *
   * assignInventory should assign the inventory item that is in inventory to this line
   */
  public function assignInventory($inventoryId, $workOrderId, $workOrderItemId)
  {
    try {
      $item = Inventory::where('id', '=', $inventoryId)->get();
      $item[0]->assign($workOrderId, $workOrderItemId);
      $line = WorkOrderItem::where('id', '=', $workOrderItemId)->get();
      //$line[0]->assign($inventoryId);
      $result = array();
      $result["inventory"] = $line[0]->toArray();
      $result["workorderItem"] = $item[0]->toArray();
      return response()->json([
        'data' => $result
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Unable to assign inventory to item id: $inventoryId", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
  public function getAvailableInventory(Request $request)
  {
    /*
     * Returns Inventory Data Structure with available Inventory
     *
     *
     *
     *
     */
    $inventoryData = array();
    $productIds = json_decode($request->input('productIds'));
    foreach ($productIds as $productId) {
      $inventoryData[$productId] =  $this->getInventoryDetail($productId);
    }
    return response()->json([
      'data' => $inventoryData->toArray()
    ], 200);
  }
  public function getInventoryDetail($productId)
  {
    try {
      $inventoryItems = Inventory::where('fk_products_id', '=', $productId)->whereNull('assigned_to_invoice')->whereNull('fk_workorder_item_id')->get();
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Inventory not found for productId: $productId", 'exceptionMessage' => $e->getMessage())
      ], 200);
    }
    $inventoryDetail = array();
    foreach ($inventoryItems as $item) {
      $inventoryDetail['available'][$item->fk_location_id][$item->current_condition][] = $item;
    }
    $committedQty = WorkOrderItemController::getCommitted($productId);
    $inventoryDetail['committed'] = $committedQty;
    return $inventoryDetail;
  }
  public function getInventoryList($productId)
  {
    try {
      $inventoryItems = Inventory::where('fk_products_id', '=', $productId)->whereNull('assigned_to_invoice')->whereNull('fk_workorder_item_id')->get();
      $inventoryDetail = array();
      foreach ($inventoryItems as $item) {
        $inventoryDetail[] = $item->toArray();
      }
      return response()->json([
        'data' => $inventoryDetail
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Inventory not found for productId: $productId", 'exceptionMessage' => $e->getMessage())
      ], 200);
    }
  }
  // public function scanAssignInventory($upc, $serials, $workOrderId, $workOrderItemId, $productId){
  public function scanAssignInventory(Request $request)
  {
    $items = array();
    try {
      foreach ($request->serials as $serialNumber) {
        $item = Inventory::where('upc', '=', $request->upc)->where('fk_products_id', '=', $request->productId)->where('serial_number', '=', $serialNumber)->first();
        $item->assign($request->workOrderId, $request->workOrderItemId);
        $items[] = $item->toArray();
      }
      return response()->json([
        'data' => $items
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Invalid Serial Number Scaned", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
  public function scanInventoryReceive($upc, $serial, $location, $condition, $poLineItemId)
  {
    /*
     *
     */
    try {
      // first check to make sure we have not already received this item before
      $upcSerialKey = $upc . ':-:' . $serial;
      $item = Inventory::where('key_upc_serial', '=', $upcSerialKey)->get();
      if (sizeof($item) > 0) {
        throw new \Exception("Item already in inventory");
      }
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Already Received : $upc : $serial", 'exceptionMessage' => $e->getMessage())
      ], 400);
    }
    // now look up poline item
    //        try {
    //            $poLineItemDetail = PurchaseOrderItem::where('id', '=', $poLineItemId)->get();
    //            if(sizeof($poLineItemDetail) == 0){
    //                throw new \Exception("No Po Line Item Found = for $poLineItemId");
    //            }
    //        }
    //        catch(\Exception $e)
    //        {
    //            return response()->json([
    //                'data' => array('error'=> "Cannot Find Matching Po Line : $poLineItemId ", 'exceptionMessage' => $e->getMessage() )
    //            ], 404);
    //        }
    // now look up product in Products DB
    try {
      //$product = Product::where('id', '=', $poLineItemDetail->products_id)->with('brand', 'brands_id')->get();
      $product = Product::where('upc', '=', $upc)->get();
      if (sizeof($product) == 0) {
        throw new \Exception("No Product Found = $upc in products DB");
      }
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Cannot Find Matching Model products_id : $upc ", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
    //
    //
    // continue on with insert into inventory table we have everything we need
    try {
      $upcSerialKey = $upc . ':-:' . $serial;
      $inventoryItem = new Inventory();
      $inventoryItem->key_upc_serial = $upcSerialKey;
      $inventoryItem->fk_products_id = $product[0]->id;
      $inventoryItem->model_number =  $product[0]->model_number; //$poLineItemDetail->product_model;
      $inventoryItem->fk_brand_id = $product[0]->fk_brand_id;
      $inventoryItem->fk_category_id = $product[0]->fk_category_id;
      $inventoryItem->part_number = $product[0]->part_number;
      $inventoryItem->upc = $upc;
      $inventoryItem->box_code = $product[0]->upc;
      $inventoryItem->model_bar_code = $product[0]->model_number;
      $inventoryItem->ean = $product[0]->upc;
      $inventoryItem->asin = $product[0]->upc;
      $inventoryItem->serial_tracked = 1;
      $inventoryItem->serial_number = $serial;
      $inventoryItem->initial_purchase_condition = $condition;
      $inventoryItem->current_condition = $condition;
      $inventoryItem->selling_status = "AVAILABLE";
      $inventoryItem->fk_vendor_id = $poLineItemId;
      $inventoryItem->fk_purchase_order_id = $request->purchaseOrderId;
      $inventoryItem->ordered_at = "2017-07-12 14:19:39"; //$poLineItemDetail->created_at;
      $inventoryItem->received_by = "Test";
      $inventoryItem->received_at = "2017-07-12 14:19:39";
      $inventoryItem->invoice_cost = 25.00;
      $inventoryItem->program_cost = 15.00;
      $inventoryItem->purchase_shipping_cost = 1;
      $inventoryItem->mdf = 0; //$poLineItemDetail->mdf_amount;
      $inventoryItem->vir = 0; //$poLineItemDetail->vir_amount;
      $inventoryItem->fk_location_id = $location;
      $inventoryItem->current_condition_notes = "";
      $inventoryItem->save();
      $result["inventory"] = $inventoryItem->toArray();
      return response()->json([
        'data' => $result
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Error Inserting : $upc : $serial", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
  public function scanInventoryTransfer($locationId, $upc, $serial)
  {
    //return $locationId . $upc . $serial ;
    try {
      $inventoryItem = Inventory::where('upc', '=', $upc)->where('serial_number', '=', $serial)->get();
      //print_r($inventoryItem);
      $inventoryItem[0]->location_id = $locationId;
      $inventoryItem[0]->save();
      $result["inventory_item"] = $inventoryItem->toArray();
      return response()->json([
        'data' => $result
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Error Transfering Item : ", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
  public function scanSetup($products_id,  $upc, $model_bar_code = "")
  {
    try {
      $product = Product::find($products_id);
      $product->model_code = $model_bar_code;
      $product->upc = $upc;
      $product->scan_setup = true;
      $product->save();
      $result["product"] = $product->toArray();
      $existingPurchaseOrderItems = PurchaseOrderItem::where('products_id', '=', $products_id)->get();
      // need to update lines that are already in the system
      if (sizeof($existingPurchaseOrderItems)) {
        foreach ($existingPurchaseOrderItems as $lineItem) {
          $lineItem->scan_setup = true;
          $lineItem->save();
        }
      }
      return response()->json([
        'data' => $result
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Error Seting up Scan or Updating PurchaseOrderItems : ", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
}

