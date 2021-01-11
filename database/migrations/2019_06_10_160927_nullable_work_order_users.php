<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableWorkOrderUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder_notes', function (Blueprint $table) {
            $table->unsignedInteger('fk_user_id')->nullable()->change();
            $table->unsignedInteger('fk_user_id_recipient')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workorder_notes', function (Blueprint $table) {
            $table->unsignedInteger('fk_user_id')->unsigned(false)->change();
            $table->unsignedInteger('fk_user_id_recipient')->nullable(false)->change();
        });
    }
}
