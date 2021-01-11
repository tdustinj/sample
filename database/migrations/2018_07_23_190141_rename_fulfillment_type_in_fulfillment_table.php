<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameFulfillmentTypeInFulfillmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfillment', function (Blueprint $table) {
            //
            $table->renameColumn('fulfillment_type','fk_fulfillment_type_id');

        });
        Schema::table('fulfillment', function (Blueprint $table) {
            //

            $table->integer('fk_fulfillment_type_id')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fulfillment', function (Blueprint $table) {
            //
        });
    }
}
