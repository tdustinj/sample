<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxExemptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_exemptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_account_id'); // id of account holder
            $table->integer('fk_state_state_code'); // state_code i.e. AZ
            $table->integer('fk_user_id'); // user that created and filed cert
            $table->string('exemption_code');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
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
        Schema::dropIfExists('tax_exemptions');
    }
}
