<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleFieldsToFulfillmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfillment', function (Blueprint $table) {
           $table->integer('fk_workorder_item_id');
            $table->integer('fk_workorder_id');

            $table->string('fulfillment_type')->default('Carry Out');
           $table->string('fulfillment_request_status')->default('NEW');
           $table->decimal('fulfillment_cost_total_quote', 10, 2)->default(0.00);
            $table->decimal('fulfillment_cost_total_actual', 10, 2)->default(0.00);
            $table->integer('fk_ship_to_contact_id');
            $table->integer('total_items_in_fulfillment');
            $table->string('master_tracking_id')->nullable();
            $table->string('fulfillment_partner_order_id')->nullable();
            $table->string('notification_email')->nullable();
            $table->dateTime('original_order_date')->nullable();
            $table->dateTime('order_release_date')->nullable();
            $table->dateTime('expected_ship_date')->nullable();
            $table->dateTime('actual_ship_date')->nullable();
            $table->dateTime('expected_delivery_date')->nullable();
            $table->dateTime('fulfillment_partner_expected_delivery_date')->nullable();

            $table->dateTime('actual_delivery_date')->nullable();
            $table->string('tracking_url')->nullable();
            $table->string('partner_fulfillment_request_id')->nullable();
            $table->string('partner_invoice_number')->nullable();

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
