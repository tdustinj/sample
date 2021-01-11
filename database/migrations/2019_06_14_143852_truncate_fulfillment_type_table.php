<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TruncateFulfillmentTypeTable extends Migration
{
    const oldData = [
        [
            'fulfillment_name' => 'Carry Out',
        ], [
            'fulfillment_name' => 'Local Delivery',
        ], [
            'fulfillment_name' => 'Installation',
        ], [
            'fulfillment_name' => 'Dock FedEx',
        ], [
            'fulfillment_name' => 'Dock FedEx Freight',
        ], [
            'fulfillment_name' => 'Dock UPS',
        ], [
            'fulfillment_name' => 'Dock AIT',
        ], [
            'fulfillment_name' => 'Dock LTL Manual Other',
        ], [
            'fulfillment_name' => 'Dock OnTrac',
        ], [
            'fulfillment_name' => 'UPS',
        ], [
            'fulfillment_name' => 'USPS',
        ], [
            'fulfillment_name' => 'Drop Ship Ingram Micro',
        ], [
            'fulfillment_name' => 'Drop Ship Tech Data',
        ], [
            'fulfillment_name' => 'Amazon Multi Channel Fulfillment',
        ], [
            'fulfillment_name' => 'Amazon FBA',
        ], [
            'fulfillment_name' => 'Drop Ship Almo Distributing',
        ], [
            'fulfillment_name' => 'Drop Ship Manual Other',
        ], [
            'fulfillment_name' => 'Disposed Of Dammaged',
        ]
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('fulfillment_type')->truncate();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $now = now()->toDateTimeString();

        $inserts = array();

        foreach (self::oldData as $data) {
            $inserts[] = [
                'fulfillment_name' => $data['fulfillment_name'],
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        DB::table('fulfillment_type')->insert($inserts);
    }
}
