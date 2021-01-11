<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeQuoteTypeAndQuoteClassToOrderTypeAndOrderClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote', function (Blueprint $table) {
            $table->unsignedInteger('sold_contact_id')->change();
            $table->unsignedInteger('ship_contact_id')->change();
            $table->unsignedInteger('bill_contact_id')->change();
            $table->renameColumn('quote_class', 'order_class');
            $table->renameColumn('quote_type', 'order_type');
            $table->renameColumn('quote_status', 'status');
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
        Schema::table('quote', function (Blueprint $table) {
            //

            $table->renameColumn('order_class', 'quote_class');
            $table->renameColumn('order_type', 'quote_type');
            $table->renameColumn('status', 'quote_status');

            $table->dropForeign(['sold_contact_id']);
            $table->dropForeign(['ship_contact_id']);
            $table->dropForeign(['bill_contact_id']);
        });
    }
}
