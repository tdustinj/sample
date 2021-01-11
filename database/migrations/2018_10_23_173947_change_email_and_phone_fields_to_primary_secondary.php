<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEmailAndPhoneFieldsToPrimarySecondary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact', function (Blueprint $table) {
            $table->renameColumn('personal_email', 'primary_email');
            $table->renameColumn('work_email', 'secondary_email');
            $table->renameColumn('home_phone', 'secondary_phone');
            $table->renameColumn('work_phone', 'tertiary_phone');
            $table->renameColumn('mobile_phone', 'primary_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact', function (Blueprint $table) {
            $table->renameColumn('primary_email', 'personal_email');
            $table->renameColumn('secondary_email', 'work_email');
            $table->renameColumn('secondary_phone', 'home_phone');
            $table->renameColumn('tertiary_phone', 'work_phone');
            $table->renameColumn('primary_phone', 'mobile_phone');
        });
    }
}




