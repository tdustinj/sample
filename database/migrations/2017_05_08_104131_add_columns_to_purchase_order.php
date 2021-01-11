<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPurchaseOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase', function (Blueprint $table) {



            $table->string('fulfillment_order_id')->nullable();


            $table->string('flooring_company')->default('None');
            $table->integer('flooring_term_days')->default(30);
            $table->decimal('flooring_upcharge_percent')->default(0);

            $table->string('flooring_approval_status')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase', function (Blueprint $table) {
            //
        });
    }
}
