<?php

namespace App\Listeners;

use App\Events\contactUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class contactUpdatedListener
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
   * @param  contactUpdated  $event
   * @return void
   */
  public function handle(contactUpdated $event)
  {
    //
  }
}
