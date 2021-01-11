<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_item', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('products_id');
            $table->string('product_mpn');
            $table->string('product_model');
            $table->string('product_upc');
            $table->string('product_description');
            $table->string('product_cogs_class');
            $table->string('product_revenue_class');
            $table->integer('purchase_order_id');
            $table->string('note');
            $table->decimal('invoice_cost', 10,2);
            $table->decimal('dfi_amount', 10,2); // adjustment amount
            $table->decimal('shipping_cost', 10,2); // cost for shipping of item to purchaser

            $table->decimal('adj_cost', 10,2); // adj cost including shipping
            $table->integer('qty_ordered');
            $table->integer('qty_received');
            $table->integer('qty_refused'); // number refused
            $table->integer('qty_pending'); // reamining to be received
            $table->string('inventory_ids'); // contains ids of inventory records created, csv seperated


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_item');
    }
}
