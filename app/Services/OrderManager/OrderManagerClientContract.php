<?php

namespace App\Services\OrderManager;

interface OrderManagerClientContract
{

    public function getAvailableOrdersToImport($platform = "all", $offset, $limit);

    public function setOrderImported($orderManagerOrderId, $importOrderId);

    public function getFullOrder($unifiedOrderId);
}

?>