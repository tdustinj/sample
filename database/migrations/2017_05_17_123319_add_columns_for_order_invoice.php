<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsForOrderInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfillment', function (Blueprint $table) {
           $table->integer('vendor_id');
           $table->string('vendor_name');
           $table->string('vendor_type')->default('distributor');
            $table->string('vendor_order_id')->nullable();
            $table->string('vendor_order_line_id')->nullable();
            $table->decimal('vendor_product_cost', 10,2)->default(0.00);
            $table->decimal('vendor_shipping_cost', 10, 2)->default(0.00);
            $table->decimal('vendor_fulfillment_fee', 10, 2)->default(0.00);
            $table->decimal('vendor_tax_amount', 10,2)->default(0.00);
            $table->string('vendor_shipping_company')->nullable();
            $table->string('vendor_shipping_service_code')->nullable();
            $table->string('vendor_tracking_number')->nullable();
            $table->string('vendor_shipping_date')->nullable();
            $table->dateTime('vendor_promise_shipping_date')->nullable();
            $table->string('vendor_shipping_status')->default('NOT_SHIPPED');
            $table->dateTime('vendor_promise_delivery_date')->nullable();

           $table->boolean('shipped')->default(false);
           $table->integer('workorder_id');
           $table->bigInteger('workorder_item_id');
           $table->string('part_number');
           $table->integer('product_id');

            $table->integer('invoice_id')->nullable();
            $table->bigInteger('invoice_item_id')->nullable();

           $table->integer('purchase_order_number')->nullable();
            $table->integer('purchase_order_line_id')->nullable();


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
            $table->dropColumn([
                'vendor_id',
                'vendor_name',
                'vendor_type',
                'vendor_order_id',
                'vendor_order_line_id',
                'vendor_product_cost',
                'vendor_shipping_cost',
                'vendor_fulfillment_fee',
                'vendor_tax_amount',
                'vendor_shipping_company',
                'vendor_shipping_service_code',
                'vendor_tracking_number',
                'vendor_shipping_date',
                'vendor_promise_shipping_date',
                'vendor_shipping_status',
                'vendor_promise_delivery_date',
                'shipped',
                'workorder_id',
                'workorder_item_id',
                'part_number',
                'product_id',
                'invoice_id',
                'invoice_item_id',
                'purchase_order_number',
                'purchase_order_line_id',
            ]);        
        });
    }
}
