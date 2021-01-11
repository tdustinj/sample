<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('inventory', function (Blueprint $table) {
            $table->increments('id');
            // identification props
            $table->integer('products_id');
            $table->string('model');
            $table->string('brand');
            $table->string('primary_category');
            $table->string('part_number');
            $table->string('upc');
            $table->string('box_code');
            $table->string('model_bar_code');
            $table->string('ean');
            $table->string('asin');
            $table->boolean('serial_tracked');
            $table->string('serial_number');
            $table->string('alternate_serial_number');
            $table->string('bundle_products_id');

            // actual product disposition
            $table->integer('location_id');

            $table->string('initial_purchase_condition');
            $table->string('current_condition');
            $table->string('current_condition_notes');
            $table->string('selling_status');
            $table->string('assigned_to_invoice');
            $table->dateTime('sold_at');
            // rma information
            $table->string('rma_number');
            $table->string('rma_tracking_number');
            $table->string('rma_status');
            $table->decimal('rma_credit_amount_rec', 10, 2);
            $table->dateTime('rma_credit_rec_at');

             // purchased from
            $table->integer('vendor_id');
            $table->integer('purchase_order_id');
            $table->dateTime('ordered_at');
            $table->dateTime('received_at');
            $table->string('received_by');


            // costs associated with purchase
            $table->decimal('invoice_cost', 10, 2);
            $table->decimal('program_cost', 10, 2);
            $table->decimal('billed_amount', 10, 2);
            $table->decimal('purchase_shipping_cost', 10, 2);

             //cost associated with selling/fulfillment
            $table->string('fulfillment_type');
            $table->decimal('fulfillment_cost', 10, 2);
            $table->decimal('commission_paid', 10, 2);
            $table->decimal('spiff_paid', 10, 2);
            $table->decimal('other_costs', 10, 2);


            // backend or upfront discounts to cost of product
            $table->decimal('spa', 10, 2);
            $table->decimal('mdf', 10, 2);
            $table->decimal('vir', 10, 2);
            $table->decimal('payment_discount', 10, 2);
            $table->string('trailing_credit_program');
            $table->string('trailing_credit_program_notes');
            $table->string('trailing_credit_submission_status');
            $table->dateTime('trailing_credit_claimed_at');
            $table->dateTime('trailing_credit_received_at');
            $table->decimal('trailing_credit_amount', 10, 2);

            // computed columns

            $table->decimal('pre_tc_gross_margin', 10, 2);
            $table->decimal('post_tc_gross_margin', 10, 2);
            $table->decimal('initial_gross_margin', 10, 2);
            $table->decimal('program_cost_gross_margin', 10, 2);
            $table->decimal('gross_profit_after_commission_spiff', 10, 2);
            $table->decimal('final_gross_profit', 10, 2);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
