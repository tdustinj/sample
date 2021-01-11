<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = "account";
    //
    public function contacts(){
        return $this->hasMany('App\Models\Contact');

    }
}
