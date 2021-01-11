<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Payment extends Model
{
    protected $table = "payment";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_workorder_id',
        'fk_invoie_id', 
        'amount',
        'fk_user_id', 
        'fk_payment_class_id',
        'fk_payment_method_id',
        'fk_payment_terminal_id',
        'fk_payment_batch_id',
        'note',
        'transaction_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'payment_cleared_on'
    ];

    public function user(){
        return $this->hasOne('App\Models\User', 'id','fk_user_id');
    }
    
    public function paymentMethod(){
        return $this->hasOne('App\Models\PaymentMethod', 'id','fk_payment_method_id');
    }

    public function paymentClass(){
        return $this->hasOne('App\Models\PaymentClass', 'id','fk_payment_class_id');
    }

    public function paymentTerminal(){
        return $this->hasOne('App\Models\PaymentTerminal', 'id','fk_payment_terminal_id');
    }

    public function paymentBatch(){
        return $this->hasOne('App\Models\PaymentBatch', 'id','fk_payment_batch_id');
    }

    public function workorder(){
        return $this->hasOne('App\Models\WorkOrder', 'id','fk_workorder_id');
    }

    public function invoice(){
        return $this->hasOne('App\Models\Invoice', 'id','fk_invoice_id');
    }

    public function getCreatedAtAttribute(){ 
        $createdAt = new Carbon($this->attributes['created_at']); 
         return $createdAt->toDayDateTimeString();
         // return $createdAt->diffForHumans(); 
    }

    public function getPaymentClearedOnAttribute(){ 
        $paymentClearedOn = new Carbon($this->attributes['payment_cleared_on']); 
        return $paymentClearedOn->toDayDateTimeString();
        // return $createdAt->diffForHumans(); 
    }
}
