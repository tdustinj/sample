<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {

            $table->string('upc');
            $table->string('description');
            $table->string('category');
            $table->string('brand');
            $table->string('manufacturer');
            $table->string('model');
            $table->string('part_number');
            $table->string('serial_number');
            $table->string('marketing_class'); // Standard | MAP | UPP | EMBARGO | RESTRICTED

            $table->integer('qty_on_hand');
            $table->integer('qty_on_order');
            $table->integer('qty_committed');
            $table->integer('qty_on_quoted');
            $table->string('item_class');
            $table->string('item_type');

            $table->string('stock_class'); // STOCK | STOCK DISPLAY | DISPLAY | SPECIAL ORDER | DISPLAY SPECIAL ORDER
            $table->string('box_qty');



            $table->string('external_data_source'); // DATA_SOURCE // will require a settings entry for app boot to provide a list in views
            $table->string('external_data_model');
            $table->string('external_data_source_id'); // Record ID
            $table->string('external_data_source_update_status'); // contains data soruce updated_at

            $table->string('status'); // ACTIVE | DISCONTINUED | BUILDING | PRE-SELL

            $table->decimal('current_cost', 10,2);
            $table->decimal('current_rebate_credit', 10,2);
            $table->decimal('current_adj_cost', 10,2);
            $table->decimal('current_map', 10,2);
            $table->decimal('current_rebate', 10,2);
            $table->decimal('current_adj_map', 10,2);
            $table->decimal('msrp', 10,2);
            $table->decimal('map', 10,2);
            $table->decimal('minimum_price', 10,2);


            $table->decimal('spiff', 10,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            //
        });
    }
}
