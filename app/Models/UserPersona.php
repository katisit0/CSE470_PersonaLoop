<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPersona extends Model
{
    protected $fillable = [
        'user_id',
        'persona_id',
        'xp',
        'level',
        'date',
        'is_unlocked',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
    