<?php

namespace App\Listeners;

use App\Events\OrderDoesNotExist;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\OrderImport;

class DeleteOrder
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderDoesNotExist  $event
     * @return void
     */
    public function handle(OrderDoesNotExist $event)
    {
        
        try{
            OrderImport::where('order_manager_id', '=', $event->orderManagerId)->delete();
            print_r($event->orderManagerId . " Deleted");
        }
        catch(Exception $e){
            print_r("OrderImportId: " . $event->orderManagerId . " exception failure\n");
            print_r($e->getMessage());
        }
    }
}
