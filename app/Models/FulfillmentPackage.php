<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfillmentPackage extends Model
{

    protected $table = "fulfillment_package";
    //
    public function items(){
        return $this->hasMany('App\Models\FulfillmentPackageProduct','fk_fulfillment_package_id');

    }
}
