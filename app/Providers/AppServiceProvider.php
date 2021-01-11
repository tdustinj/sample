<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App;
use App\Services\AvailableInventory\CalculatesAvailableInventoryContract;
use App\Services\AvailableInventory\AvailableInventoryService;
use App\Services\AvailableInventory\LegacyAvailableInventoryService;
use App\Services\AvailableInventory\AvailableInventoryServiceMock;
use App\Services\FulfillmentServiceFactory\FulfillmentServiceFactoryContract;
use App\Services\FulfillmentServiceFactory\FulfillmentServiceFactory;
use App\Services\FulfillmentServiceFactory\FulfillmentServiceFactoryMock;
use App\Services\OrderManager\OrderManagerClientContract;
use App\Services\OrderManager\OrderManagerClient;
use App\Services\OrderManager\OrderManagerClientMock;
use App\Repositories\SalesReport\SalesReportRepositoryContract;
use App\Repositories\SalesReport\SalesReportRepository;
use App\Repositories\SalesReport\SalesReportRepositoryMock;
use App\Repositories\Purchase\PurchaseRepositoryContract;
use App\Repositories\Purchase\PurchaseRepository;
use App\Repositories\Purchase\PurchaseRepositoryMock;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepositoryContract;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepository;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepositoryMock;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CalculatesAvailableInventoryContract::class, function ($app) {

            if (App::environment('production')) {

                if (env('INVENTORY_SOURCE') == 'legacy') {
                    return new LegacyAvailableInventoryService();
                } else {
                    return new AvailableInventoryService();
                }
            } else {
                return new AvailableInventoryServiceMock();
            }
        });

        $this->app->bind(FulfillmentServiceFactoryContract::class, function ($app) {

            if (App::environment('production')) {
                return new FulfillmentServiceFactory();
            } else {
                return new FulfillmentServiceFactoryMock();
            }
        });

        $this->app->bind(OrderManagerClientContract::class, function ($app) {

            if (App::environment('production')) {
                return new OrderManagerClient();
            } else {
                return new OrderManagerClientMock();
            }
        });

        $this->app->bind(SalesReportRepositoryContract::class, function ($app) {

            if (App::environment('production')) {
                return new SalesReportRepository();
            } else {
                return new SalesReportRepositoryMock();
            }
        });

        $this->app->bind(PurchaseRepositoryContract::class, function () {
            if (App::environment('testing')) {
                return new PurchaseRepositoryMock();
            } else {
                return new PurchaseRepository();
            }
        });

        $this->app->bind(PurchaseOrderItemRepositoryContract::class, function () {
            if (App::environment('testing')) {
                return new PurchaseOrderItemRepositoryMock();
            } else {
                return new PurchaseOrderItemRepository();
            }
        });
    }
}
