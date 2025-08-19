<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaSelection extends Model
{
    protected $fillable = [
        'user_id',
        'persona_id',
        'selected_at',
        'xp_earned',
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
