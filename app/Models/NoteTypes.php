<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteTypes extends Model
{   
    protected $table = "note_types";
    protected $primaryKey = 'note_type_code';
    public $incrementing = false;
    protected $keyType = 'string';
    //

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'created_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [ 'fk_workorder_id','fk_user_id', 'message'];

    //TODO:: try and carbon the created_at timestamp for human eyes.
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = [
    //     'created_at',
    // ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = ['created_at'];

    public function notes() {
        return $this->hasMany('App\Models\WorkorderNotes', 'fk_note_type');
    }

    public function quoteNotes() {
        return $this->hasMany('App\Models\QuoteNote', 'fk_note_type');
    }
}
