<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkorderNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorder_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fk_workorder_id')->comment('Workorder ID for this note.');
            $table->unsignedInteger('fk_user_id')->comment('User ID for this note.'); 
            $table->text('message')->comment('Message added to this workorder.'); 
            $table->string('fk_note_type')->comment('Foreign Key for the type of note this is.');
            $table->unsignedInteger('fk_user_id_recipient')->nullable()->comment("Foreign Key of user_id for message to notify.");
            $table->timestamps();
        });

        Schema::table('workorder_notes', function (Blueprint $table) {
            $table->foreign('fk_workorder_id')
                ->references('id')
                ->on('workorder');

            $table->foreign('fk_user_id')
                ->references('id')
                ->on('users');
            
            $table->foreign('fk_user_id_recipient')
                ->references('id')
                ->on('users');
            
            $table->foreign('fk_note_type')
                ->references('note_type_code')
                ->on('note_types');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workorder_notes');
    }
}
