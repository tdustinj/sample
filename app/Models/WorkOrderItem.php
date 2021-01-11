<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    protected $table = "workorder_item";
    //
    public function updateTotals($taxZone){


        $taxInfo = new TaxInfo();

        $this->ext_price = $this->unit_price * $this->number_units;
        $this->tax_amount = $taxInfo->getTaxByZone($taxZone, $this->tax_code, $this->ext_price);

        $this->save();

    }




    /*
     *  fulfillViaAmazon will be used to direct the line to be fullfilled via AmazonFBA
     */
    public function inventory()
    {

        return $this->hasMany('App\Models\Inventory', 'fk_workorder_item_id');
    }
    public function workorder(){
        return $this->belongsTo('App\Models\WorkOrder', 'fk_workorder_id');
    }



}
