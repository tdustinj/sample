<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkorderNotes extends Model
{
    protected $table = "workorder_notes";
    //

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
    protected $fillable = ['fk_workorder_id', 'fk_user_id', 'message', 'fk_note_type', 'fk_user_id_recipient'];

    //TODO:: try and carbon the created_at timestamp for human eyes.
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

    public function workorder()
    {
        return $this->belongsTo('App\Models\WorkOrder', 'fk_workorder_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'fk_user_id', 'id');
    }

    public function to()
    {
        return $this->belongsTo('App\Models\User', 'fk_user_id_recipient', 'id');
    }

    public function note_type()
    {
        return $this->belongsTo('App\Models\NoteTypes', 'fk_note_type', 'note_type_code');
    }

    public function getCreatedAtAttribute()
    {
        $createdAt = new Carbon($this->attributes['created_at']);
        return $createdAt->toDayDateTimeString();
    }
}
