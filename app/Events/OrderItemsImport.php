<?php

namespace App\Events;
use App\Models\WorkOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderItemsImport
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $workorderId;
    public $items;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(WorkOrder $workorder, $orderItems)
    {
        //
        $this->workorderId = $workorder->id;
        $this->items = $orderItems;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
