<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteLaborLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_labor_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fk_quote_id')->comment("Foreign Key Quote ID.");
            $table->string('sku', 256);
            $table->unsignedInteger('fk_products_id');
            $table->mediumText('description');
            $table->unsignedInteger('fk_technician_id')->comment("foregin Key to users table");
            $table->decimal('rate', 8, 2);
            $table->decimal('hours', 8, 2);
            $table->timestamps();
        });

        Schema::table('quote_labor_lines', function (Blueprint $table) {
            $table->foreign('fk_quote_id')
                ->references('id')
                ->on('quote');
            $table->foreign('fk_products_id')
                ->references('id')
                ->on('product');
            $table->foreign('fk_technician_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_labor_lines', function (Blueprint $table) {
            $table->dropForeign(['fk_products_id']);
            $table->dropForeign(['fk_technician_id']);
            $table->dropForeign(['fk_quote_id']);
        });

        Schema::dropIfExists('quote_labor_lines');
    }
}
