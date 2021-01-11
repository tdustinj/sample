<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Services\OrderManager\OrderManagerClientContract; 
use App\Models\OrderImport;
use Exception;


class NewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $echoMe;
    public $tries = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OrderManagerClientContract $orderManagerClient)
    {
        print_r("Getting New Orders");

        $offset = 0;
        do {
            $ordersToImport = $orderManagerClient->getAvailableOrdersToImport(null, $offset, 500);
            $ordersToImport = json_decode($ordersToImport);
            // print_r($ordersToImport);
            foreach($ordersToImport->data->orders as $order){
                //store the order to import id in order_import table.
                $orderToImport = new OrderImport();
                $orderToImport->order_manager_id = $order->id;

                try {
                     $orderToImport->save();
                }catch(Exception $e){
                    print_r($e->getMessage());
                }
            }
            $offset = $offset + $ordersToImport->data->limit;
            sleep(3);
        } while ($offset < $ordersToImport->data->totalOrders);
        
        print_r($offset);
    }
    // public function failed($exception)
    // {
    //     // Send user notification of failure, etc...
    //     print_r($exception->getMessage());
    // }
}
