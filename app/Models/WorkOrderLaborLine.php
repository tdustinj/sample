<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class WorkOrderLaborLine extends Model
{
    //
    protected $table = "workorder_labor_lines";
    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = ['updated_at'];

    /**
     * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['fk_workorder_id', 'fk_technician_id', 'fk_products_id', 'sku', 'rate', 'hours', 'description'];

    /**
     * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = [
        'created_at',
    ];
    
    /**
     * The accessors to append to the model's array form.
    *
    * @var array
    */
    protected $appends = ['created_at'];

    /**
     * Returns created_at in human readable format.
    *
    * 
    */
    public function getCreatedAtAttribute(){ 
        $createdAt = new Carbon($this->attributes['created_at']); 
        return $createdAt->toDayDateTimeString();
    }

    public function workorder(){
        return $this->belongsTo('App\Models\WorkOrder', 'id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'fk_technician_id', 'id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'fk_products_id', 'id');
    }
}
