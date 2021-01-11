<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_types', function (Blueprint $table) {
            $table->string('note_type_code')
                ->unique()
                ->primary()
                ->nullable(false)
                ->comment('String primary key for this table (succinct and descriptive, should be lowercase letters and underscores only)');

            $table->string('note_type')
                ->nullable(false)
                ->comment('The name of the type of note.');
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
        Schema::dropIfExists('note_types');
    }
}
