<?php
namespace App\Repositories\Purchase;

//use stdClass;
use App\Repositories\Purchase\PurchaseRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Vendor;

class PurchaseRepository implements PurchaseRepositoryContract
{
    public function getPurchaseById($id): object
    {
        return Purchase::findOrFail($id);
    }

    public function getRecent($limit = 500): Collection
    {
        return Purchase::orderBy('submitted_on', 'desc')->limit($limit)->get();
    }

    public function createFromRequest(Request $request): object
    {
        $vendor = Vendor::findOrFail($request->vendorId);
        $fields = [
            'purchase_order_type' => $request->purchaseOrderType,
            'purchase_order_status' => 'NEW',
            'vendor_id' => $request->vendorId,
            'vendor_name' => $vendor['company_name'], //companyName
            'submission_status' => 'NOT_SUBMITTED',
            'submitted_on' => '1999-12-31 01:01:01',
            'payment_method' => $vendor['flooring_company'], //flooringCompany
            'created_by' => $request->createdBy,
            'last_received_by' => '1999-12-31 01:01:01',
            'receiving_status' => '',
            'payment_terms_schema' => '60 Days',
            'payment_discount_schema' => '15:.02',
            'payment_authorized' => '',
            'payment_authorization_code' => '',
            'payment_authorized_on' => '1999-12-31 01:01:01',
            'payment_invoice_number' => '',
            'payment_amount_authorized' => 0.00,
            'notes' => "",
            'total' => 0.00,
            'total_received' => 0.00,
            'balance_due' => 0.00,
            'shipping_location_contact_id' => 1,
            'shipping_location_attn' => 'RECEIVING',
            'shipping_requirements' => $request->shippingRequirements,
        ];

        return Purchase::create($fields);
    }
    public function getFull(int $id): object
    {
        $po = $this->getPurchaseById($id);
        $fullData = (object)[
            "PO" => $po,
            "PO_LINES" => $po->items
        ];
        return $fullData;
    }
    public function updateFromRequest(Request $request, int $id): object
    {
        $purchaseOrder = Purchase::findOrFail($id);
        $purchaseOrder->purchase_order_type = $request->purchase_order_type;
        //$purchaseOrder->purchase_order_status = 'NEW' ;
        $purchaseOrder->vendor_id = $request->vendor_id;
        $purchaseOrder->vendor_name = $request->vendor_name;
        $purchaseOrder->submission_status = $request->submission_status;
        $purchaseOrder->submitted_on = $request->submitted_on;
        $purchaseOrder->payment_method = $request->payment_method;
        // $purchaseOrder->created_by = $request->createdBy ;
        // $purchaseOrder->last_received_by = $request-> ;
        //$purchaseOrder->receiving_status = '' ;
        $purchaseOrder->payment_terms_schema = $request->payment_terms_schema;
        $purchaseOrder->payment_discount_schema = $request->payment_discount_schema;
        $purchaseOrder->payment_authorized = $request->payment_authorized;
        $purchaseOrder->notes = $request->notes;
        $purchaseOrder->payment_authorization_code = $request->payment_authorization_code;
        $purchaseOrder->payment_authorized_on = $request->payment_authorized_on;
        $purchaseOrder->payment_invoice_number = $request->payment_invoice_number;
        $purchaseOrder->payment_amount_authorized = $request->payment_amount_authorized;

        $purchaseOrder->shipping_location_contact_id = $request->shipping_location_contact_id;
        $purchaseOrder->shipping_location_attn = $request->shipping_location_attn;
        $purchaseOrder->shipping_requirements = $request->shipping_requirements;
        $purchaseOrder->updateTotals();
        $purchaseOrder->save();
        return $purchaseOrder;
    }

    public function submit(int $id): object
    {
        $today = date("Y-m-d H:i:s");
        $purchaseOrder = Purchase::findOrFail($id);
        $purchaseOrder->submission_status = "SUBMITTED";
        $purchaseOrder->submitted_on = $today;
        $purchaseOrder->save();
        return $purchaseOrder;
    }

    public function receive(Request $request, int $id): object
    {
        $purchaseOrder = Purchase::findOrFail($id);
        $purchaseOrder->last_received_by = $request->receivedBy;
        $purchaseOrder->receiving_status = 'RECEIVING';
        $purchaseOrder->save();
        return $purchaseOrder;
    }

    public function finish(int $id): object
    {
        $purchaseOrder = Purchase::findOrFail($id);
        $purchaseOrder->purchase_order_status = 'FINISHED';
        $purchaseOrder->save();
        return $purchaseOrder;
    }
}
