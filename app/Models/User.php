<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'address',
        'credit',
        'country_id',
        'state_id',
        'city_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* ------------------------
     | Location Relationships
     -------------------------*/
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /* ------------------------
     | Clubs Relationships
     -------------------------*/
    // Clubs the user owns
    public function ownedClubs()
    {
        return $this->hasMany(Club::class, 'owner_id');
    }

    // Clubs user is a member of
    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'clubs_member_users');
    }

    // Join requests
    public function clubJoinRequests()
    {
        return $this->belongsToMany(Club::class, 'club_join_request_users');
    }

    // Clubs that blocked this user
    public function blockedByClubs()
    {
        return $this->belongsToMany(Club::class, 'club_blocked_users');
    }

    // Clubs user has blocked
    public function blockedClubs()
    {
        return $this->belongsToMany(Club::class, 'user_blocked_clubs');
    }

    /* ------------------------
     | Events Relationships
     -------------------------*/
    public function hostedEvents()
    {
        return $this->hasMany(Event::class, 'host_id');
    }

    public function eventParticipations()
    {
        return $this->belongsToMany(Event::class, 'event_participants');
    }

    /* ------------------------
     | Chats
     -------------------------*/
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
