<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderManagerIdToWorkorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder', function (Blueprint $table) {
            $table->string('order_manager_id')->nullable();
            $table->foreign('order_manager_id')->references('order_manager_id')->on('order_import');
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
        Schema::table('workorder', function (Blueprint $table) {
            $table->dropForeign(['order_manager_id']);
        });
    }
}
