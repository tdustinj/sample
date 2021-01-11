<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMoreColumnsNullableWorkorder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder', function (Blueprint $table) {
            //

            $table->string('notes')->nullable()->change();
            $table->string('lead_source')->nullable()->change();
            $table->string('primary_development')->nullable()->change(); // on site , phone, store
            $table->string('primary_product_interest')->nullable()->change();
            $table->string('primary_feature_interest')->nullable()->change();
            $table->string('demo_affinity')->nullable()->change();
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
