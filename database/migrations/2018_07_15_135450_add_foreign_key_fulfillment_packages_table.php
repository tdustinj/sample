<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyFulfillmentPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     *
     * @return void
     */
    public function up()
    {

        Schema::table('fulfillment_packages', function (Blueprint $table) {
            //
            $table->integer('fulfillment_id')->unsigned()->nullable()->change();

           // $table->foreign('fulfillment_id')->references('id')->on('fulfillment');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fulfillment_packages', function (Blueprint $table) {
            //
        });
    }
}
