<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemShippingTaxAndItemShippingTaxRateToWorkorderItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder_item', function (Blueprint $table) {
            $table->decimal('item_shipping_cost', 10, 2)
                ->default(0.00)
                ->comment('Total shipping costs for workorder_item, given by platform.');

            $table->decimal('item_shipping_tax_rate', 10, 4)
                ->default(0.00)
                ->comment('Shipping tax rate received from TaxJar');

            $table->decimal('computed_shipping_tax', 10, 2)
                ->default(0.00)
                ->comment('item_shipping_cost multiplied by the item_shipping_tax_rate');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workorder_item', function (Blueprint $table) {
            //
            $table->dropColumn(['item_shipping_cost', 'item_shipping_tax_rate', 'computed_shipping_tax']);
        });
    }
}
