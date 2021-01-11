<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BootstrapDatabaseInventoryConditions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:bootstrapInventoryConditions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bootstraps your environment\'s database with inventory conditions.';

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
        $this->info("No inventory conditions have been added yet to " . self::class . ".");
    }
}
