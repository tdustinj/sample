<?php

namespace App\Console\Commands;

use App\Models\Contact;

use App\Services\AvailableInventory\CalculatesAvailableInventoryContract;
use App\Services\Fulfillment\FulfillmentGeneratorContract;
use App\Services\AvailableInventory\AvailableInventoryService;
use App\Services\AvailableInventory\LegacyAvailableInventoryService;
use Illuminate\Console\Command;

class FetchInventoryTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetchInventoryTest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tests Fetching Inventory from Service Provider';

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

    //

    public function handle(CalculatesAvailableInventoryContract $inventoryService)
    {


      //  $inventoryService->setProductIds(array(1692,2862,107));
      //  $inventoryList = $inventoryService->getAvailableInventoryListByAggregation();
      //  print_r($inventoryList);

        $inventoryService->setProductIds(array('UN65MU8000', 'OLED55E7P', 'OLED65C7P', 'TX-NR676'));
        $inventoryList = $inventoryService->getAvailableInventoryListByAggregation();
        print_r($inventoryList);
    }


}
