<?php
namespace App\Repositories\Purchase;

use App\Repositories\Purchase\PurchaseRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class PurchaseRepositoryMock implements PurchaseRepositoryContract
{
    public function __construct()
    {
        $this->data = json_decode($this->_data());
    }
    public function getRecent(): Collection
    {
        return collect($this->data);
    }

    public function getPurchaseById(int $id): object
    {
        return $this->data[0];
    }

    public function createFromRequest(Request $request): object
    {
        return $this->data[0];
    }

    public function updateFromRequest(Request $request, int $id): object
    {
        return $this->data[0];
    }
    public function submit(int $id): object
    {
        return $this->data[0];
    }
    public function receive(Request $request, int $id): object
    {
        return $this->data[0];
    }
    public function finish(int $id): object
    {
        return $this->data[0];
    }

    public function getFull(int $id): object
    {
        return json_decode($this->_fullSingleData());
    }

    private function _fullSingleData()
    {
        return '
  {
      "data": {
      "PO": {
      "id": 3,
          "created_at": "2019-06-19 09:53:58",
          "updated_at": "2019-06-19 13:53:04",
          "purchase_order_type": "Default",
          "purchase_order_status": "NEW",
          "vendor_id": 1,
          "vendor_name": "1",
          "submission_status": "NOT_SUBMITTED",
          "submitted_on": "1999-12-31 01:01:01",
          "payment_method": "Wells Fargo Finance",
          "created_by": "Ryan Morton",
          "last_received_by": "1999-12-31 01:01:01",
          "receiving_status": "",
          "payment_terms_schema": "60 Days",
          "payment_discount_schema": "15:.02",
          "payment_authorized": "",
          "payment_authorization_code": "",
          "payment_authorized_on": "1999-12-31 01:01:01",
          "payment_invoice_number": "",
          "payment_amount_authorized": "0.00",
          "total": "1591.99",
          "total_received": "0.00",
          "balance_due": "1591.99",
          "shipping_location_contact_id": 1,
          "shipping_location_attn": "RECEIVING",
          "shipping_requirements": "Default",
          "fulfillment_order_id": null,
          "flooring_company": "None",
          "flooring_term_days": 30,
          "flooring_upcharge_percent": "0.00",
          "flooring_approval_status": null,
          "notes": "",
          "items": [
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
            "invoice_cost": "72.00",
            "dfi_amount": "72.00",
            "shipping_cost": "0.00",
            "adj_cost": "0.00",
            "qty_ordered": 1,
            "qty_received": 0,
            "qty_refused": 0,
            "qty_pending": 1,
            "inventory_ids": "",
            "vir_amount": "0.00",
            "mdf_amount": "0.00",
            "program_amount": "0.00",
            "trailing_credit": "0.00",
            "spa": "0.00",
            "spiff": "0.00",
            "line_total": "72.00",
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
            "invoice_cost": "219.99",
            "dfi_amount": "219.99",
            "shipping_cost": "0.00",
            "adj_cost": "0.00",
            "qty_ordered": 1,
            "qty_received": 0,
            "qty_refused": 0,
            "qty_pending": 1,
            "inventory_ids": "",
            "vir_amount": "0.00",
            "mdf_amount": "0.00",
            "program_amount": "0.00",
            "trailing_credit": "0.00",
            "spa": "0.00",
            "spiff": "0.00",
            "line_total": "219.99",
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
            "invoice_cost": "1300.00",
            "dfi_amount": "1300.00",
            "shipping_cost": "0.00",
            "adj_cost": "0.00",
            "qty_ordered": 1,
            "qty_received": 0,
            "qty_refused": 0,
            "qty_pending": 1,
            "inventory_ids": "",
            "vir_amount": "0.00",
            "mdf_amount": "0.00",
            "program_amount": "0.00",
            "trailing_credit": "0.00",
            "spa": "0.00",
            "spiff": "0.00",
            "line_total": "1300.00",
            "scan_setup": 0
    }
]
    },
        "PO_LINES": [
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
            "invoice_cost": "72.00",
            "dfi_amount": "72.00",
            "shipping_cost": "0.00",
            "adj_cost": "0.00",
            "qty_ordered": 1,
            "qty_received": 0,
            "qty_refused": 0,
            "qty_pending": 1,
            "inventory_ids": "",
            "vir_amount": "0.00",
            "mdf_amount": "0.00",
            "program_amount": "0.00",
            "trailing_credit": "0.00",
            "spa": "0.00",
            "spiff": "0.00",
            "line_total": "72.00",
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
            "invoice_cost": "219.99",
            "dfi_amount": "219.99",
            "shipping_cost": "0.00",
            "adj_cost": "0.00",
            "qty_ordered": 1,
            "qty_received": 0,
            "qty_refused": 0,
            "qty_pending": 1,
            "inventory_ids": "",
            "vir_amount": "0.00",
            "mdf_amount": "0.00",
            "program_amount": "0.00",
            "trailing_credit": "0.00",
            "spa": "0.00",
            "spiff": "0.00",
            "line_total": "219.99",
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
            "invoice_cost": "1300.00",
            "dfi_amount": "1300.00",
            "shipping_cost": "0.00",
            "adj_cost": "0.00",
            "qty_ordered": 1,
            "qty_received": 0,
            "qty_refused": 0,
            "qty_pending": 1,
            "inventory_ids": "",
            "vir_amount": "0.00",
            "mdf_amount": "0.00",
            "program_amount": "0.00",
            "trailing_credit": "0.00",
            "spa": "0.00",
            "spiff": "0.00",
            "line_total": "1300.00",
            "scan_setup": 0
    }
]
    }
    }
