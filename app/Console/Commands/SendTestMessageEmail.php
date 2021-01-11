<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\WorkOrderNoteAdded;
use App\Models\WorkOrderNotes;

class SendTestMessageEmail extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'command:send-test-notes-email';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Sends an email that will be easier than testing via GUI';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    event(new WorkOrderNoteAdded(WorkOrderNotes::find(1)));
  }
}
