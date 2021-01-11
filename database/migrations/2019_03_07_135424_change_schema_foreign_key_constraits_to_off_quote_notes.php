<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSchemaForeignKeyConstraitsToOffQuoteNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_notes', function (Blueprint $table) {
            // Schema::disableForeignKeyConstraints();
            $table->dropForeign('quote_notes_fk_note_type_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_notes', function (Blueprint $table) {
            // Schema::enableForeignKeyConstraints();
            $table->foreign('fk_note_type')
                ->references('note_type_code')
                ->on('note_types');
        });
    }
}
