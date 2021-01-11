<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\WorkorderNotes;

class WorkOrderNoteEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $recipientEmail;
    private $note;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recipientEmail, WorkOrderNotes $note)
    {
        $this->recipientEmail = $recipientEmail;
        $this->note = $note;
        // Tried to use $note->workorder->id, but didn't work for whatever reason
        $this->notes = WorkOrderNotes::where('fk_workorder_id', '=', $note->fk_workorder_id)->get();
        $this->workOrder = $note->workOrder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.messages')
            ->with(['to' => $this->note->to, 'notes' => $this->notes->reverse(), 'workOrder' => $this->workOrder]);
    }
}
