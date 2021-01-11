<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusBoard extends Model
{
    protected $table = "workorder";


    public function workOrderItems()

    {

        return $this->hasMany('App\Models\WorkOrderItem', 'workorder_id');

    }


}
