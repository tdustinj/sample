<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'google_id', 'g_token', 'g_refresh_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'auth_super_user', 'auth_sales', 'auth_admin', 'auth_operations'
    ];

    public function notes() {
        return $this->hasMany('App\Models\WorkorderNotes', 'fk_user_id');
    }

    public function notesRecipient() {
        return $this->hasMany('App\Models\WorkorderNotes', 'fk_user_id_recipient');
    }

    public function quoteNotes() {
        return $this->hasMany('App\Models\QuoteNote', 'fk_user_id');
    }

    public function quoteNotesRecipient() {
        return $this->hasMany('App\Models\QuoteNote', 'fk_user_id_recipient');
    }

    public function primarySalesQuote(){
        return $this->hasMany('App\Models\Quote', 'primary_sales_id');
    }

    public function quoteLaborLines(){
        return $this->hasMany('App\Models\QuoteLaborLine', 'fk_technician_id');
    }

    public function workorderLaborLines(){
        return $this->hasMany('App\Models\WorkOrderLaborLine', 'fk_technician_id');
    }
}
