<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfillmentPackageProduct extends Model
{
    protected $table = "fulfillment_package_product";
    //

    public function workorderItem(){
        return $this->belongsTo('App\Models\WorkOrderItem','fk_workorder_item_id');

    }

    public function fulfillment(){
        return $this->belongsTo('App\Models\Fulfillment','fk_fulfillment_id');

    }

}
