<?php

namespace App\Services\FulfillmentServiceFactory;

use App\Services\Fulfillment\FulfillmentGeneratorContract;

use App\Services\Fulfillment\FulfillmentServiceMock;

class FulfillmentServiceFactoryMock implements FulfillmentServiceFactoryContract
{
    public function getFulfillmentService($fulfillmentType) : FulfillmentGeneratorContract
    {
        return new FulfillmentServiceMock();
    }
}

?>