<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToPurchaseOrderItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_item', function (Blueprint $table) {
            $table->decimal('vir_amount', 10,2)->default(0);
            $table->decimal('mdf_amount', 10,2)->default(0);
            $table->decimal('program_amount', 10,2)->default(0);
            $table->decimal('trailing_credit', 10,2)->default(0);
            $table->decimal('spa', 10,2)->default(0);
            $table->decimal('spiff', 10,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_item', function (Blueprint $table) {
            //
        });
    }
}
