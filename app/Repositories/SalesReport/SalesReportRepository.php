<?php

namespace App\Repositories\SalesReport;

use App\Repositories\SalesReport\SalesReportRepositoryContract;
use Illuminate\Support\Collection;

class SalesReportRepository implements SalesReportRepositoryContract
{
    public function getTotalsByInvoiceType(): Collection
    {
        throw new Exception('Method not implemented');
        return new Collection();
    }

    public function getAllOpenOrders(): Collection
    {
        throw new Exception('Method not implemented');
        return new Collection();
    }

    public function getOpenOrdersByPartner(): Collection
    {
        throw new Exception('Method not implemented');
        return new Collection();
    }

    public function getOpenOrdersByOrderStatus(): Collection
    {
        throw new Exception('Method not implemented');
        return new Collection();
    }

    public function getOpenNonPartnerOrders(): Collection
    {
        throw new Exception('Method not implemented');
        return new Collection();
    }

    public function getOpenInstalls(): Collection
    {
        throw new Exception('Method not implemented');
        return new Collection();
    }

}

?>