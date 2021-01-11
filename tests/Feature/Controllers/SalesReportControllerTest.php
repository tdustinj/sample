<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;

class SalesReportControllerTest extends TestCase
{
    public function test_totalsByInvoiceType_WithValidRequest_ReturnsExpectedJsonStructure()
    {
        //Arrange
        $user = User::first();

        //Act
        $response = $this->actingAs($user, 'api')
                        ->get('/api/salesReport/totalsByInvoiceType');

        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(
                // array of objects with keys:
                [
                    [
                        "invoiceType", "thisYearsRevenue", "lastYearsRevenue"
                    ]
                ]
            );
    }

    public function test_openOrders_WithValidRequest_ReturnsExpectedJsonStructure()
    {
        //Arrange
        $user = User::first();

        //Act
        $response = $this->actingAs($user, 'api')
            ->get('/api/salesReport/openOrders');

        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(
                // array of objects with keys:
                [
                    [
                        "Invoice",
                        "Invoice Type",
                        "Sales Person",
                        "Customer Name",
                        "Invoice Date",
                        "Schedule Date",
                        "Total",
                    ]
                ]
            );
    }

    public function test_openPartnerOrders_WithOrderByPartner_ReturnsExpectedJsonStructure()
    {
        //Arrange
        $user = User::first();

        //Act
        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/salesReport/openOrders/partner', ['orderBy' => 'partner']);

        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(
                // array of objects with keys:
                [
                    [
                        "Invoice",
                        "Invoice Type",
                        "Order Status",
                        "Partner",
                        "Customer Name",
                        "Invoice Date",
                        "Partner Order Number",
                        "Total",
                    ]
                ]
            );
    }

    public function test_openPartnerOrders_WithOrderByOrderStatus_ReturnsExpectedJsonStructure()
    {
        //Arrange
        $user = User::first();

        //Act
        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/salesReport/openOrders/partner', ['orderBy' => 'orderstatus']);

        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(
                // array of objects with keys:
                [
                    [
                        "Invoice",
                        "Invoice Type",
                        "Order Status",
                        "Partner",
                        "Customer Name",
                        "Invoice Date",
                        "Partner Order Number",
                        "Total",
                    ]
                ]
            );
    }

    public function test_openPartnerOrders_WithUnspecifiedOrderBy_ReturnsExpectedJsonStructure()
    {
        //Arrange
        $user = User::first();

        //Act
        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/salesReport/openOrders/partner');

        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(
                // array of objects with keys:
                [
                    [
                        "Invoice",
                        "Invoice Type",
                        "Order Status",
                        "Partner",
                        "Customer Name",
                        "Invoice Date",
                        "Partner Order Number",
                        "Total",
                    ]
                ]
            );
    }

    public function test_openPartnerOrders_WithInvalidOrderBy_Returns422()
    {
        //Arrange
        $user = User::first();

        //Act
        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/salesReport/openOrders/partner', ['orderBy' => 'foo']);

        //Assert
        $response->assertStatus(422);
    }

    public function test_openNonPartnerOrders_WithValidRequest_ReturnsExpectedJsonStructure()
    {
        //Arrange
        $user = User::first();

        //Act
        $response = $this->actingAs($user, 'api')
            ->get('/api/salesReport/openOrders/nonPartner');

        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(
                // array of objects with keys:
                [
                    [
                        "Invoice",
                        "Invoice Type",
                        "Order Status",
                        "Salesperson",
                        "Customer Name",
                        "Invoice Date",
                        "Total",
                    ]
                ]
            );
    }

    public function test_openInstalls_WithValidRequest_ReturnsExpectedJsonStructure()
    {
        //Arrange
        $user = User::first();

        //Act
        $response = $this->actingAs($user, 'api')
            ->get( '/api/salesReport/openInstalls');

        //Assert
        $response->assertStatus(200)
            ->assertJsonStructure(
                // array of objects with keys:
                [
                    [
                        "Invoice",
                        "Invoice Type",
                        "Sales Person",
                        "Customer Name",
                        "Invoice Date",
                        "Schedule Date",
                        "Total",
                    ]
                ]
            );
    }
}
