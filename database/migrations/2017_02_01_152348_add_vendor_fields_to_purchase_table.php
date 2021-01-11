<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorFieldsToPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase', function (Blueprint $table) {
            $table->string('purchase_order_type');
            $table->string('purchase_order_status');
            $table->integer('vendor_id');
            $table->string('vendor_name');
            $table->string('submission_status');
            $table->dateTime('submitted_on');
            $table->string('payment_method');
            $table->string('created_by');
            $table->string('last_received_by');
            $table->string('receiving_status');
            $table->string('payment_terms_schema');
            $table->string('payment_discount_schema');
            $table->string('payment_authorized');
            $table->string('payment_authorization_code');
            $table->dateTime('payment_authorized_on');
            $table->string('payment_invoice_number');
            $table->decimal('payment_amount_authorized', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('total_received', 10, 2);
            $table->decimal('balance_due', 10, 2);
            $table->integer('shipping_location_contact_id');
            $table->string('shipping_location_attn');
            $table->string('shipping_requirements');







        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase', function (Blueprint $table) {
            //
        });
    }
}
