<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameVendorPurchaseOrderInInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            //
            $table->renameColumn('vendor_id','fk_vendor_id');
            $table->renameColumn('purchase_order_id','fk_purchase_order_id');
            $table->renameColumn('products_id','fk_products_id');
            $table->renameColumn('location_id','fk_location_id');
            $table->renameColumn('workorder_id','fk_workorder_id');
            $table->renameColumn('workorder_item_id','fk_workorder_item_id');
            $table->renameColumn('invoice_item_id','fk_invoice_item_id');
            $table->integer('fk_invoice_id')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            //
        });
    }
}
