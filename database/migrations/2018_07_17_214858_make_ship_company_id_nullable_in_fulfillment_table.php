<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeShipCompanyIdNullableInFulfillmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfillment', function (Blueprint $table) {
            $table->integer('ship_company_id')->nullable()->change();
            $table->string('ship_method')->nullable()->change();
            $table->integer('purchase_id')->nullable()->change();
            $table->string('partner')->nullable()->change();
            $table->string('partner_order_number')->nullable()->change();
            $table->string('partner_order_item_number')->nullable()->change();
            $table->string(	'partner_platfrom_shipment_status')->nullable()->change();
            $table->integer('fk_workorder_item_id')->nullable()->change();
            $table->integer('fk_ship_to_contact_id')->nullable()->change();

            $table->integer('total_items_in_fulfillment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
