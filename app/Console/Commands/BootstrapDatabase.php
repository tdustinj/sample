<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use \Exception;

class BootstrapDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:bootstrapDatabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bootstraps ospos database with prod data needed for all environments.'
                            . ' (E.g. importActiveBrands etc.)';

    public $bootstrapDatabaseCommands = [
        'command:importActiveBrands',
        'command:importActiveCategories',
        'command:importActiveProducts',
        'command:bootstrapInventoryConditions',
        'command:bootstrapLocations',
        'command:bootstrapOrderTypes',
        // 'command:importLaborLinesToQuoteItem', // doesn't work (invalid column name in a field)
        // 'command:importInvoicesAsQuotes', // doesn't work (invalid column name in a field)
        // 'command:importCustomerstoContacts', // doesn't work (invalid column name in a field)
        // 'command:importDetailLinesToQuoteItem', // doesn't work (invalid column name in a field)
        // 'command:importInventory', // works, but do we really need the full real-time inventory? maybe just build a seeder?
        // 'command:getAvailableOrdersFromOrderManager' // can we just build a seeder instead?
    ];

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
        $failedCommands = array();

        $listOfCommandsAsString = implode("\n\t", $this->bootstrapDatabaseCommands);

        $this->info("\nThe following commands will be exectuted:");
        $this->comment("\n\t" . $listOfCommandsAsString . "\n");

        $proceed = $this->confirm("Proceed to execute the above commands?");

        if (!$proceed) {
            $this->line("Exiting.");
            return;
        }

        foreach ($this->bootstrapDatabaseCommands as $command) {
            
            try {
                $this->comment("Executing 'php artisan $command'...");
                Artisan::call($command);
                $this->info("Command executed successfully!");
            }
            catch (Exception $ex) {

                $this->error("Failed: An exception occurred during execution of '$command'.");
                $this->line($ex->getMessage());
                $this->line($ex->getTraceAsString());

                $failedCommands[] = $command;
            }
        }

        $this->comment("All commands have finished executing.");

        if (count($failedCommands)) {
            $this->comment("The following commands failed during execution:");
            $this->error("\n\t" . implode("\n\t", $failedCommands) . "\n");
        }
        else {
            $this->info("All successful!");
        }
    }
}
