<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingTaxTotalToWorkorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder', function (Blueprint $table) {
            $table->decimal('shipping_tax_total', 10, 2)
                ->default(0.00)
                ->comment('Total shipping taxes based on individual workorder_items');

            $table->decimal('tax_rate', 10, 4)
                ->default(0.00)
                ->comment('Shipping tax rate received from TaxJar');

            $table->decimal('computed_tax', 10, 2)
                ->default(0.00)
                ->comment('Computed Tax based off of TaxJar Rate and order');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workorder', function (Blueprint $table) {
            //
            $table->dropColumn(['shipping_tax_total', 'tax_rate', 'computed_tax']);
        });
    }
}
