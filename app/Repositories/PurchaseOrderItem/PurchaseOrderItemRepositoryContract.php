<?php

namespace App\Repositories\PurchaseOrderItem;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface PurchaseOrderItemRepositoryContract
{
    public function getRecent(int $limit): object;
    public function createFromRequest(Request $request): object;
    public function updateFromRequest(Request $request, int $id): object;
    public function getFromRequest(int $id): object;
    public function updateOrderTotal(int $id, int $qty): object;
    public function receiveFromRequest(Request $request, int $qty): object;
    public function softDeleteFromRequest(int $id): int;
}
