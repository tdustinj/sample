<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Services\FulfillmentServiceFactory\FulfillmentServiceFactory;
use App\Services\Fulfillment\FulfillmentGeneratorContract;
use App\Services\Fulfillment\FedExShippingService;
use App\Services\Fulfillment\AmazonMultiChannelService;
use App\Services\Fulfillment\UPSShippingService;
use App\Services\Fulfillment\AITShippingService;
use App\Services\Fulfillment\WaltsFulfillmentService;

class FulfillmentServiceFactoryTest extends TestCase
{
    public function test_getFulfillmentService_WithDockFedexCode_ReturnsExpected()
    {
        // Arrange
        $serviceFactory = new FulfillmentServiceFactory();

        // Act
        $fulfillmentService = $serviceFactory->getFulfillmentService('dock_fedex');

        // Assert
        $this->assertInstanceOf(FedExShippingService::class, $fulfillmentService);
    }

    public function test_getFulfillmentService_WithDockUpsCode_ReturnsExpected()
    {
        // Arrange
        $serviceFactory = new FulfillmentServiceFactory();

        // Act
        $fulfillmentService = $serviceFactory->getFulfillmentService('dock_ups');

        // Assert
        $this->assertInstanceOf(UPSShippingService::class, $fulfillmentService);
    }

    public function test_getFulfillmentService_WithAmazonMultiChannelCode_ReturnsExpected()
    {
        // Arrange
        $serviceFactory = new FulfillmentServiceFactory();

        // Act
        $fulfillmentService = $serviceFactory->getFulfillmentService('amazon_multi_channel');

        // Assert
        $this->assertInstanceOf(AmazonMultiChannelService::class, $fulfillmentService);
    }

    public function test_getFulfillmentService_WithUnrecognizedArg_ReturnsExpectedDefault()
    {
        // Arrange
        $serviceFactory = new FulfillmentServiceFactory();

        // Act
        $fulfillmentService = $serviceFactory->getFulfillmentService('fizz_buzz');

        // Assert
        $this->assertInstanceOf(WaltsFulfillmentService::class, $fulfillmentService);
    }
}