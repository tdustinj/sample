<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnsToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->string('alternate_serial_number')->nullable()->change();
            $table->integer('bundle_products_id')->nullable()->change();
            $table->integer('assigned_to_invoice')->nullable()->change();
            $table->dateTime('sold_at')->nullable()->change();
            $table->string('rma_number')->nullable()->change();
            $table->string('rma_tracking_number')->nullable()->change();
            $table->string('rma_status')->nullable()->change();
            $table->decimal('rma_credit_amount_rec')->nullable()->change();
            $table->dateTime('rma_credit_rec_at')->nullable()->change();
            $table->decimal('invoice_cost')->nullable()->change();
            $table->decimal('program_cost')->nullable()->change();
            $table->decimal('billed_amount')->nullable()->change();
            $table->decimal('purchase_shipping_cost')->nullable()->change();
            $table->decimal('fulfillment_type')->nullable()->change();
            $table->decimal('fulfillment_cost')->nullable()->change();
            $table->decimal('commission_paid')->nullable()->change();
            $table->decimal('spiff_paid')->nullable()->change();
            $table->decimal('other_costs')->nullable()->change();
            $table->decimal('spa')->nullable()->change();
            $table->decimal('mdf')->nullable()->change();
            $table->decimal('vir')->nullable()->change();
            $table->decimal('payment_discount')->nullable()->change();

            $table->string('trailing_credit_program')->nullable()->change();
            $table->string('trailing_credit_program_notes')->nullable()->change();
            $table->string('trailing_credit_submission_status')->nullable()->change();

            $table->dateTime('trailing_credit_claimed_at')->nullable()->change();
            $table->dateTime('trailing_credit_received_at')->nullable()->change();

            $table->decimal('trailing_credit_amount')->nullable()->change();
            $table->decimal('pre_tc_gross_margin')->nullable()->change();
            $table->decimal('post_tc_gross_margin')->nullable()->change();
            $table->decimal('initial_gross_margin')->nullable()->change();
            $table->decimal('program_cost_gross_margin')->nullable()->change();
            $table->decimal('gross_profit_after_commission_spiff')->nullable()->change();
            $table->decimal('final_gross_profit')->nullable()->change();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            //
        });
    }
}
