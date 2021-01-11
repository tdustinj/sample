<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = "purchase";
    protected $fillable = [
        "purchase_order_type",
        "purchase_order_status",
        "vendor_id",
        "vendor_name",
        "submission_status",
        "submitted_on",
        "payment_method",
        "created_by",
        "last_received_by",
        "receiving_status",
        "payment_terms_schema",
        "payment_discount_schema",
        "payment_authorized",
        "payment_authorization_code",
        "payment_authorized_on",
        "payment_invoice_number",
        "payment_amount_authorized",
        "notes",
        "total",
        "total_received",
        "balance_due",
        "shipping_location_contact_id",
        "shipping_location_attn",
    ];

    public function items()
    {
        return $this->hasMany('App\Models\PurchaseOrderItem', 'purchase_order_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function updateTotals()
    {
        $total = 0.00;
        $totalReceived = 0.00;

        foreach ($this->items as $lineItem) {
            $total += $lineItem->line_total;
            $totalReceived += $lineItem->qty_received * $lineItem->invoice_cost;
        }

        $this->total = $total;
        $this->total_received = $totalReceived;
        $this->balance_due = $total - $totalReceived;
        $this->save();
    }
}
