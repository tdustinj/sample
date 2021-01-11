<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkorderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorder_item', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('workorder_id');
            $table->integer('product_id');
            $table->string('employee_id');

            $table->string('model');
            $table->string('part_number');
            $table->string('brand');
            $table->string('description');
            $table->string('upc');

            $table->string('category');
            $table->string('item_class');
            $table->string('item_type');
            $table->string('serial_number');
            $table->string('workorder_date');
            $table->string('source_vendor');
            $table->string('condition');





            $table->string('tax_code');
            $table->decimal('tax_amount', 10,2);
            $table->decimal('ext_price', 10,2);
            $table->decimal('unit_price', 10,2);
            $table->decimal('number_units', 10,2);
            $table->decimal('standard_gross_profit', 10,2);
            $table->decimal('final_gross_profit', 10,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table');
    }
}
