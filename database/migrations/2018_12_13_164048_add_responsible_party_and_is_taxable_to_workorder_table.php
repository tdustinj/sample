<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResponsiblePartyAndIsTaxableToWorkorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workorder', function (Blueprint $table) {
            $table->string('responsible_party')
                ->default('dealer')
                ->comment('Who is responsible to Collect And Remit Tax for this workorder.');

            $table->boolean('is_taxable')
                ->default(false)
                ->comment('Whether or not this workorder is Taxable.');
            $table->dropColumn('taxable');
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
            $table->dropColumn(['responsible_party', 'is_taxable' ]);
            $table->boolean('taxable')->default(true);
        });
    }
}
