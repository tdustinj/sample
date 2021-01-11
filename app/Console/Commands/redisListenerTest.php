<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class redisListenerTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:hey-redis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Redis::subscribe(['inventory', 'price', 'order', 'product' ], function ($message) {
           $message= json_decode($message);
          // print_r($message);
           switch($message->message_type){
                case "inventory-update" :
                    event(new \App\Events\inventoryUpdated($message));
                    break;
                case "price-update" :
                    event(new \App\Events\priceUpdated($message));
                    break;
                case "product-update" :
                    event(new \App\Events\productUpdated($message));
                    break;
                case "order-imported" :
                    event(new \App\Events\orderImported($message));
                    break;
            }
        });
    }
}