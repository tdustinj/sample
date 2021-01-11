<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];

  protected $table = "product";
  protected $fillable = [
    "upc",
    "description",
    "fk_category_id",
    "fk_brand_id",
    "fk_brand_id",
    "fk_manufacturer_id",
    "model_number",
    "part_number",
    "serial_number",
    "marketing_class",
    "qty_on_hand",
    "qty_on_order",
    "qty_committed",
    "qty_on_quoted",
    "item_class",
    "item_type",
    "stock_class",
    "box_qty",
    "external_data_source",
    "external_data_model",
    "external_data_source_id",
    "external_data_source_update_status",
    "status",
    "current_cost",
    "current_rebate_credit",
    "current_adj_cost",
    "current_adj_map",
    "map",
    "current_map",
    "current_rebate",
    "msrp",
    "minimum_price",
    "spiff",
    "model_code"
  ];

  public static function inStock()
  {
    return Product::where('qty_on_hand', '>', 0);
  }

  public function brand()
  {
    return $this->belongsTo('App\Models\Brand', 'fk_brand_id');
  }

  public function category()
  {
    return $this->belongsTo('App\Models\Category', 'fk_category_id');
  }
  public function manufacturer()
  {
    return $this->belongsTo('App\Models\Brand', 'fk_brand_id');
  }

  public function quoteLaborLines()
  {
    return $this->hasMany('App\Models\QuoteLaborLine', 'fk_products_id');
  }

  public function workorderLaborLines()
  {
    return $this->hasMany('App\Models\WorkOrderLaborLine', 'fk_products_id');
  }
}
