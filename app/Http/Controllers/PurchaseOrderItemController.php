<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Purchase;
use App\PurchaseOrderItem;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepositoryContract;
use Illuminate\Http\Request, App\Brand, App\Category;
use Exception;

class PurchaseOrderItemController extends Controller
{
    public function __construct(PurchaseOrderItemRepositoryContract $purchaseOrderItemRepository)
    {
        $this->middleware('auth:api');
        $this->purchaseOrderItemRepository = $purchaseOrderItemRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'limit' => 'nullable|int'
        ]);
        $limit = $validated['limit'] ?? 100;
        $purchaseItem = $this->purchaseOrderItemRepository->getRecent($limit);

        return response()->json([
            'data' => $purchaseItem
        ], 200);
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
            $purchaseOrderItem = $this->purchaseOrderItemRepository->createFromRequest($request);
            return response()->json([
                'data' => $purchaseOrderItem
            ], 200);
        } catch (exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Add New Purchase Order Line Item", 'exceptionMessage' => $e->getMessage())
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
            $po = $this->purchaseOrderItemRepository->getFromRequest($id);
            return response()->json([
                'data' => $po->toArray()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "Purchase Order Item not found for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $updatedPurchaseOrderItem = $this->purchaseOrderItemRepository->updateFromRequest($request, $id);
            return response()->json([
                'data' => $updatedPurchaseOrderItem
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => ['error' => 'Cannot update Purchase Order Item' . $e]
            ]);
        }
    }

    public function updateOrderTotal($id, $qty)
    {
        try {
            return $this->purchaseOrderItemRepository->updateOrderTotal($id, $qty);
        } catch (exception $e) {
            return $e;
        }
    }

    public function receive(Request $request)
    {
        try {
            $updatedPurchaseOrderItem = $this->purchaseOrderItemRepository->receiveFromRequest($request);
            return response()->json([
                'data' => $updatedPurchaseOrderItem->toArray()
            ], 200);
        } catch (exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Receive Item", 'exceptionMessage' => $e->getMessage())
            ], 500);
        }
    }


    public function addInventoryItem($serialNumber, $product, $purchaseOrder, $purchaseOrderItem, $request)
    {
        // first attempt to rec item into inventory via InventoryController Method
        $today = date("Y-m-d H:i:s");
        $upcSerialKey = $product->upc . ':-:' . $serialNumber;
        $item = Inventory::where('key_upc_serial', '=', $upcSerialKey)->get();
        if (sizeof($item) > 0) {
            throw new \Exception("Item already in inventory");
        }
        $inventoryItem = new Inventory();
        $inventoryItem->key_upc_serial = $upcSerialKey;
        $inventoryItem->fk_products_id = $purchaseOrderItem->products_id;
        $inventoryItem->model_number = $product->model_number;
        $inventoryItem->fk_brand_id = $product->fk_brand_id;
        $inventoryItem->fk_category_id = $product->fk_category_id;
        $inventoryItem->part_number = $product->part_number;
        $inventoryItem->upc = $product->upc;
        $inventoryItem->box_code = $product->box_code;
        $inventoryItem->model_bar_code = $product->model_bar_code;
        $inventoryItem->ean = $product->ean;
        $inventoryItem->asin = $product->asin;
        $inventoryItem->serial_tracked = $product->scan_setup;
        $inventoryItem->serial_number = $serialNumber;
        $inventoryItem->alternate_serial_number = $serialNumber;
        $inventoryItem->bundle_products_id = $product->bundle_products_id;
        $inventoryItem->fk_location_id = $request->locationId;
        $inventoryItem->initial_purchase_condition = $request->initialPurchaseCondition;
        $inventoryItem->current_condition = $request->initialPurchaseCondition;
        $inventoryItem->current_condition_notes = "";
        $inventoryItem->selling_status = 'AVAILABLE';
        $inventoryItem->assigned_to_invoice = null;
        $inventoryItem->sold_at = null;
        $inventoryItem->rma_number = null;
        $inventoryItem->rma_tracking_number = null;
        $inventoryItem->rma_status = null;
        $inventoryItem->rma_credit_amount_rec = null;
        $inventoryItem->rma_credit_rec_at = null;
        $inventoryItem->fk_vendor_id = $purchaseOrder->vendor_id;
        $inventoryItem->fk_purchase_order_id = $purchaseOrder->id;
        $inventoryItem->ordered_at = $purchaseOrderItem->created_at;
        $inventoryItem->received_at = $today;
        $inventoryItem->received_by = $request->userId;
        $inventoryItem->invoice_cost = $purchaseOrderItem->invoice_cost;
        $inventoryItem->program_cost = $purchaseOrderItem->adj_cost;
        $inventoryItem->billed_amount = $purchaseOrderItem->adj_cost;
        $inventoryItem->purchase_shipping_cost = $purchaseOrderItem->shipping_cost;
        $inventoryItem->fulfillment_type = '';
        $inventoryItem->fulfillment_cost = 0.00;
        $inventoryItem->commission_paid = 0.00;
        $inventoryItem->spiff_paid = 0.00;
        $inventoryItem->other_costs = 0.00;
        $inventoryItem->spa = 0.00;
        $inventoryItem->mdf = 0.00;
        $inventoryItem->vir = 0.00;
        $inventoryItem->payment_discount = 0.00;
        $inventoryItem->trailing_credit_program = '';
        $inventoryItem->trailing_credit_program_notes = '';
        $inventoryItem->trailing_credit_submission_status = '';
        $inventoryItem->trailing_credit_claimed_at = null;
        $inventoryItem->trailing_credit_received_at = null;
        $inventoryItem->trailing_credit_amount = 0.00;
        $inventoryItem->pre_tc_gross_margin = 0.00;
        $inventoryItem->post_tc_gross_margin = 0.00;
        $inventoryItem->initial_gross_margin = 0.00;
        $inventoryItem->program_cost_gross_margin = 0.00;
        $inventoryItem->gross_profit_after_commission_spiff = 0.00;
        $inventoryItem->final_gross_profit = 0.00;
        $result = $inventoryItem->save();
        return $result;
    }

    public function refuse(Request $request, $id)
    {
        try {
            $purchaseOrderItem = Purchase::findOrFail($id);
            $totalReceivedRefused = $purchaseOrderItem->qty_received + $purchaseOrderItem->qty_refused;
            $purchaseOrderItem->qty_pending = $totalReceivedRefused + $request->qtyRefused;
            $purchaseOrderItem->qty_refused = $purchaseOrderItem->qty_refused + $request->qtyRefused;
            return response()->json([
                'data' => $purchaseOrderItem->toArray()
            ], 200);
        } catch (exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Update Purchase Order", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function blkReceive()
    { }

    public function search(Request $request)
    {
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        //print_r($searchKey);
        try {
            switch ($searchKey) {
                case 'brand':
                    $brand = Brand::where('brand_name', '=', $searchValue)->get();
                    $searchValue = $brand[0]['id'];
                    //print_r("hit brand");
                    break;
                case 'category':
                    $category = Category::where('category_name', '=', $searchValue)->get();
                    $searchValue = $category[0]['id'];
                    //print_r("hit category");
                    break;
                case 'model':
                    $model = PurchaseOrderItem::where('model', 'like', $searchValue . '%')->get();
                    $searchValue = $model[0]['id'];

                    break;
                case 'mpn':
                    $mpn = PurchaseOrderItem::where('mpn', 'like', $searchValue . '%')->get();
                    $searchValue = $mpn[0]['id'];

                    break;
                default:
                    $model = PurchaseOrderItem::where('model', 'like', $searchValue . '%')->get();
                    $searchValue = $model[0]['id'];
            }




            $purchaseOrderItem = PurchaseOrderItem::where($searchKey, 'like', $searchValue . '%')->orderBy($searchKey, 'desc')
                ->take(30)
                ->get();
            return response()->json([
                'data' => $purchaseOrderItem->toArray()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => array('error' => "No Purchase Items Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $purchaseOrderItemMightBeDeleted = $this->purchaseOrderItemRepository->softDeleteFromRequest($id);
            if ($purchaseOrderItemMightBeDeleted === 1) {
                return response()->json([
                    'data' => array('Deleted' => "$id")
                ], 200);
            } else {
                throw new Exception("Products have already been received or refused on this line");
            }
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to delete Item ", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }
}
