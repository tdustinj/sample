<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkContactsAndOrderDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder', function (Blueprint $table) {
            $table->unsignedInteger('sold_contact_id')->change();
            $table->unsignedInteger('ship_contact_id')->change();
            $table->unsignedInteger('bill_contact_id')->change();
            $table->dateTime('order_placed_date')->nullable()->comment("The time and date the order was placed.");
            $table->foreign('sold_contact_id')
                ->references('id')
                ->on('contact');
            
            $table->foreign('ship_contact_id')
                ->references('id')
                ->on('contact');

            $table->foreign('bill_contact_id')
                ->references('id')
                ->on('contact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workorder', function (Blueprint $table) {
            $table->dropForeign(['sold_contact_id']);
            $table->dropForeign(['ship_contact_id']);
            $table->dropForeign(['bill_contact_id']);
            $table->dropColumn('order_placed_date');
        });
    }
}
