<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Http\Controllers\PurchaseController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Request;
use App\Repositories\Purchase\PurchaseRepositoryMock;

class PurchaseControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function test_getAllPurchases_Return200Status()
    {
        // Arranage
        $purchaseRepository = new PurchaseRepositoryMock();
        $purchaseController = new PurchaseController($purchaseRepository);
        $request = Request::create('/api/purchase/', 'GET');

        // Act
        $response = $purchaseController->index($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_getSinglePurchase_Return200Status()
    {
        // Arranage
        $purchaseRepository = new PurchaseRepositoryMock();
        $purchaseController = new PurchaseController($purchaseRepository);

        // Act
        $response = $purchaseController->show(1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_postNewPurchase_Return200Status()
    {
        // Arranage
        $purchaseRepository = new PurchaseRepositoryMock();
        $purchaseController = new PurchaseController($purchaseRepository);
        $request = Request::create('/api/purchase/', 'POST');

        // Act
        $response = $purchaseController->store($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_updatePurchase_Return200Status()
    {
        // Arranage
        $purchaseRepository = new PurchaseRepositoryMock();
        $purchaseController = new PurchaseController($purchaseRepository);
        $request = Request::create('/api/purchase/', 'PUT');

        // Act
        $response = $purchaseController->update($request, 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }
}
