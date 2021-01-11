<?php

namespace App\Jobs;

use App\Models\Fulfillment, App\Models\Contact, App\Services\Fulfillment\AmazonMultiChannelService; 
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AmazonMCCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fulfillment;
    protected $contact;
    protected $serviceType;
    protected $service;
    public $tries = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fulfillment, Contact $contact, $serviceType, $service)
    {
        Log::debug('AmazonMCCreate');
        $this->fulfillment = $fulfillment;
        $this->contact = $contact;
        $this->serviceType = $serviceType;
        $this->service = $service;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo('Dam, the sneaky worker found me!');
        /* Here is where we will create the actual AmazonMC Request */
        $fulfillment = new AmazonMultiChannelService();
        $fulfillment->createFulfillment($this->fulfillment, $this->contact, $this->serviceType, $this->service);

    }

    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
        print_r($exception);
    }
}
