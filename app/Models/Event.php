<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'event_date',
        'event_time',
        'club_id',
        'host_id',
        'country_id',
        'state_id',
        'city_id',
        'ticket_cost',
    ];

    /* ------------------------
     | Club & Host
     -------------------------*/
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /* ------------------------
     | Location
     -------------------------*/
    public function country() 
    { 
        return $this->belongsTo(Country::class, 'country_id'); 
    }

    public function state()   
    { 
        return $this->belongsTo(State::class, 'state_id'); 
    }

    public function city()    
    { 
        return $this->belongsTo(City::class, 'city_id'); 
    }

    /* ------------------------
     | Ticket Buyers (Participants)
     -------------------------*/
    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants');
    }
}