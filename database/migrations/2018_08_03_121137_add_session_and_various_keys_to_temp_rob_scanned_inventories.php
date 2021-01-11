<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSessionAndVariousKeysToTempRobScannedInventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_rob_scanned_inventories', function (Blueprint $table) {
            //
            $table->string('session_name')->nullable();
            $table->string('session_upc_serial_combo_key')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_rob_scanned_inventories', function (Blueprint $table) {
            //
        });
    }
}
