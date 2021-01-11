<?php

namespace App\Console\Commands;

use App\Jobs\DispatchToOrderManager;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Console\Command;
use App\Models\QuoteItem;
use App\Models\OrderImport;
use DB;


class DispatchImportedOrdersToOrderManager  extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dispatchImportedOrdersToOrderManager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command that creates Jobs to tell ordermanager that and order was succesffully imported into api-ospos';

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
        $importedOrders = OrderImport::where('imported', '=', true)->get();
        foreach($importedOrders as $order){
            DispatchToOrderManager::dispatch($order);

            ImportOrder::dispatch($order);
        }
    }
}
