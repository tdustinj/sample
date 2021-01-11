<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItem extends Model
{
    use SoftDeletes;
    protected $table = "purchase_order_item";
    protected $dates = ['deleted_at'];
    // Add method for product relatuionship

    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase');
    }
}
