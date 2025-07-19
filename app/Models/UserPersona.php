<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPersona extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
    