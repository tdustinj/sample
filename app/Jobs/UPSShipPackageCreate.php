<?php

namespace App\Jobs;

use App\Models\Fulfillment, App\Models\Contact;
use App\Services\Fulfillment\UPSShippingService; 
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UPSShipPackageCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fulfillment;
    protected $contact;
    protected $serviceType;
    protected $service;
    protected $rate;
    public $tries = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fulfillment, Contact $contact, $serviceType, $service, $rate)
    {
        Log::debug('UPSShipPackageCreate');
        $this->fulfillment = $fulfillment;
        $this->contact = $contact;
        $this->serviceType = $serviceType;
        $this->service = $service;
        $this->rate = $rate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo('Dam, the sneaky worker found me for UPS!');

        /* Here is where we will create the actual UPS Package Request */
        $fulfillment = new UPSShippingService();
        $createFulfillment = $fulfillment->createFulfillment($this->fulfillment, $this->contact, $this->serviceType, $this->service);
        // print_r($createFulfillment);
        if($createFulfillment['status'] == "Success"){
            // Check if the Rate we started with is similar.
            print_r("$");
            print_r($createFulfillment['totalCharges']);
            print_r(" vs rate price: $");
            print_r($this->rate);

            $fulfillmentAccept = $fulfillment->createFulfillmentAccept($createFulfillment['shipDigest']);
            print_r($fulfillmentAccept);
            if(isset($fulfillmentAccept['packageContainer'])){
                //we have a multiple package fulfillment
                // $this->fulfillment[0]
                print_r($this->fulfillment);
                $packageCount = 0;
                foreach($fulfillmentAccept['packageContainer'] as $package){
                    $this->fulfillment[0]->package[$packageCount]->tracking_number = $package['trackingNumber'];
                    $this->fulfillment[0]->package[$packageCount]->save();
                    $packageCount++;
                }
                echo "multi";
            }else{
                //we have a single package fulfillment
                echo "single";
            }
            $this->fulfillment[0]->master_tracking_id = $fulfillmentAccept['masterTracking'];
            $this->fulfillment[0]->fulfillment_cost_total_actual = $fulfillmentAccept['totalCharges']->MonetaryValue;
            $this->fulfillment[0]->save();
        }


    }

    public function failed($exception)
    {
        // Send user notification of failure, etc...
        print_r($exception->getMessage());
    }
}
