<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BootstrapDatabaseLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:bootstrapLocations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bootstraps your environment\'s database with locations.';

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
        $locations = array(
            'AMAZON_FBA',
            'AMAZON_FBA_INBOUND',
            'AMAZON_FBA_LOST',
            'AMAZON_FBA_DAMAGED',
            'CARVER',
            'RUBY',
            'DISPLAY',
            'OTHER',
            'RETURN',
            'UNKNOWN',

        );

        foreach ($locations as  $key => $locationValue) {
            $location = new \App\Models\Location();
            $location->location_name = $locationValue;
            try {
                $location->save();
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
    }
}
