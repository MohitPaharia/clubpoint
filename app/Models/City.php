<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'country_id',
        'state_id',
        'name',
        'lat',
        'lng',
    ];

    /**
     * City belongs to a country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * City belongs to a state — nullable.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
