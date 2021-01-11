<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVariousFieldsToPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment', function (Blueprint $table) {
           $table->integer('fk_workorder_id')->nullable();
            $table->integer('fk_invoice_id')->nullable();
            $table->decimal('amount')->default(0.00);
            $table->integer('fk_user_id')->nullable();
            $table->dateTime('payment_cleared_on')->nullable();

            $table->integer('fk_payment_class_id')->nullable();
            $table->integer('fk_payment_method_id')->nullable();
            $table->integer('fk_payment_terminal_id')->nullable();
            $table->string('fk_payment_batch_id')->nullable();
            $table->string('note')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment', function (Blueprint $table) {
            //
        });
    }
}
