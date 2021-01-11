<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            // string primary key
            $table->string('state_code')
                ->unique()
                ->primary()
                ->nullable(false)
                ->comment('String primary key for this table (succinct and descriptive, should be lowercase letters and underscores only)');

            $table->string('state_name')
                ->nullable(false)
                ->comment('The name of the state.');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
}
