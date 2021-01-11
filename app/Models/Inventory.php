<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
  protected $table = "inventory";


  /*
     *  relese inventory from order line
     */
  public function release()
  {
    $this->fk_workorder_item_id = null;
    $this->fk_workorder_id = null;
    $this->assigned_to_invoice = null;
    $this->save();
  }

  public function assign($workOrderId, $workOrderItemId)
  {
    $this->fk_workorder_item_id = $workOrderItemId;
    $this->fk_workorder_id = $workOrderId;
    $this->save();
  }
  public function workorder_item()
  {
    return $this->belongsTo('App\Models\WorkOrderItem', 'workorder_item_id');
  }
  public function fulfillment()
  {
    return $this->belongsTo('App\Models\Fulfillment', 'fulfillment_id');
  }

  public function brand()
  {
    return $this->belongsTo('App\Models\Brand', 'fk_brand_id');
  }

  public function category()
  {
    return $this->belongsTo('App\Models\Category', 'fk_category_id');
  }
}
