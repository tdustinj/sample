<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fulfillment extends Model
{
    protected $table = "fulfillment";
    //
    public function package(){
        return $this->hasMany('App\Models\FulfillmentPackage','fk_fulfillment_id');
    }

    public function fulfillmentType(){
        return $this->belongsTo('App\Models\FulfillmentType', 'fk_fulfillment_type_id');        
    }

    public function workorder(){
        return $this->belongsTo('App\Models\WorkOrder', 'fk_workorder_id');
    }

    public function scopeTrackedItems($query){
        return $query->where('vendor_shipping_status', '=', 'Shipped');
    }

}
