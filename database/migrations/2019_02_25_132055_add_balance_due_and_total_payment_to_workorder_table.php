<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBalanceDueAndTotalPaymentToWorkorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder', function (Blueprint $table) {
            $table->decimal('balance_due', 10, 2)
                ->default(0.00)
                ->comment('Balance Remaing to be paid on the workorder.');
            
            $table->decimal('total_payment', 10, 2)
                ->default(0.00)
                ->comment('Total Amount of Payments made towards this workorder.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workorder', function (Blueprint $table) {
            $table->dropColumn(['balance_due', 'total_payment']);
        });
    }
}
