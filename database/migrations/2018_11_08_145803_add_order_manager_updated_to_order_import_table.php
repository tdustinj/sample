<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderManagerUpdatedToOrderImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_import', function (Blueprint $table) {
            //
            $table->boolean('order_manager_updated')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_import', function (Blueprint $table) {
            //
            $table->dropColumn(['order_manager_updated']);
        });
    }
}
