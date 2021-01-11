<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\WorkOrder;

class AmazonFBAOrderImported
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $workorder;
    public $workorderItems;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(WorkOrder $workorder)
    {
        //
        $this->workorder = $workorder;
        $this->workorderItems = $workorder->workOrderItems()->get();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('amazonfba-order-imported');
    }


}
