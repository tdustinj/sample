<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Vendor;
use App\Models\PurchaseOrderItem;
use App\Traits;
use App\Mail\VendorPurchaseOrder;
use App\Traits\PDFActions;

class PDFController extends Controller
{
  use PDFActions;

  public function __construct()
  {
    $this->middleware('auth:api');
  }
  
  public function purchaseOrder($id)
  {
    $purchaseOrder = Purchase::findOrFail($id);
    $vendor = Vendor::findOrFail($purchaseOrder->vendor_id);
    $lineItems = PurchaseOrderItem::where('purchase_order_id', '=', $purchaseOrder->id)->get();
    $view = 'pdf.purchaseorder';
    $data = ['purchaseOrder' => $purchaseOrder, 'vendor' => $vendor, 'lineItems' => $lineItems];
    $pdf = $this->createPDF($view, $data);
    return $pdf->download($purchaseOrder->id . '.pdf');
  }
}
