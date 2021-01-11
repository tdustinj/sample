<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platforms', function (Blueprint $table) {
            // string primary key
            $table->string('platform_code')
                ->unique()
                ->primary()
                ->nullable(false)
                ->comment('String primary key for this table (succinct and descriptive, should be lowercase letters and underscores only)');

            $table->string('platform_name')
                ->nullable(false)
                ->comment('The name of the platform, which could change with time.');

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
        Schema::dropIfExists('platforms');
    }
}
