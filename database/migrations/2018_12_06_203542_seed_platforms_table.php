<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedPlatformsTable extends Migration
{
    const platforms = [
        [
            'platform_name' => 'Walts',
            'platform_code' => 'walts'
        ],[
            'platform_name' => 'Amazon',
            'platform_code' => 'amazon'
        ],[
            'platform_name' => 'Walmart',
            'platform_code' => 'walmart'
        ],[
            'platform_name' => 'Sears',
            'platform_code' => 'sears'
        ],[
            'platform_name' => 'Rakuten',
            'platform_code' => 'rakuten'
        ],[
            'platform_name' => 'Newegg',
            'platform_code' => 'newegg'
        ],[
            'platform_name' => 'eBay',
            'platform_code' => 'ebay'
        ],[
            'platform_name' => 'Jet',
            'platform_code' => 'jet'
        ]
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('platforms')->insert(self::platforms);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // create a new array made up of platform_code value of each element of the array
        $platformCodes = array_map(function($platform) {
            return $platform['platform_code'];
        }, self::platforms);

        DB::table('platforms')
            ->whereIn('platform_code', $platformCodes)
            ->delete();
    }
}
