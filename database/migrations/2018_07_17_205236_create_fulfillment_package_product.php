<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFulfillmentPackageProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fulfillment_package_product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_sku')->nullable();
            $table->integer('fk_product_id');
            $table->integer('fk_fulfillment_package_id');
            $table->integer('fk_workorder_item_id');
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
        Schema::dropIfExists('fulfillment_package_product');
    }
}
