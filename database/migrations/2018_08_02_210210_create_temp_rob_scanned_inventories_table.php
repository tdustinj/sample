<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempRobScannedInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_rob_scanned_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('upc');
            $table->string('serial');
            $table->integer('fk_products_id')->nullable();
            $table->string('condition')->default('a');
            $table->string('inventory_model')->nullable();
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
        Schema::dropIfExists('temp_rob_scanned_inventories');
    }
}
