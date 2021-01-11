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
use App\Models\Contact;

class OrderImported
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $workorder;
    public $contact;
    public $workorderItems;
    public $platform;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(WorkOrder $workorder)
    {
        //
        $this->workorder = $workorder;
        $this->contact = $workorder->shipTo()->get();
        $this->workorderItems = $workorder->workOrderItems()->get();
        $this->platform = $this->getPlatformContactIdName($this->workorder->bill_contact_id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('order-imported');
    }

    private function getPlatformContactIdName($id){
      $platform = Contact::find($id);
      if($platform){
        return $platform->first_name;
      }
      return "No platform found";
    }
}
