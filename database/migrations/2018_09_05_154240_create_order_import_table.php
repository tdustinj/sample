<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_import', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_manager_id')->nullable();
            $table->boolean('imported')->default(false);
            $table->boolean('import_failed')->default(false);
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
        // Schema::drop('order_manager_id');
        Schema::dropIfExists('order_import');
    }
}