';
    }

    private function _data()
    {
        return '
[
  {
    "id": 1,
    "created_at": "2019-06-19 09:37:11",
    "updated_at": "2019-06-19 14:07:53",
    "purchase_order_type": "Default",
    "purchase_order_status": "NEW",
    "vendor_id": 1,
    "vendor_name": "1",
    "submission_status": "NOT_SUBMITTED",
    "submitted_on": "1999-12-31 01:01:01",
    "payment_method": "Wells Fargo Finance",
    "created_by": "Ryan Morton",
    "last_received_by": "1999-12-31 01:01:01",
    "receiving_status": "",
    "payment_terms_schema": "60 Days",
    "payment_discount_schema": "15:.02",
    "payment_authorized": "",
    "payment_authorization_code": "",
    "payment_authorized_on": "1999-12-31 01:01:01",
    "payment_invoice_number": "",
    "payment_amount_authorized": 0.00,
    "total": 299.00,
    "total_received": 0.00,
    "balance_due": 299.00,
    "shipping_location_contact_id": 1,
    "shipping_location_attn": "RECEIVING",
    "shipping_requirements": "Default",
    "fulfillment_order_id": null,
    "flooring_company": "None",
    "flooring_term_days": 30,
    "flooring_upcharge_percent": 0.00,
    "flooring_approval_status": null,
    "notes": ""
  },
  {
    "id": 2,
    "created_at": "2019-06-19 09:53:57",
    "updated_at": "2019-06-19 09:53:57",
    "purchase_order_type": "Default",
    "purchase_order_status": "NEW",
    "vendor_id": 1,
    "vendor_name": "1",
    "submission_status": "NOT_SUBMITTED",
    "submitted_on": "1999-12-31 01:01:01",
    "payment_method": "Wells Fargo Finance",
    "created_by": "Ryan Morton",
    "last_received_by": "1999-12-31 01:01:01",
    "receiving_status": "",
    "payment_terms_schema": "60 Days",
    "payment_discount_schema": "15:.02",
    "payment_authorized": "",
    "payment_authorization_code": "",
    "payment_authorized_on": "1999-12-31 01:01:01",
    "payment_invoice_number": "",
    "payment_amount_authorized": 0.00,
    "total": 0.00,
    "total_received": 0.00,
    "balance_due": 0.00,
    "shipping_location_contact_id": 1,
    "shipping_location_attn": "RECEIVING",
    "shipping_requirements": "Default",
    "fulfillment_order_id": null,
    "flooring_company": "None",
    "flooring_term_days": 30,
    "flooring_upcharge_percent": 0.00,
    "flooring_approval_status": null,
    "notes": ""
  },
  {
    "id": 3,
    "created_at": "2019-06-19 09:53:58",
    "updated_at": "2019-06-19 13:53:04",
    "purchase_order_type": "Default",
    "purchase_order_status": "NEW",
    "vendor_id": 1,
    "vendor_name": "1",
    "submission_status": "NOT_SUBMITTED",
    "submitted_on": "1999-12-31 01:01:01",
    "payment_method": "Wells Fargo Finance",
    "created_by": "Ryan Morton",
    "last_received_by": "1999-12-31 01:01:01",
    "receiving_status": "",
    "payment_terms_schema": "60 Days",
    "payment_discount_schema": "15:.02",
    "payment_authorized": "",
    "payment_authorization_code": "",
    "payment_authorized_on": "1999-12-31 01:01:01",
    "payment_invoice_number": "",
    "payment_amount_authorized": 0.00,
    "total": 1591.99,
    "total_received": 0.00,
    "balance_due": 1591.99,
    "shipping_location_contact_id": 1,
    "shipping_location_attn": "RECEIVING",
    "shipping_requirements": "Default",
    "fulfillment_order_id": null,
    "flooring_company": "None",
    "flooring_term_days": 30,
    "flooring_upcharge_percent": 0.00,
    "flooring_approval_status": null,
    "notes": ""
  },
  {
    "id": 4,
    "created_at": "2019-06-19 09:55:08",
    "updated_at": "2019-06-19 09:55:08",
    "purchase_order_type": "Default",
    "purchase_order_status": "NEW",
    "vendor_id": 1,
    "vendor_name": "1",
    "submission_status": "NOT_SUBMITTED",
    "submitted_on": "1999-12-31 01:01:01",
    "payment_method": "Wells Fargo Finance",
    "created_by": "Ryan Morton",
    "last_received_by": "1999-12-31 01:01:01",
    "receiving_status": "",
    "payment_terms_schema": "60 Days",
    "payment_discount_schema": "15:.02",
    "payment_authorized": "",
    "payment_authorization_code": "",
    "payment_authorized_on": "1999-12-31 01:01:01",
    "payment_invoice_number": "",
    "payment_amount_authorized": 0.00,
    "total": 0.00,
    "total_received": 0.00,
    "balance_due": 0.00,
    "shipping_location_contact_id": 1,
    "shipping_location_attn": "RECEIVING",
    "shipping_requirements": "Default",
    "fulfillment_order_id": null,
    "flooring_company": "None",
    "flooring_term_days": 30,
    "flooring_upcharge_percent": 0.00,
    "flooring_approval_status": null,
    "notes": ""
  },
  {
    "id": 5,
    "created_at": "2019-06-19 13:57:39",
    "updated_at": "2019-06-19 14:05:32",
    "purchase_order_type": "Default",
    "purchase_order_status": "FINISHED",
    "vendor_id": 2,
    "vendor_name": "Ryan\'s Tech Palace",
    "submission_status": "NOT_SUBMITTED",
    "submitted_on": "1999-12-31 01:01:01",
    "payment_method": "Wells Fargo Finance",
    "created_by": "Ryan Morton",
    "last_received_by": "1999-12-31 01:01:01",
    "receiving_status": "",
    "payment_terms_schema": "60 Days",
    "payment_discount_schema": "15:.02",
    "payment_authorized": "",
    "payment_authorization_code": "",
    "payment_authorized_on": "1999-12-31 01:01:01",
    "payment_invoice_number": "",
    "payment_amount_authorized": 0.00,
    "total": 0.00,
    "total_received": 0.00,
    "balance_due": 0.00,
    "shipping_location_contact_id": 1,
    "shipping_location_attn": "RECEIVING",
    "shipping_requirements": "Default",
    "fulfillment_order_id": null,
    "flooring_company": "None",
    "flooring_term_days": 30,
    "flooring_upcharge_percent": 0.00,
    "flooring_approval_status": null,
    "notes": ""
  }
]';
    }
}
