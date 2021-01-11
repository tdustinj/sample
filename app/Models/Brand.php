<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = "brand";
    //

    public function items(){
        return  $this->hasMany('App\Models\QuoteItem', 'id'); 
    }
}
