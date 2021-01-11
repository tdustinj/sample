<?php

namespace App\Console\Commands;

use App\Models\Contact;

use App\EchoMessage;
use App\EchoMessageProcessor;
use App\Jobs\EchoChamber;
use App\Models\Quote;
use Illuminate\Console\Command;

class GetMessageTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getTestMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets a SQS for Connected inter apps to process';

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

    //

    public function handle()
    {

    }


}
