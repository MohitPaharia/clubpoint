<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    public $timestamps = false; // because migration only uses created_at

    protected $fillable = [
        'message',
        'club_id',
        'user_id',
        'created_at',
    ];

    // Chat belongs to one Club
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    // Chat belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
