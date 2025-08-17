<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'xp',
        'is_profile_public',
        'streak_days',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            
            'is_profile_public' => 'boolean',
        ];
    }

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'user_persona')
                    ->withPivot('xp', 'level', 'date', 'is_unlocked')
                    ->withTimestamps();
    }

    /**public function userPersonas()
    {
        return $this->hasMany(UserPersona::class);
    }
    }*/

    public function selectedPersona()
    {
        return $this->belongsToMany(Persona::class, 'user_persona')
                    ->wherePivot('date', today())
                    ->withPivot('xp', 'level', 'is_unlocked')
                    ->withTimestamps()
                    ->first();
    }

    

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withTimestamps()
                    ->withPivot('unlocked_at');
    }


}
