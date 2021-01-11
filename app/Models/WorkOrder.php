<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $table = "workorder";
    //
    // public function payments(){
    //     return $this->hasMany('App\Models\Payment', 'workorder_id');
    // }
    public function updateTotals(){
        $productTotal = 0;
        $laborTotal = 0;
        $shippingTotal = 0;
        $taxTotal = 0;

        $workOrderItems = WorkOrderItem::where('workorder_id', '=', $this->id)->get();
        $workOrderLaborLines = WorkOrderLaborLine::where('fk_workorder_id', '=', $this->id)->get();
        $totalPaymentMade = Payment::where('fk_workorder_id', '=', $this->id)->sum('amount');

        foreach($workOrderItems as $line){

            $line->updateTotals($this->tax_zone);
            switch($line->tax_code){
                case 'GEN_PRODUCT' :
                    $productTotal += $line->ext_price;
                    $taxTotal += $line->tax_amount;

                    break;
                case 'GEN_LABOR' :

                    $laborTotal += $line->ext_price;
                    $taxTotal += $line->tax_amount;

                    break;
                // case 'GEN_SHIPPING' :
                //     $shippingTotal += $line->ext_price;
                //     $taxTotal += $line->tax_amount;

                //     break;
                case 'GEN_INSURANCE' :
                    $productTotal += $line->ext_price;
                    $taxTotal += $line->tax_amount;

                    break;
                case 'tbd' :
                case 'TBD' :
                    $itemTotal = $line->unit_price * $line->number_units;
                    $itemTaxTotal = $itemTotal * $this->tax_rate;
                    $line->tax_amount = $itemTaxTotal;
                    $line->computed_tax = $itemTaxTotal;
                    $line->save();
                    $productTotal += $line->ext_price;
                    // $taxTotal += $line->tax_amount;
                    $taxTotal += $line->computed_tax;

                    break;
            }
        }

        foreach($workOrderLaborLines as $line){
            $laborTotal += $line->rate * $line->hours;
        }

        $this->product_total = $productTotal;
        $this->labor_total = $laborTotal;
        $this->shipping_total = $shippingTotal;
        $this->tax_total = $taxTotal;
        $this->total = $productTotal + $laborTotal + $shippingTotal;
        $this->total_payment = $totalPaymentMade;

        if($this->is_taxable){
            $this->total = $this->total + $this->tax_total;
        }
        
        $this->balance_due = $this->total - $this->total_payment;
        return true;

    }
    public function workOrderItems()
    {

        return $this->hasMany('App\Models\WorkOrderItem', 'workorder_id');
    }
    public function fulfillment()
    {

        return $this->hasMany('App\Models\Fulfillment', 'workorder_id');
    }    
    public function primarySales()
    {

        return $this->hasOne('App\Models\User', 'id','primary_sales_id');
    }
    public function secondSales()
    {

        return $this->hasOne('App\Models\User', 'id','second_sales_id');
    }
    public function thirdSales()
    {

        return $this->hasOne('App\Models\User', 'id','third_sales_id');
    }

    public function soldTo()
    {

        return $this->hasOne('App\Models\Contact', 'id', 'sold_contact_id');
    }
    public function billTo()
    {

        return $this->hasOne('App\Models\Contact', 'id','bill_contact_id');
    }
    public function shipTo()
    {

        return $this->hasOne('App\Models\Contact', 'id','ship_contact_id');
    }
    public function account()
    {

        return $this->hasOne('App\Models\Account', 'id','sold_account_id');
    }
    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment','fk_workorder_id');

    }
    public function workorderNotes()
    {
        return $this->hasMany('App\Models\WorkorderNotes', 'fk_workorder_id', 'id');
    }

    public function workorderLaborLines()
    {
        return $this->hasMany('App\Models\WorkOrderLaborLine', 'fk_workorder_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'fk_workorder_id', 'id');
    }


    public static function getJsonLight($data){
        $fields = array(
            "id",
            "workorder_id",
            "order_type",
            "status",
            "total", 
            "sold_contact_id",
            "product_total",
            "labor_total",
            "shipping_total",
            "tax_total",
            "notes",
            "approval_status",
            "approval_status_notes"
        );
        $result = new ResizedSearchResults();
        $results = $result->resultsToDataStructure($data, $fields);

        return $results;

    }
}
