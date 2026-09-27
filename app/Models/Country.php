<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{   
    protected $fillable = [
        'name',
        'iso2',
        'iso3',
    ];

    /**
     * A country has many states.
     */
    public function states()
    {
        return $this->hasMany(State::class);
    }

    /**
     * A country has many cities directly (for countries without states).
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
