<?php

namespace App\Services\AvailableInventory;

use App\Services\AvailableInventory\CalculatesAvailableInventoryContract;
use GuzzleHttp;
use App\Models\Inventory;
use Exception;

class AvailableInventoryServiceMock implements CalculatesAvailableInventoryContract
{
    protected $productIds;  // product ids of ospos.products

    protected $baseUrl;

	public function __construct()
	{
		$this->baseUrl ='';
        $this->productIds = null;
	}

    public function setProductIds($productIds)
    {
	    $this->productIds = $productIds;
    }


    public function getAvailableInventoryListBySource()
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
        return array();
    }

    public function getAvailableInventoryListByAggregation()
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
        return array();
    }

    public function calculateSellableInventoryByAggregation($aggregation)
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
    }

    public function calculateCommitedInventoryByAggregation($aggregation)
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
    }
}

?>