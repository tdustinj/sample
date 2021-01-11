<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = "vendor";

    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }
}
