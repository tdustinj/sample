<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamps();
            $table->string('name');
            $table->string('notes');
            $table->string('tax_code');
            $table->enum('account_type', ['b2c', 'b2b']);
            $table->decimal('credit_line_limit', 5, 2);
            $table->integer('terms_number_days');
            $table->string('terms_payment_type');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account');
    }
}
