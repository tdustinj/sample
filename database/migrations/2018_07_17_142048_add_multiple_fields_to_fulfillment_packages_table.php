<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleFieldsToFulfillmentPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfillment_packages', function (Blueprint $table) {
            $table->integer('fk_fulfillment_id');

            $table->string('partner_sku')->nullable();
            $table->integer('fk_product_id');

            $table->decimal('package_length', 10,2)->nullable();
            $table->decimal('package_width', 10,2)->nullable();
            $table->decimal('package_height', 10,2)->nullable();
            $table->decimal('package_weight', 10,2)->nullable();
            $table->string('tracking_number')->nullable();
            $table->decimal('shipping_quote', 10,2)->nullable();
            $table->decimal('shipping_actual', 10,2)->nullable();
            $table->integer('partner_order_line_number')->nullable();




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fulfillment_packages', function (Blueprint $table) {
            //
        });
    }
}
