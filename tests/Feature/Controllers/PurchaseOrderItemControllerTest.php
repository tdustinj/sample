<?php

namespace Tests\Unit;

use Tests\TestCase;
//use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use App\Http\Controllers\PurchaseOrderItemController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepositoryMock;

class PurchaseOrderItemControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Using mock data, request the index
     *
     * @return void
     */
    public function test_getIndex_Return200Status()
    {
        // Arrange
        $purchaseOrderItemRepository = new PurchaseOrderItemRepositoryMock();
        $purchaseOrderItemController = new PurchaseOrderItemController($purchaseOrderItemRepository);
        $request = Request::create('/api/purchase/orderItem?limit=200', 'GET');

        // Act
        $response = $purchaseOrderItemController->index($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_storeRequest_return200Status()
    {
        // Arrange
        $purchaseOrderItemRepository = new PurchaseOrderItemRepositoryMock();
        $purchaseOrderItemController = new PurchaseOrderItemController($purchaseOrderItemRepository);
        $request = Request::create('/api/purchase/orderItem?limit=200', 'POST');

        // Act
        $response = $purchaseOrderItemController->store($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }
}
