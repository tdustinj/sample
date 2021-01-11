<?php

namespace App\Console\Commands;

use App\Jobs\NewOrder;
use App\Jobs\ImportOrder;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Console\Command;
use App\Models\QuoteItem;
use DB;


class GetAvailableOrdersFromOrderManager  extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getAvailableOrdersFromOrderManager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Import Orders';

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
        NewOrder::dispatch();

    }
}
