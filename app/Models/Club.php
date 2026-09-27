<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'approved',
        'owner_id',
        'country_id',
        'state_id',
        'city_id',
    ];

    /* ------------------------
     | Owner
     -------------------------*/
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    /* ------------------------
     | Approving 
     -------------------------*/
    public function approve()
    {
       $this->approved = true;
       $this->save();
    }

    /* ------------------------
     | Location
     -------------------------*/
    public function country() { return $this->belongsTo(Country::class); }
    public function state()   { return $this->belongsTo(State::class); }
    public function city()    { return $this->belongsTo(City::class); }

    /* ------------------------
     | Members & Requests
     -------------------------*/
    public function members()
    {
        return $this->belongsToMany(User::class, 'clubs_member_users');
    }

    public function joinRequests()
    {
        return $this->belongsToMany(User::class, 'club_join_request_users');
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'club_blocked_users');
    }

    /* ------------------------
     | Blocking
     -------------------------*/
    public function blockedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_blocked_clubs');
    }

    /* ------------------------
     | Events
     -------------------------*/
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /* ------------------------
     | Chats
     -------------------------*/
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
