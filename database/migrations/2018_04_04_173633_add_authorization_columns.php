<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorizationColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('auth_super_user')->default(false);
            $table->boolean('auth_admin')->default(false);
            $table->boolean('auth_operations')->default(false);
            $table->boolean('auth_sales')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['auth_super_user', 'auth_admin', 'auth_sales', 'auth_operations']);
        });
    }
}
