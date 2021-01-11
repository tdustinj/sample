<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToWorkOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder', function (Blueprint $table) {
            $table->integer('sold_contact_id');
            $table->integer('ship_contact_id');
            $table->integer('bill_contact_id');
            $table->integer('sold_account_id');
            $table->string('workorder_type');
            $table->string('workorder_class');
            $table->string('workorder_status');
            $table->string('requirement_status');
            $table->integer('quote_id');
            $table->integer('invoice_id');

            $table->integer('primary_sales_id');
            $table->integer('second_sales_id');
            $table->integer('third_sales_id');

            $table->decimal('product_total', 10,2);
            $table->decimal('labor_total', 10,2);
            $table->decimal('shipping_total', 10,2);
            $table->decimal('tax_total', 10,2);
            $table->decimal('total', 10,2);
            $table->string('notes');
            $table->string('lead_source');
            $table->string('primary_development'); // on site , phone, store
            $table->string('primary_product_interest');
            $table->string('primary_feature_interest');
            $table->string('demo_affinity');


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
            //
        });
    }
}
