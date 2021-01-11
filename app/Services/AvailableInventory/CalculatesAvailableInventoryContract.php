<?php

namespace App\Services\AvailableInventory;

interface CalculatesAvailableInventoryContract
{

    public function setProductIds($productIds);

    public function getAvailableInventoryListBySource();

    public function getAvailableInventoryListByAggregation();

    public function calculateSellableInventoryByAggregation($aggregation);

    public function calculateCommitedInventoryByAggregation($aggregation);
}

?>