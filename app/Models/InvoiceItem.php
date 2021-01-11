<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = "invoice_item";
    //

    public function inventory()
    {

        return $this->hasMany('App\Models\Inventory', 'fk_invoice_item_id');
    }
    public function workorder(){
        return $this->belongsTo('App\Models\WorkOrder', 'fk_invoice_id');
    }

}
