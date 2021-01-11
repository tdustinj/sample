<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorPurchaseOrder extends Mailable
{
  use Queueable, SerializesModels;

  protected $pdf;
  public $data;
  public $content;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($pdf, $data, $content = null)
  {
    $this->pdf = $pdf;
    $this->data = $data;
    $this->content = $content;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->subject("Purchase Order #" . $this->data['purchaseOrder']->id)
      ->view('mail.vendor')
      ->attachData($this->pdf, 'purchaseOrder.pdf', [
        'mime' => 'application/pdf'
      ]);
  }
}
