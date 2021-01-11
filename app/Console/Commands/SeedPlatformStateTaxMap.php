<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Platform;
use App\Models\State;
use App\Models\PlatformStateTaxMap;

class SeedPlatformStateTaxMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:seed-platform-state-tax-map';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the PlatformStateTaxMap table based off Platforms and States';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $states = State::all();
        $platforms = Platform::all();
        foreach($states as $state){
            foreach($platforms as $platform){
                $platformStateTaxMap = new PlatformStateTaxMap();
                $platformStateTaxMap->platform_code = $platform->platform_code;
                $platformStateTaxMap->state_code = $state->state_code;
                $platformStateTaxMap->collectAndRemitTax = 0;
                $platformStateTaxMap->save();
            }                
        }
    }
}
