<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResizedSearchResults;

class Contact extends Model
{
    protected $table = "contact";
    //

    public static function getJsonLight($data)
    {
    	$fields = array("id", "first_name", "last_name", "personal_email", "mobile_phone");

    	$results = ResizedSearchResults::resultsToDataStructure($data, $fields);

    	return $results;
    }

    public static function getJsonMedium($data)
    {
    	$fields = array("id", "first_name", "last_name", "personal_email", "mobile_phone", "city", "state", "zip");
        $results = ResizedSearchResults::resultsToDataStructure($data, $fields);
    	return $results;
    }

    public function account(){
        return  $this->belongsTo('App\Models\Account', 'account_id');
    }

    public function shipContact() {
        return $this->hasMany('App\Models\WorkOrder', 'ship_contact_id');
    }

    public function soldContact() {
        return $this->hasMany('App\Models\WorkOrder', 'sold_contact_id');
    }

    public function billContact() {
        return $this->hasMany('App\Models\WorkOrder', 'bill_contact_id');
    }

    public function shipContactQuote() {
        return $this->hasMany('App\Models\Quote', 'ship_contact_id');
    }

    public function soldContactQuote() {
        return $this->hasMany('App\Models\Quote', 'sold_contact_id');
    }

    public function billContactQuote() {
        return $this->hasMany('App\Models\Quote', 'bill_contact_id');
    }

    public function shipContactWorkOrder() {
        return $this->hasMany('App\Models\WorkOrder', 'ship_contact_id');
    }

    public function soldContactWorkOrder() {
        return $this->hasMany('App\Models\WorkOrder', 'sold_contact_id');
    }

    public function billContactWorkOrder() {
        return $this->hasMany('App\Models\WorkOrder', 'bill_contact_id');
    }

}
