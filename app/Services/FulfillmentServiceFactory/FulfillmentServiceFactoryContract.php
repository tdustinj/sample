<?php

namespace App\Services\FulfillmentServiceFactory;

use App\Services\Fulfillment\FulfillmentGeneratorContract;

interface FulfillmentServiceFactoryContract
{
    public function getFulfillmentService($fulfillmentType) : FulfillmentGeneratorContract;
}

?>