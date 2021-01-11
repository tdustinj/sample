<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedNoteTypesTable extends Migration
{
    const noteTypes = [
        [
            'note_type_code' => 'shipping',
            'note_type' => 'Shipping'
        ],[
            'note_type_code' => 'admin',
            'note_type' => 'Admin'
        ],[
            'note_type_code' => 'log',
            'note_type' => 'Log'
        ],[
            'note_type_code' => 'tracking',
            'note_type' => 'Tracking'
        ],[
            'note_type_code' => 'fyi',
            'note_type' => 'FYI'
        ],[
            'note_type_code' => 'sent_email',
            'note_type' => 'Sent Email'
        ],[
            'note_type_code' => 'rcvd_email',
            'note_type' => 'Received Email'
        ],[
            'note_type_code' => 'rcvd_vm',
            'note_type' => 'Received VoiceMail'
        ],[
            'note_type_code' => 'lt_vm',
            'note_type' => 'Left VoiceMail'
        ],[
            'note_type_code' => 'claim',
            'note_type' => 'Claim'
        ],[
            'note_type_code' => 'acct',
            'note_type' => 'Accounting'
        ],[
            'note_type_code' => 'sk_w_cust',
            'note_type' => 'Spoke W/ Cust'
        ],
        
    ];


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('note_types')->insert(self::noteTypes);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note_types', function (Blueprint $table) {
            //
        });

        $noteTypeCodes = array_map(function($state) {
            return $state['note_type_code'];
        }, self::noteTypes);

        DB::table('note_types')
            ->whereIn('note_type_code', $noteTypeCodes)
            ->delete();

    }
}
