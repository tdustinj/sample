<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefineRelationshipForPrimarySalesId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('quote', function (Blueprint $table) {
        //     $table->unsignedInteger('primary_sales_id')->change();
        //     $table->unsignedInteger('second_sales_id')->change();
        //     $table->unsignedInteger('third_sales_id')->change();
        //     $table->foreign('primary_sales_id')
        //         ->references('id')
        //         ->on('users');
            
        //     $table->foreign('second_sales_id')
        //         ->references('id')
        //         ->on('users');

        //     $table->foreign('third_sales_id')
        //         ->references('id')
        //         ->on('users');
        // });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('quote', function (Blueprint $table) {
        //     $table->dropForeign(['primary_sales_id']);
        //     $table->dropForeign(['second_sales_id']);
        //     $table->dropForeign(['third_sales_id']);
        // });
    }
}
