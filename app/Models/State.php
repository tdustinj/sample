<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
   	protected $primaryKey = 'state_code';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // tells which fields should be hidden when model is serialized to JSON
    protected $hidden = ['created_at', 'updated_at'];

    public function orders()
    {
        // the second argument refers to the foreign key used in the child table.
        // the third argument refers to which column we're joining on in this table.
        return $this->hasMany('App\Models\PlatformStateTaxMap', 'state_code', 'state_code');
    }
}
