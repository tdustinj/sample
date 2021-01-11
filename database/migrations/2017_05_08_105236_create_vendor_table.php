<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('rep');
            $table->string('dealer_number');
            $table->string('order_desk_name')->nullable();
            $table->string('address');
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('country')->default('US');
            $table->string('vendor_type')->default('PRODUCT'); // Product, Service, Fulfillment Partner
            $table->string('default_cogs_account')->default('PRODUCT');
            $table->string('rep_email');
            $table->string('order_desk_email')->nullable();
            $table->string('order_portal_url')->nullable();
            $table->string('rebate_portal_url')->nullable();
            $table->string('spiff_submission_url')->nullable();

            $table->string('rep_phone');
            $table->string('order_desk_phone')->nullable();
            $table->string('fax_phone')->nullable();

            $table->decimal('minimum_order_free_freight', 10,2)->default(0);
            $table->string('fiscal_year_start')->default('01-01');

            $table->string('program_name')->nullable;
            $table->decimal('dfi_program_discount', 10,2)->default(0);
            $table->decimal('vir_percent', 10,2)->default(0);
            $table->decimal('accrued_mdf_percent', 10,2)->default(0);
            $table->decimal('accrued_coop_percent', 10,2)->default(0);


// payment methods and discounts
            $table->string('default_payment_method')->default('Open Account');
            $table->decimal('credit_line', 10,2)->default(0);
            $table->decimal('payment_discount_prepay', 10,2)->default(0);
            $table->decimal('payment_discount_15_day', 10,2)->default(0);
            $table->decimal('payment_discount_30_day', 10,2)->default(0);
            $table->decimal('payment_discount_60_day', 10,2)->default(0);
            $table->decimal('payment_discount_90_day', 10,2)->default(0);
            $table->decimal('payment_discount_120_day', 10,2)->default(0);

            $table->string('flooring_company')->default('Wells Fargo Finance');
            $table->integer('standard_flooring_term_days')->default(30);


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
        Schema::dropIfExists('vendor');
    }
}
