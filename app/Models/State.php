<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'country_id',
        'name',
    ];

    /**
     * State belongs to a country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * State has many cities.
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
