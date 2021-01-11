<?php

namespace App\Services\FulfillmentServiceFactory;

use App\Services\Fulfillment\FulfillmentGeneratorContract;

use App\Services\Fulfillment\AITShippingService;
use App\Services\Fulfillment\AmazonMultiChannelService;
use App\Services\Fulfillment\FedExShippingService;
use App\Services\Fulfillment\IngramMicroDropShipService;
use App\Services\Fulfillment\UPSShippingService;
use App\Services\Fulfillment\USPSShippingService;
use App\Services\Fulfillment\WaltsFulfillmentService;

class FulfillmentServiceFactory implements FulfillmentServiceFactoryContract
{
    public function getFulfillmentService($fulfillmentType) : FulfillmentGeneratorContract
    {
        switch (strtolower(trim($fulfillmentType))) {

            case 'dock_fedex':
                return new FedExShippingService();

            case 'dock_ups':
                return new UPSShippingService();

            case 'amazon_multi_channel':
                return new AmazonMultiChannelService();

            // case ?:
            //     return new AITShippingService();

            // case ?:
            //     return new IngramMicroDropShipService();

            // case ?:
            //     return new USPSShippingService();


            default:
                return new WaltsFulfillmentService();
        }
    }
}

?>