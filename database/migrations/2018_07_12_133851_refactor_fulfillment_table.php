<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorFulfillmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfillment', function (Blueprint $table) {

               // $table->foreign('workorder_id')->references('id')->on('workorder');
                $table->string('ship_company_id');
                $table->string('ship_method');
                $table->string('purchase_id');
                //$table->foreign('purchase_id')->references('id')->on('purchase');

                $table->string('partner');
                $table->string('partner_order_number');
                $table->string('partner_order_item_number');
                $table->string('partner_platfrom_shipment_status');






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

        });
    }
}
