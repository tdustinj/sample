<?php

namespace App\Listeners;

use App\Events\WorkOrderNoteAdded;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\WorkOrderNoteEmail;

class EmailWorkOrderNote
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
     * @param  WorkOrderNoteAdded  $event
     * @return void
     */
    public function handle(WorkOrderNoteAdded $event)
    {
        $recipient = $event->note->to->email ?? '';
        if ($recipient) {
            Mail::to($recipient)->send(new WorkOrderNoteEmail($recipient, $event->note));
        }
    }
}
