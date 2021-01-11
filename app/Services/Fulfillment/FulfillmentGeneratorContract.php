<?php

namespace App\Services\Fulfillment;

interface FulfillmentGeneratorContract
{
    public function configShipment($config);

    //public function configShipmentQuote($config);

    public function getQuote();

    public function confirmFulfillment();
}

?>