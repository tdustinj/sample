<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfillmentType extends Model
{
    protected $table = "fulfillment_type";
    //
    public function fulfillments(){
        return $this->hasMany('App\Models\Fulfillment','fk_fulfillment_type_id_');

    }
}
