<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableFulfillmentTypeAddUniqueColumnFulfillmentCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfillment_type', function (Blueprint $table) {
            
            $table->string('fulfillment_code')
                ->unique()
                ->comment('Unique string code that our application can rely on irrespective of the environment.'
                    . ' This code should be all lowercase letters, with underscores as spaces. It is intended'
                    . ' to be uneditable; whereas fulfillment_name may change over time.')
                ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fulfillment_type', function (Blueprint $table) {
            
            $table->dropColumn(['fulfillment_code']);
        });
    }
}
