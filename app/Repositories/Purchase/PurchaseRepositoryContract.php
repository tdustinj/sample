<?php

namespace App\Repositories\Purchase;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;

interface PurchaseRepositoryContract
{
    public function getRecent(): Collection;
    public function getPurchaseById(int $id): object;
    public function createFromRequest(Request $request): object;
    public function updateFromRequest(Request $request, int $id): object;
    public function getFull(int $id): object;
    public function submit(int $id): object;
    public function receive(Request $request, int $id): object;
    public function finish(int $id): object;
}
