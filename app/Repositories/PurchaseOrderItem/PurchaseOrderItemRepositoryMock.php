<?php

namespace App\Repositories\PurchaseOrderItem;

use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PurchaseOrderItemRepositoryMock implements PurchaseOrderItemRepositoryContract
{
    public function __construct()
    {
        $this->data = json_decode($this->_data());
    }

    public function getRecent($limit): object
    {
        return (object)$this->data;
    }

    public function createFromRequest(Request $request): object
    {
        return $this->data[0];
    }

    public function updateFromRequest($request, int $id): object
    {
        return $this->data[0];
    }
    public function getFromRequest(int $id): object
    {
        return $this->data[0];
    }
    public function updateOrderTotal(int $id, int $qty): object
    {
        return $this->data[0];
    }
    public function receiveFromRequest(Request $request, int $qty): object
    {
        return $this->data[0];
    }
    public function softDeleteFromRequest(int $id): int
    {
        return 1;
    }

    private function _data(): string
    {
        return '
[
  {
    "id": 2,
    "created_at": "2019-06-19 11:30:45",
    "updated_at": "2019-06-19 13:30:27",
    "products_id": 2944,
    "product_mpn": "PL-414BG",
    "product_model": "PL-414BG",
    "product_upc": "853042007229",
    "product_description": "Antop PL-414BG Outdoor UFO Pro-Line Attic RV Amplified HDTV Antenna",
    "product_cogs_class": "PRODUCT",
    "product_revenue_class": "PRODUCT",
    "purchase_order_id": 3,
    "note": "",
    "invoice_cost": 72.00,
    "dfi_amount": 72.00,
    "shipping_cost": 0.00,
    "adj_cost": 0.00,
    "qty_ordered": 1,
    "qty_received": 0,
    "qty_refused": 0,
    "qty_pending": 1,
    "inventory_ids": "",
    "vir_amount": 0.00,
    "mdf_amount": 0.00,
    "program_amount": 0.00,
    "trailing_credit": 0.00,
    "spa": 0.00,
    "spiff": 0.00,
    "line_total": 72.00,
    "scan_setup": 0
  },
  {
    "id": 3,
    "created_at": "2019-06-19 11:37:55",
    "updated_at": "2019-06-19 13:30:27",
    "products_id": 4687,
    "product_mpn": "UN19F4000 ",
    "product_model": "UN19F4000",
    "product_upc": "887276020228",
    "product_description": "Samsung UN19F4000 19\" Class (1366 x 768) LED High Definition Flat Screen TV",
    "product_cogs_class": "PRODUCT",
    "product_revenue_class": "PRODUCT",
    "purchase_order_id": 3,
    "note": "",
    "invoice_cost": 219.99,
    "dfi_amount": 219.99,
    "shipping_cost": 0.00,
    "adj_cost": 0.00,
    "qty_ordered": 1,
    "qty_received": 0,
    "qty_refused": 0,
    "qty_pending": 1,
    "inventory_ids": "",
    "vir_amount": 0.00,
    "mdf_amount": 0.00,
    "program_amount": 0.00,
    "trailing_credit": 0.00,
    "spa": 0.00,
    "spiff": 0.00,
    "line_total": 219.99,
    "scan_setup": 0
  },
  {
    "id": 4,
    "created_at": "2019-06-19 11:55:24",
    "updated_at": "2019-06-19 13:38:13",
    "products_id": 317,
    "product_mpn": "",
    "product_model": "QAS740RMSS",
    "product_upc": "084691829287",
    "product_description": "HAIER ELECTRIC RANGES",
    "product_cogs_class": "PRODUCT",
    "product_revenue_class": "PRODUCT",
    "purchase_order_id": 3,
    "note": "",
    "invoice_cost": 1300.00,
    "dfi_amount": 1300.00,
    "shipping_cost": 0.00,
    "adj_cost": 0.00,
    "qty_ordered": 1,
    "qty_received": 0,
    "qty_refused": 0,
    "qty_pending": 1,
    "inventory_ids": "",
    "vir_amount": 0.00,
    "mdf_amount": 0.00,
    "program_amount": 0.00,
    "trailing_credit": 0.00,
    "spa": 0.00,
    "spiff": 0.00,
    "line_total": 1300.00,
    "scan_setup": 0
  },
  {
    "id": 5,
    "created_at": "2019-06-19 14:07:53",
    "updated_at": "2019-06-20 13:08:25",
    "products_id": 4488,
    "product_mpn": "PLAY3BLACK",
    "product_model": "PLAY3 BLACK",
    "product_upc": "180501002014",
    "product_description": "Sonos Play:3 Wireless Mid-Size Black Speaker (Each)",
    "product_cogs_class": "PRODUCT",
    "product_revenue_class": "PRODUCT",
    "purchase_order_id": 1,
    "note": "",
    "invoice_cost": 299.00,
    "dfi_amount": 299.00,
    "shipping_cost": 0.00,
    "adj_cost": 0.00,
    "qty_ordered": 1,
    "qty_received": 0,
    "qty_refused": 0,
    "qty_pending": 1,
    "inventory_ids": "",
    "vir_amount": 0.00,
    "mdf_amount": 0.00,
    "program_amount": 0.00,
    "trailing_credit": 0.00,
    "spa": 0.00,
    "spiff": 0.00,
    "line_total": 299.00,
    "scan_setup": 1
  },
  {
    "id": 8,
    "created_at": "2019-06-20 11:22:20",
    "updated_at": "2019-06-20 12:34:24",
    "products_id": 2900,
    "product_mpn": "OK55",
    "product_model": "OK55",
    "product_upc": "719192620315",
    "product_description": "LG OK55 Loud 500 Watt Bluetooth Party System",
    "product_cogs_class": "PRODUCT",
    "product_revenue_class": "PRODUCT",
    "purchase_order_id": 2,
    "note": "",
    "invoice_cost": 399.99,
    "dfi_amount": 399.99,
    "shipping_cost": 0.00,
    "adj_cost": 0.00,
    "qty_ordered": 1,
    "qty_received": 0,
    "qty_refused": 0,
    "qty_pending": 1,
    "inventory_ids": "",
    "vir_amount": 0.00,
    "mdf_amount": 0.00,
    "program_amount": 0.00,
    "trailing_credit": 0.00,
    "spa": 0.00,
    "spiff": 0.00,
    "line_total": 399.99,
    "scan_setup": 0
  }
]';
    }
}
