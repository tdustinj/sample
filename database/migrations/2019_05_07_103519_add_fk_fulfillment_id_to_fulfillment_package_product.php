<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkFulfillmentIdToFulfillmentPackageProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfillment_package_product', function (Blueprint $table) {
            $table->integer('fk_fulfillment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fulfillment_package_product', function (Blueprint $table) {
            $table->dropColumn('fk_fulfillment_id');
        });
    }
}
