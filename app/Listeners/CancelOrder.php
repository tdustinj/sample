<?php
namespace App\Listeners;
use App\Events\OrderCancelled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\OrderManager\OrderManagerClientContract; 

class CancelOrder
{
    protected $orderManagerClient;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderManagerClientContract $orderManagerClient)
    {
        $this->orderManagerClient = $orderManagerClient;
    }

    /**
     * Handle the event.
     *
     * @param  OrderCancelled  $event
     * @return void
     */
    public function handle(OrderCancelled $event)
    {   /**   
          * Order that has been cancelled before we Imported into the api. Flag as cancelled and dispatch to OrderManager that we imported it.
          * so we stop getting it when we request orders 
        */

        $event->order->cancelled = true;
        $event->order->save();

        /** Dispatch to Order Manager: set order as imported */
        $setOrderManagerExported = $this->orderManagerClient->setOrderImported($event->order->order_manager_id, $event->order->id);

        $setOrderManagerExported = json_decode($setOrderManagerExported);
        
        if($setOrderManagerExported->data == "success"){
            /* Set order as imported in API-OSPOS order_import table */
            $event->order->order_manager_updated = true;
        }
    }

}
