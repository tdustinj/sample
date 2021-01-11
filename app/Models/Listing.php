<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $table = "listing";
    //
    public function product(){
        return $this->hasOne('App\Models\Product');

    }
}
