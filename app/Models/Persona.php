<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{

    protected $fillable = ['name', 'description', 'image', 'unlock_condition',];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_persona')
            ->withPivot('xp', 'level', 'date', 'is_unlocked')
            ->withTimestamps();
    }
}
