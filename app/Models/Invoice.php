<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = "invoice";
    //
    public function payments(){
        return $this->hasMany('App\Models\Payment', 'workorder_id');
    }
    public function updateTotals(){



        $productTotal = 0;
        $laborTotal = 0;
        $shippingTotal = 0;
        $taxTotal = 0;


        $workOrderItems = WorkOrderItem::where('workorder_id', '=', $this->id)->get();

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
                case 'GEN_SHIPPING' :
                    $shippingTotal += $line->ext_price;
                    $taxTotal += $line->tax_amount;

                    break;
                case 'GEN_INSURANCE' :
                    $productTotal += $line->ext_price;
                    $taxTotal += $line->tax_amount;

                    break;


            }

        }
        $this->product_total = $productTotal;
        $this->labor_total = $laborTotal;
        $this->shipping_total = $shippingTotal;
        $this->tax_total = $taxTotal;
        $this->total = $productTotal + $laborTotal + $shippingTotal ;
        if($this->taxable){
            $this->total = $this->total + $taxTotal;
        }
        return true;

    }
    public function workOrderItems()
    {

        return $this->hasMany('App\Models\WorkOrderItem', 'workorder_id');
    }
    public function primarySales()
    {

        return $this->hasOne('App\Models\User', 'primary_sales_id');
    }
    public function secondSales()
    {

        return $this->hasOne('App\Models\User', 'second_sales_id');
    }
    public function thirdSales()
    {

        return $this->hasOne('App\Models\User', 'third_sales_id');
    }

    public function soldTo()
    {

        return $this->hasOne('App\Models\Contact', 'sold_contact_id');
    }
    public function billTo()
    {

        return $this->hasOne('App\Models\Contact', 'bill_contact_id');
    }
    public function account()
    {

        return $this->hasOne('App\Models\Account', 'sold_account_id');
    }
    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment');

    }

    public static function getJsonLight($data){
        $fields = array(
            "id",
            "workorder_id",
            "workorder_type",
            "workorder_status",
            "total", "sold_contact_id",
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
