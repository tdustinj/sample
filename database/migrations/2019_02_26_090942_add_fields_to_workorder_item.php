<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToWorkorderItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder_item', function (Blueprint $table) {
            $table->string('ship_method_requested', 256);
            $table->dateTime('expected_delivery_date')->nullable();
            $table->dateTime('expected_ship_date')->nullable();
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
            $table->dropColumn('ship_method_requested');
            $table->dropColumn('expected_delivery_date');
            $table->dropColumn('expected_ship_date');
        });
    }
}
