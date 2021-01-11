<?php

namespace App\Console\Commands;




use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class SendMessageTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendTestMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a test message to SQS for Connected inter apps to process';

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

    public function handle()
    {

        for($i =0; $i < 10; $i++) {

            $message = json_encode(array("inventory-update", $i, date("Y-m-d h:m:s")));
            Queue::dispatch($message)->onQueue('inventory-updates');

            $message = json_encode(array("listing-update", $i, date("Y-m-d h:m:s")));
            \App\Jobs\ListingUpdate::dispatch($message)->onQueue('listing-updates');

            $message = json_encode(array("new-product", $i, date("Y-m-d h:m:s")));
            \App\Jobs\NewProduct::dispatch($message)->onQueue('new-products');

            $message = json_encode(array("product-update", $i, date("Y-m-d h:m:s")));
            \App\Jobs\ProductUpdate::dispatch($message)->onQueue('product-updates');

            $message = json_encode(array("new-order", $i, date("Y-m-d h:m:s")));
            \App\Jobs\NewOrder::dispatch($message)->onQueue('order-imported');

            sleep(3);
        }

    }


}
