<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PlatformStateTaxMap extends Model
{
    protected $table = "platform_state_tax_map";
    public $timestamps = false;

 	public function platform()
    {
        // since we're using platform_code as the foreign key mapping to the Platform model, we have to specify as such.
        // the second argument refers to the column name in this table that is the foreign key.
        // the third argument refers to which column in the parent table we're using for the join.
        return $this->belongsTo('App\Models\Platform', 'platform_code', 'platform_code');
    }

    public function state()
    {
        // since we're using platform_code as the foreign key mapping to the Platform model, we have to specify as such.
        // the second argument refers to the column name in this table that is the foreign key.
        // the third argument refers to which column in the parent table we're using for the join.
        return $this->belongsTo('App\Models\State', 'state_code', 'state_code');
    }

    /**
     * Set the keys for a save update query.
     * This is a fix for tables with composite keys
     * TODO: Investigate this later on
     * https://github.com/laravel/framework/issues/5517
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            //Put appropriate values for your keys here:
            ->where('platform_code', '=', $this->platform_code)
            ->where('state_code', '=', $this->state_code);

        return $query;
    }
        
}
