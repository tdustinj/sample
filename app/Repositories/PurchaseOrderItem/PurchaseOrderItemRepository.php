<?php

namespace App\Repositories\PurchaseOrderItem;

use App\Models\Product;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;

class PurchaseOrderItemRepository implements PurchaseOrderItemRepositoryContract
{
    public function getRecent($limit): object
    {
        return PurchaseOrderItem::orderBy('created_at', 'desc')->limit($limit)->get();
    }
    public function createFromRequest(Request $request): object
    {
        $product = Product::findOrFail($request->productId);
        $purchaseOrderItem = new PurchaseOrderItem();
        $purchaseOrderItem->products_id  = $product->id;
        $purchaseOrderItem->product_mpn  = $product->part_number;
        $purchaseOrderItem->product_model = $product->model_number;
        $purchaseOrderItem->product_upc = $product->upc;
        $purchaseOrderItem->product_description  = $product->description;
        $purchaseOrderItem->product_cogs_class  = 'PRODUCT';
        $purchaseOrderItem->product_revenue_class = 'PRODUCT';
        $purchaseOrderItem->purchase_order_id   = $request->purchaseOrderId;
        $purchaseOrderItem->scan_setup = $product->scan_setup;
        $purchaseOrderItem->note  = '';
        $purchaseOrderItem->invoice_cost = $request->cost; // look up from wPOS Listing (Listing::where platform = WPOS cost;)
        $purchaseOrderItem->dfi_amount = $product->current_cost - $request->cost;
        $purchaseOrderItem->shipping_cost = 0.00;
        $purchaseOrderItem->adj_cost = $request->cost;
        $purchaseOrderItem->qty_ordered = $request->qty;
        $purchaseOrderItem->qty_received = 0;
        $purchaseOrderItem->qty_refused = 0;
        $purchaseOrderItem->qty_pending = $request->qty;
        $purchaseOrderItem->inventory_ids = '';
        $purchaseOrderItem->vir_amount = 0.00;
        $purchaseOrderItem->mdf_amount = 0.00;
        $purchaseOrderItem->line_total = $request->qty * $request->cost;
        $purchaseOrderItem->save();
        return $purchaseOrderItem;
    }
    public function updateFromRequest(Request $request, int $id): object
    {
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($id);
        $product = Product::findOrFail($purchaseOrderItem->products_id);
        $purchaseOrderItem->note  = $request->note;
        $purchaseOrderItem->invoice_cost = $request->invoice_cost;
        $purchaseOrderItem->dfi_amount = $product->current_cost - $request->cost;
        $purchaseOrderItem->shipping_cost = $request->shipping_cost;
        $purchaseOrderItem->adj_cost = $request->cost + $request->shippingCost;
        $purchaseOrderItem->qty_ordered = $request->qty;
        $purchaseOrderItem->line_total = $request->qty * $request->invoice_cost;

        if (isset($request->qty_received)) {
            $purchaseOrderItem->qty_received = $request->qty_received;
        }

        $purchaseOrderItem->save();
        return $purchaseOrderItem;
    }

    public function getFromRequest(int $id): object
    {
        $po = PurchaseOrderItem::findOrFail($id);
        return $po;
    }

    public function updateOrderTotal(int $id, int $qty): object
    {
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($id);
        $totalReceivedRefused = $purchaseOrderItem->qty_received + $purchaseOrderItem->qty_refused;
        $purchaseOrderItem->qty_pending = $totalReceivedRefused + $qty;
        $purchaseOrderItem->qty_received = $purchaseOrderItem->qty_received + $qty;
        $purchaseOrderItem->save();
        return $purchaseOrderItem;
    }

    public function receiveFromRequest(Request $request, int $qty): object
    {
        $id = $request->id;
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($id);
        $purchaseOrder = Purchase::findOrFail($purchaseOrderItem->purchase_order_id);
        $product = Product::findOrFail($purchaseOrderItem->products_id);
        $serialNumber = ($request->serial_number !== null ? $request->serial_number : random_int(100000000000, 999999999999));
        $result = $this->addInventoryItem($serialNumber, $product, $purchaseOrder, $purchaseOrderItem, $request);

        if ($result) {
            return $this->updateOrderTotal($purchaseOrderItem->id, 1);
        } else {
            throw new \Exception("Error Creating Item in Inventory");
        }
    }

    public function softDeleteFromRequest(int $id): int
    {
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($id);
        // do not want to delete if we have already received of refused
        if ($purchaseOrderItem->qty_refused == 0 and $purchaseOrderItem->qty_received == 0) {
            return PurchaseOrderItem::findOrFail($id)->delete();
        } else {
            return 0;
        }
    }
}
