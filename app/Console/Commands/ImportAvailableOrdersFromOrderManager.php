<?php

namespace App\Console\Commands;

use App\Jobs\NewOrder;
use App\Jobs\ImportOrder;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Console\Command;
use App\Models\QuoteItem;
use App\Models\OrderImport;
use DB;
use App\Models\Platform;
use App\Models\State;
use App\Models\PlatformStateTaxMap;


class ImportAvailableOrdersFromOrderManager  extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importAvailableOrdersFromOrderManager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Import Orders from OrderManager';

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
    public function handle(){ 
        $count = 1;
        $ordersToImport = OrderImport::where('imported', '=', false)->where('cancelled', '=', false)->get();
        foreach($ordersToImport as $order){
            ImportOrder::dispatch($order); 
            if ($count >= 100) {
              die;
            }
            $count++;
            sleep(2);
        }
    }
}
