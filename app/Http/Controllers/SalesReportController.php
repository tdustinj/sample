<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SalesReport\SalesReportRepositoryContract;

class SalesReportController extends Controller
{
    protected $salesReportRepository;

	public function __construct(SalesReportRepositoryContract $salesReportRepository)
	{
        $this->salesReportRepository = $salesReportRepository;
    }

    public function totalsByInvoiceType()
    {
        $results = $this->salesReportRepository->getTotalsByInvoiceType();

        return $results;
    }

    public function openOrders()
    {
        $results = $this->salesReportRepository->getAllOpenOrders();

        return $results;
    }

    public function openPartnerOrders(Request $request)
    {
        $validatedData = $request->validate([
            'orderBy'   =>  'string|in:partner,orderstatus',
        ]);

        $orderBy = strtolower($validatedData['orderBy'] ?? 'partner');

        $results = null;

        if ($orderBy === 'partner') {
            $results = $this->salesReportRepository->getOpenOrdersByPartner();
        }
        else {
            $results = $this->salesReportRepository->getOpenOrdersByOrderStatus();
        }

        return $results;
    }

    public function openNonPartnerOrders()
    {
        $results = $this->salesReportRepository->getOpenNonPartnerOrders();

        return $results;
    }

    public function openInstalls()
    {
        $results = $this->salesReportRepository->getOpenInstalls();

        return $results;
    }
}
