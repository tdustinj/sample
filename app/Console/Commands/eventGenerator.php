<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;


class eventGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generateTestEvents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a command to work out the PUB broadcast configuration that will become the Test to make sure this instace is connecting to a working redis server';

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
        //4i = 0; $i < 25)
        // The data will be recieved by the listener
        for($i = 0; $i < 250000; $i++) {
            $data = array('message_type' => 'inventory-update', 'products-id' => 1, 'available-inventory-total' => 12);
            //print_r($data);
          //  Redis::publish('inventory', json_encode($data));


            $data = array('message_type' => 'price-update', 'products-id' => 1, 'available-inventory-total' => 12);
            //print_r($data);
           // Redis::publish('price', json_encode($data));

            $data = array('message_type' => 'product-update', 'products_id' => 8, 'update' => 'multi-field');
            //print_r($data);
            Redis::publish('product', json_encode($data));
         sleep(10);
        }

    }
}
