<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeWorkorderTypeAndWorkorderClassAndWorkorderStatusToOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder', function (Blueprint $table) {
            $table->renameColumn('workorder_class', 'order_class');
            $table->renameColumn('workorder_type', 'order_type');
            $table->renameColumn('workorder_status', 'status');
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
            //
            $table->renameColumn('order_class', 'workorder_class');
            $table->renameColumn('order_type', 'workorder_type');
            $table->renameColumn('status', 'workorder_status');
        });
    }
}
