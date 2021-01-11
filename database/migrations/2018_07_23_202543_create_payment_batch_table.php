<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_batch', function (Blueprint $table) {
            $table->increments('id');
            $table->string('batch_type')->default('ACH_CREDIT_CARD');
            $table->decimal('total', 10,2)->default(0.00);
            $table->decimal('total_discount', 10,2)->default(0.00);
            $table->dateTime('settlement_date')->nullable();
            $table->decimal('total_deposit')->default(0.00);
            $table->dateTime('deposit_date')->nullable();
            $table->boolean('deposit_verified')->default(false);
            $table->string('deposit_account_number')->nullable();
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
        Schema::dropIfExists('payment_batch');
    }
}
