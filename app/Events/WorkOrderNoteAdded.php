<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\WorkorderNotes;

class WorkOrderNoteAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $workOrderNote;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(WorkorderNotes $workOrderNote)
    {
        $this->note = $workOrderNote;
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
