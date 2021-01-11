<?php

namespace App\Repositories\SalesReport;

use Illuminate\Support\Collection;

interface SalesReportRepositoryContract
{
    public function getTotalsByInvoiceType() : Collection;

    public function getAllOpenOrders() : Collection;

    public function getOpenOrdersByPartner() : Collection;

    public function getOpenOrdersByOrderStatus() : Collection;

    public function getOpenNonPartnerOrders() : Collection;

    public function getOpenInstalls() : Collection;
}

?>