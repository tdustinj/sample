<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropVendorFieldsFromFulfillmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfillment', function (Blueprint $table) {
            //
            // $table->dropColumn([
            //     'vendor_id',
            //     'vendor_name',
            //     'vendor_type',
            //     'vendor_order_id',
            //     'vendor_order_line_id',
            //     'vendor_product_cost',
            //     'vendor_shipping_cost',
            //     'vendor_fulfillment_fee',
            //     'vendor_tax_amount',
            //     'vendor_shipping_company',
            //     'vendor_shipping_service_code',
            //     'vendor_tracking_number',
            //     'vendor_shipping_date',
            //     'vendor_promise_shipping_date',
            //     'vendor_shipping_status',
            //     'vendor_promise_delivery_date',
            //     'shipped',
            //     'workorder_id',
            //     'workorder_item_id',
            //     'part_number',
            //     'product_id',
            //     'invoice_id',
            //     'invoice_item_id',
            //     'purchase_order_number',
            //     'purchase_order_line_id',
            // ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fulfillment', function (Blueprint $table) {
            //
        });
    }
}
