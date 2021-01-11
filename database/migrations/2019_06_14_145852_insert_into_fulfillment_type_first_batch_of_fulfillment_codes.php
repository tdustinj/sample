<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertIntoFulfillmentTypeFirstBatchOfFulfillmentCodes extends Migration
{
    const inserts = [
        [
            'fulfillment_code' => 'carry_out',
            'fulfillment_name' => 'Carry Out',
        ], [
            'fulfillment_code' => 'local_delivery',
            'fulfillment_name' => 'Local Delivery',
        ], [
            'fulfillment_code' => 'installation',
            'fulfillment_name' => 'Installation',
        ], [
            'fulfillment_code' => 'dock_fedex',
            'fulfillment_name' => 'Dock FedEx',
        ], [
            'fulfillment_code' => 'dock_fedex_freight',
            'fulfillment_name' => 'Dock FedEx Freight',
        ], [
            'fulfillment_code' => 'dock_ups',
            'fulfillment_name' => 'Dock UPS',
        ], [
            'fulfillment_code' => 'dock_ait',
            'fulfillment_name' => 'Dock AIT',
        ], [
            'fulfillment_code' => 'dock_ltl_manual_other',
            'fulfillment_name' => 'Dock LTL Manual Other',
        ], [
            'fulfillment_code' => 'dock_ontrac',
            'fulfillment_name' => 'Dock OnTrac',
        ], [
            'fulfillment_code' => 'ups',
            'fulfillment_name' => 'UPS',
        ], [
            'fulfillment_code' => 'usps',
            'fulfillment_name' => 'USPS',
        ], [
            'fulfillment_code' => 'drop_ship_ingram_micro',
            'fulfillment_name' => 'Drop Ship Ingram Micro',
        ], [
            'fulfillment_code' => 'drop_ship_tech_data',
            'fulfillment_name' => 'Drop Ship Tech Data',
        ], [
            'fulfillment_code' => 'amazon_multi_channel',
            'fulfillment_name' => 'Amazon Multi Channel',
        ], [
            'fulfillment_code' => 'amazon_fba',
            'fulfillment_name' => 'Amazon FBA',
        ], [
            'fulfillment_code' => 'drop_ship_almo_distributing',
            'fulfillment_name' => 'Drop Ship Almo Distributing',
        ], [
            'fulfillment_code' => 'drop_ship_manual_other',
            'fulfillment_name' => 'Drop Ship Manual Other',
        ], [
            'fulfillment_code' => 'disposed_of_damaged',
            'fulfillment_name' => 'Disposed Of Damaged',
        ]
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = now()->toDateTimeString();

        $inserts = array();

        foreach (self::inserts as $data) {
            $inserts[] =[
                'fulfillment_code' => $data['fulfillment_code'],
                'fulfillment_name' => $data['fulfillment_name'],
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        DB::table('fulfillment_type')->insert($inserts);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('fulfillment_type')->truncate();
    }
}
