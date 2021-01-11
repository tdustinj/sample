<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaxInfo;

class QuoteItem extends Model
{
    protected $table = "quote_item";
    //
  public function updateTotals($taxZone){


      $taxInfo = new TaxInfo();
      if($this->item_class != 'LABOR') {
          $this->ext_price = $this->unit_price * intval($this->number_units);
      }
      else {
          $this->ext_price = $this->unit_price * $this->number_units;
      }
      //$this->ext_price = $this->unit_price * intval($this->number_units);
      $this->tax_amount = $taxInfo->getTaxByZone($taxZone, $this->tax_code, $this->ext_price);

      $this->save();

  }
    public function getNumberUnitsAttribute($val)
    {
        if($this->attributes['item_class'] != 'LABOR') {
            return intval($val);
        }
        else {
            return $val;
        }
    }
    public function setNumberUnitsAttribute($val)
    {
        if($this->attributes['item_class'] != 'LABOR') {
            $this->attributes['number_units'] = intval($val);
        }
        else {
            return $this->attributes['number_units'] = intval($val);

        }
    }

    public function Quote(){
        return $this->belongsTo('App\Models\Quote', 'quote_id'); 
    }

    public function brand(){
        return $this->belongsTo('App\Models\Brand', 'fk_brand_id'); 
    }
    public function category(){
        return $this->belongsTo('App\Models\Category', 'fk_category_id'); 
    }
}
