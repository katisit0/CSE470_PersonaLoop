<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('date', 'is_unlocked')
            ->withTimestamps();
    }
}
