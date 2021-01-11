<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\QuoteItem;
use App\Models\ResizedSearchResults;
use Carbon\Carbon;
class Quote extends Model
{
    protected $table = "quote";
    //

    public static function getJsonLight($data){
        //var_dump($data);
        $fields = array("id", "order_type", "total", "status", "sold_contact_id", "quote_id");
        $result = new ResizedSearchResults();
        $results = $result->resultsToDataStructure($data, $fields);

        return $results;

    }

    public static function getJsonMedium($data)
    {
        $fields = array("id", "first_name", "last_name", "personal_email", "mobile_phone", "city", "state", "zip");
        $result = new ResizedSearchResults();
        $results = $result->resultsToDataStructure($data, $fields);

        return $results;
    }


    public function updateTotals(){



        $productTotal = 0;
        $laborTotal = 0;
        $shippingTotal = 0;
        $taxTotal = 0;


        $quoteItems = QuoteItem::where('quote_id', '=', $this->id)->get();

        foreach($quoteItems as $line){

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
    public function quoteItems()
    {
        return $this->hasMany('App\Models\QuoteItem');
    }

    public function soldTo()
    {

        return $this->belongsTo('App\Models\Contact', 'sold_contact_id');
    }
    public function billTo()
    {

        return $this->belongsTo('App\Models\Contact', 'bill_contact_id');
    }
    public function shipTo()
    {

        return $this->belongsTo('App\Models\Contact', 'ship_contact_id');
    }

    public function quoteNote()
    {
        return $this->hasMany('App\Models\QuoteNote', 'fk_quote_id', 'id');
    }

    public function primarySalesId()
    {
        return $this->belongsTo('App\Models\User', 'primary_sales_id')->select(array('id', 'name'));
    }

    public function quoteLaborLines()
    {
        return $this->hasMany('App\Models\QuoteLaborLine', 'fk_quote_id', 'id');
    }

}
