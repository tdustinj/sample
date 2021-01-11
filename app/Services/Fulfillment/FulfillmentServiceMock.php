<?php

namespace App\Services\Fulfillment;

use App\Services\Fulfillment\FulfillmentGeneratorContract;

class FulfillmentServiceMock implements FulfillmentGeneratorContract
{
    protected $config;

    public function __construct()
    {
        $this->config = null;

    }
    
    public function configShipment($config)
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
    }

    public function getQuote()
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
    }

    public function confirmFulfillment()
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
    }

}
?>