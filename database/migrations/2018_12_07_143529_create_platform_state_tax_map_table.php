<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformStateTaxMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_state_tax_map', function (Blueprint $table) {
            
            $table->string('platform_code')
                ->nullable()
                ->comment('May have no more than one platform_code to map to.');

            $table->string('state_code')
                ->nullable()
                ->comment('May have no more than one state_code to map to.');

            $table->integer('collectAndRemitTax')
                ->nullable()
                ->comment('Whether Business should collect and remit the tax, or the Platform is going to collect and remit the tax');
                
            $table->primary(['platform_code', 'state_code']);
        });



        Schema::table('platform_state_tax_map', function (Blueprint $table) {
            $table->foreign('platform_code')
                ->references('platform_code')
                ->on('platforms');

            $table->foreign('state_code')
                ->references('state_code')
                ->on('states');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platform_state_tax_map');
    }
}
