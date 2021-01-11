<?php

namespace App\Services\Fulfillment;

use App\Services\Fulfillment\FulfillmentGeneratorContract;

class WaltsFulfillmentService implements FulfillmentGeneratorContract
{
    protected $config;

    public function __construct()
    {
        $this->config = null;

    }
    
    public function configShipment($config){
        /*
         *
         * THis will contain the logic to load up the data structure
         */
    }

    public function getQuote(){

    }

    public function confirmFulfillment(){

    }

}
?>